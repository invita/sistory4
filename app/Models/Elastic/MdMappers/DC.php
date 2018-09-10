<?php

namespace App\Models\Elastic\MdMappers;

use App\Helpers\DcHelpers;
use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;

class DC extends AbstractMdMapper
{
    function mdTypeToHandle() {
        return "DC";
    }

    function mapXmlData($xmlData) {
        $result = [];
        $fieldDefs = Si4Helpers::$si4FieldDefinitions;

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
            if (!isset($result[$fieldDef["fieldName"]])) $result[$fieldDef["fieldName"]] = [];

            foreach ($val as $valIdx => $valValue) {
                $value = $valValue["value"];

                $fieldResult = [ "value" => $value ];

                if ($fieldDef["hasLanguage"]) {
                    $lang = isset($valValue["LangPropName"]) ? $valValue["LangPropName"] : "";
                    if (!$lang) $lang = Si4Helpers::$defaultLang;
                    $fieldResult["lang"] = $lang;
                }

                $result[$fieldDef["fieldName"]][] = $fieldResult;
            }
        }

        return $result;
    }
}