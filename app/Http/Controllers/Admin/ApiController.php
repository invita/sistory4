<?php
namespace App\Http\Controllers\Admin;

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

        $entity = Entity::findOrNew($postJson["id"]);
        $entity->entity_type_id = $postJson["entity_type_id"];
        $entity->data = $postJson["xml"];
        $entity->save();

        return ["status" => true, "error" =>  null];
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

}