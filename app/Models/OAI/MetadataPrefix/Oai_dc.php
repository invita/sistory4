<?php
namespace App\Models\OAI\MetadataPrefix;

use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Models\OAI\OAIHelper;
use App\Models\OAI\OAIXmlElement;
use App\Models\OAI\OAIXmlOutput;
use App\Models\OaiField;
use App\Models\Si4Field;

class OAI_DC extends AbsMetadataPrefixHandler {

    function metadataToXml($oaiRecord) {

        $mdPrefixData = OAIHelper::getMetadataPrefix("oai_dc");
        $metadata = new OAIXmlElement("metadata");

        $oai_dc = new OAIXmlElement("oai_dc:dc");
        $oai_dc->setAttribute("xmlns:oai_dc", $mdPrefixData["namespace"]);
        $oai_dc->setAttribute("xmlns:dc", "http://purl.org/dc/elements/1.1/");
        $oai_dc->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $oai_dc->setAttribute("xsi:schemaLocation", $mdPrefixData["schema"]);
        $oai_dc->appendTo($metadata);

        $si4 = Si4Util::pathArg($oaiRecord->dataElastic, "_source/data/si4", []);
        //$oai_dc->setValue(OAIXmlOutput::wrapInCDATA(print_r($si4, true)));



        /*
        foreach ($si4 as $fieldName => $fieldDataArray) {
            foreach ($fieldDataArray as $fieldData) {
                $metadataSrc = Si4Util::getArg($fieldData, "metadataSrc", "");
                //if ($metadataSrc != "DC") continue;

                $fieldDef = Si4Util::getArg(Si4Field::getSi4Fields(), $fieldName, null);
                if (!$fieldDef) continue;

                $value = Si4Util::getArg($fieldData, "value", "");
                $lang = Si4Util::getArg($fieldData, "lang", "");

                $fieldEl = new OAIXmlElement($fieldDef->field_name);
                $fieldEl->setValue($value);
                if ($lang) $fieldEl->setAttribute("xml:lang", $lang);
                $fieldEl->appendTo($oai_dc);
            }
        }
        */

        $oaiFields = OaiField::getOaiFieldsForGroup(1);
        //print_r($oaiFields); die();
        //print_r($si4); die();

        foreach ($oaiFields as $oaiField) {
            $xml_path = Si4Util::getArg($oaiField, "xml_path", null);
            $xml_name = Si4Util::getArg($oaiField, "xml_name", null);
            $has_language = Si4Util::getArg($oaiField, "has_language", null);

            if (isset($oaiField["mapping"]) && $oaiField["mapping"]) {
                foreach ($oaiField["mapping"] as $mapping) {

                    $si4field = Si4Util::getArg($mapping, "si4field", null);
                    if (!$si4field) continue;

                    $xml_value = Si4Util::getArg($mapping, "xml_value", "value");
                    $xml_attributes = Si4Util::getArg($mapping, "xml_attributes", null);

                    $fieldDatas = Si4Util::getArg($si4, $mapping["si4field"], []);
                    foreach ($fieldDatas as $fieldData) {
                        /*
                        $fieldData:
                            [metadataSrc] => dc
                            [value] => 1848–1918
                            [lang] => slv
                            [test] => 1848–1918
                            [test2] => Hello!
                        */
                        $fieldEl = new OAIXmlElement($xml_name);

                        $value = Si4Util::getArg($fieldData, $xml_value, null);
                        $fieldEl->setValue($value);

                        // TODO
                        $lang = Si4Util::getArg($fieldData, "lang", null);
                        if ($has_language && $lang) {
                            $fieldEl->setAttribute("xml:lang", $lang);
                        }

                        if ($xml_attributes) {
                            foreach ($xml_attributes as $attr) {
                                $attrName = Si4Util::getArg($attr, "name", null);
                                $attrValue = Si4Util::getArg($attr, "value", "value");
                                $attrValueEvaluated = Si4Util::getArg($fieldData, $attrValue, null);
                                if (!$attrName) continue;
                                $fieldEl->setAttribute($attrName, $attrValueEvaluated);
                            }
                        }

                        $fieldEl->appendTo($oai_dc);
                    }
                }
            }
        }



        //print_r("foo"); die();



        //$oai_dc->setValue($oaiRecord->identifier);


        //print_r($this->data["IDENTIFIER_V2"]);
        //die();


        /*
        $dcFields = $this->parseMetadataFields();

        foreach ($dcFields as $idx => $dcField){

            $tagName = $dcField["tagName"];
            $tag = new OAIXmlElement($tagName);
            foreach ($dcField as $attrName => $attrValue){
                if ($attrName == "tagName") continue;
                if ($attrName == "value") { $tag->setValue($attrValue); continue; }

                if ($attrName == "xml:lang") $attrValue = $this->getLangCode($attrValue);

                $tag->setAttribute($attrName, $attrValue);
            }

            $tag->appendTo($oai_dc);
        }
        */

        return $metadata;
    }
}