<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\MappingField;
use App\Models\MappingGroup;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MdMapping extends Controller
{
    public function mappingGroupList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);

        $query = MappingGroup::query();
        $rowCount = $query->select()->count();

        $mappingGroups = $query->offset($pageStart)->limit($pageCount)->get();

        return ["status" => true, "data" => $mappingGroups, "rowCount" => $rowCount, "error" =>  null];
    }

    public function saveMappingGroup(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);

        $mappingGroup = MappingGroup::findOrNew($postJson["id"]);
        $mappingGroup->name = $postJson["name"];
        $mappingGroup->base_xpath = $postJson["base_xpath"];
        $mappingGroup->description = $postJson["description"];
        $mappingGroup->data = "";
        //$mappingGroup->data = $postJson["data"];
        $mappingGroup->save();

        return ["status" => true, "error" =>  null, "data" => $mappingGroup->toArray()];
    }

    public function deleteMappingGroup(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        if ($id) {
            // Delete group fields
            DB::table('mapping_fields')->where('mapping_group_id', $id)->delete();

            // Delete group itself
            $mappingGroup = MappingGroup::find($id);
            if ($mappingGroup) {
                $mappingGroup->delete();
            }
        }

        return $this->mappingGroupList($request);
    }


    public function mappingGroupFieldsList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);

        $staticData = Si4Util::getArg($postJson, "staticData", []);
        $mapping_group_id = Si4Util::getArg($staticData, "mapping_group_id", 0);

        if ($mapping_group_id) {
            $query = MappingField::query();
            $rowCount = $query->where(["mapping_group_id" => $mapping_group_id])->select()->count();
            $mappingGroups = $query->offset($pageStart)->limit($pageCount)->get();
        } else {
            $mappingGroups = [];
            $rowCount = 0;
        }

        return ["status" => true, "data" => $mappingGroups, "rowCount" => $rowCount, "error" =>  null];
    }

    public function saveMappingGroupField(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);

        $mappingField = MappingField::findOrNew($postJson["id"]);
        $mappingField->mapping_group_id = $postJson["mapping_group_id"];
        $mappingField->source_xpath = $postJson["source_xpath"];
        $mappingField->value_xpath = $postJson["value_xpath"];
        $mappingField->lang_xpath = $postJson["lang_xpath"];
        $mappingField->target_field = $postJson["target_field"];
        $mappingField->data = "";
        $mappingField->save();

        return ["status" => true, "error" =>  null, "data" => $mappingField->toArray()];
    }



}