<?php
namespace App\Models\OAI\MetadataPrefix;

use App\Helpers\Si4Helpers;
use App\Helpers\Si4Util;
use App\Models\OAI\OAIXmlElement;

class OAI_SI4 extends AbsMetadataPrefixHandler {

    function metadataToXml($oaiRecord) {

        $metadata = new OAIXmlElement("metadata");

        $oai = new OAIXmlElement("resource");

        $attrs = Si4Util::getArg($this->mdPrefixData, "attrs", []);
        foreach ($attrs as $attrKeyValue) {
            if (!isset($attrKeyValue["key"])) continue;
            $oai->setAttribute($attrKeyValue["key"], Si4Util::getArg($attrKeyValue, "value", ""));
        }
        $oai->appendTo($metadata);

        $si4 = Si4Util::pathArg($oaiRecord->dataElastic, "_source/data/si4", []);

        foreach ($this->oaiFields as $oaiField) {
            $xml_path = Si4Util::getArg($oaiField, "xml_path", null);
            $xml_name = Si4Util::getArg($oaiField, "xml_name", null);
            $has_language = Si4Util::getArg($oaiField, "has_language", null);

            if (isset($oaiField["mapping"]) && $oaiField["mapping"]) {
                foreach ($oaiField["mapping"] as $mapping) {

                    $si4field = Si4Util::getArg($mapping, "si4field", null);
                    if (!$si4field) continue;

                    $xml_values = Si4Util::getArg($mapping, "xml_values", []);
                    //$xml_attributes = Si4Util::getArg($mapping, "xml_attributes", null);

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

                        $lang = Si4Util::getArg($fieldData, "lang", null);
                        if ($has_language && $lang) {
                            $fieldEl->setAttribute("xml:lang", $lang);
                        }

                        foreach ($xml_values as $xml_pathValue) {
                            $path = Si4Util::getArg($xml_pathValue, "path", "");
                            $value = Si4Util::getArg($xml_pathValue, "value", "");
                            $valueEvaluated = $this->getValue($fieldData, $value);

                            $pathComps = explode("/", $path);
                            $curPath = "";
                            foreach ($pathComps as $idx => $pathComp) {
                                $lastComp = $idx == count($pathComps) -1;
                                if ($pathComp && $pathComp[0] === "@") {
                                    if ($lastComp) {
                                        $valueEl = $fieldEl->forcePath($curPath);
                                        $valueEl->setAttribute(substr($pathComp, 1), $valueEvaluated);
                                    } else {
                                        // What?
                                    }
                                } else {
                                    if ($curPath && $pathComp) $curPath .= "/";
                                    $curPath .= $pathComp;
                                    if ($lastComp) {
                                        $valueEl = $fieldEl->forcePath($curPath);
                                        $valueEl->setValue($valueEvaluated);
                                    }
                                }
                            }
                        }



                        /*
                        if ($xml_attributes) {
                            foreach ($xml_attributes as $attr) {
                                $attrName = Si4Util::getArg($attr, "name", null);
                                $attrValue = Si4Util::getArg($attr, "value", "value");
                                $attrValueEvaluated = $this->getValue($fieldData, $attrValue);
                                if (!$attrName) continue;
                                $fieldEl->setAttribute($attrName, $attrValueEvaluated);
                            }
                        }
                        */

                        /*
                        if (count($xml_values) && isset($xml_values[0]["name"]) && $xml_values[0]["name"]) {
                            // Multiple element values variant
                            foreach ($xml_values as $xml_name_value) {
                                $elName = Si4Util::getArg($xml_name_value, "name", $mapping["si4field"]);
                                if (!$elName) continue;
                                $elValue = Si4Util::getArg($xml_name_value, "value", "");
                                $elValueEvaluated = $this->getValue($fieldData, $elValue);

                                $valueEl = new OAIXmlElement($elName);
                                $valueEl->setValue($elValueEvaluated);
                                $valueEl->appendTo($fieldEl);
                            }
                        } else {
                            // Simple element values variant
                            $xml_elValue = Si4Util::pathArg($xml_values, "0/value", "");
                            $value = Si4Util::getArg($fieldData, $xml_elValue, null);
                            $fieldEl->setValue($value);
                        }
                        */


                        $fieldEl->appendTo($oai->forcePath($xml_path));
                    }
                }
            }
        }

        return $metadata;
    }

    private function getValue($fieldData, $fieldValue) {
        $isQuoted = preg_match('/^(["]).*\1$/m', $fieldValue);
        if ($isQuoted) {
            return substr($fieldValue, 1, strlen($fieldValue) -2);
        } else {
            return Si4Util::getArg($fieldData, $fieldValue, null);
        }
    }
}