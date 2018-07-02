<?php
namespace App\Helpers;

use App\Models\Elastic\EntityNotIndexedException;
use App\Models\Entity;
use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $terms["handle_id"] = array_map(function($x) { return strtolower($x); }, $handleIds);
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

    // Select entities from mysql database (no elastic)
    public static function selectEntitiesFromDb($requestData = null) {
        $filter = Si4Util::getArg($requestData, "filter", []);
        $pageStart = Si4Util::getArg($requestData, "pageStart", 0);
        $pageCount = Si4Util::getArg($requestData, "pageCount", 20);

        $query = DB::table("entities")->where($filter)->offset($pageStart)->limit($pageCount);

        $rowCount = $query->count();

        $data = $query->get();

        $result = [];
        foreach ($data as $idx => $row) {
            $rowArray = (array)$row;
            $rowData = array_merge($rowArray, [
                "title" => "",
                "creator" => "",
                "date" => "",
                "fileName" => "",
                "fileUrl" => "",
                "fileThumb" => "",
                "fileMimeType" => "",
                "fileSize" => "",
                "fileTimestamp" => "",
                "fileChecksum" => "",
                "fileChecksumType" => "",
                "active" => "",
                "xmlData" => $rowArray["data"],
                //"elasticData" => $data,
            ]);
            unset($rowData["data"]);

            $result[] = $rowData;
        }

        return ["status" => true, "data" => $result, "rowCount" => $rowCount, "error" => null];
    }


    // ********** ********** **********


    // Map filter fields, to match elastic structure
    private static $filterMap = [
        "id" => "id",
        "handle_id" => "handle_id",
        "parent" => "parent",
        "primary" => "primary",
        "collection" => "collection",
        "struct_type" => "struct_type",
        "struct_subtype" => "struct_subtype",
        "entity_type" => "entity_type",
        "title" => "data.dmd.dc.title.value",
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

        $nonWildCardKeys = ["id"];

        foreach ($terms as $termKey => $termVal) {
            if (is_array($termVal)) {
                $queryFilterMust[] = [
                    "terms" => [ $termKey => $termVal ]
                ];
            } else {
                if (in_array($termKey, $nonWildCardKeys)) {
                    $queryFilterMust[] = [
                        "term" => [ $termKey => $termVal ]
                    ];
                } else {
                    $queryFilterMust[] = [
                        "wildcard" => [ $termKey => $termVal ]
                    ];
                }
            }
        }

        $query = [
            "constant_score" => [
                "query" => [
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
            $parent = "";
            $primary = "";
            $title = "";
            $creator = "";
            $date = "";
            $xml = "";
            $data = null;

            $fileName = "";
            $fileUrl = "";
            $fileThumb = "";
            $fileMimeType = "";
            $fileSize = 0;
            $fileTimestamp = "";
            $fileChecksum = "";
            $fileChecksumType = "";

            $structType = "";
            $entityType = "";
            $entitySubtype = "";

            $_source = Si4Util::getArg($hit, "_source", null);

            if ($_source) {
                $structType = Si4Util::getArg($_source, "struct_type", "");
                $structSubtype = Si4Util::getArg($_source, "struct_subtype", "");
                $entityType = Si4Util::getArg($_source, "entity_type", "");

                $handle_id = Si4Util::getArg($_source, "handle_id", "");
                $parent = Si4Util::getArg($_source, "parent", "");
                $primary = Si4Util::getArg($_source, "primary", "");
                $collection = Si4Util::getArg($_source, "collection", "");
                $data = Si4Util::getArg($_source, "data", null);
                $xml = Si4Util::getArg($_source, "xml", "");
                $active = Si4Util::getArg($_source, "active", 0);

                //print_r($data);

                $dcMetadata = Si4Util::pathArg($data, "dmd/dc", []);
                $title = isset($dcMetadata["title"]) ? join(" : ", DcHelpers::dcTextArray($dcMetadata["title"])) : "";
                $creator = isset($dcMetadata["creator"]) ? join(" : ", DcHelpers::dcTextArray($dcMetadata["creator"])) : "";
                //$date = isset($dcMetadata["date"]) ? join("; ", $dcMetadata["date"]) : "";
                $date = isset($dcMetadata["date"]) ? join(", ", DcHelpers::dcTextArray($dcMetadata["date"])) : "";

                $fileName = Si4Util::pathArg($data, "files/0/ownerId", "");
                if ($fileName) {
                    $fileUrl = FileHelpers::getPreviewUrl($parent, $structType, $fileName);
                    $fileThumb = FileHelpers::getThumbUrl($parent, $structType, $fileName);
                    $fileMimeType = Si4Util::pathArg($data, "files/0/mimeType", "");
                    $fileSize = Si4Util::pathArg($data, "files/0/size", "");
                    $fileTimestamp = Si4Util::pathArg($data, "files/0/created", "");
                    $fileChecksum = Si4Util::pathArg($data, "files/0/checksum", "");
                    $fileChecksumType = Si4Util::pathArg($data, "files/0/checksumType", "");
                }
            }

            $result[] = [
                "id" => $id,
                "handle_id" => $handle_id,
                "parent" => $parent,
                "primary" => $primary,
                "collection" => $collection,
                "struct_type" => $structType,
                "struct_subtype" => $structSubtype,
                "entity_type" => $entityType,
                "title" => $title,
                "creator" => $creator,
                "date" => $date,
                "fileName" => $fileName,
                "fileUrl" => $fileUrl,
                "fileThumb" => $fileThumb,
                "fileMimeType" => $fileMimeType,
                "fileSize" => $fileSize,
                "fileTimestamp" => $fileTimestamp,
                "fileChecksum" => $fileChecksum,
                "fileChecksumType" => $fileChecksumType,
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

    // Recursive helper function visits each collection in a graph
    // and appends children attribute from so called parentMap
    private static function _selectMenu_recurseChildren($handle_id, $parentMap) {
        if (!isset($parentMap[$handle_id])) return [];
        $result = $parentMap[$handle_id];
        foreach ($result as $idx => $childDoc) {
            $result[$idx]["children"] = self::_selectMenu_recurseChildren($childDoc["handle_id"], $parentMap);
        }
        return $result;
    }

    // Select Top Menu collections from Elastic
    private static $_topMenu = null;
    public static function selectTopMenu() {
        if (self::$_topMenu) return self::$_topMenu;

        $elasticQuery = [
            "constant_score" => [
                "query" => [
                    "bool" => [
                        "must" => [
                            ["term" => [
                                "struct_type" => "collection"
                            ]],
                            ["term" => [
                                "active" => 1
                            ]]
                        ],
                    ]
                ]
            ]
        ];

        $dataElastic = ElasticHelpers::search($elasticQuery, 0, 10000);
        $hits = Si4Util::pathArg($dataElastic, "hits/hits", []);

        $parentMap = [];
        $result = [];

        foreach ($hits as $hit) {
            $source = $hit["_source"];
            $handle_id = $source["handle_id"];
            $parent = $source["parent"];

            $title = Si4Util::pathArg($source, "data/dmd/dc/title/0/value", "");

            $parentKey = $parent ? $parent : "_noparent";
            if (!isset($parentMap[$parentKey])) $parentMap[$parentKey] = [];
            $parentMap[$parentKey][] = [
                "handle_id" => $handle_id,
                "parent" => $parent,
                "title" => $title,
            ];
        }

        $topMenuHandle = ElasticHelpers::getTopMenuHandleId();

        if (isset($parentMap[$topMenuHandle])) {
            foreach ($parentMap[$topMenuHandle] as $rootDoc) {
                $rootDoc["children"] = self::_selectMenu_recurseChildren($rootDoc["handle_id"], $parentMap);
                $result[] = $rootDoc;
            }
        }

        self::$_topMenu = $result;

        return $result;
    }


    // Select Bottom Menu collections from Elastic
    private static $_bottomMenu = null;
    public static function selectBottomMenu() {
        if (self::$_bottomMenu) return self::$_bottomMenu;

        $elasticQuery = [
            "constant_score" => [
                "query" => [
                    "bool" => [
                        "must" => [
                            ["term" => [
                                "struct_type" => "collection"
                            ]],
                            ["term" => [
                                "active" => 1
                            ]]
                        ],
                    ]
                ]
            ]
        ];

        $dataElastic = ElasticHelpers::search($elasticQuery, 0, 10000);
        $hits = Si4Util::pathArg($dataElastic, "hits/hits", []);

        $parentMap = [];
        $result = [];

        foreach ($hits as $hit) {
            $source = $hit["_source"];
            $handle_id = $source["handle_id"];
            $parent = $source["parent"];

            $title = Si4Util::pathArg($source, "data/dmd/dc/title/0/text", "");

            $parentKey = $parent ? $parent : "_noparent";
            if (!isset($parentMap[$parentKey])) $parentMap[$parentKey] = [];
            $parentMap[$parentKey][] = [
                "handle_id" => $handle_id,
                "parent" => $parent,
                "title" => $title,
            ];
        }

        $bottomMenuHandle = ElasticHelpers::getBottomMenuHandleId();

        if (isset($parentMap[$bottomMenuHandle])) {
            foreach ($parentMap[$bottomMenuHandle] as $rootDoc) {
                $rootDoc["children"] = self::_selectMenu_recurseChildren($rootDoc["handle_id"], $parentMap);
                $result[] = $rootDoc;
            }
        }

        self::$_bottomMenu = $result;
        return $result;
    }


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

        $found_handle_id = $entity && isset($entity["handle_id"]) && $entity["handle_id"] ? $entity["handle_id"] : false;
        if (!$found_handle_id) {
            throw new EntityNotIndexedException("Entity ".$handle_id." not found in Elastic index");
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
        $children = EntitySelect::selectEntitiesByParentHandle($handle_id);
        $children = isset($children["data"]) ? $children["data"] : [];

        //print_r(["children" => $children]);

        return ["status" => true, "data" => [
            "parents" => $parents,
            "currentEntity" => $entity,
            "children" => $children,
        ]];
    }


    public static function selectParentHierarchy($first_parent_handle_id) {

        $parents = [];
        if (!$first_parent_handle_id) return ["status" => true, "data" => []];

        // Select first parent entity by handle
        $entity = EntitySelect::selectEntitiesByHandleIds([$first_parent_handle_id]);
        $entity = isset($entity["data"]) && isset($entity["data"][0]) ? $entity["data"][0] : null;

        $found_handle_id = $entity && isset($entity["handle_id"]) && $entity["handle_id"] ? $entity["handle_id"] : false;
        if (!$found_handle_id) {
            throw new EntityNotIndexedException("Entity '".$first_parent_handle_id."' not found in Elastic index");
        }

        array_unshift($parents, $entity);

        // Select grandparent entities
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


        return ["status" => true, "data" => $parents];
    }


    public static function selectChildren($handle_id) {

        $children = EntitySelect::selectEntitiesByParentHandle($handle_id);
        $children = isset($children["data"]) ? $children["data"] : [];

        return ["status" => true, "data" => $children];
    }

}