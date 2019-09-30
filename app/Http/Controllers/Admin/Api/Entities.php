<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Helpers\EntitySelect;
use App\Helpers\Enums;
use App\Helpers\FileHelpers;
use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Behaviour;
use App\Models\Entity;
use App\Models\EntityHandleSeq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Entities extends Controller
{
    public function entityList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        return EntitySelect::selectEntities($postJson);
    }
    public function entityListDb(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        return EntitySelect::selectEntitiesFromDb($postJson);
    }

    public function reserveEntityId(Request $request)
    {

        $postJson = json_decode(file_get_contents("php://input"), true);
        $struct_type = Si4Util::getArg($postJson, "struct_type", "entity");
        $struct_subtype = Si4Util::getArg($postJson, "struct_subtype", "default");

        $status = true;
        $error = null;

        $behaviour = Behaviour::getBehaviour($struct_subtype);
        $template = null;
        if ($behaviour && isset($behaviour["template_".$struct_type]) && $behaviour["template_".$struct_type]) {
            $template = $behaviour["template_".$struct_type];
        }

        $newEntityId = Si4Util::nextEntityId();
        $entity = Entity::findOrNew($newEntityId);
        $entity->id = $newEntityId;
        $entity->handle_id = EntityHandleSeq::nextNumSeq($struct_type);
        //$entity->handle_id = "";
        $entity->parent = "";
        $entity->primary = "";
        $entity->collection = "";

        $entity->struct_type = $struct_type;
        $entity->struct_subtype = $struct_subtype;
        $entity->entity_type = "";
        $entity->child_order = $newEntityId;
        $entity->save();
        return [
            "status" => $status,
            "error" => $error,
            "data" => [
                "id" => $newEntityId,
                "handle_id" => $entity->handle_id,
                "struct_subtype" => $entity->struct_subtype,
                "xml" => $template
            ]
        ];
    }

    public function saveEntity(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = Si4Util::getArg($postJson, "id", 0);
        $handle_id = Si4Util::getArg($postJson, "handle_id", "");
        $parent = Si4Util::getArg($postJson, "parent", "");
        //$primary = Si4Util::getArg($postJson, "primary", "");
        //$entityType = Si4Util::getArg($postJson, "entity_type", "");
        $structSubtype = Si4Util::getArg($postJson, "struct_subtype", "");
        $structType = Si4Util::getArg($postJson, "struct_type", "");
        $childOrder = Si4Util::getArg($postJson, "child_order", $id);
        $xml = Si4Util::getArg($postJson, "xml", "");
        $active = Si4Util::getArg($postJson, "active", false);


        $status = true;
        $error = null;

        // File
        $realFileName = Si4Util::getArg($postJson, "realFileName", "");
        $tempFileName = Si4Util::getArg($postJson, "tempFileName", "");
        if ($tempFileName && $realFileName) {
            $tempStorageName = "public/temp/".$tempFileName;
            $destStorageName = FileHelpers::getPublicStorageName($parent, $realFileName);
            if (Storage::exists($destStorageName)) Storage::delete($destStorageName);
            Storage::move($tempStorageName, $destStorageName);
        }

        // Custom Thumbnail
        $realThumbName = Si4Util::getArg($postJson, "realThumbName", "");
        $tempThumbName = Si4Util::getArg($postJson, "tempThumbName", "");
        if ($tempThumbName && $realThumbName) {
            $tempStorageName = "public/temp/".$tempThumbName;
            $destStorageName = FileHelpers::getPublicStorageName($handle_id, $realThumbName);
            if (Storage::exists($destStorageName)) Storage::delete($destStorageName);
            Storage::move($tempStorageName, $destStorageName);
        }

        $entity = Entity::findOrNew($id);
        $entity->parent = $parent;
        $entity->struct_type = in_array($structType, Enums::$structTypes) ? $structType : null;
        $entity->struct_subtype = $structSubtype;
        $entity->child_order = $childOrder;
        $entity->xml = $xml;
        $entity->active = $active;
        $entity->req_thumb_regen = false;

        if (!$handle_id) $handle_id = EntityHandleSeq::nextNumSeq($entity->struct_type);
        $entity->handle_id = $handle_id;

        $entity->calculateParents();
        $entity->updateXml();

        $entity->save();

        Artisan::call("reindex:entity", ["entityId" => $entity->id]);
        Artisan::call("thumbs:create", ["entityId" => $entity->id]);

        ElasticHelpers::refreshIndex();

        // Update parent
        if ($entity->parent) {

            Log::warning("Updating parent (".$entity->parent.") for ".$entity->handle_id);
            $parentEntity = Entity::where(["handle_id" => $entity->parent])->first();
            if ($parentEntity) {
                Log::warning("Parent found");
                $parentEntity->calculateParents();
                $parentEntity->updateXml();

                $parentEntity->save();
                Log::warning("Parent saved");

                Artisan::call("reindex:entity", ["entityId" => $parentEntity->id]);

                Log::warning("Parent reindexed");

                ElasticHelpers::refreshIndex();
            } else {
                Log::warning("Parent entity (".$entity->parent.") not found for entity ".$entity->handle_id);
            }
        }
        /*
        */

        return ["status" => $status, "error" => $error];
    }

    // TODO: delete child files and prompt first
    public function deleteEntity(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        $entity = Entity::find($id);
        if ($entity) {
            $entity->delete();
        }

        Artisan::call("reindex:entity", ["entityId" => $id]);

        return $this->entityList($request);
    }

    public function entityHierarchy(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        return EntitySelect::selectEntityHierarchy($postJson);
    }

    public function forceReindexEntity(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["id"];

        $status = true;
        $error = null;

        //$entity = Entity::find($id);

        Artisan::call("reindex:entity", ["entityId" => $id]);

        Artisan::call("reindex:entityText", ["entityId" => $id]);

        return [
            "status" => $status,
            "error" => $error,
        ];
    }

    public function forceRegenThumb(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["id"];

        $status = true;
        $error = null;

        Artisan::call("thumbs:create", ["entityId" => $id]);

        $entity = Entity::find($id);

        $hdlElasticData = ElasticHelpers::searchByHandleArray([$entity->handle_id]);
        $hdlDocData = $hdlElasticData[array_keys($hdlElasticData)[0]];
        $hdlDoc = Si4Helpers::getEntityListPresentation($hdlDocData);
        //print_r($hdlDocData);
        //print_r($hdlDoc);

        $thumb = Si4Util::getArg($hdlDoc, "thumb", FileHelpers::getDefaultThumbForStructType($entity->struct_type));

        //$firstFile
        //FileHelpers::getThumbUrl($entity->handle_id, $entity->struct_type, $firstFile);

        return [
            "status" => $status,
            "thumb" => $thumb,
            "error" => $error,
        ];
    }

}