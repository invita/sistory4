<?php
namespace App\Models\Si4;

use App\Models\MappingGroup;
use App\Models\Si4Field;
use Mockery\CountValidator\Exception;

class MetsToSi4
{
    private $mappingStack = [];
    private $errorReporting = true;


    private $metsXmlString;
    private $metsXmlDOMDoc;
    private $domXPath;

    private $fieldDefs;
    private $mappingGroups;
    private $result;
    public function __construct($metsXmlString) {
        $this->metsXmlString = $metsXmlString;
    }

    private $fullText;
    public function setFullText($fullText) {
        $this->fullText = $fullText;
    }

    public function run() {
        try {
            $this->prepare();
            $this->doMetsStaticMapping();
            $this->doSi4Mapping();
        } catch (\Exception $e) {
            $this->markException("general", $e);
        }

        return $this->result;
    }

    private function prepare() {
        $this->metsXmlDOMDoc = new \DOMDocument();
        $this->metsXmlDOMDoc->loadXML($this->metsXmlString);
        $this->domXPath = new \DOMXPath($this->metsXmlDOMDoc);

        // Register all the namespaces found through the xml
        $nsMatches = [];
        preg_match_all("/xmlns:(.*?)=\\\"(.*?)\\\"/", $this->metsXmlString, $nsMatches);
        //print_r($nsMatches);
        if (isset($nsMatches[1]) && $nsMatches[1]) {
            foreach ($nsMatches[1] as $idx => $nsName)
                $this->domXPath->registerNamespace($nsMatches[1][$idx], $nsMatches[2][$idx]);
        }

        $this->fieldDefs = Si4Field::getSi4Fields();
        $this->mappingGroups = MappingGroup::getMappingGroups();
        $this->result = [];
    }

    private function doMetsStaticMapping() {
        $this->result["header"] = [];
        $this->result["si4tech"] = [];
        $this->result["files"] = [];

        if ($this->metsXmlDOMDoc) {

            // METS:mets
            $this->result["header"]["id"] = $this->domXPath->evaluate("string(/METS:mets/@ID)");
            $this->result["header"]["objId"] = $this->domXPath->evaluate("string(/METS:mets/@OBJID)");
            $this->result["header"]["type"] = $this->domXPath->evaluate("string(/METS:mets/@TYPE)");

            // METS:mets/METS:metsHdr
            $this->result["header"]["createDate"] = $this->domXPath->evaluate("string(/METS:mets/METS:metsHdr/@CREATEDATE)");
            $this->result["header"]["lastModDate"] = $this->domXPath->evaluate("string(/METS:mets/METS:metsHdr/@LASTMODDATE)");
            $this->result["header"]["recordStatus"] = $this->domXPath->evaluate("string(/METS:mets/METS:metsHdr/@RECORDSTATUS)");


            // SI4 Technical administrative metadata
            // elements with single value (only one value allowed per document)
            $si4techMD_single = [
                "additionalMetadata" => "additionalMetadata",
                "pdfPage" => "pdfPage",
                "newVersion" => "newVersion",
                "removedTo" => "removedTo",
                "externalCollection" => "externalCollection",
                "searchResultsSort" => "searchResultsSort",
                "searchResultsShow" => "searchResultsShow",
            ];
            // elements with multiple values (more values allowed per document)
            $si4techMD_multi = [
                "additionalMetadataURL" => "additionalMetadataURL",
            ];
            // elements with xml:lang parameter
            $si4techMD_withLang = [
                "desc" => "description",
                "description" => "description"
            ];
            $si4tech_xmlDatas = $this->domXPath->query("/METS:mets/METS:amdSec/METS:techMD/METS:mdWrap[@OTHERMDTYPE='SI4']/METS:xmlData");
            if ($si4tech_xmlDatas->length) {
                foreach ($si4tech_xmlDatas as $si4techXmlData) {

                    // Single
                    foreach ($si4techMD_single as $elName => $reName) {
                        $mdValue = $this->domXPath->evaluate("string(si4:".$elName.")", $si4techXmlData);
                        if ($mdValue) $this->result["si4tech"][$reName] = $mdValue;
                    }

                    // Multi
                    foreach ($si4techMD_multi as $elName => $reName) {
                        $mdEls = $this->domXPath->query("si4:".$elName, $si4techXmlData);
                        if ($mdEls->length) {
                            foreach ($mdEls as $mdEl) {
                                $mdValue = $this->domXPath->evaluate("string()", $mdEl);
                                if ($mdValue) {
                                    if (!isset($this->result["si4tech"][$reName])) $this->result["si4tech"][$reName] = [];
                                    $this->result["si4tech"][$reName][] = $mdValue;
                                }
                            }
                        }
                    }

                    // WithLang
                    foreach ($si4techMD_withLang as $elName => $reName) {
                        $mdEls = $this->domXPath->query("si4:".$elName, $si4techXmlData);
                        if ($mdEls->length) {
                            foreach ($mdEls as $mdEl) {
                                $mdValue = $this->domXPath->evaluate("string()", $mdEl);
                                if ($mdValue) {
                                    $lang = $this->domXPath->evaluate("string(@xml:lang)", $mdEl);
                                    if (!$lang) $lang = $this->domXPath->evaluate("string(@lang)", $mdEl);
                                    if (!isset($this->result["si4tech"][$reName])) $this->result["si4tech"][$reName] = [];
                                    $this->result["si4tech"][$reName][] = [
                                        "lang" => $lang,
                                        "value" => $mdValue
                                    ];
                                }
                            }
                        }
                    }

                }
            }



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

            // METS:mets/METS:fileSec
            $fileSecs = $this->domXPath->query("/METS:mets/METS:fileSec");
            $firstFile = true;
            foreach ($fileSecs as $fileSec) {
                $fileSecID = $this->domXPath->evaluate("string(@ID)", $fileSec);

                // METS:mets/METS:fileSec/METS:fileGrp
                $fileGrps = $this->domXPath->query("METS:fileGrp", $fileSec);
                foreach ($fileGrps as $fileGrp) {
                    $fileGrpUSE = $this->domXPath->evaluate("string(@USE)", $fileGrp);

                    $files = $this->domXPath->query("METS:file", $fileGrp);
                    foreach ($files as $file) {
                        $fileID = $this->domXPath->evaluate("string(@ID)", $file);
                        $fileOWNERID = $this->domXPath->evaluate("string(@OWNERID)", $file);
                        $fileUSE = $this->domXPath->evaluate("string(@USE)", $file);

                        $fileCREATED = $this->domXPath->evaluate("string(@CREATED)", $file);
                        $fileSIZE = $this->domXPath->evaluate("string(@SIZE)", $file);
                        $fileCHECKSUM = $this->domXPath->evaluate("string(@CHECKSUM)", $file);
                        $fileCHECKSUMTYPE = $this->domXPath->evaluate("string(@CHECKSUMTYPE)", $file);
                        $fileMIMETYPE = $this->domXPath->evaluate("string(@MIMETYPE)", $file);

                        if (!$fileID) $fileID = $this->domXPath->evaluate("string(METS:FLocat/@ID)", $file);
                        $fileLocLOCTYPE = $this->domXPath->evaluate("string(METS:FLocat/@LOCTYPE)", $file);
                        $fileLocUSE = $this->domXPath->evaluate("string(METS:FLocat/@USE)", $file);
                        $fileLocTITLE = $this->domXPath->evaluate("string(METS:FLocat/@title)", $file);
                        if (!$fileLocTITLE) $fileLocTITLE = $this->domXPath->evaluate("string(METS:FLocat/@xlink:title)", $file);

                        $fileLocHREF = $this->domXPath->evaluate("string(METS:FLocat/@href)", $file);
                        if (!$fileLocHREF) $fileLocHREF = $this->domXPath->evaluate("string(METS:FLocat/@xlink:href)", $file);


                        $behaviour = $fileLocUSE;
                        if (!$behaviour) $behaviour = $fileUSE;
                        if (!$behaviour) $behaviour = $fileGrpUSE;

                        $fileMetadata = [
                            "handle_id" => $fileID,
                            "fileName" => $fileOWNERID,
                            "behaviour" => $behaviour,
                            "fullText" => "",
                            "url" => $fileLocHREF,
                            "extLocType" => $fileLocLOCTYPE,
                            "extTitle" => $fileLocTITLE,
                        ];

                        $fileMetadata["createDate"] = $fileCREATED;
                        $fileMetadata["size"] = $fileSIZE;
                        $fileMetadata["checksum"] = $fileCHECKSUM;
                        $fileMetadata["checksumType"] = $fileCHECKSUMTYPE;
                        $fileMetadata["mimeType"] = $fileMIMETYPE;
                        $fileMetadata["fullText"] = "";

                        // Keep previous fullText for first file
                        if ($firstFile && $this->fullText) $fileMetadata["fullText"] = $this->fullText;
                        $firstFile = false;

                        $this->result["files"][] = $fileMetadata;
                    }
                }
            }

            //print_r($this->result["files"]);
        }
    }

    private function doSi4Mapping() {

        $this->result["si4"] = [];

        if ($this->metsXmlDOMDoc) {

            // foreach MappingGroup
            foreach ($this->mappingGroups as $mgName => $mappingGroup) {

                //if ($mgName === "Mods") continue;
                if ($this->errorReporting)
                    array_push($this->mappingStack, "mappingGroup=".$mgName);

                try {

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
                            $variables = $mappingField["variables"];

                            if ($this->errorReporting)
                                array_push($this->mappingStack, "mappingField=".$target_field);

                            try {

                                // Find MappingField source elements
                                $fieldSourceElements = $this->domXPath->query($source_xpath, $baseXmlElement);

                                // foreach MappingField source element found
                                foreach ($fieldSourceElements as $fieldSourceElement) {

                                    if ($this->errorReporting)
                                        array_push($this->mappingStack, "sourceElement=".$fieldSourceElement->tagName);

                                    try {

                                        $value = $value_xpath ? $this->domXPath->evaluate($value_xpath, $fieldSourceElement) : "";
                                        $lang = $lang_xpath ? $this->domXPath->evaluate($lang_xpath, $fieldSourceElement) : "";

                                        //echo $target_field." -> '" .$value. "', lang: '" .$lang. "'\n";

                                        $fieldResult = [];
                                        $fieldResult["metadataSrc"] = strtolower($mgName);
                                        $fieldResult["value"] = $value;
                                        if ($lang) $fieldResult["lang"] = $lang;

                                        if ($variables) {
                                            foreach ($variables as $var) {
                                                $var_name = $var["name"];
                                                $var_xpath = $var["value"];
                                                $var_value = $var_xpath ? $this->domXPath->evaluate($var_xpath, $fieldSourceElement) : "";
                                                $fieldResult[$var_name] = $var_value;
                                            }
                                        }

                                        if (!isset($this->result["si4"][$target_field])) $this->result["si4"][$target_field] = [];
                                        $this->result["si4"][$target_field][] = $fieldResult;

                                        if ($this->errorReporting) array_pop($this->mappingStack);

                                    } catch (\Exception $fieldSourceE) {
                                        if ($this->errorReporting) {
                                            $this->markException("fieldSource", $fieldSourceE);
                                            array_pop($this->mappingStack);
                                        }
                                        /*
                                        echo "fieldSourceE\n";
                                        var_dump($fieldSourceElement);
                                        echo $fieldSourceE->__toString();
                                        */
                                    }
                                }

                                if ($this->errorReporting) array_pop($this->mappingStack);

                            } catch (\Exception $mappingFieldE) {
                                if ($this->errorReporting) {
                                    $this->markException("mappingField", $mappingFieldE);
                                    array_pop($this->mappingStack);
                                }
                                /*
                                echo "mappingField Exception\n";
                                var_dump($mappingField);
                                echo $mappingFieldE->__toString();
                                */
                            }
                        }
                    }

                    if ($this->errorReporting) array_pop($this->mappingStack);

                } catch (\Exception $e) {
                    // Probably missing namespace for mappingGroup.
                    // Ignore
                    //echo $e->getMessage()."\n";
                    if ($this->errorReporting) {
                        $this->markException("mappingSi4", $e);
                        array_pop($this->mappingStack);
                    }
                }
            }
        }

        return $this->result["si4"];
    }

    private function markException($category, \Exception $e)
    {
        if (!$this->errorReporting) return;
        if (!isset($this->result["mappingErrors"])) $this->result["mappingErrors"] = [];
        if (count($this->result["mappingErrors"]) >= 10) return;
        $this->result["mappingErrors"][] = [
            "category" => $category,
            "mappingStack" => join("; ", $this->mappingStack),
            "message" => $e->getMessage(),
            "code" => $e->getCode(),
            "file" => $e->getFile(),
            "line" => $e->getLine()
        ];
        return $this->result["mappingErrors"];
    }
}
