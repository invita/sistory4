<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;

class Dev extends Controller
{
    public function devTools(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $action = $postJson["action"];

        $status = true;
        $error = null;
        $data = null;

        switch ($action) {
            case "elasticListIndices":
                $listIndicesArgs = [
                    "index" =>  "_cat",
                    "type" => "indices",
                    "id" => "",
                ];
                $data = \Elasticsearch::connection()->get($listIndicesArgs);
                break;

            case "elasticCreateIndex":
                $indexName = $postJson["indexName"];
                $docType = $postJson["docType"];
                $createIndexArgs = [
                    "index" =>  $indexName,
                    "type" => $docType,
                    "id" => "",
                    "body" => []
                ];
                $data = @\Elasticsearch::connection()->create($createIndexArgs);
                break;

            case "elasticDeleteIndex":
                $indexName = $postJson["indexName"];
                $deleteIndexArgs = [
                    "index" =>  $indexName,
                    "type" => "",
                    "id" => "",
                ];
                $data = \Elasticsearch::connection()->delete($deleteIndexArgs);
                break;

            case "elasticIndexTestDoc":
                $indexName = $postJson["indexName"];
                $docType = $postJson["docType"];
                $id = $postJson["id"];
                $indexArgs = [
                    "index" =>  $indexName,
                    "type" => $docType,
                    "id" => $id,
                    "body" => [
                        "name" => "Janez",
                        "description" => "Testni primerek",
                        "number" => 123,
                    ]
                ];
                $data = \Elasticsearch::connection()->index($indexArgs);
                break;

            case "elasticSearchAll":
                $indexName = $postJson["indexName"];
                $docType = $postJson["docType"];
                $searchArgs = [
                    "index" =>  $indexName,
                    "type" => $docType,
                ];
                $data = \Elasticsearch::connection()->search($searchArgs);
                break;

            case "elasticSearch":
                $query = $postJson["query"];
                $data = ElasticHelpers::search($query);
                break;
        }

        return ["status" => $status, "error" =>  $error, "data" => $data];
    }

}