<?php

namespace App\Models\Elastic;

class EntityElastic
{
    private $entityAssoc = null;
    private $data = [];

    public function __construct($entityAssoc) {
        $this->entityAssoc = $entityAssoc;
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

            $dmdType = self::get($dmdSec, "MdWrapElName/MDTYPEAttrName");

            $dmd = [
                "_id" => self::get($dmdSec, "IDAttrName"),
                "_groupId" => self::get($dmdSec, "GROUPIDAttrName"),
                "_mime" => self::get($dmdSec, "MdWrapElName/MIMETYPEAttrName"),
            ];

            $xmlData = self::get($dmdSec, "MdWrapElName/XmlDataElName");
            switch (strtolower($dmdType)) {
                case "premis:object":
                    $this->data["dmd"]["premis"] = $dmd;
                    $this->populatePremisData($xmlData);
                    break;
                case "dc":
                    $this->data["dmd"]["dc"] = $dmd;
                    $this->populateDCData($xmlData);
                    break;
                case "mods":
                    $this->data["dmd"]["mods"] = $dmd;
                    $this->populateModsData($xmlData);
                    break;
            }
        }
    }

    private function populatePremisData($xmlData) {
        // ...
    }

    private function populateDCData($xmlData) {
        foreach ($xmlData as $key => $val) {
            $keyRepl = strtolower(str_replace("PropName", "", $key));
            if ($val && count($val)) {
                // Convert CDATA to string
                foreach ($val as $i => $v) $val[$i] = (string)$v;
            }
            $this->data["dmd"]["dc"][$keyRepl] = $val;
        }
    }

    private function populateModsData($xmlData) {
        // ...
    }


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

                $fileData = [
                    "id" => self::get($file, "IDAttrName"),
                    "mime" => self::get($file, "MIMETYPEAttrName"),
                    "size" => self::get($file, "SIZEAttrName"),
                    "ownerId" => self::get($file, "OWNERIDAttrName"),
                    "locations" => $locations
                ];

                $this->data["files"][] = $fileData;
            }
        }
    }


    public function getData() {
        return $this->data;
    }

}