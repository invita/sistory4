<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Helpers\EntitySelect;
use App\Helpers\Enums;
use App\Helpers\FileHelpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\EntityHandleSeq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Entities extends Controller
{
    public function entityList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        return EntitySelect::selectEntities($postJson);
    }

    public function reserveEntityId(Request $request)
    {

        $postJson = json_decode(file_get_contents("php://input"), true);
        $struct_type = Si4Util::getArg($postJson, "struct_type", "entity");

        $status = true;
        $error = null;

        $newEntityId = Si4Util::nextEntityId();
        $entity = Entity::findOrNew($newEntityId);
        $entity->id = $newEntityId;
        //$entity->handle_id = EntityHandleSeq::nextNumSeq($struct_type);
        $entity->handle_id = "";
        $entity->parent = "";
        $entity->primary = "";
        $entity->collection = "";

        $entity->struct_type = $struct_type;
        $entity->entity_type = "";
        $entity->entity_subtype = "default";
        $entity->save();
        return [
            "status" => $status,
            "error" => $error,
            "data" => [
                "id" => $newEntityId,
                "handle_id" => $entity->handle_id,
                "entity_subtype" => $entity->entity_subtype
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
        $entitySubtype = Si4Util::getArg($postJson, "entity_subtype", "");
        $structType = Si4Util::getArg($postJson, "struct_type", "");
        $xml = Si4Util::getArg($postJson, "xml", "");
        $active = Si4Util::getArg($postJson, "active", false);


        $status = true;
        $error = null;

        // File
        $realFileName = Si4Util::getArg($postJson, "realFileName", "");
        $tempFileName = Si4Util::getArg($postJson, "tempFileName", "");
        if ($tempFileName && $realFileName) {
            $tempStorageName = "public/temp/".$tempFileName;
            $destStorageName = FileHelpers::getStorageName($id, $realFileName);
            if (Storage::exists($destStorageName)) Storage::delete($destStorageName);
            Storage::move($tempStorageName, $destStorageName);
        }

        $entity = Entity::findOrNew($id);
        $entity->parent = $parent;
        $entity->struct_type = in_array($structType, Enums::$structTypes) ? $structType : null;
        $entity->entity_subtype = $entitySubtype;
        $entity->data = $xml;
        $entity->active = $active;

        if (!$handle_id) $handle_id = EntityHandleSeq::nextNumSeq($entity->struct_type);
        $entity->handle_id = $handle_id;

        //$entity->primary = "123";
        //$entity->entity_type = "dependant";
        //print_r($entity->id); die();

        $entity->calculateParents();

        //$entity->primary = $primary;
        //$entity->entity_type = in_array($entityType, Enums::$entityTypes) ? $entityType : null;

        $entity->save();

        Artisan::call("reindex:entity", ["entityId" => $entity->id]);


        return ["status" => $status, "error" => $error];
    }

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
}