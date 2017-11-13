<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Helpers\EntitySelect;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\EntityType;
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
        $entity->entity_type_id = 0;
        $entity->save();
        return ["status" => $status, "error" => $error, "data" => $newEntityId];
    }

    public function saveEntity(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $status = true;
        $error = null;

        $entity = Entity::findOrNew($postJson["id"]);
        $entity->entity_type_id = $postJson["entity_type_id"];
        $entity->data = $postJson["xml"];

        $entity->save();

        Artisan::call("reindex:entity", ["entityId" => $postJson["id"]]);


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

}