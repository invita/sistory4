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

    private static $_advancedSearchDefaultFields = null;
    public static function getAdvancedSearchDefaultFields() {
        if (!self::$_advancedSearchDefaultFields) {
            $defaultBehaviour = Behaviour::getBehaviour("default");
            $advFieldNames = json_decode($defaultBehaviour["advanced_search"], true);
            self::$_advancedSearchDefaultFields = [];
            if ($advFieldNames) {
                foreach ($advFieldNames as $fieldName) {
                    //if ($si4field["enable_adv_search"])
                    self::$_advancedSearchDefaultFields[$fieldName] = "data.si4.".$fieldName.".value";
                }
            }
        }
        return self::$_advancedSearchDefaultFields;
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

        /*
                "left_ngram_analyzer": {
                    "tokenizer": "left_ngram_tokenizer",
                    "filter": [ "lowercase" ]
                }
            "tokenizer": {
                "left_ngram_tokenizer": {
                    "type": "edge_ngram",
                    "min_gram": 3,
                    "max_gram": 20,
                    "token_chars": [
                        "letter",
                        "digit"
                    ]
                }
            }

        */

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
                    "analyzer": "lowercase_analyzer",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "data.si4.creator.value": {
                    "type": "text",
                    "analyzer": "lowercase_analyzer",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
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
/*
                "data.files.fullTextSuggest" : {
                    "type" : "completion",
                    "analyzer": "lowercase_analyzer"
                }
*/

/*
                "left_ngram_analyzer": {
                    "tokenizer": "left_ngram_tokenizer",
                    "filter": [ "lowercase" ]
                }
            },
            "tokenizer": {
                "left_ngram_tokenizer": {
                    "type": "edge_ngram",
                    "min_gram": 3,
                    "max_gram": 20,
                    "token_chars": [
                        "letter",
                        "digit"
                    ]
                }
            }

                "fullText_suggest": {
                    "type": "text",
                    "analyzer": "left_ngram_analyzer",
                    "search_analyzer": "lowercase_analyzer",
                }
*/

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
                "from" => $offset,
                "size" => $limit,
            ]
        ];

        if ($sortField) {
            $requestArgs["body"]["sort"] = [$sortField => [ "order" => $sortDir ]];
        }

        if ($highlight) {
            $requestArgs["body"]["highlight"] = $highlight;
        }

        return \Elasticsearch::connection()->search($requestArgs);
    }


    /**
     * Retrieves all matching documents from elastic search
     * @param $queryString string to match
     * @param $searchType string SEARCH_TYPE
     * @param $offset Integer offset
     * @param $limit Integer limit
     * @param $sortField string sortField
     * @param $sortDir string sort direction (asc/desc)
     * @return array
     */
    public static function searchString($queryString, $searchType = SEARCH_TYPE_ALL, $hdl = "", $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "child_order", $sortDir = "asc")
    {

        $searchFields = [
            "data.si4.creator.value",
            "data.si4.title.value",
        ];

        $must = [];
        $should = [];
        $highlight = null;

        // Apply search string
        switch ($searchType) {
            case SEARCH_TYPE_FULL_TEXT:
                $must[] = [
                    "simple_query_string" => [
                        "fields" => ["data.files.fullText"],
                        "query" => $queryString,
                    ],
                ];
                break;
            default:
                $must[] = [
                    "simple_query_string" => [
                        "fields" => $searchFields,
                        "query" => $queryString,
                        "default_operator" => "and",
                    ],
                ];
                break;

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
                $must[] = [
                    "query_string" => [
                        "fields" => ["struct_type"],
                        "query" => 'file'
                    ],
                ];
                /*
                $should = $must;
                $must = [[
                    "query_string" => [
                        "fields" => ["data.files.fullText"],
                        "query" => $queryString
                    ],
                ]];
                */
                if (env("SI4_ELASTIC_HIGHLIGHT")) {
                    $highlight = [
                        "fields" => [
                            "data.files.fullText" => [
                                "fragment_size" => 110,
                                "number_of_fragments" => env("SI4_ELASTIC_HIGHLIGHT_COUNT"),
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
            $must[] = [
                "query_string" => [
                    "fields" => ["hierarchy"],
                    "query" => $hdl
                ],
            ];
        }

        $query = [ "bool" => [] ];
        if (count($should)) $query["bool"]["should"] = $should;
        if (count($must)) $query["bool"]["must"] = $must;

        //print_r($query);
        return self::search($query, $offset, $limit, "child_order", "asc", $highlight);
    }

    /**
     * Retrieves all matching documents from elastic search
     * @param $queryString string to match
     * @param $searchType string SEARCH_TYPE
     * @param $offset Integer offset
     * @param $limit Integer limit
     * @param $sortField string sortField
     * @param $sortDir string sort direction (asc/desc)
     * @return array
     */
    public static function searchString_old($queryString, $searchType = SEARCH_TYPE_ALL, $hdl = "", $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "child_order", $sortDir = "asc")
    {

        $searchFields = [
            "data.si4.creator.value",
            "data.si4.title.value",
        ];

        $searchWords = self::tokenize($queryString);
        $searchWords = self::cleanUpWords($searchWords);

        //$searchWords = explode(" ", $queryString);
        $must = [];
        $should = [];
        $highlight = null;

        foreach ($searchWords as $wordIdx => $searchWord) {
            /*
            print_r($searchWord." ".
                "isQuoted:".(self::isQuoted($searchWord) ? "true" : "false").", ".
                "replace:".mb_ereg_replace('"', '\"', $searchWord).", ".
                "\n");
            */

            if (self::isQuoted($searchWord)) {
                $s = [
                    "bool" => [ "should" => [] ]
                ];
                foreach ($searchFields as $searchField) {
                    $s["bool"]["should"][] = ["match_phrase" => [ $searchField => $searchWord ]];
                }
                $must[] = $s;
            } else {
                $must[] = [
                    "query_string" => [
                        "fields" => $searchFields,
                        "query" => '*'.$searchWord.'*',
                    ],
                ];
            }

            /*
            $must[] = [
                "query_string" => [
                    "fields" => $searchFields,
                    "query" => mb_ereg_replace('"', '\"', $searchWord),
                    //"query" => self::isQuoted($searchWord) ? $searchWord : '*'.$searchWord.'*',
                ],
            ];
            */
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
            $must[] = [
                "query_string" => [
                    "fields" => ["hierarchy"],
                    "query" => $hdl
                ],
            ];
        }

        $query = [ "bool" => [] ];
        if (count($should)) $query["bool"]["should"] = $should;
        if (count($must)) $query["bool"]["must"] = $must;

        //print_r($query);
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
     * @param $hdl string search inside handle_id
     * @param $offset Integer offset
     * @param $limit Integer limit
     * @param $sortField string sortField
     * @param $sortDir string sort direction (asc/desc)
     * @return array
     */
    public static function searchAdvanced($params, $hdl = null, $offset = 0, $limit = SI4_DEFAULT_PAGINATION_SIZE, $sortField = "child_order", $sortDir = "asc")
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

        if ($hdl) {
            $must[] = [
                "query_string" => [
                    "fields" => ["hierarchy"],
                    "query" => $hdl
                ],
            ];
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



    public static function suggestForField($fieldName, $term, $fieldData = [], $searchType = SEARCH_TYPE_ALL, $parent = null, $limit = 30)
    {
        $words = explode(" ", $term);
        $must = [];
        $should = [];

        foreach($words as $wordIdx => $word) {
            $isLast = $wordIdx === count($words) -1;
            $must[] = [
                "query_string" => [
                    "fields" => [
                        "data.si4.".$fieldName.".value",
                    ],
                    "query" => $isLast ? $word."*" : $word
                ]
            ];
        }


        if ($fieldData && count($fieldData)) {
            $allowedFieldNames = array_keys(self::getAdvancedSearchFieldsMap());
            $queryParams = [];
            foreach ($fieldData as $operAndNameStr => $val) {
                $operAndName = explode("-", $operAndNameStr);
                if (count($operAndName) != 2) continue;
                if (!in_array($operAndName[0], ElasticHelpers::$advancedSearchOperators)) continue;

                $operator = $operAndName[0];
                $pName = $operAndName[1];
                $pValue = $val;

                if ($pName == $fieldName) continue; // skip the one suggesting for
                if (!$pValue) continue; // skip if no value

                $queryParams[] = [
                    "operator" => $operator,
                    "fieldName" => $pName,
                    "fieldValue" => $pValue,
                ];
            }

            foreach ($queryParams as $param) {
                if (!isset($param["operator"]) || !isset($param["fieldName"]) || !isset($param["fieldValue"])) {
                    throw new Exception("Advanced search parameters invalid");
                }

                $operator = $param["operator"];
                $pName = $param["fieldName"];
                $pValue = $param["fieldValue"];

                if (!in_array($operator, self::$advancedSearchOperators)) {
                    throw new Exception("Invalid operator: ".$operator);
                }
                if (!in_array($pName, $allowedFieldNames)) {
                    throw new Exception("fieldName not allowed: ".$pName);
                }

                $fieldElastic = self::getAdvancedSearchFieldsMap()[$pName];

                $queryString = [
                    "query_string" => [
                        "fields" => [
                            $fieldElastic,
                        ],
                        "query" => $pValue
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
        }


        if ($searchType !== SEARCH_TYPE_ALL) {
            $must[] = [
                "term" => [
                    "struct_type" => $searchType
                ]
            ];
        }

        if ($parent) {
            $must[] = [
                "query_string" => [
                    "fields" => ["hierarchy"],
                    "query" => $parent
                ]
            ];
        }

        $query = [ "bool" => [] ];
        if (count($should)) $query["bool"]["should"] = $should;
        if (count($must)) $query["bool"]["must"] = $must;

        return self::search($query, 0, $limit, "child_order", "asc");
    }

    public static function suggestCreators($creatorTerm, $searchType = "all", $parent = null, $limit = 30)
    {

        $creatorWords = explode(" ", $creatorTerm);
        $creatorSimple = "";
        if (count($creatorWords) > 0) $creatorSimple .= $creatorWords[0];
        if (count($creatorWords) > 1) $creatorSimple .= " ".$creatorWords[1];

        $queryStringWild = $creatorSimple."*";

        /*
        $query = [
            "query_string" => [
                "fields" => [
                    "data.si4.creator.value",
                ],
                "query" => $queryStringWild
            ]
        ];
        */

        $must = [];
        $must[] = [
            "query_string" => [
                "fields" => [
                    "data.si4.creator.value",
                ],
                "query" => $queryStringWild
            ]
        ];

        if ($searchType !== SEARCH_TYPE_ALL) {
            $must[] = [
                "term" => [
                    "struct_type" => $searchType
                ]
            ];
        }

        if ($parent) {
            $must[] = [
                "query_string" => [
                    "fields" => ["hierarchy"],
                    "query" => $parent
                ]
            ];
        }

        $query = [
            "bool" => [ "must" => $must ]
        ];


        return self::search($query, 0, $limit, "child_order", "asc");
    }

    public static function suggestTitlesForCreator($creator, $title, $searchType = "all", $parent = null, $limit = 30)
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

        if ($searchType !== SEARCH_TYPE_ALL) {
            $must[] = [
                "term" => [
                    "struct_type" => $searchType
                ]
            ];
        }

        if ($parent) {
            $must[] = [
                "query_string" => [
                    "fields" => ["hierarchy"],
                    "query" => $parent
                ]
            ];
        }

        $query = [
            "bool" => [ "must" => $must ]
        ];

        return self::search($query, 0, $limit, "child_order", "asc");
    }

    public static function suggestFullTextWords_slow($term, $parent = null, $limit = 30)
    {
        $term = self::removeSkipCharacters($term);
        $words = explode(" ", $term);
        if (!$words || count($words) > 1) return [];

        $word = $words[0];
        $queryStringWild = $word."*";

        $must = [];

        $must[] = [
            "query_string" => [
                "fields" => ["data.files.fullText"],
                "query" => $queryStringWild
            ],
        ];

        if ($parent) {
            $must[] = [
                "query_string" => [
                    "fields" => ["hierarchy"],
                    "query" => $parent
                ]
            ];
        }

        $query = [
            "bool" => [ "must" => $must ]
        ];

        $highlight = [
            "fields" => [
                "data.files.fullText" => [
                    "fragment_size" => 18,
                    "number_of_fragments" => 5,
                    "pre_tags" => [""],
                    "post_tags" => [""]
                ]
            ]
        ];

        $elasticData = self::search($query, 0, $limit, null, null, $highlight);
        $assocData = self::elasticResultToAssocArray($elasticData);
        $results = [];
        $len = mb_strlen($word);

        foreach ($assocData as $elasticEntity) {
            if (isset($elasticEntity["highlight"]) && count($elasticEntity["highlight"])) {
                foreach ($elasticEntity["highlight"] as $hlinst) {
                    $hlinst =  self::removeSkipCharacters(mb_strtolower($hlinst));
                    $startPos = mb_stripos($hlinst, $word);
                    $spacePos = mb_strpos($hlinst, " ", $startPos+$len);
                    $result = trim(mb_substr($hlinst, $startPos, $spacePos -$startPos));
                    $results[$result] = true;
                }
            }
        }

        return array_keys($results);
    }

    public static function suggestFullTextWords($term, $parent = null, $limit = 30)
    {
        $term = self::removeSkipCharacters($term);
        $words = explode(" ", $term);
        if (!$words || count($words) > 1) return [];

        $word = $words[0];
        $queryStringWild = $word."*";

        $must = [];

        $must[] = [
            "query_string" => [
                "fields" => ["data.files.fullText"],
                "query" => $queryStringWild
            ],
        ];

        if ($parent) {
            $must[] = [
                "term" => [
                    "hierarchy" => $parent
                ]
            ];
        }

        $query = [
            "bool" => [ "must" => $must ]
        ];

        $highlight = [
            "fields" => [
                "data.files.fullText" => [
                    "fragment_size" => 18,
                    "number_of_fragments" => 5,
                    "pre_tags" => [""],
                    "post_tags" => [""]
                ]
            ]
        ];

        $elasticData = self::search($query, 0, $limit, null, null, $highlight);

        Timer::start("suggestParsing");

        $assocData = self::elasticResultToAssocArray($elasticData);
        $results = [];
        $len = mb_strlen($word);

        foreach ($assocData as $elasticEntity) {
            if (isset($elasticEntity["highlight"]) && count($elasticEntity["highlight"])) {
                foreach ($elasticEntity["highlight"] as $hlinst) {
                    $hlinst =  self::removeSkipCharacters(mb_strtolower($hlinst));
                    $hlinst = mb_ereg_replace("\n.*", "", $hlinst);
                    $startPos = mb_stripos($hlinst, $word);
                    if ($startPos) {
                        $spacePos = mb_strpos($hlinst, " ", $startPos+$len);
                        $result = trim(mb_substr($hlinst, $startPos, $spacePos -$startPos));
                        if ($result) $results[$result] = true;
                    }
                }
            }
        }

        $tookParsing = round(Timer::stop("suggestParsing") *1000);

        $results["elastic took:".$elasticData["took"]] = true;
        $results["parsing took:".$tookParsing] = true;

        return array_keys($results);
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

        $must = [];

        // Apply match parent
        $must[] = [
            "match" => [
                "parent" => $parent
            ],
        ];

        // Only Active entities
        $must[] = [
            "match" => [
                "data.header.recordStatus" => "Active"
            ],
        ];

        $query = ["bool" => [ "must" => $must ] ];

        /*
        $query = [
            "match" => [
                "parent" => $parent
            ]
        ];
        */

        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "body" => [
                "query" => $query,
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
                if ($fieldName == "title" || $fieldName == "creator") {
                    $fieldName = "data.si4.".$fieldName.".value.keyword";
                } else {
                    $fieldName = "data.si4.".$fieldName.".value";
                }
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

    public static function makeHandleIdMap($dataElastic) {
        $result = [];
        foreach ($dataElastic as $record){
            $handle_id = Si4Util::pathArg($record, "_source/handle_id", null);
            if ($handle_id) {
                $result[$handle_id] = $record;
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

    public static function escapeValue($value) {
        if (!is_string($value)) return $value;
        return str_replace(".", "\\.", $value);
    }

    public static $skipChars = [",", "\\."];
    public static function removeSkipCharacters($str) {
        foreach (self::$skipChars as $skipChar) {
            $str = mb_ereg_replace($skipChar, "", $str);
        }
        return $str;
    }

    public static function isQuoted($str) {
        return strpos($str, "\"") === 0 && strrpos($str, "\"") === strlen($str) -1;
    }

    public static function tokenize($str) {
        preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $str, $matches);
        return $matches[0];
    }

    public static function cleanUpWords($array) {
        $result = [];
        foreach ($array as $wordIdx => $word) {
            $result[] = self::cleanUpWord($word);
        }
        return $result;
    }

    public static function cleanUpWord($str) {
        $str = mb_ereg_replace("-", " ", $str);
        $str = mb_ereg_replace("  ", " ", $str);
        if (!self::isQuoted($str)) {
            $str = mb_ereg_replace("\"", "", $str);
        }
        return $str;
    }



}