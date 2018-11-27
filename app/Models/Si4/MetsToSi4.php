<?php
namespace App\Models\Si4;

use App\Helpers\Si4Util;
use App\Models\MappingGroup;
use App\Models\Si4Field;
use Mockery\CountValidator\Exception;

class MetsToSi4
{
    private $metsXmlString;
    private $metsXmlDoc;
    private $fieldDefs;
    private $mappingGroups;
    private $result;
    public function __construct($metsXmlString) {
        $this->metsXmlString = $metsXmlString;
    }

    public function run() {
        $this->prepare();
        $this->doMetsStaticMapping();
        $this->doSi4Mapping();

        return $this->result;
    }

    private function prepare() {
        $this->metsXmlDoc = simplexml_load_string($this->metsXmlString);
        $this->fieldDefs = Si4Field::getSi4Fields();
        $this->mappingGroups = MappingGroup::getMappingGroups();
        $this->result = [];
    }

    private function doMetsStaticMapping() {
        // TODO
        $this->result["header"] = [];

        if ($this->metsXmlDoc) {

            // METS:mets
            $mets = Si4Util::getArg($this->metsXmlDoc->xpath("/METS:mets"), '0', null);
            if (!$mets) { return; /* No METS:mets! */ }
            if ($mets["ID"]) $this->result["header"]["id"] = (string)$mets["ID"];
            if ($mets["OBJID"]) $this->result["header"]["objId"] = (string)$mets["OBJID"];
            if ($mets["TYPE"]) $this->result["header"]["type"] = (string)$mets["TYPE"];

            // METS:mets/METS:metsHdr
            $metsHdr = Si4Util::getArg($mets->xpath("METS:metsHdr"), '0', null);
            if (!$metsHdr) { return; /* No METS:metsHdr! */ }
            if ($metsHdr["CREATEDATE"]) $this->result["header"]["createDate"] = (string)$metsHdr["CREATEDATE"];
            if ($metsHdr["LASTMODDATE"]) $this->result["header"]["lastModDate"] = (string)$metsHdr["LASTMODDATE"];
            if ($metsHdr["RECORDSTATUS"]) $this->result["header"]["recordStatus"] = (string)$metsHdr["RECORDSTATUS"];

            $agentRolesMap = [
                "CREATOR" => "creators",
                "DISSEMINATOR" => "disseminators",
            ];

            // METS:mets/METS:metsHdr/METS:agent[@ROLE='?']
            foreach ($agentRolesMap as $agentRole => $agentGroup) {
                $agents = $metsHdr->xpath("METS:agent[@ROLE='".$agentRole."']");
                if ($agents) {
                    $this->result["header"][$agentGroup] = [];
                    foreach ($agents as $agent) {
                        $agentResult = [];
                        $name = (string)Si4Util::getArg($agent->xpath("METS:name"), '0', "");
                        $note = (string)Si4Util::getArg($agent->xpath("METS:note"), '0', "");
                        $type = (string)Si4Util::getArg($agent->xpath("@TYPE"), '0', "");
                        $id = (string)Si4Util::getArg($agent->xpath("@ID"), '0', "");
                        if ($name) $agentResult["name"] = $name;
                        if ($note) $agentResult["note"] = $note;
                        if ($type) $agentResult["type"] = strtolower($type);
                        if ($id) $agentResult["id"] = $id;
                        $this->result["header"][$agentGroup][] = $agentResult;
                    }
                }
            }

        }
    }

    private function doSi4Mapping() {

        $this->result["si4"] = [];

        if ($this->metsXmlDoc) {
            foreach ($this->mappingGroups as $mgName => $mappingGroup) {

                if ($mgName === "Mods") continue;

                $mappingFields = $mappingGroup["fields"];
                $base_xpath = $mappingGroup["base_xpath"];
                $baseXmlElements = $this->metsXmlDoc->xpath($base_xpath);

                foreach ($baseXmlElements as $baseXmlElement) {

                    foreach ($mappingFields as $mappingField) {
                        $source_xpath = $mappingField["source_xpath"];
                        $value_xpath = $mappingField["value_xpath"];
                        $lang_xpath = $mappingField["lang_xpath"];
                        $target_field = $mappingField["target_field"];

                        try {

                            $fieldSourceElements = $baseXmlElement->xpath($source_xpath);
                            foreach ($fieldSourceElements as $fieldSourceElement) {

                                try {
                                    if (!$value_xpath || $value_xpath === "/") {
                                        $value = (string)$fieldSourceElement;
                                    } else {
                                        $valueArray = $fieldSourceElement->xpath($value_xpath);
                                        $value = isset($valueArray[0]) ? (string)$valueArray[0] : "";
                                    }

                                    if ($lang_xpath) {
                                        $langArray = $fieldSourceElement->xpath($lang_xpath);
                                        $lang = isset($langArray[0]) ? (string)$langArray[0] : "";
                                    } else {
                                        $lang = "";
                                    }

                                    //echo $target_field." -> '" .$value. "', lang: '" .$lang. "'\n";

                                    $fieldResult = [];
                                    $fieldResult["metadataSrc"] = strtolower($mgName);
                                    $fieldResult["value"] = $value;
                                    if ($lang) $fieldResult["lang"] = $lang;

                                    if (!isset($this->result["si4"][$target_field])) $this->result["si4"][$target_field] = [];
                                    $this->result["si4"][$target_field][] = $fieldResult;

                                } catch (\Exception $fieldSourceE) {
                                    /*
                                    echo "fieldSourceE\n";
                                    var_dump($fieldSourceElement);
                                    echo $fieldSourceE->__toString();
                                    */
                                }
                            }

                        } catch (\Exception $mappingFieldE) {
                            /*
                            echo "mappingField Exception\n";
                            var_dump($mappingField);
                            echo $mappingFieldE->__toString();
                            */
                        }
                    }
                }

            }
        }

        return $this->result["si4"];
    }
}