<?php
namespace App\Helpers;

/**
 * Class ElasticHelper
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class ElasticHelpers
{

    /**
     * Delete and create index
     * @param $entityId Integer entity id to index
     * @param $body Array body to index
     * @return array
     */
    public static function recreateIndex()
    {

        $deleteIndexArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => "",
            "id" => "",
        ];
        \Elasticsearch::connection()->delete($deleteIndexArgs);

        /*
        $createIndexArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "id" => "",
            "body" => []
        ];
        return @\Elasticsearch::connection()->create($createIndexArgs);
        */
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
     * @param $query String to match
     * @param $offset Integer offset
     * @param $limit Integer limit
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
                /*
                [
                "term" => [ "user" => "kimchy" ]
                    "match" => [
                        "_all" => $query
                    ]
                ]
                */
                /*
"sort" : [
        { "post_date" : {"order" : "asc"}},
        "user",
        { "name" : "desc" },
        { "age" : "desc" },
        "_score"
    ],
                */
            ]
        ];
        return \Elasticsearch::connection()->search($requestArgs);
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

    public static function searchByParent($parent)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "body" => [
                "query" => [
                    "match" => [
                        "parent" => $parent
                    ]
                ]
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

    public static function escapeValue($value) {
        if (!is_string($value)) return $value;
        return str_replace(".", "\\.", $value);
    }
}