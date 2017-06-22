<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EntityType;
use Illuminate\Http\UploadedFile;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistryInterface;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;


/**
 * App\Models\Entity
 *
 * @property int $id
 * @property int $entity_type_id
 * @property string $data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\EntityType $entityType
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereEntityTypeId($value)
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
        'name',
        "entity_type_id",
        "data"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entityType()
    {
        return $this->belongsTo(EntityType::class);
    }

    /**
     * @return bool
     */
    public function dataSchemaValidate() : bool
    {
        return self::xmlStringSchemaValidate($this->data);
    }

    public function dataToObject()
    {
        $serializerBuilder = SerializerBuilder::create();
        $serializerBuilder->addMetadataDir(app_path("Xsd/Mets"), 'App\Xsd\Mets');
        $serializerBuilder->configureHandlers(function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling

            // $handler->registerSubscribingHandler(new YourhandlerHere());
        });

        $serializer = $serializerBuilder->build();

        // deserialize the XML into Demo\MyObject object
        $object = $serializer->deserialize($this->data, 'App\Xsd\Mets\Mets', 'xml');
        $array = $object->toArray();
        return $array;
    }

    /**
     * @param \App\Models\EntityType $entityType
     * @param UploadedFile $uploadedFile
     * @return Entity
     */
    public static function createFromUpload(EntityType $entityType, UploadedFile $uploadedFile) : Entity
    {
        $entity = new self;
        $entity->entity_type_id = $entityType->id;
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