<?php
namespace App\Helpers;

/**
 * Class DcHelpers
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class DcHelpers {

    private static $docPathMap = null;
    private static function getElasticEntityPathMap() {
        if (!self::$docPathMap) {
            self::$docPathMap = [
                // System
                "id" => ["path" => "id"],
                "handle_id" => ["path" => "_source/handle_id"],
                "parent" => ["path" => "_source/parent"],
                "primary" => ["path" => "_source/primary"],
                "collection" => ["path" => "_source/collection"],
                "struct_type" => ["path" => "_source/struct_type"],
                "struct_subtype" => ["path" => "_source/struct_subtype"],
                "entity_type" => ["path" => "_source/entity_type"],
                "active" => ["path" => "_source/active"],

                // Xml Basic
                "handle_url" => ["path" => "_source/data/objId"],
                "created_at" => ["path" => "_source/data/created_at"],
                "modified_at" => ["path" => "_source/data/modified_at"],

                // DC
                "dc_title" => [
                    "path" => "_source/data/dmd/dc/title",
                    "parser" => DcHelpers::getDcTitlePresentationParser(),
                ],
                "dc_creator" => [
                    "path" => "_source/data/dmd/dc/creator",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_subject" => [
                    "path" => "_source/data/dmd/dc/subject",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_description" => [
                    "path" => "_source/data/dmd/dc/description",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_publisher" => [
                    "path" => "_source/data/dmd/dc/publisher",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_contributor" => [
                    "path" => "_source/data/dmd/dc/contributor",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_date" => [
                    "path" => "_source/data/dmd/dc/date",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_type" => [
                    "path" => "_source/data/dmd/dc/type",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_format" => [
                    "path" => "_source/data/dmd/dc/format",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_identifier" => [
                    "path" => "_source/data/dmd/dc/identifier",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_source" => [
                    "path" => "_source/data/dmd/dc/source",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_language" => [
                    "path" => "_source/data/dmd/dc/language",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_relation" => [
                    "path" => "_source/data/dmd/dc/relation",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_coverage" => [
                    "path" => "_source/data/dmd/dc/coverage",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_rights" => [
                    "path" => "_source/data/dmd/dc/rights",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],

                // Files
                "fileName" => [
                    "path" => "_source/data/files",
                    "parser" => DcHelpers::getDcFirstFileNameParser(),
                ],
            ];
        }
        return self::$docPathMap;
    }

    // Entity mapping using self::$docPathMap blueprint
    public static function mapElasticEntity($elasticEntity) {
        $docPathMap = self::getElasticEntityPathMap($elasticEntity);
        $docResult = [];
        foreach ($docPathMap as $key => $bluePrint) {
            $path = $bluePrint["path"];
            $docResult[$key] = Si4Util::pathArg($elasticEntity, $path, "");
            if (isset($bluePrint["parser"]))
                $docResult[$key] = $bluePrint["parser"]($key, $docResult[$key]);
        }
        return $docResult;
    }


    // *** dcTextArray - simplify elastic data ***
    // $dcData is an array i.e. [ ["text" => "", ...] , ["text" => "", ...] , ... ]
    // function extracts texts and put them in a simple array
    public static function dcTextArray($dcData) {
        return array_map(function($item) {
            return $item["text"];
        }, $dcData);
    }


    public static function getDcTitlePresentationParser() {
        return function($inputName, $inputValue) {
            if ($inputValue === null) return "";
            if (is_string($inputValue)) $inputValue = [$inputValue];
            if (is_array($inputValue)) {
                $inputValue = self::dcTextArray($inputValue);
                $result = "";
                for ($i = 0; $i < count($inputValue); $i++) {
                    if ($i == 0)
                        $result .= "<h3>".$inputValue[$i]."</h3>";
                    else
                        $result .= "<h4>".$inputValue[$i]."</h4>";
                }
                return $result;
            }
            return print_r($inputValue, true);
        };
    }

    public static function getDcPresentationParser() {
        return function($inputName, $inputValue) {
            if ($inputValue === null) return "";
            if (is_string($inputValue)) return $inputValue;
            if (is_array($inputValue)) {
                $inputValue = self::dcTextArray($inputValue);
                return join("<br/>", array_map(function($e) { return "<span>".$e."</span>"; }, $inputValue));
            }
            return print_r($inputValue, true);
        };
    }

    public static function getDcFirstFileNameParser() {
        return function($inputName, $inputValue) {
            return isset($inputValue[0]["ownerId"]) ? $inputValue[0]["ownerId"] : "";
        };
    }
}