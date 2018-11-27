<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\Enums;
use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Translation;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Translations extends Controller
{
    public function translationsList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);
        $filter = Si4Util::getArg($postJson, "filter", []);

        $staticData = Si4Util::getArg($postJson, "staticData", []);
        $language = Si4Util::getArg($staticData, "language", "slv");

        $query = Translation::query()->where('language', '=', $language);

        $filter_key = Si4Util::getArg($filter, "key", null);
        if ($filter_key) $query->where('key', 'LIKE', "%".$filter_key."%");
        $filter_value = Si4Util::getArg($filter, "value", null);
        if ($filter_value) $query->where('value', 'LIKE', "%".$filter_value."%");

        $rowCount = $query->select()->count();

        $translations = $query->offset($pageStart)->limit($pageCount)->get();

        return ["status" => true, "data" => $translations, "rowCount" => $rowCount, "error" =>  null];
    }

    public function saveTranslation(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = Si4Util::getArg($postJson, "id", null);
        $language = Si4Util::getArg($postJson, "language", "slv");
        $module = Si4Util::getArg($postJson, "module", "fe");
        $key = Si4Util::getArg($postJson, "key", "");
        $value = Si4Util::getArg($postJson, "value", "");

        if ($id) {
            $translation = Translation::find($id);
            $translation->value = $value;
            $translation->save();
        } else {

            foreach (Enums::$feLanguages as $feLang) {
                $translation2 = new Translation();
                $translation2->language = $feLang;
                $translation2->module = $module;
                $translation2->key = $key;
                $translation2->value = "";
                if ($language == $feLang) {
                    $translation2->value = $value;
                    $translation = $translation2;
                }
                $translation2->save();
            }
        }

        return ["status" => true, "error" =>  null, "data" => $translation->toArray()];
    }

    public function deleteTranslation(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];
        $module = $postJson["data"]["module"];
        $language = $postJson["data"]["language"];
        $key = $postJson["data"]["key"];

        if ($module && $key) {
            // Delete group fields
            DB::table('translations')->where('module', $module)->where('key', $key)->delete();
        }

        return $this->translationsList($request);
    }

    public function generateTranslationFiles(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);

        $result = Artisan::call("lang:dbToFile");

        return ["status" => true, "data" => $result];
    }

}