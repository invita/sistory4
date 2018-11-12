<?php

namespace App\Models\Elastic\MdMappers;

use App\Helpers\DcHelpers;
use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Models\Si4Field;

class DC extends AbstractMdMapper
{
    function mdTypeToHandle() {
        return "DC";
    }

    function mapXmlData($xmlData) {
        $result = [];
        $fieldDefs = Si4Field::getSi4Fields();

        foreach ($xmlData as $key => $val) {

            // Continue if empty field value
            if (!$val || !count($val)) continue;

            // Find si4 field by DC element
            $fieldDef = null;
            switch ($key) {
                case "TitlePropName": $fieldDef = $fieldDefs["title"]; break;
                case "CreatorPropName": $fieldDef = $fieldDefs["creator"]; break;
                case "SubjectPropName": $fieldDef = $fieldDefs["subject"]; break;
                case "DescriptionPropName": $fieldDef = $fieldDefs["description"]; break;
                case "PublisherPropName": $fieldDef = $fieldDefs["publisher"]; break;
                case "ContributorPropName": $fieldDef = $fieldDefs["contributor"]; break;
                case "DatePropName": $fieldDef = $fieldDefs["date"]; break;
                case "TypePropName": $fieldDef = $fieldDefs["type"]; break;
                case "FormatPropName": $fieldDef = $fieldDefs["format"]; break;
                case "IdentifierPropName": $fieldDef = $fieldDefs["identifier"]; break;
                case "SourcePropName": $fieldDef = $fieldDefs["source"]; break;
                case "LanguagePropName": $fieldDef = $fieldDefs["language"]; break;
                case "RelationPropName": $fieldDef = $fieldDefs["relation"]; break;
                case "CoveragePropName": $fieldDef = $fieldDefs["coverage"]; break;
                case "RightsPropName": $fieldDef = $fieldDefs["rights"]; break;
            }

            // Continue if unknown si4 field
            if (!$fieldDef) continue;

            // Prepare an empty array for the field data if not exists yet
            if (!isset($result[$fieldDef->field_name])) $result[$fieldDef->field_name] = [];

            foreach ($val as $valIdx => $valValue) {
                $value = isset($valValue["value"]) ? $valValue["value"] : "";

                $fieldResult = [
                    "metadataSrc" => $this->mdTypeToHandle(),
                    "value" => $value
                ];

                if ($fieldDef->has_language) {
                    $lang = isset($valValue["LangPropName"]) ? $valValue["LangPropName"] : "";
                    if (!$lang) $lang = Si4Helpers::$defaultLang;
                    $fieldResult["lang"] = $lang;
                }

                $result[$fieldDef->field_name][] = $fieldResult;
            }
        }

        return $result;
    }
}