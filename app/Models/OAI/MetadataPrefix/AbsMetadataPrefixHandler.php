<?php
namespace App\Models\OAI\MetadataPrefix;

abstract class AbsMetadataPrefixHandler {

    protected $mdPrefixData;
    protected $oaiFields;

    function __construct($mdPrefixData, $oaiFields) {
        $this->mdPrefixData = $mdPrefixData;
        $this->oaiFields = $oaiFields;
    }

    // Returns OAIXmlElement
    abstract function metadataToXml($oaiRecord);

}