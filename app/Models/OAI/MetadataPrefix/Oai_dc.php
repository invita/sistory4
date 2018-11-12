<?php
namespace App\Models\OAI\MetadataPrefix;

use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Models\OAI\OAIHelper;
use App\Models\OAI\OAIXmlElement;
use App\Models\OAI\OAIXmlOutput;
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

        foreach ($si4 as $fieldName => $fieldDataArray) {
            foreach ($fieldDataArray as $fieldData) {
                $metadataSrc = Si4Util::getArg($fieldData, "metadataSrc", "");
                if ($metadataSrc != "DC") continue;
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