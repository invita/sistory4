<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\User;
use \Illuminate\Http\Request;

class Users extends Controller
{
    public function userList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);

        $query = User::query();
        $rowCount = $query->select()->count();

        $users = $query->offset($pageStart)->limit($pageCount)->get();
        return ["status" => true, "data" => $users, "rowCount" => $rowCount, "error" =>  null];
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

    public function deleteUser(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        $user = User::find($id);
        if ($user) $user->delete();

        return $this->userList($request);
    }

}