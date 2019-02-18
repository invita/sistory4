<?php
namespace App\Helpers;
use App\Models\Behaviour;
use App\Models\Si4Field;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class ElasticHelpers
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */

const ADV_SEARCH_OPERATOR_AND = "and";
const ADV_SEARCH_OPERATOR_OR = "or";

const SEARCH_TYPE_ALL = "all";
const SEARCH_TYPE_COLLECTION = "collection";
const SEARCH_TYPE_ENTITY = "entity";
const SEARCH_TYPE_FILE = "file";
const SEARCH_TYPE_FULL_TEXT = "fullText";

class ElasticHelpers
{
    public static $advancedSearchOperators = [
        ADV_SEARCH_OPERATOR_AND,
        ADV_SEARCH_OPERATOR_OR
    ];

    private static $_advancedSearchFieldsMap = null;
    public static function getAdvancedSearchFieldsMap() {
        if (!self::$_advancedSearchFieldsMap) {
            $si4fields = Si4Field::getSi4FieldsArray();
            self::$_advancedSearchFieldsMap = [];
            foreach ($si4fields as $fieldName => $si4field) {
                //if ($si4field["enable_adv_search"])
                self::$_advancedSearchFieldsMap[$fieldName] = "data.si4.".$fieldName.".value";
            }
        }
        return self::$_advancedSearchFieldsMap;
    }

    public static $searchTypes = [
        SEARCH_TYPE_ALL,
        SEARCH_TYPE_COLLECTION,
        SEARCH_TYPE_ENTITY,
        SEARCH_TYPE_FILE,
        SEARCH_TYPE_FULL_TEXT
    ];

    public static function getTopMenuHandleId() {
        return env("SI4_ELASTIC_TOP_MENU_COLLECTION", "menuTop");
    }
    public static function getBottomMenuHandleId() {
        return env("SI4_ELASTIC_BOTTOM_MENU_COLLECTION", "menuBottom");
    }


    /**
     * Delete and create index
     * @param $entityId Integer entity id to index
     * @param $body Array body to index
     * @return array
     */
    public static function recreateIndex()
    {
        $indexExists = \Elasticsearch::connection()->indices()->exists([
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities")
        ]);
        if ($indexExists) {
            $deleteIndexArgs = [
                "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            ];
            \Elasticsearch::connection()->indices()->delete($deleteIndexArgs);
        }

        $createIndexArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
        ];
        $createIndexArgs["body"] = <<<HERE
{
    "settings": {
        "number_of_shards": 1,
        "number_of_replicas": 0,
        "analysis": {
            "analyzer": {
                "lowercase_analyzer": {
                    "type": "custom",
                    "tokenizer": "standard",
                    "filter": [ "lowercase" ]
                }
            }
        }
    },
    "mappings": {
        "entity": {
            "date_detection": false,
            "properties": {
                "id": {
                    "type": "integer"
                },
                "handle_id": {
                    "type": "text"
                },
                "child_order": {
                    "type": "integer"
                },
                "data.si4.title.value": {
                    "type": "text",
                    "analyzer": "lowercase_analyzer"
                },
                "data.si4.creator.value": {
                    "type": "text",
                    "analyzer": "lowercase_analyzer"
                },
                "data.files.fullText": {
                    "type": "text",
                    "analyzer": "lowercase_analyzer",
                    "term_vector": "with_positions_offsets"
                }
            }
        }
    }
}
HERE;

        return \Elasticsearch::connection()->indices()->create($createIndexArgs);
    }


    /**
     * Refresh the index.
     * Elastic does that periodically on its own. For performance reasons not for every document.
     * Call this method to refresh the index instantly so recently indexed documents become available.
     * @return array
     */
    public static function refreshIndex()
    {
        return \Elasticsearch::connection()->indices()->refresh([
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities")
        ]);
    }


    /**
     * Sends a document to elastic search to be indexed
     * @param $entityId Integer entity id to index
     * @param $body Array body to index
     * @return array
     */
    public static function indexEntity($entityId, $body)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "id" => $entityId,
            "body" => $body
        ];
        return \Elasticsearch::connection()->index($requestArgs);
    }

    /**
     * Delete a document from elastic search index
     * @param $entityId Integer entity id to delete
     * @return array
     */
    public static function deleteEntity($entityId)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "id" => $entityId,
        ];
        return \Elasticsearch::connection()->delete($requestArgs);
    }

    /**
     * Check wether a document exists in elastic index
     * @param $entityId Integer entity id to be found
     * @return array
     */
    public static function plainEntityById($entityId)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "body" => [
                "query" => [
                    "ids" => [
                        "values" => [$entityId]
                    ]
                ]
            ]
        ];
        $elasticData = \Elasticsearch::connection()->search($requestArgs);
        return $elasticData;
    }

    /**
     * Check wether a document exists in elastic index
     * @param $entityId Integer entity id to be found
     * @return array
     */
    public static function entityExists($entityId)
    {
        $elasticData = self::plainEntityById($entityId);
        $rowCount = Si4Util::pathArg($elasticData, "hits/total", 0);
        return $rowCount > 0;
    }

    /**
     * Retrieves all matching documents from elastic search
     * @param $query array elastic search expression to match
     * @param $offset Integer offset
     * @param $limit Integer limit
     * @param $sortField string sortField
     * @param $sortDir string sort direction (asc/desc)
     * @return array
     */
    public static function search($query, $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "child_order", $sortDir = "asc", $highlight = null)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "body" => [
                "query" => $query,
                "sort" => [$sortField => [ "order" => $sortDir ]],
                "from" => $offset,
                "size" => $limit,
            ]
        ];

        if ($highlight) {
            $requestArgs["body"]["highlight"] = $highlight;
        }

        return \Elasticsearch::connection()->search($requestArgs);
    }

    /**
     * Retrieves all matching documents from elastic search
     * @param $queryString string to match
     * @param $offset Integer offset
     * @param $limit Integer limit
     * @param $sortField string sortField
     * @param $sortDir string sort direction (asc/desc)
     * @return array
     */
    public static function searchString($queryString, $searchType = "all", $hdl = "", $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "child_order", $sortDir = "asc")
    {

        $searchFields = [
            "data.si4.creator.value",
            "data.si4.title.value",
        ];

        $searchWords = explode(" ", $queryString);
        $must = [];
        $should = [];
        $highlight = null;

        foreach ($searchWords as $wordIdx => $searchWord) {
            $must[] = [
                "query_string" => [
                    "fields" => $searchFields,
                    "query" => '*'.$searchWord.'*'
                ],
            ];
        }

        // Apply search type filter
        switch ($searchType) {
            case SEARCH_TYPE_ALL:
                // No additional filter
                break;

            case SEARCH_TYPE_COLLECTION:
                $must[] = [
                    "query_string" => [
                        "fields" => ["struct_type"],
                        "query" => 'collection'
                    ],
                ];
                break;

            case SEARCH_TYPE_ENTITY:
                $must[] = [
                    "query_string" => [
                        "fields" => ["struct_type"],
                        "query" => 'entity'
                    ],
                ];
                break;

            case SEARCH_TYPE_FILE:
                $must[] = [
                    "query_string" => [
                        "fields" => ["struct_type"],
                        "query" => 'file'
                    ],
                ];
                break;
            case SEARCH_TYPE_FULL_TEXT:
                $should = $must;
                $must = [[
                    "query_string" => [
                        "fields" => ["data.files.fullText"],
                        "query" => $queryString
                    ],
                ]];
                if (env("SI4_ELASTIC_HIGHLIGHT")) {
                    $highlight = [
                        "fields" => [
                            "data.files.fullText" => [
                                "fragment_size" => 110,
                                "number_of_fragments" => 3,
                                "pre_tags" => [""],
                                "post_tags" => [""]
                            ]
                        ]
                    ];
                }
                break;
        }

        // Apply handle_id filter
        if ($hdl) {
            // TODO: Must index parent hierarchy to enable search for nested children
            // For now find only first level children, whose parent is directly $hdl
            $must[] = [
                "query_string" => [
                    "fields" => ["parent"],
                    "query" => $hdl
                ],
            ];
        }

        $query = [ "bool" => [] ];
        if (count($should)) $query["bool"]["should"] = $should;
        if (count($must)) $query["bool"]["must"] = $must;

        return self::search($query, $offset, $limit, "child_order", "asc", $highlight);
    }

    /**
     * Retrieves all matching documents from elastic search using bool query
     * @param $params array of params with structure:
     * [
     *   [
     *     "operator" => "...",   // One of $advancedSearchOperators
     *     "fieldName" => "...",  // A key in advancedSearchFieldsMap
     *     "fieldValue" => "..."  // String
     *   ],
     *   ...
     * ]
     * @param $offset Integer offset
     * @param $limit Integer limit
     * @param $sortField string sortField
     * @param $sortDir string sort direction (asc/desc)
     * @return array
     */
    public static function searchAdvanced($params, $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "child_order", $sortDir = "asc")
    {
        // "should" query is OR
        // "must" query is AND

        $must = [];
        $should = [];

        $query = [ "bool" => [] ];

        $allowedFieldNames = array_keys(self::getAdvancedSearchFieldsMap());

        foreach ($params as $param) {
            if (!isset($param["operator"]) || !isset($param["fieldName"]) || !isset($param["fieldValue"])) {
                throw new Exception("Advanced search parameters invalid");
            }

            $operator = $param["operator"];
            $fieldName = $param["fieldName"];
            $fieldValue = $param["fieldValue"];

            if (!in_array($operator, self::$advancedSearchOperators)) {
                throw new Exception("Invalid operator: ".$operator);
            }
            if (!in_array($fieldName, $allowedFieldNames)) {
                throw new Exception("fieldName not allowed: ".$fieldName);
            }

            $fieldElastic = self::getAdvancedSearchFieldsMap()[$fieldName];

            $queryString = [
                "query_string" => [
                    "fields" => [
                        $fieldElastic,
                    ],
                    "query" => $fieldValue
                ],
            ];

            switch ($operator) {
                case ADV_SEARCH_OPERATOR_AND:
                    $must[] = $queryString;
                    break;
                case ADV_SEARCH_OPERATOR_OR:
                    $should[] = $queryString;
                    break;
            }
        }


        if (count($must)) {
            $query["bool"]["must"] = $must;
        }
        if (count($should)) {
            $query["bool"]["should"] = $should;
        }

        //print_r($query);

        return self::search($query, $offset, $limit, $sortField, $sortDir);
    }



    public static function suggestCreators($creatorTerm, $searchType = "all", $limit = 30)
    {
        /*
        $creatorWords = explode(" ", $creatorTerm);
        $must = [];

        foreach ($creatorWords as $creatorWord) {
            $must[] = [
                "query_string" => [
                    "fields" => [
                        "data.si4.creator.value",
                    ],
                    "query" => $creatorWord."*"
                ],
            ];
        }
        $query = [
            "bool" => [ "must" => $must ]
        ];
        */

        $creatorWords = explode(" ", $creatorTerm);
        $creatorSimple = "";
        if (count($creatorWords) > 0) $creatorSimple .= $creatorWords[0];
        if (count($creatorWords) > 1) $creatorSimple .= " ".$creatorWords[1];

        $queryStringWild = $creatorSimple."*";
        $query = [
            "query_string" => [
                "fields" => [
                    "data.si4.creator.value",
                ],
                "query" => $queryStringWild
            ]
        ];

        return self::search($query, 0, $limit, "child_order", "asc");
    }
    public static function suggestTitlesForCreator($creator, $title, $searchType = "all", $limit = 30)
    {
        $must = [];

        if ($creator) {
            $creatorWords = explode(" ", $creator);
            foreach ($creatorWords as $creatorWord) {
                $must[] = [
                    "query_string" => [
                        "fields" => [
                            "data.si4.creator.value",
                        ],
                        "query" => $creatorWord
                    ],
                ];
            }
        }

        $titleWords = explode(" ", $title);
        foreach ($titleWords as $titleWord) {
            $must[] = [
                "query_string" => [
                    "fields" => [
                        "data.si4.title.value",
                    ],
                    "query" => $titleWord."*"
                ],
            ];
        }

        $query = [
            "bool" => [ "must" => $must ]
        ];

        return self::search($query, 0, $limit, "child_order", "asc");
    }


    public static function searchByIdArray($idArray)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "body" => [
                "query" => [
                    "ids" => [
                        "values" => $idArray
                    ]
                ]
            ]
        ];
        $dataElastic = \Elasticsearch::connection()->search($requestArgs);
        return self::mergeElasticResultAndIdArray($dataElastic, $idArray);
    }


    public static function searchByHandleArray($handleArray)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "body" => [
                "query" => [
                    "constant_score" => [
                        "filter" => [
                            "terms" => [
                                "handle_id" => $handleArray
                            ]
                        ]
                    ]
                ],
                "from" => 0,
                "size" => count($handleArray),
            ]
        ];

        $dataElastic = \Elasticsearch::connection()->search($requestArgs);
        return self::elasticResultToAssocArray($dataElastic);
    }

    public static function searchMust($filters = [], $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "child_order", $sortDir = "asc")
    {
        foreach ($filters as $fieldName => $fieldValue) {
            $must[] = [
                "query_string" => [
                    "fields" => [$fieldName],
                    "query" => $fieldValue
                ],
            ];
        }

        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "body" => [
                "query" => [
                    "bool" => [
                        "must" => $must
                    ]
                ],
                "sort" => [$sortField => [ "order" => $sortDir ]],
                "from" => $offset,
                "size" => $limit,
            ]
        ];

        $dataElastic = \Elasticsearch::connection()->search($requestArgs);

        //print_r($dataElastic);

        return self::elasticResultToAssocArray($dataElastic);
        // self::mergeElasticResultAndIdArray($dataElastic, $idArray);
    }


    public static function searchParentsRecursive($firstParent)
    {
        $parent = $firstParent;
        $result = [];
        $step = 0;
        $maxSteps = 100;
        while ($parent) {

            // Sanity check
            if ($step > $maxSteps) break;

            $requestArgs = [
                "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
                "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
                "body" => [
                    "query" => [
                        "match" => [
                            "handle_id" => $parent
                        ]
                    ],
                    "size" => 1,
                ]
            ];

            $dataElastic = \Elasticsearch::connection()->search($requestArgs);
            $parentDataAssoc = self::elasticResultToAssocArray($dataElastic);
            if (count($parentDataAssoc)) {
                $first = $parentDataAssoc[array_keys($parentDataAssoc)[0]];
                $result[] = $first;
                $parent = $first["_source"]["parent"];
            } else {
                break;
            }
        }

        return $result;
    }


    public static function searchChildren($parent, $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sort = null)
    {
        if (!$sort) {
            $sort = [
                ["child_order" => [ "order" => "asc" ]]
            ];
        }

        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "body" => [
                "query" => [
                    "match" => [
                        "parent" => $parent
                    ]
                ],
                "sort" => $sort,
                "from" => $offset,
                "size" => $limit,
            ]
        ];

        $dataElastic = \Elasticsearch::connection()->search($requestArgs);

        //print_r($dataElastic);

        return self::elasticResultToAssocArray($dataElastic);
        // self::mergeElasticResultAndIdArray($dataElastic, $idArray);
    }


    public static function elasticSortFromString($sortStr, $defaultFieldName = "child_order", $defaultOrder = "asc") {
        if ($sortStr && is_string($sortStr)) {
            $exp = explode(" ", $sortStr);
            $fieldName = isset($exp[0]) ? $exp[0] : $defaultFieldName;
            $order = isset($exp[1]) ? $exp[1] : $defaultOrder;

            $fieldName = strtolower($fieldName);
            $si4fields = array_keys(Si4Field::getSi4FieldsArray());
            if (!in_array($fieldName, $si4fields)) {
                $fieldName = $defaultFieldName;
                if (!in_array($fieldName, $si4fields)) $fieldName = "child_order";
            } else {
                // Valid si4 field
                $fieldName = "data.si4.".$fieldName.".value";
            }

            $orders = ["desc", "asc"];
            $order = @strtolower($order);
            if ($order === "descending") $order = "desc";
            if ($order === "ascending") $order = "asc";
            if (!in_array($order, $orders)) $order = $defaultOrder;
            if (!in_array($order, $orders)) $order = "asc";

        } else {
            $fieldName = $defaultFieldName;
            $order = $defaultOrder;
        }
        $result = [
            [$fieldName => [ "order" => $order ]]
        ];
        return $result;
    }


    public static function elasticResultToAssocArray($dataElastic) {
        $result = [];
        if (isset($dataElastic["hits"]) && isset($dataElastic["hits"]["hits"])) {
            foreach ($dataElastic["hits"]["hits"] as $hit){
                $result[$hit["_id"]] = [
                    "id" => $hit["_id"],
                    "_source" => $hit["_source"],
                ];
                // Structure highlights if exist
                if (isset($hit["highlight"])) {
                    $result[$hit["_id"]]["highlight"] = [];
                    foreach ($hit["highlight"] as $highlightField => $highlightValuesArray) {
                        foreach ($highlightValuesArray as $highlightValue) {
                            $result[$hit["_id"]]["highlight"][] = $highlightValue;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public static function mergeElasticResultAndIdArray($dataElastic, $idArray) {
        $hits = self::elasticResultToAssocArray($dataElastic);

        $result = [];
        foreach ($idArray as $id) $result[$id] = ["id" => $id];
        foreach ($result as $i => $val) {
            if (isset($hits[$i])) $result[$i]["_source"] = $hits[$i]["_source"];
        }
        return $result;
    }

    public static function elasticAssocStripXml($assocData) {
        $result = [];
        foreach ($assocData as $doc) {
            unset($doc["_source"]["xml"]);
            $result[] = $doc;
        }
        return $result;
    }

    public static function getDCField($assocData, $fieldName) {

    }

    public static function escapeValue($value) {
        if (!is_string($value)) return $value;
        return str_replace(".", "\\.", $value);
    }
}