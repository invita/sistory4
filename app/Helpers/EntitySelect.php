<?php
namespace App\Helpers;

use App\Models\Entity;
use App\Models\EntityType;
use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

/**
 * Class EntitySelect
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class EntitySelect
{
    public static function selectEntities($requestData) {
        $staticData = Si4Util::getArg($requestData, "staticData", []);
        $pageStart = Si4Util::getArg($requestData, "pageStart", 0);
        $pageCount = Si4Util::getArg($requestData, "pageCount", 20);
        $sortField = Si4Util::getArg($requestData, "sortField", "id");
        $sortOrder = Si4Util::getArg($requestData, "sortOrder", "asc");

        $entity_type_id  = Si4Util::getArg($staticData, "entity_type_id", 0);

        $filter = Si4Util::getArg($requestData, "filter", []);


        // Map entity types
        $entityTypesDb = EntityType::all();
        $entityTypes = [];
        foreach ($entityTypesDb as $et) $entityTypes[$et["id"]] = $et["name"];
        //print_r($entityTypes);

        $rowCount = 0;

        if (!$entity_type_id) {

            // All Entities - Admin only
            $entityIdsQuery = Entity::query();
            $entityIdsQuery->select(["id"]);
            $rowCount = $entityIdsQuery->count();

            $entityIds = $entityIdsQuery->orderBy("id")->offset($pageStart)->limit($pageCount)->get()->keyBy("id")->keys()->toArray();
            $hits = ElasticHelpers::searchByIdArray($entityIds);

        } else {
            // Only Entities of specific entity_type

            // Filter map, maps filter fields into elastic keys
            $filterMap = [
                "id" => "id",
                "IdAttr" => "data.id",
                "title" => "data.dmd.dc.title",
                "creator" => "data.dmd.dc.creator",
                "date" => "data.dmd.dc.date",
            ];

            // Must elastic query
            $mustItems = [];

            // Add entity_type_id (entity/collection)
            $mustItems[] = [
                "term" => [ "entity_type_id" => $entity_type_id ]
            ];

            // Add Filter fields, mapped by filterMap
            foreach ($filter as $fKey => $fVal) {
                if (!isset($filterMap[$fKey])) continue;
                $mustItems[] = [
                    "match" => [ $filterMap[$fKey] => ElasticHelpers::escapeValue($fVal) ]
                ];
            }

            // Prepare elastic query
            $query = [
                "bool" => [
                    "must" => $mustItems
                ]
            ];

            $dataElastic = ElasticHelpers::search($query, $pageStart, $pageCount, $sortField, $sortOrder);

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

                //print_r($data);

                $dcMetadata = Si4Util::pathArg($data, "dmd/dc", []);
                $IDAttr = Si4Util::getArg($data, "id", "");
                $title = isset($dcMetadata["title"]) ? join(" : ", $dcMetadata["title"]) : "";
                $creator = isset($dcMetadata["creator"]) ? join("; ", $dcMetadata["creator"]) : "";
                $date = isset($dcMetadata["date"]) ? join("; ", $dcMetadata["date"]) : "";
                /*
                $dcXmlData = Si4Util::pathArg($data, "DmdSecElName/1/MdWrapElName/XmlDataElName", []);
                $IDAttr = Si4Util::getArg($data, "IDAttrName", "");
                $title = isset($dcXmlData["TitlePropName"]) ? join(" : ", $dcXmlData["TitlePropName"]) : "";
                $creator = isset($dcXmlData["CreatorPropName"]) ? join("; ", $dcXmlData["CreatorPropName"]) : "";
                $date = isset($dcXmlData["DatePropName"]) ? join("; ", $dcXmlData["DatePropName"]) : "";
                */
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
}