<?php
namespace App\Models\OAI\MetadataPrefix;

use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Models\OAI\OAIXmlElement;

class OAI_Datacite extends AbsMetadataPrefixHandler {

    function metadataToXml($oaiRecord) {

        $metadata = new OAIXmlElement("metadata");

        $oai_datacite = new OAIXmlElement("resource");
        //$oai_datacite->setAttribute("xmlns", $this->mdPrefixData["namespace"]);
        //$oai_datacite->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        //$oai_datacite->setAttribute("xsi:schemaLocation", $this->mdPrefixData["schema"]);
        $oai_datacite->appendTo($metadata);

        $si4 = Si4Util::pathArg($oaiRecord->dataElastic, "_source/data/si4", []);
        //$oai_datacite->setValue(OAIXmlOutput::wrapInCDATA(print_r($si4, true)));

        foreach ($this->oaiFields as $oaiField) {
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

                        $fieldEl->appendTo($oai_datacite->forcePath($xml_path));
                    }
                }
            }
        }

        return $metadata;
    }
}