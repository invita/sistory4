<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\EntityType;
use App\Models\Relation;
use App\Models\RelationType;
use \Illuminate\Http\Request;

class EntityRelations extends Controller
{
    public function entityRelationsList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $entityId = $postJson["staticData"]["entityId"];
        $relations = Relation::all()->where("first_entity_id", $entityId)->union(
                     Relation::all()->where("second_entity_id", $entityId));
        return ["status" => true, "data" => $relations, "error" =>  null];
    }

    public function saveEntityRelation(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $status = true;
        $error = null;

        $relation = Relation::findOrNew($postJson["id"]);
        $relation->first_entity_id = $postJson["first_entity_id"];
        $relation->relation_type_id = $postJson["relation_type_id"];
        $relation->second_entity_id = $postJson["second_entity_id"];
        $relation->save();

        return $this->entityRelationsList($request);
    }

    public function deleteEntityRelation(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        $relation = Relation::find($id);
        if ($relation) $relation->delete();

        return $this->entityRelationsList($request);
    }


}