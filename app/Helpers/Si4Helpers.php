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

    // Field definitions mapped from any scheme into si4 format
    public static $si4FieldDefinitions = [
        /*
        "fieldName" => [
            "fieldName" => "fieldname",     // * Mandatory * Field name
            "translateKey" => "field_key",  // * Mandatory * Translation key
            "hasLanguage" => true,          // Field data have lang attribute
            "showAllLanguages" => true,     // Display all languages, not only FE selected language
        ]
        */
        "title" => [
            "fieldName" => "title",
            "translateKey" => "field_title",
            "hasLanguage" => true,
        ],
        "creator" => [
            "fieldName" => "creator",
            "translateKey" => "field_creator",
        ],
        "date" => [
            "fieldName" => "date",
            "translateKey" => "field_date",
        ],
        "description" => [
            "fieldName" => "description",
            "translateKey" => "field_description",
        ],
    ];

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
            "si4" => [],
        ];

        foreach (self::$si4FieldDefinitions as $fieldName => $fieldDef) {
            $fieldPath = "_source/data/si4/".$fieldName;
            $fieldData = Si4Util::pathArg($elasticEntity, $fieldPath, []);

            $hasLanguage = Si4Util::getArg($fieldDef, "hasLanguage", false);
            $showAllLanguages = Si4Util::getArg($fieldDef, "showAllLanguages", false);

            $result["si4"][$fieldName] = [];

            foreach($fieldData as $fieldEntry) {
                $langMatch = !$hasLanguage || $showAllLanguages || SessionLanguage::current() === $fieldEntry["lang"];
                if (!$langMatch) continue;
                $result["si4"][$fieldName][] = $fieldEntry["value"];
            }

            // Let Non-titled documents at least have something to display
            if (!count($result["si4"]["title"])) {
                $result["si4"]["title"] = [$result["system"]["handle_id"]];
            }
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