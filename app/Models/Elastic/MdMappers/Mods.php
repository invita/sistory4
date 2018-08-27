<?php

namespace App\Models\Elastic\MdMappers;

use App\Helpers\DcHelpers;
use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;

class Mods extends AbstractMdMapper
{
    function mdTypeToHandle() {
        return "Mods";
    }

    function mapXmlData($xmlData) {
        $result = [];
        $fieldDefs = Si4Helpers::$si4FieldDefinitions;

        // Test mods implementation

        $modsTitles = Si4Util::pathArg($xmlData,
            "ModsPropName/0/RelatedItemPropName/0/RelatedItemPropName/0/TitleInfoPropName/0/TitlePropName", []);

        $fieldDef = $fieldDefs["title"];

        foreach ($modsTitles as $modsTitle) {
            $value = $modsTitle["value"];
            $lang = Si4Helpers::$defaultLang;

            if ($fieldDef["hasLanguage"]) {
                $result[$fieldDef["fieldName"]][] = [ "lang" => $lang, "value" => $value ];
            } else {
                $result[$fieldDef["fieldName"]][] = [ "value" => $value ];
            }

        }

        return $result;
    }
}