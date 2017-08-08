<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
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
        $staticData = Si4Util::getArg($postJson, "staticData", []);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);
        $entity_type_id  = Si4Util::getArg($staticData, "entity_type_id", 0);

        // Map entity types
        $entityTypesDb = EntityType::all();
        $entityTypes = [];
        foreach ($entityTypesDb as $et) $entityTypes[$et["id"]] = $et["name"];
        //print_r($entityTypes);

        $rowCount = 0;

        if (!$entity_type_id) {
            // All Entities
            //$entitiesDb = Entity::all->keyBy("id");

            $entityIdsQuery = Entity::query();
            $entityIdsQuery->select(["id"]);
            $rowCount = $entityIdsQuery->count();

            $entityIds = $entityIdsQuery->orderBy("id")->offset($pageStart)->limit($pageCount)->get()->keyBy("id")->keys()->toArray();
            //$entityIds = Entity::select(["id"])->orderBy("id")->offset($pageStart)->limit($pageCount)->get()->keyBy("id")->keys()->toArray();
            //$entitiesDb = Entity::select()->offset(0)->limit(10)->get()->keyBy("id");
            //print_r($entitiesDb->keys()->toArray());
            $hits = ElasticHelpers::searchByIdArray($entityIds);
            //print_r($entityIds);
            //print_r($hits);

            /*
            [
                [#id] => [
                    id: #id,
                    _source: [
                        entity_type_id: #,
                        xml: ...,
                        data: [ IDAttrName: ... ]
                    ]
                ]
            ]
            */


        } else {
            // Only Entities of specific entity_type
            $dataElastic = ElasticHelpers::search([
                "term" => [ "entity_type_id" => $entity_type_id ]
            ], $pageStart, $pageCount);

            $rowCount = Si4Util::pathArg($dataElastic, "hits/total", 0);
            $hits = ElasticHelpers::elasticResultToAssocArray($dataElastic);
            //print_r($hits);
        }

        $result = [];
        foreach ($hits as $id => $hit) {

            //print_r($entity);

            $IDAttr = "";
            $title = "";
            $creator = "";
            $date = "";
            $xml = "";

            $entityTypeId = 0;
            $entityTypeName = "";

            $_source = Si4Util::getArg($hit, "_source", null);

            if ($_source) {
                $entityTypeId = Si4Util::getArg($_source, "entity_type_id", 0);
                $entityTypeName = Si4Util::getArg($entityTypes, $entityTypeId, "");
                //print_r($entityTypeId." ".$entityTypeName."\n");

                $data = Si4Util::getArg($_source, "data", null);
                $xml = Si4Util::getArg($_source, "xml", "");

                $dcXmlData = Si4Util::pathArg($data, "DmdSecElName/1/MdWrapElName/XmlDataElName", []);
                $IDAttr = Si4Util::getArg($data, "IDAttrName", "");
                $title = isset($dcXmlData["TitlePropName"]) ? join(" : ", $dcXmlData["TitlePropName"]) : "";
                $creator = isset($dcXmlData["CreatorPropName"]) ? join("; ", $dcXmlData["CreatorPropName"]) : "";
                $date = isset($dcXmlData["DatePropName"]) ? join("; ", $dcXmlData["DatePropName"]) : "";
            }

            $result[] = [
                "id" => $id,
                "entity_type_id" => $entityTypeId,
                "entity_type_name" => $entityTypeName,
                "IdAttr" => $IDAttr,
                "title" => $title,
                "creator" => $creator,
                "date" => $date,
                "data" => $xml,
            ];
        }

        return ["status" => true, "data" => $result, "rowCount" => $rowCount, "error" => null];
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

        /*
        $entityXmlParsed = $entity->dataToObject();
        //print_r($entityXmlParsed);

        $indexBody = [
            "entity_type_id" => $postJson["entity_type_id"],
            "xml" => $postJson["xml"],
            "data" => $entityXmlParsed
        ];
        $elasticResponse = ElasticHelpers::indexEntity($postJson["id"], $indexBody);
        */

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