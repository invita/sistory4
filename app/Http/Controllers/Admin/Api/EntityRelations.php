<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Relation;
use App\Models\RelationType;
use \Illuminate\Http\Request;

class EntityRelations extends Controller
{
    public function entityRelationsList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $entityId = $postJson["staticData"]["entityId"];
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);


        $relationsQ = Relation::select()
            ->where("first_entity_id", $entityId)
            ->orWhere("second_entity_id", $entityId);

        $rowCount = $relationsQ->count();
        $relations = $relationsQ->orderBy("id")->offset($pageStart)->limit($pageCount)->get();
        //print_r($relations->get()->toArray());

        /*
        $relations = Relation::all()->where("first_entity_id", $entityId)->union(
                     Relation::all()->where("second_entity_id", $entityId))->toArray();
        */

        $result = [];
        foreach ($relations as $idx => $rel) {
            $isFirstEntity = $rel["first_entity_id"] == $entityId;
            $result[] = [
                "id" => $rel["id"],
                "relation_type_id" => $isFirstEntity ? $rel["relation_type_id"]."n" : $rel["relation_type_id"]."r",
                "related_entity_id" => $isFirstEntity ? $rel["second_entity_id"] : $rel["first_entity_id"],
            ];
        }

        //print_r($result);
        return ["status" => true, "data" => $result, "rowCount" => $rowCount, "error" =>  null];
    }

    public function saveEntityRelation(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $status = true;
        $error = null;

        // called from dataTable
        if (isset($postJson["methodName"]) && $postJson["methodName"] == "dataTableUpdateRow") {
            $orig = $postJson["data"]["orig"];
            $row = $postJson["data"]["row"];
            $entityId = $postJson["staticData"]["entityId"];
            $relType = strval($row["relation_type_id"]);
            if (strpos($relType, "r") !== false) {
                $relType = intval(str_replace("r", "", $relType));
                $relation = Relation::find($orig["id"]);
                $relation->first_entity_id = $row["related_entity_id"];
                $relation->relation_type_id = $relType;
                $relation->second_entity_id = $entityId;
                $relation->save();
            } else {
                $relType = intval(str_replace("n", "", $relType));
                $relation = Relation::find($orig["id"]);
                $relation->first_entity_id = $entityId;
                $relation->relation_type_id = $relType;
                $relation->second_entity_id = $row["related_entity_id"];
                $relation->save();
            }

        } else {
            $relation = Relation::findOrNew($postJson["id"]);
            $relation->first_entity_id = $postJson["first_entity_id"];
            $relation->relation_type_id = $postJson["relation_type_id"];
            $relation->second_entity_id = $postJson["second_entity_id"];
            $relation->save();
        }

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