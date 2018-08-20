<?php

namespace App\Models\Elastic;

use App\Helpers\DcHelpers;
use App\Helpers\FileHelpers;
use App\Helpers\TikaParseDoc;
use App\Models\Elastic\MdMappers\DC;
use App\Models\Elastic\MdMappers\Mods;

class EntityElastic
{
    private $entityAssoc = null;
    private $entityDb = null;
    private $prevElasticSource = null;
    private $data = [];

    private $mdHandlerClasses = [DC::class, Mods::class];
    private $mdHandlers = [];

    public function __construct($entityDb, $entityAssoc, $prevElasticSource = null) {
        $this->entityDb = $entityDb;
        $this->entityAssoc = $entityAssoc;
        $this->prevElasticSource = $prevElasticSource;

        // Instantiate Metadata mappers
        foreach ($this->mdHandlerClasses as $mdHandlerClass) {
            $mdHandler = new $mdHandlerClass();
            $mdType = strtolower($mdHandler->mdTypeToHandle());
            $this->mdHandlers[$mdType] = $mdHandler;
        }

        $this->populate();
    }

    private static function get($data, $path, $defaultValue = null) {
        $pathExplode = explode("/", $path);
        $result = $data;
        for ($i = 0; $i < count($pathExplode); $i++) {
            if (isset($result[$pathExplode[$i]])) {
                $result = $result[$pathExplode[$i]];
            } else {
                return $defaultValue;
            }
        }
        return $result;
    }

    private function getFromAssoc($path, $defaultValue = null) {
        return self::get($this->entityAssoc, $path, $defaultValue);
    }

    // Populate internal data from entityAssoc object
    public function populate() {
        $this->data = [];
        if (!$this->entityAssoc) return;

        $this->populateHeader();
        $this->populateDescMetadata();
        $this->populateAdminMetadata();
        $this->populateFileMetadata();
    }

    private function populateHeader() {
        $this->data["id"] = $this->getFromAssoc("IDAttrName");
        $this->data["objId"] = $this->getFromAssoc("OBJIDAttrName");
        $this->data["type"] = $this->getFromAssoc("TYPEAttrName");

        $this->data["createdAt"] = $this->getFromAssoc("MetsHdrElName/CREATEDATEAttrName");
        $this->data["modifiedAt"] = $this->getFromAssoc("MetsHdrElName/LASTMODDATEAttrName");
        $this->data["recordStatus"] = strtolower($this->getFromAssoc("MetsHdrElName/RECORDSTATUSAttrName", ""));

        // Agents
        $agents = $this->getFromAssoc("MetsHdrElName/AgentElName");
        $this->data["agents"] = [];
        foreach ($agents as $idx => $agent) {
            $agentRole = strtolower(self::get($agent, "ROLEAttrName", ""));

            $agentId = self::get($agent, "IDAttrName");
            $agentType = strtolower(self::get($agent, "TYPEAttrName", ""));
            $agentName = self::get($agent, "NameElName");
            $agentNotes = self::get($agent, "NoteElName", array());

            switch ($agentRole) {
                case "disseminator":
                    if (!isset($this->data["agents"]["disseminators"]))
                        $this->data["agents"]["disseminators"] = [];
                    $this->data["agents"]["disseminators"][] = [
                        "id" => $agentId,
                        "type" => $agentType,
                        "name" => $agentName,
                        "notes" => $agentNotes,
                    ];
                    break;
                case "creator":
                    if (!isset($this->data["agents"]["creators"]))
                        $this->data["agents"]["creators"] = [];
                    $this->data["agents"]["creators"][] = [
                        "id" => $agentId,
                        "type" => $agentType,
                        "name" => $agentName,
                        "notes" => $agentNotes,
                    ];
                    break;
            }
        }
    }

    // Descriptive metadata section
    private function populateDescMetadata() {
        $this->data["dmd"] = [];

        $dmdSecs = $this->getFromAssoc("DmdSecElName");

        foreach ($dmdSecs as $idx => $dmdSec) {

            $dmdId = self::get($dmdSec, "IDAttrName");
            $dmdType = self::get($dmdSec, "MdWrapElName/MDTYPEAttrName");

            $dmd = [
                "_id" => self::get($dmdSec, "IDAttrName"),
                "_groupId" => self::get($dmdSec, "GROUPIDAttrName"),
                "_mime" => self::get($dmdSec, "MdWrapElName/MIMETYPEAttrName"),
            ];

            $xmlData = self::get($dmdSec, "MdWrapElName/XmlDataElName");

            switch (strtolower($dmdType)) {
                /*
                case "premis:object":
                    $this->data["dmd"]["premis"] = $dmd;
                    $this->populatePremisData($xmlData);
                    break;
                */
                case "dc":
                    $this->data["dmd"]["dc"] = $dmd;
                    $this->populateDCData($xmlData);

                    /*
                    if ($dmdId == "default.dc") {
                        // <METS:dmdSec ID="default.dc"> + <METS:mdWrap MDTYPE="DC">
                        $this->data["dmd"]["dc"] = $dmd;
                        $this->populateDCData($xmlData);
                    } else if ($dmdId == "default.dcterms") {
                        // <METS:dmdSec ID="default.dcterms"> + <METS:mdWrap MDTYPE="DC">
                        $this->data["dmd"]["dcterms"] = $dmd;
                        $this->populateDCTermsData($xmlData);
                    }
                    */
                    break;
                /*
                case "mods":
                    $this->data["dmd"]["mods"] = $dmd;
                    $this->populateModsData($xmlData);
                    break;
                */
            }


            if (!isset($this->data["si4"])) $this->data["si4"] = [];
            foreach ($this->mdHandlers as $mdType => $mdTypeHandler) {
                if ($mdType === strtolower($dmdType)) {
                    //echo $mdType."\n";
                    $si4 = $mdTypeHandler->mapXmlData($xmlData);

                    // Merge si4 fields
                    foreach (DcHelpers::$si4FieldDefinitions as $fieldName => $fieldDef) {
                        if (!isset($si4[$fieldName])) continue;
                        if (!isset($this->data["si4"][$fieldName])) $this->data["si4"][$fieldName] = [];
                        foreach ($si4[$fieldName] as $fieldValue)
                            $this->data["si4"][$fieldName][] = $fieldValue;
                    }
                }
            }
        }

        //echo "All si4\n"; print_r($this->data["si4"]);
    }

    /*
    private function populatePremisData($xmlData) {
        // ...
    }
    */

    private function populateDCData($xmlData) {
        //echo "EntityElastic.populateDCData: "; print_r($xmlData);
        foreach ($xmlData as $key => $val) {
            $keyRepl = strtolower(str_replace("PropName", "", $key));
            if ($val && count($val)) {
                // Convert CDATA to string
                foreach ($val as $i => $v) {
                    $val[$i]["value"] = (string)$v["value"];
                    $langPropName = (string)$v["LangPropName"];
                    unset($val[$i]["LangPropName"]);
                    if ($langPropName) $val[$i]["lang"] = $langPropName;
                }
            }
            //$this->data["dmd"]["dc"][$keyRepl] = ["text" => $val];
            $this->data["dmd"]["dc"][$keyRepl] = $val;
        }
        //print_r($this->data["dmd"]["dc"]);
    }

    /*
    // Obsolete
    private function populateDCTermsData($xmlData) {
        // ...
    }


    private function populateModsData($xmlData) {
        // ...
    }
    */


    // Administrative metadata section
    private function populateAdminMetadata() {

    }

    // File metadata section
    private function populateFileMetadata() {
        $this->data["files"] = [];

        $fileSecGroups = $this->getFromAssoc("FileSecElName/FileGrpElName");
        if (!$fileSecGroups) return;
        //print_r($fileSecGroups);

        foreach ($fileSecGroups as $gIdx => $fileSecGroup) {
            $use = strtolower(self::get($fileSecGroup, "USEAttrName", ""));
            $files = self::get($fileSecGroup, "FileElName", []);
            foreach ($files as $fIdx => $file) {

                $flocs = self::get($file, "FLocatElName", []);
                $locations = [];
                foreach ($flocs as $flIdx => $location) {
                    $locations[] = [
                        "id" => self::get($location, "IDAttrName"),
                        "type" => self::get($location, "LOCTYPEAttrName"),
                        "href" => self::get($location, "HrefPropName"),
                        "title" => self::get($location, "TitlePropName"),
                    ];
                }

                $fId = self::get($file, "IDAttrName");
                $fOwnerId = self::get($file, "OWNERIDAttrName");

                // Try to find previous file fullText
                $fullText = "";
                if ($this->prevElasticSource) {
                    if (isset($this->prevElasticSource["data"]) &&
                        isset($this->prevElasticSource["data"]["files"]))
                    {
                        foreach ($this->prevElasticSource["data"]["files"] as $prevFIdx => $prevF) {
                            if ($prevF["id"] == $fId && $prevF["ownerId"] == $fOwnerId) {
                                $fullText = isset($prevF["fullText"]) ? $prevF["fullText"] : "";
                                break;
                            }
                        }
                    }
                }

                $fileData = [
                    "id" => $fId,
                    "ownerId" => $fOwnerId,
                    "mimeType" => self::get($file, "MIMETYPEAttrName"),
                    "size" => self::get($file, "SIZEAttrName"),
                    "created" => self::get($file, "CREATEDAttrName"),
                    "checksum" => self::get($file, "CHECKSUMAttrName"),
                    "checksumType" => self::get($file, "CHECKSUMTYPEAttrName"),
                    "locations" => $locations,
                    "fullText" => $fullText
                ];

                $this->data["files"][] = $fileData;
            }
        }
    }

    public static function extractTextFromFiles($entityDb, &$entityElastic) {

        $entityHandleId = $entityDb->handle_id;
        if ($entityDb->struct_type == "file") $entityHandleId = $entityDb->parent;

        $files = isset($entityElastic["data"]) && isset($entityElastic["data"]["files"]) ? $entityElastic["data"]["files"] : null;
        if (!$files) return false;

        foreach ($files as $idx => $file) {
            $filePath = FileHelpers::getPublicStorageName($entityHandleId, $file["ownerId"]);
            $docText = TikaParseDoc::parseText($filePath);
            $entityElastic["data"]["files"][$idx]["fullText"] = $docText;
        }

        return true;
    }

    public function getData() {
        return $this->data;
    }

}