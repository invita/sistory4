<?php
namespace App\Helpers;
use App\Models\Entity;
use Illuminate\Support\Facades\Artisan;

/**
 * Class EntityImport
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class EntityImport
{
    public static function importEntity($handleId, $xmlContent) {

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


        $existing = Entity::where(["handle_id" => $handleId])->first();
        if ($existing) $existing->delete();

        //$id = explode(".", $maId)[2];
        //echo "id: ".$id."\n";

        $newEntityId = Si4Util::nextEntityId();
        $entity = Entity::findOrNew($newEntityId);
        $entity->handle_id = $handleId;
        $entity->struct_type = "entity";

        $entity->parent = "";
        //$entity->calculatePrimary();
        $entity->entity_type = "";
        $entity->primary = "";

        $entity->data = $xmlContent;

        //print_r($entity);

        $entity->save();

        Artisan::call("reindex:entity", ["entityId" => $newEntityId]);

        return true;

    }
}