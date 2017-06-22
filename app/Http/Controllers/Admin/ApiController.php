<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\User;
use \Illuminate\Http\Request;

class ApiController extends Controller
{
    public function entityList(Request $request)
    {
        $entities = Entity::all();

        return ["status" => true, "data" => $entities, "error" =>  null];
    }

    public function saveEntity(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $status = true;
        $error = null;

        $entity = Entity::findOrNew($postJson["id"]);
        $entity->entity_type_id = $postJson["entity_type_id"];
        $entity->data = $postJson["xml"];

        $entityXmlParsed = $entity->dataToObject();
        $elasticResponse = ElasticHelpers::indexEntity($postJson["id"], $entityXmlParsed);

        $entity->save();

        return ["status" => $status, "error" =>  $error];
    }


    public function userList(Request $request)
    {
        $users = User::all();
        return ["status" => true, "data" => $users, "error" =>  null];
    }

    public function saveUser(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);

        $user = User::findOrNew($postJson["id"]);
        $user->name = $postJson["name"];
        $user->email = $postJson["email"];
        if ($postJson["password"]) $user->password = bcrypt($postJson["password"]);
        $user->firstname = $postJson["firstname"];
        $user->lastname = $postJson["lastname"];
        $user->save();

        return ["status" => true, "error" =>  null];
    }


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