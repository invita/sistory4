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

    // Select entities by handle ids
    public static function selectEntitiesByHandleIds($handleIds, $requestData = null) {
        $filter = Si4Util::getArg($requestData, "filter", []);
        $terms = self::makeTermsFromFilter($filter);
        $terms["handle_id"] = $handleIds;
        $elasticQuery = self::prepareElasticQuery($terms);
        //print_r($elasticQuery);

        $dataElastic = self::fetchElasticData($elasticQuery, $requestData);

        $rowCount = Si4Util::pathArg($dataElastic, "hits/total", 0);
        $hits = ElasticHelpers::elasticResultToAssocArray($dataElastic);
        $result = self::processElasticResponse($hits);

        return ["status" => true, "data" => $result, "rowCount" => $rowCount, "error" => null];
    }

    // Select entities by id database field
    public static function selectEntitiesBySystemIds($sysIds, $requestData = null) {
        $filter = Si4Util::getArg($requestData, "filter", []);
        $terms = self::makeTermsFromFilter($filter);
        $terms["id"] = $sysIds;
        $elasticQuery = self::prepareElasticQuery($terms);
        //print_r($elasticQuery);

        $dataElastic = self::fetchElasticData($elasticQuery, $requestData);
        $rowCount = Si4Util::pathArg($dataElastic, "hits/total", 0);
        $hits = ElasticHelpers::elasticResultToAssocArray($dataElastic);
        $result = self::processElasticResponse($hits);

        return ["status" => true, "data" => $result, "rowCount" => $rowCount, "error" => null];
    }

    // Select entities by parent id
    public static function selectEntitiesByParentHandle($parentHandle, $requestData = null) {
        $filter = Si4Util::getArg($requestData, "filter", []);
        $terms = self::makeTermsFromFilter($filter);
        $terms["parent"] = $parentHandle;
        $elasticQuery = self::prepareElasticQuery($terms);
        //print_r($elasticQuery);

        $dataElastic = self::fetchElasticData($elasticQuery, $requestData);
        $rowCount = Si4Util::pathArg($dataElastic, "hits/total", 0);
        $hits = ElasticHelpers::elasticResultToAssocArray($dataElastic);
        $result = self::processElasticResponse($hits);

        return ["status" => true, "data" => $result, "rowCount" => $rowCount, "error" => null];
    }

    // Select entities general
    public static function selectEntities($requestData = null) {
        $filter = Si4Util::getArg($requestData, "filter", []);
        $terms = self::makeTermsFromFilter($filter);
        $elasticQuery = self::prepareElasticQuery($terms);
        //print_r($elasticQuery);

        $dataElastic = self::fetchElasticData($elasticQuery, $requestData);
        $rowCount = Si4Util::pathArg($dataElastic, "hits/total", 0);
        $hits = ElasticHelpers::elasticResultToAssocArray($dataElastic);
        $result = self::processElasticResponse($hits);

        return ["status" => true, "data" => $result, "rowCount" => $rowCount, "error" => null];
    }


    // ********** ********** **********


    // Map filter fields, to match elastic structure
    private static $filterMap = [
        "id" => "id",
        "handle_id" => "handle_id",
        "parent" => "parent",
        "primary" => "primary",
        "struct_type" => "struct_type",
        "title" => "data.dmd.dc.title",
        "creator" => "data.dmd.dc.creator",
        "date" => "data.dmd.dc.date",
    ];

    private static function makeTermsFromFilter($filter) {
        // Add Filter fields, mapped by filterMap
        $terms = [];
        foreach ($filter as $fKey => $fVal) {
            if (!isset(self::$filterMap[$fKey])) continue;
            $terms[self::$filterMap[$fKey]] = ElasticHelpers::escapeValue($fVal);
        }
        return $terms;
    }

    private static function prepareElasticQuery($terms = []) {
        $queryFilterMust = [];

        foreach ($terms as $termKey => $termVal) {
            if (is_array($termVal)) {
                $queryFilterMust[] = [
                    "terms" => [ $termKey => $termVal ]
                ];
            } else {
                $queryFilterMust[] = [
                    "term" => [ $termKey => $termVal ]
                ];
            }
        }

        $query = [
            "constant_score" => [
                "filter" => [
                    "bool" => [
                        "must" => $queryFilterMust
                    ]
                ]
            ]
        ];
        return $query;
    }

    private static function fetchElasticData($elasticQuery, $requestData = null) {
        $pageStart = Si4Util::getArg($requestData, "pageStart", 0);
        $pageCount = Si4Util::getArg($requestData, "pageCount", 20);
        $sortField = Si4Util::getArg($requestData, "sortField", "id");
        $sortOrder = Si4Util::getArg($requestData, "sortOrder", "asc");
        $dataElastic = ElasticHelpers::search($elasticQuery, $pageStart, $pageCount, $sortField, $sortOrder);
        return $dataElastic;
    }

    private static function processElasticResponse($hits) {
        $result = [];
        foreach ($hits as $id => $hit) {

            //print_r($entity);

            $handle_id = "";
            $parent = 0;
            $primary = 0;
            $title = "";
            $creator = "";
            $date = "";
            $xml = "";
            $data = null;

            $fileName = "";
            $fileUrl = "";

            $structType = "";
            $entityType = "";

            $_source = Si4Util::getArg($hit, "_source", null);

            if ($_source) {
                $structType = Si4Util::getArg($_source, "struct_type", "");
                $entityType = Si4Util::getArg($_source, "entity_type", "");

                $handle_id = Si4Util::getArg($_source, "handle_id", "");
                $parent = Si4Util::getArg($_source, "parent", "");
                $primary = Si4Util::getArg($_source, "primary", "");
                $data = Si4Util::getArg($_source, "data", null);
                $xml = Si4Util::getArg($_source, "xml", "");
                $active = Si4Util::getArg($_source, "active", 0);

                //print_r($data);

                $dcMetadata = Si4Util::pathArg($data, "dmd/dc", []);
                $title = isset($dcMetadata["title"]) ? join(" : ", $dcMetadata["title"]) : "";
                $creator = isset($dcMetadata["creator"]) ? join("; ", $dcMetadata["creator"]) : "";
                $date = isset($dcMetadata["date"]) ? join("; ", $dcMetadata["date"]) : "";

                $fileName = Si4Util::pathArg($data, "files/0/id", "");
                if ($fileName) $fileUrl = FileHelpers::getPreviewUrl($id, $fileName);

            }

            $result[] = [
                "id" => $id,
                "handle_id" => $handle_id,
                "parent" => $parent,
                "primary" => $primary,
                "struct_type" => $structType,
                "entity_type" => $entityType,
                "title" => $title,
                "creator" => $creator,
                "date" => $date,
                "fileName" => $fileName,
                "fileUrl" => $fileUrl,
                "active" => $active,
                "xmlData" => $xml,
                "elasticData" => $data,
            ];
        }

        return $result;
    }



    /*

    public static function selectEntities($requestData) {
        $pageStart = Si4Util::getArg($requestData, "pageStart", 0);
        $pageCount = Si4Util::getArg($requestData, "pageCount", 20);
        $sortField = Si4Util::getArg($requestData, "sortField", "id");
        $sortOrder = Si4Util::getArg($requestData, "sortOrder", "asc");

        $parent = Si4Util::getArg($requestData, "parent", null);
        $entityIds = Si4Util::getArg($requestData, "entityIds", null);

        //$staticData = Si4Util::getArg($requestData, "staticData", []);
        //$struct_type  = Si4Util::getArg($staticData, "struct_type", "");
        $filter = Si4Util::getArg($requestData, "filter", []);
        $struct_type  = Si4Util::getArg($filter, "struct_type", "");


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

            $handle_id = "";
            $parent = 0;
            $primary = 0;
            $IDAttr = "";
            $title = "";
            $creator = "";
            $date = "";
            $xml = "";
            $data = null;

            $fileName = "";
            $fileUrl = "";

            $structType = "";
            $entityType = "";

            $_source = Si4Util::getArg($hit, "_source", null);

            if ($_source) {
                $structType = Si4Util::getArg($_source, "struct_type", "");
                $entityType = Si4Util::getArg($_source, "entity_type", "");

                $handle_id = Si4Util::getArg($_source, "handle_id", "");
                $parent = Si4Util::getArg($_source, "parent", 0);
                $primary = Si4Util::getArg($_source, "primary", 0);
                $data = Si4Util::getArg($_source, "data", null);
                $xml = Si4Util::getArg($_source, "xml", "");

                //print_r($data);

                $dcMetadata = Si4Util::pathArg($data, "dmd/dc", []);
                $IDAttr = Si4Util::getArg($data, "id", "");
                $title = isset($dcMetadata["title"]) ? join(" : ", $dcMetadata["title"]) : "";
                $creator = isset($dcMetadata["creator"]) ? join("; ", $dcMetadata["creator"]) : "";
                $date = isset($dcMetadata["date"]) ? join("; ", $dcMetadata["date"]) : "";

                $fileName = Si4Util::pathArg($data, "files/0/id", "");
                if ($fileName) $fileUrl = FileHelpers::getPreviewUrl($id, $fileName);

            }

            $result[] = [
                "id" => $id,
                "handle_id" => $handle_id,
                "parent" => $parent,
                "primary" => $primary,
                "struct_type" => $structType,
                "entity_type" => $entityType,
                //"IdAttr" => $IDAttr,
                "title" => $title,
                "creator" => $creator,
                "date" => $date,
                "fileName" => $fileName,
                "fileUrl" => $fileUrl,
                "xmlData" => $xml,
                "elasticData" => $data,
            ];
        }

        return ["status" => true, "data" => $result, "rowCount" => $rowCount, "error" => null];
    }
    */

    public static function selectEntityHierarchy($requestData) {
        $handle_id = Si4Util::getArg($requestData, "handle_id", null);
        $entity = Si4Util::getArg($requestData, "entity", null);

        if (!$handle_id) {
            return ["status" => false, "error" => "No handle_id given"];
        }

        $parents = [];
        $children = [];

        // Select current entity by Id
        if (!$entity) {
            $entity = EntitySelect::selectEntitiesByHandleIds([$handle_id]);
            $entity = isset($entity["data"]) && isset($entity["data"][0]) ? $entity["data"][0] : null;
        }

        // Select parent entity

        $parentId = $entity && isset($entity["parent"]) && $entity["parent"] ? $entity["parent"] : false;
        while ($parentId) {
            $parentEntity = EntitySelect::selectEntitiesByHandleIds([$parentId]);
            $parentEntity = isset($parentEntity["data"]) && isset($parentEntity["data"][0]) ? $parentEntity["data"][0] : null;
            if ($parentEntity) {
                array_unshift($parents, $parentEntity);
                $parentId = $parentEntity["parent"];
            } else {
                $parentId = null;
            }
        }

        // Select child entities
        $children = EntitySelect::selectEntitiesByParentHandle($entity["handle_id"]);
        $children = isset($children["data"]) ? $children["data"] : [];

        //print_r(["children" => $children]);

        return ["status" => true, "data" => [
            "parents" => $parents,
            "currentEntity" => $entity,
            "children" => $children,
        ]];
    }

}