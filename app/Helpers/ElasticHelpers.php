<?php
namespace App\Helpers;

/**
 * Class ElasticHelpers
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class ElasticHelpers
{

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
                "data.dmd.dc.title.text": {
                    "type": "string",
                    "analyzer": "lowercase_analyzer"
                },
                "data.dmd.dc.creator.text": {
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
    public static function search($query, $offset = 0, $limit = 20, $sortField = "id", $sortDir = "asc")
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
     * @param $queryString a string to match
     * @param $offset Integer offset
     * @param $limit Integer limit
     * @param $sortField string sortField
     * @param $sortDir string sort direction (asc/desc)
     * @return array
     */
    public static function searchString($queryString, $offset = 0, $limit = 20, $sortField = "id", $sortDir = "asc")
    {
        $query = [
            "query_string" => [
                "default_field" => "_all",
                "query" => $queryString
            ]
        ];
        return self::search($query, $offset, $limit, $sortField, $sortDir);
    }

    /*
    public static function suggestEntities($queryString, $limit = 20)
    {

        $queryStringWild = $queryString."*";

        $query = [
            "query_string" => [
                "fields" => [
                    "data.dmd.dc.title.value",
                    "data.dmd.dc.creator.value",
                    "data.dmd.dc.date.value",
                ],
                "query" => $queryStringWild
            ]
        ];
        return self::search($query, 0, $limit, "id", "asc");
    }
    */

    public static function suggestCreators($queryString, $limit = 20)
    {
        $queryStringWild = $queryString."*";
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
    public static function suggestTitlesForCreator($creator, $title, $limit = 20)
    {
        $titleWildcard = $title."*";
        $creatorWildcard = $creator."*";
        $query = [
            "bool" => [
                "must" => [
                    "query_string" => [
                        "fields" => [
                            "data.dmd.dc.title.value",
                        ],
                        "query" => $titleWildcard
                    ]
                ],
                "must" => [
                    "query_string" => [
                        "fields" => [
                            "data.dmd.dc.creator.value",
                        ],
                        "query" => $creatorWildcard
                    ]
                ]
            ]
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

    public static function searchByParent($parent, $offset = 0, $limit = 20, $sortField = "id", $sortDir = "asc")
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


    public static function searchChildren($parent, $offset = 0, $limit = 20)
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