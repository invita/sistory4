<?php
namespace App\Models\OAI\MetadataPrefix;

abstract class AbsMetadataPrefixHandler {

    // Returns OAIXmlElement
    abstract function metadataToXml($oaiRecord);

}