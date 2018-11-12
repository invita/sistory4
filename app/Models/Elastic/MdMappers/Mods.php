<?php

namespace App\Models\Elastic\MdMappers;

use App\Helpers\DcHelpers;
use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Models\Si4Field;

class Mods extends AbstractMdMapper
{
    function mdTypeToHandle() {
        return "Mods";
    }

    function mapXmlData($xmlData) {
        $result = [];
        $fieldDefs = Si4Field::getSi4Fields();


        // MODS test implementation
        // TODO: XSD attribute named 'type' does not work with xsd2php...

        $mods = Si4Util::pathArg($xmlData, "ModsPropName/0", null);
        if (!$mods) return $result;

        // TitleInfo
        $titleInfos = Si4Util::pathArg($mods, "TitleInfoPropName", []);
        //print_r($titleInfos);
        //die();

        foreach ($titleInfos as $titleInfo) {
            $titles = Si4Util::pathArg($titleInfo, "TitlePropName", []);
            foreach ($titles as $title) {
                $value = $title["value"];
                $fieldResult = [
                    "metadataSrc" => $this->mdTypeToHandle(),
                    "value" => $value
                ];

                if ($fieldDefs["title"]->has_language) {
                    $lang = Si4Helpers::$defaultLang;
                    $fieldResult["lang"] = $lang;
                }

                $result[$fieldDefs["title"]->field_name][] = $fieldResult;
            }
        }

        return $result;
    }

}