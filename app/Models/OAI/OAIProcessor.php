<?php
namespace App\Models\OAI;

use App\Helpers\Si4Util;
use App\Models\OaiGroup;

class OAIProcessor {

    private $request;
    private $resumptionToken = null;

    public function __construct($request){
        $this->request = $request;
    }

    public function process() {
        $response = new OAIResponse($this->request);

        $resultElement = "";

        switch ($this->request->verb) {

            case "Identify":
                $resultElement = $this->processIdentify();
                break;

            case "ListMetadataFormats":
                $resultElement = $this->processListMetadataFormats();
                break;

            case "GetRecord":
                $resultElement = $this->processGetRecord();
                break;

            case "ListRecords":
                $resultElement = $this->processListRecords();
                break;

            case "ListIdentifiers":
                $resultElement = $this->processListIdentifiers();
                break;

            case "ListSets":
                $resultElement = $this->processListSets();
                //$this->request->error("noSetHierarchy", "This repository does not support sets");
                break;

            default:
                break;
        }

        //print_r($resultElement); die();

        if ($resultElement) $response->setResultElement($resultElement);
        if ($this->request->resumptionToken) $response->setResumptionToken($this->request->resumptionToken);

        return $response;
    }


    // Identify

    private function processIdentify(){
        $identify = new OAIXmlElement("Identify");

        $identifyInfo = si4config("oai_identifyInfo");
        foreach ($identifyInfo as $nodeName => $nodeValue){
            $node = new OAIXmlElement($nodeName);
            $node->setValue($nodeValue);
            $node->appendTo($identify);
        }

        return $identify;
    }


    // ListMetadataFormats

    private function processListMetadataFormats() {
        $listMetadataFormats = new OAIXmlElement("ListMetadataFormats");

        $metadataFormats = OaiGroup::getOaiGroups();

        foreach ($metadataFormats as $metaFormat) {

            $prefix = $metaFormat["name"];
            $schema = $metaFormat["schema"];
            $namespace = $metaFormat["namespace"];

            $metadataFormat = new OAIXmlElement("metadataFormat");
            $metadataFormat->appendTo($listMetadataFormats);

            // metadataPrefix
            $metadataPrefix = new OAIXmlElement("metadataPrefix");
            $metadataPrefix->setValue($prefix);
            $metadataPrefix->appendTo($metadataFormat);

            // schema
            $schemaTag = new OAIXmlElement("schema");
            $schemaTag->setValue($schema);
            $schemaTag->appendTo($metadataFormat);

            // metadataNamespace
            $metadataNamespace = new OAIXmlElement("metadataNamespace");
            $metadataNamespace->setValue($namespace);
            $metadataNamespace->appendTo($metadataFormat);

        }

        $this->request->resumptionToken->setCompleteListSize(count($metadataFormats));

        return $listMetadataFormats;
    }


    // GetRecord

    private function processGetRecord() {
        $getRecord = new OAIXmlElement("GetRecord");
        $record = OAIRecord::getMetadataRecord($this->request);
        //$record->setOutputAllMetadata($this->request->getOutAsJson());

        if (!$record) $this->request->error("idDoesNotExist", "Unknown identifier");

        $getRecord->setContentXml($record->toXml());
        return $getRecord;
    }


    // ListRecords

    private function processListRecords() {
        $listRecords = new OAIXmlElement("ListRecords");

        $recordArray = OAIRecord::getRecordsArray($this->request);

        if (!$recordArray || count($recordArray) == 0) {
            $this->request->error("noRecordsMatch", "No records were found for chosen criteria");
        }

        $content = "";
        foreach ($recordArray as $record){
            if ($content) $content .= OAIXmlOutput::$newLine;
            $content .= $record->toXml();
        }

        $listRecords->setContentXml($content);

        if ($this->resumptionToken) {
            $this->xmlOut->outputXml($this->resumptionToken->toXml());
        }

        return $listRecords;
    }


    // ListIdentifiers

    private function processListIdentifiers() {
        $listRecords = new OAIXmlElement("ListIdentifiers");

        $recordArray = OAIRecord::getRecordArray($this->request);

        $content = "";
        foreach ($recordArray as $record){
            if ($content) $content .= OAIXmlOutput::$newLine;
            $content .= $record->headerToXml();
        }

        $listRecords->setContentXml($content);
        return $listRecords;
    }


    // ListSets

    private function processListSets() {
        $listSets = new OAIXmlElement("ListSets");

        $metadataFormats = OaiGroup::getOaiGroups();
        $setsSet = [];

        // Make a set of all set names for each metadataFormat (no duplicates)
        foreach ($metadataFormats as $metaFormat) {
            $mdPrefixSets = Si4Util::getArg($metaFormat, "sets");
            if ($mdPrefixSets && count($mdPrefixSets)) {
                foreach($mdPrefixSets as $set) {
                    $setsSet[$set] = true;
                }
            }
        }

        $sets = array_keys($setsSet);
        foreach ($sets as $set) {

            // Append set elements
            $setEl = new OAIXmlElement("set");
            $setEl->appendTo($listSets);

            $setSpec = new OAIXmlElement("setSpec");
            $setSpec->setValue($set);
            $setSpec->appendTo($setEl);

            $setName = new OAIXmlElement("setName");
            $setName->setValue($set);
            $setName->appendTo($setEl);
        }

        return $listSets;
    }

}