<?php
namespace App\Helpers;
use App\Models\Entity;
use App\Models\EntityType;
use Illuminate\Support\Facades\Artisan;

/**
 * Class EntityExport
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class EntityExport
{
    public static function exportEntities($entityList) {

        //print_r($entityList["data"][0]);

        $status = true;
        $error = null;
        //$fileName = "entities.zip";
        $fileName = tempnam("/tmp", "si4.zip");
        $pathPrefix = "entities/";

        $archive = new \ZipArchive();
        $archive->open($fileName, \ZipArchive::CREATE);

        foreach ($entityList["data"] as $entity) {
            $id = $entity["id"];
            $xml = $entity["data"];
            $archive->addFromString($pathPrefix.$id."/mets.xml", $xml);
        }

        $archive->setArchiveComment('Created '.date('Y-M-d'));
        $archive->close();

        return $fileName;


        //die();

        /*
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
        */

    }
}