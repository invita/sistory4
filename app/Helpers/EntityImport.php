<?php
namespace App\Helpers;
use App\Models\Elastic\EntityNotIndexedException;
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
    public static function importEntity($handleId, $parentHandleId, $xmlContent) {

        Timer::start("xmlParsing");
        $xmlDoc = simplexml_load_string($xmlContent);
        Timer::stop("xmlParsing");

        //print_r($xml);

        //$type = $xml->attributes("TYPE");
        //$mets = $xml->xpath('/');
        //print_r($type);
        //foreach($xml->attributes() as $attrKey => $attrVal) {
        //    echo $attrKey.' = "'.$attrVal."\"\n";
        //}

        $metsAttributes = $xmlDoc->attributes();
        $maType = (string)$metsAttributes["TYPE"];
        $maId = (string)$metsAttributes["ID"];
        $maObjId = (string)$metsAttributes["OBJID"];

        $metsFile = null;

        if ($maType == "file") {
            $metsFileEl = $xmlDoc->xpath("METS:fileSec[@ID='default.file']/METS:fileGrp/METS:file");
            if ($metsFileEl && $metsFileEl[0]) {
                $metsFile = [
                    "id" => (string)$metsFileEl[0]["ID"],
                    "fileName" => (string)$metsFileEl[0]["OWNERID"],
                    "mimeType" => (string)$metsFileEl[0]["MIMETYPE"],
                    "created" => (string)$metsFileEl[0]["CREATED"],
                    "size" => (string)$metsFileEl[0]["SIZE"],
                ];
            }
        }

        //echo "Importing handleId:".$handleId."\n";
        Timer::start("database");

        $existing = Entity::where(["handle_id" => $handleId])->first();
        if ($existing && $existing->id) {
            $newEntityId = $existing->id;
            $replaced = true;
        } else {
            $newEntityId = Si4Util::nextEntityId();
            $replaced = false;
        }

        //$id = explode(".", $maId)[2];
        //echo "id: ".$id."\n";


        $entity = Entity::findOrNew($newEntityId);
        $entity->handle_id = $handleId;
        $entity->struct_type = $maType;

        $entity->parent = $parentHandleId;
        //$entity->calculateParents();
        $entity->entity_type = "";
        $entity->primary = "";
        $entity->child_order = $newEntityId;

        $entity->xml = $xmlContent;

        // Mark both flags, required full text reindex and thumb regeneration.
        $entity->req_text_reindex = true;
        $entity->req_thumb_regen = true;

        //prin

        $entity->save();

        Timer::stop("database");


        Artisan::call("reindex:entity", ["entityId" => $newEntityId]);

        //return $entity;
        return [
            "sysId" => $newEntityId,
            "structType" => $maType,
            "metsFile" => $metsFile,
            "entity" => $entity,
            "replaced" => $replaced,
        ];

    }

    public static function postImportEntity($sysId) {

        Timer::start("database");
        $entity = Entity::find($sysId);
        Timer::stop("database");

        $entity->calculateParents();

        Timer::start("xmlParsing");
        $entity->updateXml();
        Timer::stop("xmlParsing");

        Timer::start("database");
        $entity->save();
        Timer::stop("database");

        // Reindex
        Artisan::call("reindex:entity", ["entityId" => $sysId]);

        // Create thumb
        // TODO: cron job, to run thumb generator
        //Artisan::call("thumbs:create", ["entityId" => $sysId]);
    }

}