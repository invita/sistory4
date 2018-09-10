<?php
namespace App\Helpers;
use App\Http\Middleware\SessionLanguage;

/**
 * Class Si4Helpers
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class Si4Helpers {

    // ***** Si4 Model *****

    // Return a new field definition assuming default values for non-specified attributes
    private static function fieldDefinition($fieldDefs) {
        return [
            "fieldName" => $fieldDefs["fieldName"],
            "translateKey" => Si4Util::getArg($fieldDefs, "translateKey", "si4_field_".$fieldDefs["fieldName"]),
            "hasLanguage" => Si4Util::getArg($fieldDefs, "hasLanguage", false),
            "showAllLanguages" => Si4Util::getArg($fieldDefs, "showAllLanguages", false),
            "inline" => Si4Util::getArg($fieldDefs, "inline", false),
            "inlineSeparator" => Si4Util::getArg($fieldDefs, "inlineSeparator", ", "),
            "displayOnFrontend" => Si4Util::getArg($fieldDefs, "displayOnFrontend", false),
        ];
    }

    // Si4 field definitions
    public static $si4FieldDefinitions;
    public static function initFieldDefinitions() {
        self::$si4FieldDefinitions = [
            /*
            // Notes:
            "fieldName" => self::fieldDefinition([
                "fieldName" => "fieldname",     // * Mandatory * Field name
                "translateKey" => "field_key",  // Translation key
                "hasLanguage" => true,          // Field data have lang attribute
                "showAllLanguages" => true,     // Display all languages, not only FE selected language
                "inline" => false,              // Values are displayed inline
                "inlineSeparator" => ":",       // If inline, defines values separator
                "displayOnFrontend" => false,   // Should this field be displayed on frontend
            ]),
            */
            "title" => self::fieldDefinition([
                "fieldName" => "title",
                "hasLanguage" => true,
                "showAllLanguages" => true,
                "displayOnFrontend" => true,
            ]),
            "creator" => self::fieldDefinition([
                "fieldName" => "creator",
                "inline" => true,
                "inlineSeparator" => " / ",
                "displayOnFrontend" => true,
            ]),
            "subject" => self::fieldDefinition([
                "fieldName" => "subject",
                "inline" => true,
                "inlineSeparator" => ", ",
                "displayOnFrontend" => true,
            ]),
            "description" => self::fieldDefinition([
                "fieldName" => "description",
            ]),
            "publisher" => self::fieldDefinition([
                "fieldName" => "publisher",
                "inline" => true,
                "inlineSeparator" => " / ",
                "displayOnFrontend" => true,
            ]),
            "contributor" => self::fieldDefinition([
                "fieldName" => "contributor",
                "inline" => true,
                "inlineSeparator" => " / ",
                "displayOnFrontend" => true,
            ]),
            "date" => self::fieldDefinition([
                "fieldName" => "date",
            ]),
            "type" => self::fieldDefinition([
                "fieldName" => "type",
                "inline" => true,
                "inlineSeparator" => " / ",
            ]),
            "format" => self::fieldDefinition([
                "fieldName" => "format",
            ]),
            "identifier" => self::fieldDefinition([
                "fieldName" => "identifier",
            ]),
            "source" => self::fieldDefinition([
                "fieldName" => "source",
            ]),
            "language" => self::fieldDefinition([
                "fieldName" => "language",
                "inline" => true,
                "inlineSeparator" => " / ",
            ]),
            "relation" => self::fieldDefinition([
                "fieldName" => "relation",
            ]),
            "coverage" => self::fieldDefinition([
                "fieldName" => "coverage",
                "inline" => true,
                "inlineSeparator" => ", ",
            ]),
            "rights" => self::fieldDefinition([
                "fieldName" => "rights",
            ]),
        ];
    }


    // defaultLang is used for fields with no language data but with hasLanguage definition attribute set to true
    public static $defaultLang = "eng";

    // ***** End of Si4 Model *****


    // Entity List presentation

    public static function getEntityListPresentation($elasticEntity) {

        $result = [
            "system" => [
                "id" => Si4Util::pathArg($elasticEntity, "id", ""),
                "handle_id" => Si4Util::pathArg($elasticEntity, "_source/handle_id", ""),
                "parent" => Si4Util::pathArg($elasticEntity, "_source/parent", ""),
                "primary" => Si4Util::pathArg($elasticEntity, "_source/primary", ""),
                "collection" => Si4Util::pathArg($elasticEntity, "_source/collection", ""),
                "struct_type" => Si4Util::pathArg($elasticEntity, "_source/struct_type", ""),
                "struct_subtype" => Si4Util::pathArg($elasticEntity, "_source/struct_subtype", ""),
                "entity_type" => Si4Util::pathArg($elasticEntity, "_source/entity_type", ""),
                "active" => Si4Util::pathArg($elasticEntity, "_source/active", ""),
            ],
            //"si4orig" => Si4Util::pathArg($elasticEntity, "_source/data/si4", []),
            "si4" => [],
        ];

        // Parse si4 metadata for frontend displaying
        foreach (self::$si4FieldDefinitions as $fieldName => $fieldDef) {

            // Skip non-frontend fields
            if (!$fieldDef["displayOnFrontend"]) continue;

            // Get field elastic data
            $fieldPath = "_source/data/si4/".$fieldName;
            $fieldData = Si4Util::pathArg($elasticEntity, $fieldPath, []);
            if (!count($fieldData)) continue;

            $result["si4"][$fieldName] = [];

            foreach($fieldData as $fieldEntry) {
                $langMatch = !$fieldDef["hasLanguage"] || $fieldDef["showAllLanguages"] || SessionLanguage::current() === $fieldEntry["lang"];
                if (!$langMatch) continue;
                $result["si4"][$fieldName][] = $fieldEntry["value"];
            }

            // Join array entries into only one, if field definition says inline
            if ($fieldDef["inline"]) {
                $result["si4"][$fieldName] = [join($fieldDef["inlineSeparator"], $result["si4"][$fieldName])];
            }
        }

        // Let Non-titled documents at least have something to display
        if (!isset($result["si4"]["title"]) || !count($result["si4"]["title"])) {
            $result["si4"]["title"] = [$result["system"]["handle_id"]];
        }

        $result["thumb"] = self::getThumbUrl($elasticEntity);

        if ($result["system"]["struct_type"] === "file") {
            $result["file"] = [
                "fileName" => Si4Util::pathArg($elasticEntity, "_source/data/files/0/ownerId", ""),
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
        if (!isset($entityPresentation["file"])) return;
        $entityPresentation["file"]["fullTextHits"] = [];
        $fullText = Si4Util::pathArg($elasticEntity, "_source/data/files/0/fullText", "");
        //print_r($elasticEntity);
        if (!$fullText) return;
        if (!$q) return;

        $qWords = explode(" ", $q);

        $beforeCharsCount = 50;
        $afterCharsCount = 50;

        foreach ($qWords as $qWord) {
            $qWordLen = mb_strlen($qWord);
            $pos = -1;
            for ($i = 0; $i < 3; $i++) {
                $pos = stripos($fullText, $qWord, $pos +1);
                if ($pos === false) break;

                $innerText = substr($fullText, $pos -$beforeCharsCount, $qWordLen +$beforeCharsCount +$afterCharsCount);

                $firstSpacePos = stripos($innerText, " ");
                if (!$firstSpacePos || $firstSpacePos > $beforeCharsCount) $firstSpacePos = 0;

                $lastSpacePos = strripos($innerText, " ");
                if (!$lastSpacePos || $lastSpacePos < $beforeCharsCount + $qWordLen) $lastSpacePos = strlen($innerText);

                $innerTextClean = substr($innerText, $firstSpacePos +1, $lastSpacePos -$firstSpacePos -1);

                //$innerTextStyled = "...".preg_replace("/(".$qWord.")/i", '<span class="match">$1</span>', $innerTextClean)."...";

                $entityPresentation["file"]["fullTextHits"][] = $innerTextClean;
                //$entityPresentation["file"]["fullTextHitsHtml"][] = $innerTextStyled;
            }
        }
    }


    // Private Helpers

    private static function getThumbUrl($elasticEntity) {
        // Find default file
        $handleId = Si4Util::pathArg($elasticEntity, "_source/handle_id");
        $structType = Si4Util::pathArg($elasticEntity, "_source/struct_type");
        $fileName = Si4Util::pathArg($elasticEntity, "_source/data/files/0/ownerId");

        if ($handleId && $fileName) {
            $thumbUrl = FileHelpers::getThumbUrl($handleId, $structType, $fileName);
            return $thumbUrl;
        }
        return FileHelpers::getDefaultThumbForStructType($structType);
    }
}

Si4Helpers::initFieldDefinitions();