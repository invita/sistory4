<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\OaiField;
use App\Models\OaiGroup;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Oai extends Controller
{
    public function oaiGroupList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);

        $query = OaiGroup::query();
        $rowCount = $query->select()->count();

        $oaiGroups = $query->offset($pageStart)->limit($pageCount)->orderBy("id")->get();

        return ["status" => true, "data" => $oaiGroups, "rowCount" => $rowCount, "error" =>  null];
    }

    public function saveOaiGroup(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);

        $oaiGroup = OaiGroup::findOrNew($postJson["id"]);
        $oaiGroup->name = $postJson["name"];
        $oaiGroup->schema = $postJson["schema"];
        $oaiGroup->namespace = $postJson["namespace"];
        $oaiGroup->attrs = $postJson["attrs"];
        $oaiGroup->behaviours = $postJson["behaviours"];
        $oaiGroup->save();

        return ["status" => true, "error" =>  null, "data" => $oaiGroup->toArray()];
    }

    public function deleteOaiGroup(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        if ($id) {
            // Delete group fields
            DB::table('oai_fields')->where('oai_group_id', $id)->delete();

            // Delete group itself
            $oaiGroup = OaiGroup::find($id);
            if ($oaiGroup) {
                $oaiGroup->delete();
            }
        }

        return $this->oaiGroupList($request);
    }


    public function oaiGroupFieldsList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);

        $staticData = Si4Util::getArg($postJson, "staticData", []);
        $oai_group_id = Si4Util::getArg($staticData, "oai_group_id", 0);

        if ($oai_group_id) {
            $query = OaiField::query();
            $rowCount = $query->where(["oai_group_id" => $oai_group_id])->select()->count();
            $oaiFields = $query->offset($pageStart)->limit($pageCount)->get();
        } else {
            $oaiFields = [];
            $rowCount = 0;
        }

        return ["status" => true, "data" => $oaiFields, "rowCount" => $rowCount, "error" =>  null];
    }

    public function saveOaiGroupField(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);

        $oaiField = OaiField::findOrNew($postJson["id"]);
        $oaiField->oai_group_id = $postJson["oai_group_id"];
        $oaiField->name = $postJson["name"];
        $oaiField->has_language = $postJson["has_language"];
        $oaiField->xml_path = $postJson["xml_path"];
        $oaiField->xml_name = $postJson["xml_name"];
        $oaiField->mapping = $postJson["mapping"];
        $oaiField->save();

        return ["status" => true, "error" =>  null, "data" => $oaiField->toArray()];
    }

    public function deleteOaiGroupField(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        if ($id) {
            $oaiField = OaiField::find($id);
            if ($oaiField) {
                $oaiField->delete();
            }
        }

        return $this->oaiGroupFieldsList($request);
    }


}