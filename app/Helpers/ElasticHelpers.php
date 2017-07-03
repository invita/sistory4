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
     * Sends a document to elastic search to be indexed
     * @param $entityId Entity Id to be index
     * @param $body Data to index
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
     * Retrieves all matching documents from elastic search
     * @param $query String to match
     * @return array
     */
    public static function search($query)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ENTITY_INDEX", "entities"),
            "type" => env("SI4_ELASTIC_ENTITY_DOCTYPE", "entity"),
            "body" => [
                "query" => [
                    "match" => [
                        "_all" => $query
                    ]
                ]
            ]
        ];
        return \Elasticsearch::connection()->search($requestArgs);
    }
}