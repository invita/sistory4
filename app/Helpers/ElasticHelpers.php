<?php
namespace App\Helpers;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class ElasticHelpers
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */

const ADV_SEARCH_OPERATOR_AND = "and";
const ADV_SEARCH_OPERATOR_OR = "or";

class ElasticHelpers
{
    public static $advancedSearchOperators = [ADV_SEARCH_OPERATOR_AND, ADV_SEARCH_OPERATOR_OR];
    public static $advancedSearchFieldMap = [
        "title" => "data.dmd.dc.title.value",
        "creator" => "data.dmd.dc.creator.value",
        "subject" => "data.dmd.dc.subject.value",
        "description" => "data.dmd.dc.description.value",
        "publisher" => "data.dmd.dc.publisher.value",
        "contributor" => "data.dmd.dc.contributor.value",
        "date" => "data.dmd.dc.date.value",
        "format" => "data.dmd.dc.format.value",
        "identifier" => "data.dmd.dc.identifier.value",
        "source" => "data.dmd.dc.source.value",
        "relation" => "data.dmd.dc.relation.value",
        "coverage" => "data.dmd.dc.coverage.value",
        "rights" => "data.dmd.dc.rights.value",
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
                    "type": "string"
                },
                "data.dmd.dc.title.value": {
                    "type": "string",
                    "analyzer": "lowercase_analyzer"
                },
                "data.dmd.dc.creator.value": {
                    "type": "string",
                    "analyzer": "lowercase_analyzer"
                }
            }
        }
    }
}
HERE;

        return \Elasticsearch::connection()->indices()->create($createIndexArgs);
    }


    /**
     * Refresh index
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
    public static function entityExists($entityId)
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
    public static function search($query, $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "id", $sortDir = "asc")
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
    public static function searchString($queryString, $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "id", $sortDir = "asc")
    {

        $searchFields = [
            "data.dmd.dc.creator.value",
            "data.dmd.dc.title.value",
        ];

        $searchWords = explode(" ", $queryString);
        $must = [];
        $should = [];

        foreach ($searchWords as $wordIdx => $searchWord) {
            $must[] = [
                "query_string" => [
                    "fields" => $searchFields,
                    "query" => '*'.$searchWord.'*'
                ],
            ];

            /*
            if ($wordIdx <= 1) {
                $must[] = [
                    "query_string" => [
                        "fields" => ["data.dmd.dc.creator.value"],
                        "query" => "*".$searchWord."*"
                    ],
                ];
            } else {
                $should[] = [
                    "query_string" => [
                        "fields" => $searchFields,
                        "query" => $searchWord
                    ],
                ];
            }
            */
        }

        $query = [ "bool" => [] ];

        if (count($should)) $query["bool"]["should"] = $should;
        if (count($must)) $query["bool"]["must"] = $must;

        return self::search($query, 0, $limit, "id", "asc");


        /*
        $query = [
            "query_string" => [
                "default_field" => "_all",
                "query" => $queryString
            ]
        ];
        return self::search($query, $offset, $limit, $sortField, $sortDir);
        */
    }

    /**
     * Retrieves all matching documents from elastic search using bool query
     * @param $params array of params with structure:
     * [
     *   [
     *     "operator" => "...",   // One of $advancedSearchOperators
     *     "fieldName" => "...",  // A key in $advancedSearchFieldMap
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
    public static function searchAdvanced($params, $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "id", $sortDir = "asc")
    {
        // "should" query is OR
        // "must" query is AND

        $must = [];
        $should = [];

        $query = [ "bool" => [] ];

        $allowedFieldNames = array_keys(self::$advancedSearchFieldMap);

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

            $fieldElastic = self::$advancedSearchFieldMap[$fieldName];

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



    public static function suggestCreators($creatorTerm, $limit = 30)
    {
        /*
        $creatorWords = explode(" ", $creatorTerm);
        $must = [];

        foreach ($creatorWords as $creatorWord) {
            $must[] = [
                "query_string" => [
                    "fields" => [
                        "data.dmd.dc.creator.value",
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
                    "data.dmd.dc.creator.value",
                ],
                "query" => $queryStringWild
            ]
        ];

        return self::search($query, 0, $limit, "id", "asc");
    }
    public static function suggestTitlesForCreator($creator, $title, $limit = 30)
    {
        $must = [];

        if ($creator) {
            $creatorWords = explode(" ", $creator);
            foreach ($creatorWords as $creatorWord) {
                $must[] = [
                    "query_string" => [
                        "fields" => [
                            "data.dmd.dc.creator.value",
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
                        "data.dmd.dc.title.value",
                    ],
                    "query" => $titleWord."*"
                ],
            ];
        }

        $query = [
            "bool" => [ "must" => $must ]
        ];

        return self::search($query, 0, $limit, "id", "asc");
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

    public static function searchMust($filters = [], $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "id", $sortDir = "asc")
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


    public static function searchChildren($parent, $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "body" => [
                "query" => [
                    "match" => [
                        "parent" => $parent
                    ]
                ],
                "sort" => [
                    ["struct_type_sort" => [ "order" => "desc" ]]
                ],

                "from" => $offset,
                "size" => $limit,
            ]
        ];

        $dataElastic = \Elasticsearch::connection()->search($requestArgs);

        //print_r($dataElastic);

        return self::elasticResultToAssocArray($dataElastic);
        // self::mergeElasticResultAndIdArray($dataElastic, $idArray);
    }


    public static function elasticResultToAssocArray($dataElastic) {
        $result = [];
        if (isset($dataElastic["hits"]) && isset($dataElastic["hits"]["hits"])) {
            foreach ($dataElastic["hits"]["hits"] as $hit){
                $result[$hit["_id"]] = [
                    "id" => $hit["_id"],
                    "_source" => $hit["_source"],
                ];
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