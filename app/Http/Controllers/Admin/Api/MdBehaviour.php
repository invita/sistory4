<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Behaviour;
use App\Models\BehaviourField;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MdBehaviour extends Controller
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
        $behaviour->description = $postJson["description"];
        $behaviour->template_entity = $postJson["template_entity"];
        $behaviour->template_collection = $postJson["template_collection"];
        $behaviour->template_file = $postJson["template_file"];
        $behaviour->advanced_search = $postJson["advanced_search"];
        $behaviour->save();

        return ["status" => true, "error" =>  null];
    }

    public function deleteBehaviour(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        $behaviour = Behaviour::find($id);
        if ($behaviour) {
            BehaviourField::where('behaviour_name', $behaviour->name)->delete();
            $behaviour->delete();
        }

        return $this->behaviourList($request);
    }


    public function behaviourFieldList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);
        $staticData = Si4Util::getArg($postJson, "staticData", []);
        $behaviour_name = Si4Util::getArg($staticData, "behaviour_name", null);

        $status = true;
        $error = "";

        if ($behaviour_name) {
            $query = BehaviourField::query()->where("behaviour_name", $behaviour_name)->orderBy("sort_order");
            $rowCount = $query->select()->count();
            $behaviourFields = $query->offset($pageStart)->limit($pageCount)->get();
        } else {
            $status = false;
            $behaviourFields = [];
            $rowCount = 0;
            $error = "Missing parameter behaviour_name";
        }

        return ["status" => $status, "data" => $behaviourFields, "rowCount" => $rowCount, "error" =>  $error];
    }

    public function saveBehaviourField(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);

        if (!isset($postJson["behaviour_name"]) || !$postJson["behaviour_name"])
            return ["status" => false, "error" =>  "Missing behaviour_name"];

        $sort_order = intval($postJson["sort_order"]);
        if (!$sort_order) $sort_order = BehaviourField::getLastSortOrder($postJson["behaviour_name"]) +1;

        $behaviourField = BehaviourField::findOrNew($postJson["id"]);
        $behaviourField->behaviour_name = $postJson["behaviour_name"];
        $behaviourField->field_name = $postJson["field_name"];
        $behaviourField->show_all_languages = $postJson["show_all_languages"];
        $behaviourField->inline = $postJson["inline"];
        $behaviourField->inline_separator = $postJson["inline_separator"];
        $behaviourField->display_frontend = $postJson["display_frontend"];
        $behaviourField->sort_order = $sort_order;
        $behaviourField->save();

        // Resort all
        BehaviourField::recalculateSort($postJson["behaviour_name"]);

        return ["status" => true, "error" =>  null];
    }

    public function deleteBehaviourField(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        $behaviourField = BehaviourField::find($id);
        if ($behaviourField) {
            $behaviourField->delete();
        }

        return $this->behaviourFieldList($request);
    }

}