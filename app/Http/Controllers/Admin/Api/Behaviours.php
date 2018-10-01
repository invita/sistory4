<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Behaviour;
use \Illuminate\Http\Request;

class Behaviours extends Controller
{
    public function behaviourList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);

        $query = Behaviour::query();
        $rowCount = $query->select()->count();

        $behaviours = $query->offset($pageStart)->limit($pageCount)->get();

        return ["status" => true, "data" => $behaviours, "rowCount" => $rowCount, "error" =>  null];
    }

    public function saveBehaviour(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);

        $behaviour = Behaviour::findOrNew($postJson["id"]);
        $behaviour->name = $postJson["name"];
        $behaviour->data = $postJson["data"];
        $behaviour->save();

        return ["status" => true, "error" =>  null];
    }

    public function deleteBehaviour(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        $behaviour = Behaviour::find($id);
        if ($behaviour) $behaviour->delete();

        return $this->behaviourList($request);
    }

}