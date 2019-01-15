<?php
namespace App\Models\OAI;

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
                $this->request->error("noSetHierarchy", "This repository does not support sets");
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

        $identifyInfo = OAIHelper::$identifyInfo;
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

        $metadataFormats = OAIHelper::getMetadataPrefixList();
        foreach ($metadataFormats as $metaFormat) {

            $prefix = $metaFormat["prefix"];
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
        $record = OAIRecord::getMetadataRecord($this->request->arguments["identifier"]);
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

}