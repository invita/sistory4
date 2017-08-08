<?php
namespace App\Helpers;
use App\Models\Entity;
use App\Models\EntityType;
use Illuminate\Support\Facades\Artisan;

/**
 * Class EntityImport
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class EntityImport
{
    public static function importEntity($xmlContent) {

        $xml = simplexml_load_string($xmlContent);

        //print_r($xml);

        //$type = $xml->attributes("TYPE");
        //$mets = $xml->xpath('/');
        //print_r($type);
        //foreach($xml->attributes() as $attrKey => $attrVal) {
        //    echo $attrKey.' = "'.$attrVal."\"\n";
        //}

        $metsAttributes = $xml->attributes();
        $maType = (string)$metsAttributes["TYPE"];
        $maId = (string)$metsAttributes["ID"];
        $maObjId = (string)$metsAttributes["OBJID"];

        $id = explode(".", $maId)[2];

        //echo "id: ".$id."\n";

        $entityTypesDb = EntityType::all();
        $entityTypes = [];
        foreach ($entityTypesDb as $et) $entityTypes[$et["name"]] = $et["id"];
        $entityTypeId = isset($entityTypes[$maType]) ? $entityTypes[$maType] : false;

        if ($id && $entityTypeId) {

            $entity = Entity::findOrNew($id);
            $entity->id = $id;
            $entity->entity_type_id = $entityTypeId;
            $entity->data = $xmlContent;

            //print_r($entity);

            $entity->save();

            Artisan::call("reindex:entity", ["entityId" => $id]);
        }

        return true;

    }
}