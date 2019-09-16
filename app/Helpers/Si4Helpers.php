<?php
namespace App\Helpers;
use App\Http\Middleware\SessionLanguage;
use App\Models\Behaviour;
use App\Models\Si4Field;

/**
 * Class Si4Helpers
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class Si4Helpers {

    // defaultLang is used for fields with no language data but with hasLanguage definition attribute set to true
    public static $defaultLang = "eng";

    // Entity List presentation

    public static function getEntityListPresentation($elasticEntity, $elasticParent = null) {

        $result = [
            "system" => [
                "id" => Si4Util::pathArg($elasticEntity, "id", ""),
                "handle_id" => Si4Util::pathArg($elasticEntity, "_source/handle_id", ""),
                "parent" => Si4Util::pathArg($elasticEntity, "_source/parent", ""),
                "primary" => Si4Util::pathArg($elasticEntity, "_source/primary", ""),
                "collection" => Si4Util::pathArg($elasticEntity, "_source/collection", ""),
                "struct_type" => Si4Util::pathArg($elasticEntity, "_source/struct_type", ""),
                "struct_subtype" => Si4Util::pathArg($elasticEntity, "_source/struct_subtype", "default"),
                "entity_type" => Si4Util::pathArg($elasticEntity, "_source/entity_type", ""),
                "active" => Si4Util::pathArg($elasticEntity, "_source/active", ""),
            ],
            //"si4orig" => Si4Util::pathArg($elasticEntity, "_source/data/si4", []),
            "si4" => [],
        ];

        $result["si4tech"] = [];
        $si4tech = Si4Util::pathArg($elasticEntity, "_source/data/si4tech", []);
        foreach ($si4tech as $key => $val) {
            if ($key == "description") {
                // Copy only matching lang description from si4tech
                foreach ($val as $desc) {
                    if (!isset($desc["lang"]) || SessionLanguage::current() == $desc["lang"]) {
                        if (!isset($result["si4tech"][$key])) $result["si4tech"][$key] = [];
                        $result["si4tech"][$key][] = $desc["value"];
                    }
                }
            } else {
                // Copy other si4tech fields normally
                $result["si4tech"][$key] = $val;
            }
        }

        $si4Fields = Si4Field::getSi4Fields();
        $behaviour = Behaviour::getBehaviourForElasticEntity($elasticEntity);

        // Parse si4 metadata for frontend displaying
        foreach ($behaviour["fields"] as $fieldName => $fieldBehaviour) {
            $fieldDef = $si4Fields[$fieldName];

            // Skip non-frontend fields
            if (!$fieldBehaviour["display_frontend"]) continue;

            // Get field elastic data
            $fieldPath = "_source/data/si4/".$fieldName;
            $fieldData = Si4Util::pathArg($elasticEntity, $fieldPath, []);
            if (!count($fieldData)) continue;

            $result["si4"][$fieldName] = [];

            foreach($fieldData as $fieldEntry) {
                $langMatch = !$fieldDef["has_language"] || $fieldBehaviour["show_all_languages"] || SessionLanguage::current() === $fieldEntry["lang"];
                if (!$langMatch) continue;
                $result["si4"][$fieldName][] = $fieldEntry["value"];
            }

            // Join array entries into only one, if field definition says inline
            if ($fieldBehaviour["inline"]) {
                $result["si4"][$fieldName] = [join($fieldBehaviour["inline_separator"], $result["si4"][$fieldName])];
            }
        }

        // Let Non-titled documents at least have something to display
        if (!isset($result["si4"]["title"]) || !count($result["si4"]["title"])) {
            $surrogateTitle = $result["system"]["handle_id"];
            if ($result["system"]["struct_type"] === "file") {
                if ($elasticParent) {
                    $surrogateTitle = Si4Util::pathArg($elasticParent, "_source/data/si4/title/0/value", $surrogateTitle);
                } else {
                    $surrogateTitle = Si4Util::pathArg($elasticEntity, "_source/data/files/0/fileName", $surrogateTitle);
                }
            }
            $result["si4"]["title"] = [$surrogateTitle];
        }

        $result["thumb"] = self::getThumbUrl($elasticEntity);
        $result["thumbJson"] = self::getThumbJsonUrl($elasticEntity);

        // Override thumb if THUMB file is defined
        $files = Si4Util::pathArg($elasticEntity, "_source/data/files", []);
        foreach ($files as $file) {
            if (strtoupper($file["behaviour"]) === "THUMB") {
                $result["thumb"] = FileHelpers::getPreviewUrl($result["system"]["handle_id"], $result["system"]["struct_type"], $file["url"]);
                break;
            }
        }



        if ($result["system"]["struct_type"] === "collection") {
            // Collection specific
            $searchResultsShow = Si4Util::getArg($si4tech, "searchResultsShow", "cards");
            if (!in_array($searchResultsShow, ["cards", "table"])) $searchResultsShow = "cards";
            $result["system"]["child_style"] = $searchResultsShow;
        } elseif ($result["system"]["struct_type"] === "file") {
            // File specific
            $result["file"] = [
                "fileName" => Si4Util::pathArg($elasticEntity, "_source/data/files/0/fileName", ""),
            ];
        }

        return $result;
    }


    // Entity Details presentation

    public static function getEntityDetailsPresentation($elasticEntity) {
        $result = self::getEntityListPresentation($elasticEntity);

        // ...

        return $result;
    }


    public static function findFileFullTextHits(&$entityPresentation, $elasticEntity, $q) {

        // This is now done by Elastic.
        if (env("SI4_ELASTIC_HIGHLIGHT")) {
            $entityPresentation["file"]["fullTextHits"] = Si4Util::pathArg($elasticEntity, "highlight", []);
        } else {

            // Custom PHP highlighting
            if (!isset($entityPresentation["file"])) return;
            $entityPresentation["file"]["fullTextHits"] = [];
            $fullText = Si4Util::pathArg($elasticEntity, "_source/data/files/0/fullText", "");
            //print_r($elasticEntity);
            if (!$fullText) return;
            if (!$q) return;

            $qWords = explode(" ", $q);

            $beforeCharsCount = 50;
            $afterCharsCount = 50;

            //print_r($qWords); die();

            foreach ($qWords as $qWord) {
                $qWordLen = mb_strlen($qWord);
                $pos = -1;
                for ($i = 0; $i < env("SI4_ELASTIC_HIGHLIGHT_COUNT"); $i++) {
                    $pos = stripos($fullText, $qWord, $pos +1);
                    if ($pos === false) break;

                    $startPos = max(0, $pos -$beforeCharsCount);
                    $len = $qWordLen +($pos -$startPos) +$afterCharsCount;
                    $innerText = substr($fullText, $startPos, $len);

                    $firstSpacePos = stripos($innerText, " ");
                    if (!$firstSpacePos || $firstSpacePos > $beforeCharsCount) $firstSpacePos = 0;

                    $lastSpacePos = strripos($innerText, " ");
                    if (!$lastSpacePos || $lastSpacePos < $beforeCharsCount + $qWordLen) $lastSpacePos = strlen($innerText);

                    $innerTextClean = substr($innerText, $firstSpacePos +1, $lastSpacePos -$firstSpacePos -1); //." ...";

                    //if ($startPos > 0) $innerTextClean = "... ".$innerTextClean;

                    //$innerTextStyled = "...".preg_replace("/(".$qWord.")/i", '<span class="match">$1</span>', $innerTextClean)."...";

                    $entityPresentation["file"]["fullTextHits"][] = $innerTextClean;
                    //$entityPresentation["file"]["fullTextHitsHtml"][] = $innerTextStyled;
                }
            }

        }
    }


    // Private Helpers

    private static function getThumbUrl($elasticEntity) {
        // Find default file
        $handleId = Si4Util::pathArg($elasticEntity, "_source/handle_id");
        $structType = Si4Util::pathArg($elasticEntity, "_source/struct_type");
        $fileName = Si4Util::pathArg($elasticEntity, "_source/data/files/0/fileName");

        if ($handleId && $fileName) {
            $thumbUrl = FileHelpers::getThumbUrl($handleId, $structType, $fileName);
            //$thumbUrl = ImageHelpers::getMainThumbUrl($handleId, $fileName);
            return $thumbUrl;
        }
        return FileHelpers::getDefaultThumbForStructType($structType);
    }

    private static function getThumbJsonUrl($elasticEntity) {

        $structType = Si4Util::pathArg($elasticEntity, "_source/struct_type");
        if ($structType === "file") {
            $handleId = Si4Util::pathArg($elasticEntity, "_source/parent");
        } else {
            $handleId = Si4Util::pathArg($elasticEntity, "_source/handle_id");
        }
        $fileName = Si4Util::pathArg($elasticEntity, "_source/data/files/0/fileName");

        if ($handleId && $fileName) {
            $jsonUrl = ImageHelpers::getInfoJsonUrl($handleId, $fileName);
            return $jsonUrl;
        }
        return "";
    }




    // $breadcrumbs - [[0] => [ "link" => "/link/1", "text" => "Entity title" ]]
    public static function breadcrumbsPresentation($breadcrumbs) {
        return join(
            '<span class="sep"> / </span>',
            array_map(function($bcItem) {
                $icon = isset($bcItem["link"]) && $bcItem["link"] === "/" ?
                    '<img src="/images/home.png" class="breadcrumbsIcon" />' : '';
                return '<a href="'.$bcItem["link"].'">'.$icon.$bcItem["text"].'</a>';
            }, $breadcrumbs)
        );
    }

}

//Si4Helpers::initFieldDefinitions();