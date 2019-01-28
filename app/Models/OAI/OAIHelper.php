<?php
namespace App\Models\OAI;

class OAIHelper {

    private static $fieldList = array('title', 'creator', 'subject', 'description', 'publisher', 'contributor',
        'date', 'type', 'format', 'identifier', 'source', 'language', 'relation', 'coverage', 'rights');

    private static $allowedVerbList = array('Identify', 'ListSets', 'ListIdentifiers', 'ListRecords',
        'ListMetadataFormats', 'GetRecord');

    private static $listRequestVerbs = array('ListSets', 'ListIdentifiers', 'ListRecords', 'ListMetadataFormats');

    private static $allowedArgumentList = array('verb', 'from', 'until', 'metadataPrefix',
        'cursor', 'resumptionToken', 'batchSize', 'DBGSESSID', 'identifier');

    /*
    private static $metadataPrefixList = array(
        'oai_dc' => array(
            'prefix' => 'oai_dc',
            'attributes' => [
                'xmlns' => 'http://www.openarchives.org/OAI/2.0/oai_dc/',
                'xmlns:dc' => 'http://purl.org/dc/elements/1.1/',
                'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation' => 'http://www.openarchives.org/OAI/2.0/oai_dc.xsd'
            ],
            'handler' => \App\Models\OAI\MetadataPrefix\OAI_SI4::class,
        ),
        'oai_datacite' => array(
            'prefix' => 'oai_datacite',
            'attributes' => [
                'xmlns' => 'http://datacite.org/schema/kernel-4',
                'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation' => 'http://datacite.org/schema/kernel-4 http://schema.datacite.org/meta/kernel-4/metadata.xsd'
            ],
            'handler' => \App\Models\OAI\MetadataPrefix\OAI_SI4::class,
        ),
    );
    */


    /*
    public static $identifyInfo = array(
        "repositoryName" => "Sistory.si OAI Repository",
        "baseURL" => "http://www.sistory.si/oai.php",
        "protocolVersion" => "2.0",
        "adminEmail" => "gregor@invita.si",
        "earliestDatestamp" => "2011-08-01T00:00:00Z",
        "deletedRecord" => "no",
        "granularity" => "YYYY-MM-DDThh:mm:ssZ"
    );
    */

    public static $defaultBatchSize = 100;
    public static $resumpTokenValidHours = 24;

    // Getters
    public static function getAllowedArgumentList() { return self::$allowedArgumentList; }
    public static function getAllowedVerbList() { return self::$allowedVerbList; }
    public static function getListRequestVerbs() { return self::$listRequestVerbs; }
    public static function getFieldList() { return self::$fieldList; }

    /*
    public static function getMetadataPrefixList() { return self::$metadataPrefixList; }
    public static function getMetadataPrefix($mdPrefix) {
        if (!$mdPrefix || !isset(self::$metadataPrefixList[$mdPrefix])) return null;
        return self::$metadataPrefixList[$mdPrefix];
    }
    */

    public static function error($request, $oaiErrorCode, $message) {
        throw new OAIError($request, $oaiErrorCode, $message);
    }

    public static function currentTimestamp() {

    }

}