<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;

class MdFieldDefinitions extends Controller
{

    public function fieldDefinitions(Request $request) {
        return ["status" => true, "data" => Si4Helpers::$si4FieldDefinitions];
    }

    public function fieldDefinitionsList(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);

        $dataList = [];
        $i = 0;
        foreach (Si4Helpers::$si4FieldDefinitions as $fieldName => $fieldData) {
            if ($i >= $pageStart && $i < $pageStart + $pageCount) {
                $dataList[] = $fieldData;
            }
            $i++;
        }

        $rowCount = count(array_keys(Si4Helpers::$si4FieldDefinitions));

        return ["status" => true, "data" => $dataList, "rowCount" => $rowCount];
    }

}