<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\EntityType;
use \Illuminate\Http\Request;

class Entities extends Controller
{
    public function entityList(Request $request)
    {
        $entitiesDb = Entity::all()->where("reservation_only", false)->keyBy("id");
        $entityTypesDb = EntityType::all()->keyBy("id");

        $dataElastic = ElasticHelpers::searchByIdArray($entitiesDb->keys());
        //print_r($dataElastic);

        $hits = [];
        foreach ($dataElastic["hits"]["hits"] as $hit) $hits[$hit["_id"]] = $hit["_source"];

        $entities = [];
        foreach ($entitiesDb as $entityId => $entity) {

            $IDAttr = "";
            $title = "";
            $creator = "";
            $date = "";

            if (isset($hits[$entityId])) {
                $eData = $hits[$entityId];
                $dcXmlData = $eData["DmdSecElName"][1]["MdWrapElName"]["XmlDataElName"];

                $IDAttr = isset($eData["IDAttrName"]) ? $eData["IDAttrName"] : "";
                $title = isset($dcXmlData["TitlePropName"]) ? join(", ", $dcXmlData["TitlePropName"]) : "";
                $creator = isset($dcXmlData["CreatorPropName"]) ? join(",", $dcXmlData["CreatorPropName"]) : "";
                $date = isset($dcXmlData["DatePropName"]) ? join(",", $dcXmlData["DatePropName"]) : "";
            }

            $entityTypeName = isset($entityTypesDb[$entity["entity_type_id"]]) ?
                $entityTypesDb[$entity["entity_type_id"]]["name"] : "";

            $entities[] = [
                "id" => $entityId,
                "entity_type_id" => $entity["entity_type_id"],
                "entity_type_name" => $entityTypeName,
                "IdAttr" => $IDAttr,
                "title" => $title,
                "creator" => $creator,
                "date" => $date,
                "data" => $entity["data"],
            ];
        }

        return ["status" => true, "data" => $entities, "error" => null];
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
        $entity->reservation_only = true;
        $entity->save();
        return ["status" => $status, "error" => $error, "data" => $newEntityId];
    }

    public function saveEntity(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $status = true;
        $error = null;

        $entity = Entity::findOrNew($postJson["id"]);
        $entity->reservation_only = false;
        $entity->entity_type_id = $postJson["entity_type_id"];
        $entity->data = $postJson["xml"];

        $entityXmlParsed = $entity->dataToObject();
        //print_r($entityXmlParsed);
        $elasticResponse = ElasticHelpers::indexEntity($postJson["id"], $entityXmlParsed);

        $entity->save();

        return ["status" => $status, "error" => $error];
    }

    public function deleteEntity(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        $entity = Entity::find($id);
        if ($entity) $entity->delete();

        return $this->entityList($request);
    }


}