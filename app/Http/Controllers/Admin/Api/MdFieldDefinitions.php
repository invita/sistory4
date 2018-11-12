<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Si4Field;
use \Illuminate\Http\Request;

class MdFieldDefinitions extends Controller
{

    public function fieldDefinitions(Request $request) {
        return ["status" => true, "data" => Si4Field::getSi4FieldsArray()];
    }

    public function fieldDefinitionsList(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);

        $query = Si4Field::query();
        $rowCount = $query->select()->count();

        $si4fields = $query->offset($pageStart)->limit($pageCount)->get();

        return ["status" => true, "data" => $si4fields, "rowCount" => $rowCount, "error" =>  null];
    }

    public function saveFieldDefinition(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);

        $si4field = Si4Field::findOrNew($postJson["id"]);
        $si4field->field_name = $postJson["field_name"];
        $si4field->translate_key = $postJson["translate_key"];
        $si4field->save();

        return ["status" => true, "error" =>  null];
    }

    public function deleteFieldDefinition(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        $si4field = Si4Field::find($id);
        if ($si4field) $si4field->delete();

        return $this->fieldDefinitionsList($request);
    }

}