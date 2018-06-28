<?php

namespace App\Models;

use App\Helpers\EntitySelect;
use App\Helpers\Enums;
use App\Helpers\FileHelpers;
use App\Helpers\Si4Util;
use App\Helpers\XmlHelpers;
use App\Models\Elastic\EntityNotIndexedException;
use App\Xsd\AnySimpleTypeHandler;
use App\Xsd\AnyTypeHandler;
use App\Xsd\AsTextTypeHandler;
use App\Xsd\Base64TypeHandler;
use App\Xsd\XmlDataATypeHandler;
use App\Xsd\DcTypeHandler;
use App\Xsd\XsiTypeHandler;
use Elasticsearch\Common\Exceptions\ServerErrorResponseException;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Storage;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistryInterface;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use Psr\Log\Test\LoggerInterfaceTest;


/**
 * App\Models\Entity
 *
 * @property int $id
 * @property int $struct_type_id
 * @property string $data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereStructTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Entity extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent',
        'primary',
        'collection',
        'name',
        'struct_type',
        'struct_subtype',
        'entity_type',
        'data',
        'active'
    ];

    /**
     * @return bool
     */
    // public function dataSchemaValidate() : bool
    public function dataSchemaValidate()
    {
        return self::xmlStringSchemaValidate($this->data, $this->struct_type);
    }

    public function dataToObject()
    {
        if (!$this->data) return null;
        $serializerBuilder = SerializerBuilder::create();

        $serializerBuilder->addMetadataDir(app_path("Xsd/Mets"), 'App\Xsd\Mets');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Dc"), 'App\Xsd\Dc');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Premis"), 'App\Xsd\Premis');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Mods"), 'App\Xsd\Mods');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Entity"), 'App\Xsd\Entity');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Collection"), 'App\Xsd\Collection');

        $serializerBuilder->configureHandlers(function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling

            //$handler->registerSubscribingHandler(new XsiTypeHandler());

            //$handler->registerSubscribingHandler(new XmlDataATypeHandler());
            //$handler->registerSubscribingHandler(new AnySimpleTypeHandler());
            //$handler->registerSubscribingHandler(new DcTypeHandler());

            //$handler->registerSubscribingHandler(new Base64TypeHandler());
            //$handler->registerSubscribingHandler(new AnyTypeHandler());
            //$handler->registerSubscribingHandler(new AsTextTypeHandler());

        });

        $serializer = $serializerBuilder->build();


        // deserialize the XML into object
        $object = $serializer->deserialize($this->data, 'App\Xsd\Mets\Mets', 'xml');
        //print_r($object);

        $array = $object->toArray();
        //print_r($array);

        return $array;
    }

    public function updateXml() {
        $xmlDoc = simplexml_load_string($this->data);
        $xmlDoc['ID'] = $this->handle_id;
        $xmlDoc['OBJID'] = "http://hdl.handle.net/11686/".$this->handle_id;
        $xmlDoc['TYPE'] = $this->struct_type;

        $premisIdentifiers = $xmlDoc->xpath(
            "METS:amdSec/METS:techMD/METS:mdWrap[@MDTYPE='PREMIS:OBJECT']/METS:xmlData/premis:objectIdentifier");
        foreach ($premisIdentifiers as $premisIdentifier) {
            $piTypeNode = $premisIdentifier->xpath("premis:objectIdentifierType")[0];
            $piValueNode = $premisIdentifier->xpath("premis:objectIdentifierValue")[0];
            $piType = (string)$piTypeNode;

            if ($piType == "si4") {
                $piValueNode[0] = $this->id;
            }
            else if ($piType == "Local name") {
                $piValueNode[0] = $this->handle_id;
            }
            else if ($piType == "hdl") {
                $piValueNode[0] = "http://hdl.handle.net/11686/".$this->handle_id;
            }

        }

        $premisObjCategory = $xmlDoc->xpath(
            "METS:amdSec/METS:techMD/METS:mdWrap[@MDTYPE='PREMIS:OBJECT']/METS:xmlData/premis:objectCategory")[0];
        $premisObjCategory[0] = $this->struct_type;


        // structMap hierarchy
        $hierarchy = $this->getHierarchy();
        $parents = $hierarchy["data"]["parents"];
        if (count($parents)) {
            $parent = $parents[count($parents) -1];

            // METS:structMap
            $structMapArr = $xmlDoc->xpath("METS:structMap");
            if (count($structMapArr))
                $structMap = $structMapArr[0];
            else
                $structMap = $xmlDoc->addChild("METS:structMap");
            $structMap["ID"] = "default.structure";
            $structMap["TYPE"] = $parent["entity_type"];



            // Remove METS:structMap/METS:div and reconstruct
            unset($xmlDoc->xpath("METS:structMap/METS:div")[0][0]);


            // METS:structMap/METS:div - parentDiv
            $structParentDiv = $structMap->addChild("METS:div");
            $structParentDiv["TYPE"] = $parent["struct_type"];

            // METS:structMap/METS:div/METS:mptr - parent mptr
            $structParentMptr = $structParentDiv->addChild("METS:mptr");
            $structParentMptr["LOCTYPE"] = "HANDLE";
            $structParentMptr["xlink:href"] = "http://hdl.handle.net/11686/".$this->parent;

            // METS:structMap/METS:div/METS:div - currentDiv
            $structCurrentDiv = $structParentDiv->addChild("METS:div");
            $structCurrentDiv["TYPE"] = $this->struct_type;
            $structCurrentDiv["DMDID"] = "default.dc default.mods";
            $structCurrentDiv["AMDID"] = "default.amd";

            $children = $hierarchy["data"]["children"];
            //print_r(array_keys($children[0]));
            //print_r($children[0]["handle_id"]);
            foreach ($children as $child) {
                $childHandleId = $child["handle_id"];
                $childStructType = $child["struct_type"];

                if ($childStructType == "file") {
                    // METS:structMap/METS:div/METS:div/METS:fptr - childFptr
                    $structChildFptr = $structCurrentDiv->addChild("METS:fptr");
                    $structChildFptr["FILEID"] = $childHandleId;
                } else {
                    // METS:structMap/METS:div/METS:div/METS:div - childDiv
                    $structChildDiv = $structCurrentDiv->addChild("METS:div");
                    $structChildDiv["TYPE"] = $childStructType;
                    $structChildMptr = $structChildDiv->addChild("METS:mptr");
                    $structChildMptr["LOCTYPE"] = "HANDLE";
                    $structChildMptr["xlink:href"] = "http://hdl.handle.net/11686/".$childHandleId;
                }
            }
        }



        // File attributes
        if ($this->struct_type == "file") {
            $metsFile = $xmlDoc->xpath("METS:fileSec/METS:fileGrp/METS:file")[0];
            $fileName = $metsFile["OWNERID"];
            $parent = $this->parent;
            $storageName = FileHelpers::getPublicStorageName($parent, $fileName);

            if (Storage::exists($storageName)) {
                $metsFile["MIMETYPE"] = Storage::mimeType($storageName); // FileHelpers::fileNameMime($fileName);
                $metsFile["SIZE"] = Storage::size($storageName);
                $metsFile["CREATED"] = "";
                $metsFile["CHECKSUM"] = md5_file(storage_path('app')."/".$storageName);
                $metsFile["CHECKSUMTYPE"] = "MD5";
            }
            //print_r($metsFile);
        }


        // Format XML
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlDoc->asXML());

        $this->data = $dom->saveXML();

        //$this->data = $xmlDoc->asXML();
    }

    private $hierarchy = null;
    public function getHierarchy() {
        if (!$this->hierarchy) {
            $this->hierarchy = EntitySelect::selectEntityHierarchy(["handle_id" => $this->handle_id]);
        }
        return $this->hierarchy;
    }

    private $parentHierarchy = null;
    public function getParentHierarchy() {
        if (!$this->parentHierarchy) {
            $this->parentHierarchy = EntitySelect::selectEntityHierarchy(["handle_id" => $this->parent]);
        }
        return $this->parentHierarchy;
    }

    // Calculates primary entity
    public function calculateParents() {
        switch ($this->struct_type) {
            case "collection":
                if ($this->parent) {
                    $this->entity_type = "dependant";
                    //$hierarchy = EntitySelect::selectEntityHierarchy(["handle_id" => $this->parent]);
                    $hierarchy = $this->getParentHierarchy();
                    $parents = Si4Util::pathArg($hierarchy, "data/parents", []);
                    $parents[] = Si4Util::pathArg($hierarchy, "data/currentEntity", []);
                    $this->primary = $parents[0]["handle_id"];
                } else {
                    $this->entity_type = "primary";
                    $this->primary = $this->handle_id;
                }
                break;

            case "file":
                $this->entity_type = "primary";
                $this->primary = $this->handle_id;
                break;

            case "entity": default:
                $this->entity_type = "primary";
                $this->primary = $this->handle_id;
                if ($this->parent) {
                    //$hierarchy = EntitySelect::selectEntityHierarchy(["handle_id" => $this->parent]);
                    $hierarchy = $this->getParentHierarchy();
                    $parents = Si4Util::pathArg($hierarchy, "data/parents", []);
                    $parents[] = Si4Util::pathArg($hierarchy, "data/currentEntity", []);
                    $lastCollectionParent = "";
                    // Find first entity parent for primary and take it's parent for collection
                    foreach ($parents as $parent) {
                        if ($parent["struct_type"] == "collection") {
                            $lastCollectionParent = $parent["handle_id"];
                        } else if ($parent["struct_type"] == "entity") {
                            $this->entity_type = "dependant";
                            $this->primary = $parent["handle_id"];
                            break;
                        }
                    }
                    $this->collection = $lastCollectionParent;
                }
                break;
        }
    }

    /**
     * @param \App\Models\StructType $structType
     * @param UploadedFile $uploadedFile
     * @return Entity
     */
    // public static function createFromUpload($structType, UploadedFile $uploadedFile) : Entity
    public static function createFromUpload($structType, UploadedFile $uploadedFile)
    {
        $entity = new self;
        $entity->struct_type = $structType;
        $entity->data = file_get_contents($uploadedFile->getPathname());
        $entity->save();

        return $entity;
    }

    /**
     * @param string $xmlContent
     * @param array $errors
     * @return bool
     */
    //public static function xmlStringSchemaValidate(string $xmlContent, string $structType, array &$errors = []) : bool
    public static function xmlStringSchemaValidate(string $xmlContent, string $structType, array &$errors = [])
    {
        libxml_clear_errors();
        libxml_use_internal_errors(true);

        /*
        switch ($structType) {
            case "entity": default:
                $schemaFile = "mets_entity.xsd";
                break;
            case "collection":
                $schemaFile = "mets_collection.xsd";
                break;
            case "file":
                $schemaFile = "mets_entity.xsd";
                break;
        }
        */
        $schemaFile = "mets.xsd";

        $xml = new \DOMDocument();
        $xml->loadXML($xmlContent);
        //$valid = $xml->schemaValidate(asset("xsd/mets.xsd"));
        $valid = $xml->schemaValidate(asset("xsd/".$schemaFile));
        if(!$valid){
            $errors = libxml_get_errors();
        }

        return $valid;
    }
}