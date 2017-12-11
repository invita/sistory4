<?php
namespace App\Helpers;

use App\Models\Entity;
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
        $parent = Si4Util::getArg($requestData, "parent", null);
        $entityIds = Si4Util::getArg($requestData, "entityIds", null);

        $struct_type  = Si4Util::getArg($staticData, "struct_type", "");

        $filter = Si4Util::getArg($requestData, "filter", []);

        $rowCount = 0;

        if (!$struct_type) {

            // All Entities - Admin only
            $entityIdsQuery = Entity::query();
            $entityIdsQuery->select(["id"]);
            $rowCount = $entityIdsQuery->count();

            if ($parent) {
                $hits = ElasticHelpers::searchByParent($parent);
            } else {
                if (!$entityIds)
                    $entityIds = $entityIdsQuery->orderBy("id")->offset($pageStart)->limit($pageCount)->get()->keyBy("id")->keys()->toArray();
                $hits = ElasticHelpers::searchByIdArray($entityIds);
            }
        } else {
            // Only Entities of specific struct_type

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

            // Add struct_type_id (entity/collection)
            $mustItems[] = [
                "term" => [ "struct_type" => $struct_type ]
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

            $parent = 0;
            $IDAttr = "";
            $title = "";
            $creator = "";
            $date = "";
            $xml = "";
            $data = null;

            $structType = "";
            $entityType = "";

            $_source = Si4Util::getArg($hit, "_source", null);

            if ($_source) {
                $structType = Si4Util::getArg($_source, "struct_type", "");
                $entityType = Si4Util::getArg($_source, "entity_type", "");

                $parent = Si4Util::getArg($_source, "parent", 0);
                $data = Si4Util::getArg($_source, "data", null);
                $xml = Si4Util::getArg($_source, "xml", "");

                //print_r($data);

                $dcMetadata = Si4Util::pathArg($data, "dmd/dc", []);
                $IDAttr = Si4Util::getArg($data, "id", "");
                $title = isset($dcMetadata["title"]) ? join(" : ", $dcMetadata["title"]) : "";
                $creator = isset($dcMetadata["creator"]) ? join("; ", $dcMetadata["creator"]) : "";
                $date = isset($dcMetadata["date"]) ? join("; ", $dcMetadata["date"]) : "";
            }

            $result[] = [
                "id" => $id,
                "parent" => $parent,
                "struct_type" => $structType,
                "entity_type" => $entityType,
                //"IdAttr" => $IDAttr,
                "title" => $title,
                "creator" => $creator,
                "date" => $date,
                "xmlData" => $xml,
                "elasticData" => $data,
            ];
        }

        return ["status" => true, "data" => $result, "rowCount" => $rowCount, "error" => null];
    }
}