<?php
namespace App\Helpers;
use App\Http\Middleware\SessionLanguage;
use App\Models\Si4Field;

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

                // Thumb
                "thumb" => [
                    "path" => "_source/struct_type",
                    "parser" => DcHelpers::getThumbParser(),
                ],

                // Files
                "defaultThumb" => [
                    "path" => "_source",
                    "parser" => DcHelpers::getDcDefaultThumbParser(),
                ],
                "fileName" => [
                    "path" => "_source/data/files/0/ownerId",
                ],

                // DC plain
                "dc_title" => ["path" => "_source/data/dmd/dc/title"],
                "dc_creator" => ["path" => "_source/data/dmd/dc/creator"],
                "dc_subject" => ["path" => "_source/data/dmd/dc/subject"],
                "dc_description" => ["path" => "_source/data/dmd/dc/description"],
                "dc_publisher" => ["path" => "_source/data/dmd/dc/publisher"],
                "dc_contributor" => ["path" => "_source/data/dmd/dc/contributor"],
                "dc_date" => ["path" => "_source/data/dmd/dc/date"],
                "dc_type" => ["path" => "_source/data/dmd/dc/type"],
                "dc_format" => ["path" => "_source/data/dmd/dc/format"],
                "dc_identifier" => ["path" => "_source/data/dmd/dc/identifier"],
                "dc_source" => ["path" => "_source/data/dmd/dc/source"],
                "dc_language" => ["path" => "_source/data/dmd/dc/language"],
                "dc_relation" => ["path" => "_source/data/dmd/dc/relation"],
                "dc_coverage" => ["path" => "_source/data/dmd/dc/coverage"],
                "dc_rights" => ["path" => "_source/data/dmd/dc/rights"],

                // DC With lang
                "dc_title_lang" => [
                    "path" => "_source/data/dmd/dc/title",
                    "parser" => DcHelpers::getDcWithLangParser()
                ],

                // DC first
                "first_dc_title" => [
                    "path" => "_source/data/dmd/dc/title",
                    "parser" => DcHelpers::getDcFirstParser()
                ],
                "first_dc_creator" => [
                    "path" => "_source/data/dmd/dc/creator",
                    "parser" => DcHelpers::getDcFirstParser()
                ],
                "first_dc_date" => [
                    "path" => "_source/data/dmd/dc/date",
                    "parser" => DcHelpers::getDcFirstParser()
                ],


                // DC Html Presentation
                "html_dc_title" => [
                    "path" => "_source/data/dmd/dc/title",
                    "parser" => DcHelpers::getDcTitlePresentationParser(),
                ],
                "html_dc_creator_list" => [
                    "path" => "_source/data/dmd/dc/creator",
                    "parser" => DcHelpers::getDcListCreatorsParser(),
                ],
                "html_dc_creator" => [
                    "path" => "_source/data/dmd/dc/creator",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_subject" => [
                    "path" => "_source/data/dmd/dc/subject",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_description" => [
                    "path" => "_source/data/dmd/dc/description",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_publisher" => [
                    "path" => "_source/data/dmd/dc/publisher",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_contributor" => [
                    "path" => "_source/data/dmd/dc/contributor",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_date" => [
                    "path" => "_source/data/dmd/dc/date",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_type" => [
                    "path" => "_source/data/dmd/dc/type",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_format" => [
                    "path" => "_source/data/dmd/dc/format",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_identifier" => [
                    "path" => "_source/data/dmd/dc/identifier",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_source" => [
                    "path" => "_source/data/dmd/dc/source",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_language" => [
                    "path" => "_source/data/dmd/dc/language",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_relation" => [
                    "path" => "_source/data/dmd/dc/relation",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_coverage" => [
                    "path" => "_source/data/dmd/dc/coverage",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "html_dc_rights" => [
                    "path" => "_source/data/dmd/dc/rights",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],

            ];
        }
        return self::$docPathMap;
    }

    // Entity mapping using self::$docPathMap blueprint
    public static function mapElasticEntity($elasticEntity) {
        $docPathMap = self::getElasticEntityPathMap($elasticEntity);
        $docResult = [];

        $docResult["si4"] = self::si4frontendFormat($elasticEntity);

        foreach ($docPathMap as $key => $bluePrint) {
            $path = $bluePrint["path"];
            $docResult[$key] = Si4Util::pathArg($elasticEntity, $path, "");
            if (isset($bluePrint["parser"]))
                $docResult[$key] = $bluePrint["parser"]($key, $docResult[$key]);
        }
        return $docResult;
    }

    private static function si4frontendFormat($elasticEntity) {
        $result = [];

        $fieldDefs = Si4Field::getSi4Fields();
        $behaviour = Behaviour::getBehaviourForElasticEntity($elasticEntity);
        $feLang = SessionLanguage::current();

        foreach ($behaviour["fields"] as $fieldName => $fieldBehaviour) {
            $fieldDef = $fieldDefs[$fieldName];
            $result[$fieldName] = [];
            $fieldVals = Si4Util::pathArg($elasticEntity, "_source/data/si4/".$fieldName);

            if (isset($fieldVals) && count($fieldVals)) {
                if ($fieldDef->has_language && !$fieldBehaviour->show_all_langauges) {
                    // Filter values in other lang than FE
                    $fieldVals = array_filter($fieldVals, function($x) use ($feLang) { return $x["lang"] === $feLang; });
                }

                foreach ($fieldVals as $fieldVal) {
                    $result[$fieldName][] = $fieldVal["value"];
                }
            }
        }
        return $result;
    }


    // *** dcTextArray - simplify elastic data ***
    // $dcData is an array i.e. [ ["text" => "", ...] , ["text" => "", ...] , ... ]
    // function extracts texts and put them in a simple array
    public static function dcTextArray($dcData) {
        $result = [];
        foreach ($dcData as $idx => $item) {
            if (isset($item["value"]) && $item["value"])
                $result[] = $item["value"];
        }
        return $result;

        /*
        return array_map(function($item) {
            return isset($item["value"]) && $item["value"] ? $item["value"] : "";
        }, $dcData);
        */
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
                return join("<br/>", array_map(function($e) { return "<span>".trim($e)."</span>"; }, $inputValue));
            }
            return print_r($inputValue, true);
        };
    }

    public static function getDcWithLangParser() {
        return function($inputName, $inputValue) {
            if ($inputValue === null) return "";
            if (is_string($inputValue)) return $inputValue;
            if (is_array($inputValue)) {
                $lang = "slv";
                $result = [];
                // Find fields with title
                foreach ($inputValue as $valueAndLang) {
                    if (isset($valueAndLang["lang"]) && $valueAndLang["lang"] == $lang) {
                        $result[] = $valueAndLang;
                    }
                }
                // If no fields with title, find fields with no title defined
                if (!count($result)) {
                    foreach ($inputValue as $value) {
                        if (!isset($value["lang"])) {
                            $result[] = $value;
                        }
                    }
                }
                return self::dcTextArray($result);
            }
            return print_r($inputValue, true);
        };
    }

    public static function getDcListCreatorsParser() {
        return function($inputName, $inputValue) {
            if ($inputValue === null) return "";
            if (is_string($inputValue)) return $inputValue;
            if (is_array($inputValue)) {
                $inputValue = self::dcTextArray($inputValue);
                return join(
                    '<span class="sep"> / </span>',
                    array_map(function($e) {
                        return '<span class="val">'.$e.'</span>';
                    }, $inputValue)
                );
            }
            return print_r($inputValue, true);
        };
    }

    public static function getDcFirstParser() {
        return function($inputName, $inputValue) {
            if ($inputValue === null) return "";
            if (is_string($inputValue)) return $inputValue;
            if (is_array($inputValue)) {
                $inputValue = self::dcTextArray($inputValue);
                return isset($inputValue[0]) ? $inputValue[0] : "";
            }
            return print_r($inputValue, true);
        };
    }

    public static function getThumbParser() {
        return function($inputName, $inputValue) {
            if ($inputValue === null) return "";
            switch($inputValue) {
                case "entity":
                case "collection":
                case "file":
                    return "/img/structType/".$inputValue.".png";
                default:
                    return "";
            }
        };
    }

    public static function getStructTypeSortValue($structType) {
        switch($structType) {
            case "collection": return 30;
            case "entity": return 20;
            case "file": return 10;
            default: return 0;
        }
    }

    /*
    public static function getDcFirstFileNameParser() {
        return function($inputName, $inputValue) {
            return isset($inputValue[0]["ownerId"]) ? $inputValue[0]["ownerId"] : "";
        };
    }
    */

    public static function getDcDefaultThumbParser() {
        return function($inputName, $inputValue) {
            // Default file
            //print_r($inputValue);
            $handleId = Si4Util::pathArg($inputValue, "handle_id");
            $structType = Si4Util::pathArg($inputValue, "struct_type");
            $fileName = Si4Util::pathArg($inputValue, "data/files/0/ownerId");

            if ($handleId && $fileName) {
                $thumbUrl = FileHelpers::getThumbUrl($handleId, $structType, $fileName);
                return $thumbUrl;
            }
            return FileHelpers::getDefaultThumbForStructType($structType);
        };
    }


    // $breadcrumbs - [[0] => [ "link" => "/link/1", "text" => "Entity title" ]]
    public static function breadcrumbsPresentation($breadcrumbs) {
        return join(
            '<span class="sep"> / </span>',
            array_map(function($bcItem) {
                return '<a href="'.$bcItem["link"].'">'.$bcItem["text"].'</a>';
            }, $breadcrumbs)
        );
    }

    public static function fileSizePresentation($fileSizeInBytes) {
        if ($fileSizeInBytes >= 1048576)
            $result = number_format($fileSizeInBytes / 1048576, 1) .' MB';
        elseif ($fileSizeInBytes >= 1024)
            $result = number_format($fileSizeInBytes / 1024, 1) .' KB';
        else
            $result = $fileSizeInBytes .' B';

        return $result;
    }

    public static function fileDatePresentation($fileDate) {
        // expected format "2018-06-18T19:53:30Z"
        //return $result;
        $time = strtotime($fileDate);
        $newformat = date('d.m.Y, H:i', $time);
        return $newformat;
    }

    public static function dateToISOString($date = null) {
        if (!$date) $date = time();
        $newformat = date('Y-m-dTH:i:s', $date);
        return $newformat;
    }

}