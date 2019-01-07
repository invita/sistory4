<?php
namespace App\Models\Si4;

use App\Models\MappingGroup;
use App\Models\Si4Field;

class MetsToSi4
{
    private $metsXmlString;
    private $metsXmlDOMDoc;
    private $domXPath;

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
        $this->metsXmlDOMDoc = new \DOMDocument();
        $this->metsXmlDOMDoc->loadXML($this->metsXmlString);
        $this->domXPath = new \DOMXPath($this->metsXmlDOMDoc);

        $this->fieldDefs = Si4Field::getSi4Fields();
        $this->mappingGroups = MappingGroup::getMappingGroups();
        $this->result = [];
    }

    private function doMetsStaticMapping() {
        // TODO
        $this->result["header"] = [];

        if ($this->metsXmlDOMDoc) {

            // METS:mets
            $this->result["header"]["id"] = $this->domXPath->evaluate("string(/METS:mets/@ID)");
            $this->result["header"]["objId"] = $this->domXPath->evaluate("string(/METS:mets/@OBJID)");
            $this->result["header"]["type"] = $this->domXPath->evaluate("string(/METS:mets/@TYPE)");

            // METS:mets/METS:metsHdr
            $this->result["header"]["createDate"] = $this->domXPath->evaluate("string(/METS:mets/METS:metsHdr/@CREATEDATE)");
            $this->result["header"]["lastModDate"] = $this->domXPath->evaluate("string(/METS:mets/METS:metsHdr/@LASTMODDATE)");
            $this->result["header"]["recordStatus"] = $this->domXPath->evaluate("string(/METS:mets/METS:metsHdr/@RECORDSTATUS)");

            $agentRolesMap = [
                "CREATOR" => "creators",
                "DISSEMINATOR" => "disseminators",
            ];

            // METS:mets/METS:metsHdr/METS:agent[@ROLE='?']
            foreach ($agentRolesMap as $agentRole => $agentGroup) {
                $agents = $this->domXPath->query("/METS:mets/METS:metsHdr/METS:agent[@ROLE='".$agentRole."']");
                if ($agents->length) {
                    $this->result["header"][$agentGroup] = [];
                    foreach ($agents as $agent) {
                        $agentResult = [];

                        $name = $this->domXPath->evaluate("string(METS:name)", $agent);
                        $note = $this->domXPath->evaluate("string(METS:note)", $agent);
                        $type = $this->domXPath->evaluate("string(@TYPE)", $agent);
                        $id = $this->domXPath->evaluate("string(@ID)", $agent);
                        if ($name) $agentResult["name"] = $name;
                        if ($note) $agentResult["note"] = $note;
                        if ($type) $agentResult["type"] = $type;
                        if ($id) $agentResult["id"] = $id;

                        $this->result["header"][$agentGroup][] = $agentResult;
                    }
                }
            }

        }
    }

    private function doSi4Mapping() {

        $this->result["si4"] = [];

        if ($this->metsXmlDOMDoc) {

            // foreach MappingGroup
            foreach ($this->mappingGroups as $mgName => $mappingGroup) {

                //if ($mgName === "Mods") continue;

                $mappingFields = $mappingGroup["fields"];
                $base_xpath = $mappingGroup["base_xpath"];

                // Find MappingGroup's base elements
                $baseXmlElements = $this->domXPath->query($base_xpath);

                // foreach base MappingGroup element found
                foreach ($baseXmlElements as $baseXmlElement) {

                    // foreach MappingField in MappingGroup
                    foreach ($mappingFields as $mappingField) {
                        $source_xpath = $mappingField["source_xpath"];
                        $value_xpath = $mappingField["value_xpath"];
                        $lang_xpath = $mappingField["lang_xpath"];
                        $target_field = $mappingField["target_field"];

                        try {

                            // Find MappingField source elements
                            $fieldSourceElements = $this->domXPath->query($source_xpath, $baseXmlElement);

                            // foreach MappingField source element found
                            foreach ($fieldSourceElements as $fieldSourceElement) {

                                try {

                                    $value = $this->domXPath->evaluate($value_xpath, $fieldSourceElement);
                                    $lang = $this->domXPath->evaluate($lang_xpath, $fieldSourceElement);

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