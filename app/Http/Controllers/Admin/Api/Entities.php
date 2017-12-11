<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Helpers\EntitySelect;
use App\Helpers\Enums;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class Entities extends Controller
{
    public function entityList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        return EntitySelect::selectEntities($postJson);
    }

    public function reserveEntityId(Request $request)
    {
        $status = true;
        $error = null;
        $lastEntityId = Entity::select("id")->orderBy('id', 'desc')->pluck("id")[0];
        //print_r($lastEntityId);
        $newEntityId = $lastEntityId ? intval($lastEntityId) + 1 : 1;
        $entity = Entity::findOrNew($newEntityId);
        $entity->id = $newEntityId;
        $entity->parent = 0;
        $entity->struct_type = null;
        $entity->entity_type = null;
        $entity->save();
        return ["status" => $status, "error" => $error, "data" => $newEntityId];
    }

    public function saveEntity(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = Si4Util::getArg($postJson, "id", 0);
        $parent = Si4Util::getInt($postJson, "parent", 0);
        $structType = Si4Util::getArg($postJson, "struct_type", "");
        $entityType = Si4Util::getArg($postJson, "entity_type", "");
        $xml = Si4Util::getArg($postJson, "xml", "");

        $status = true;
        $error = null;

        $entity = Entity::findOrNew($id);
        $entity->parent = $parent;
        $entity->struct_type = in_array($structType, Enums::$structTypes) ? $structType : null;
        $entity->entity_type = in_array($entityType, Enums::$entityTypes) ? $entityType : null;
        $entity->data = $xml;

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
        $id = Si4Util::getArg($postJson, "id", null);
        $recursiveUp = Si4Util::getArg($postJson, "recursiveUp", false);

        if (!$id) {
            return ["status" => false, "error" => "No id given"];
        }

        $entity = null;
        $parents = [];
        $children = [];

        // Select current entity by Id
        $entity = EntitySelect::selectEntities([
            "entityIds" => [$id]
        ]);
        $entity = isset($entity["data"]) && isset($entity["data"][0]) ? $entity["data"][0] : null;

        // Select parent entity

        $parentId = $entity && isset($entity["parent"]) && $entity["parent"] ? $entity["parent"] : false;
        while ($parentId) {
            $parentEntity = EntitySelect::selectEntities([
                "entityIds" => [$parentId]
            ]);
            $parentEntity = isset($parentEntity["data"]) && isset($parentEntity["data"][0]) ? $parentEntity["data"][0] : null;
            if ($parentEntity) {
                array_unshift($parents, $parentEntity);
                $parentId = intval($parentEntity["parent"]);
            } else {
                $parentId = null;
            }
        }

        // Select child entities
        $children = EntitySelect::selectEntities([
            "parent" => $id
        ]);
        $children = isset($children["data"]) ? $children["data"] : [];

        //print_r(["children" => $children]);

        return ["status" => true, "data" => [
            "parents" => $parents,
            "currentEntity" => $entity,
            "children" => $children,
        ]];
    }
}