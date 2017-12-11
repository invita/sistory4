<?php

namespace App\Models;

use App\Xsd\AnySimpleTypeHandler;
use App\Xsd\AnyTypeHandler;
use App\Xsd\XmlDataATypeHandler;
use App\Xsd\DcTypeHandler;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

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
        'name',
        'struct_type',
        'entity_type',
        'data'
    ];

    /**
     * @return bool
     */
    public function dataSchemaValidate() : bool
    {
        return self::xmlStringSchemaValidate($this->data);
    }

    public function dataToObject()
    {
        if (!$this->data) return null;
        $serializerBuilder = SerializerBuilder::create();
        $serializerBuilder->addMetadataDir(app_path("Xsd/Mets"), 'App\Xsd\Mets');
        $serializerBuilder->configureHandlers(function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling

            //$handler->registerSubscribingHandler(new XmlDataATypeHandler());
            // $handler->registerSubscribingHandler(new YourhandlerHere());
            //$handler->registerSubscribingHandler(new AnySimpleTypeHandler());
            $handler->registerSubscribingHandler(new DcTypeHandler());
            $handler->registerSubscribingHandler(new AnyTypeHandler());

        });

        $serializer = $serializerBuilder->build();

        // deserialize the XML into Demo\MyObject object
        $object = $serializer->deserialize($this->data, 'App\Xsd\Mets\Mets', 'xml');
        $array = $object->toArray();


        return $array;
    }

    /**
     * @param \App\Models\StructType $structType
     * @param UploadedFile $uploadedFile
     * @return Entity
     */
    public static function createFromUpload($structType, UploadedFile $uploadedFile) : Entity
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
    public static function xmlStringSchemaValidate(string $xmlContent, array &$errors = []) : bool
    {
        libxml_clear_errors();
        libxml_use_internal_errors(true);

        $xml = new \DOMDocument();
        $xml->loadXML($xmlContent);
        $valid = $xml->schemaValidate(asset("xsd/mets.xsd"));
        if(!$valid){
            $errors = libxml_get_errors();
        }

        return $valid;
    }
}