<?php
namespace App\Models\OAI;

class OAIHelper {

    private static $fieldList = array('title', 'creator', 'subject', 'description', 'publisher', 'contributor',
        'date', 'type', 'format', 'identifier', 'source', 'language', 'relation', 'coverage', 'rights');

    private static $allowedVerbList = array('Identify', 'ListSets', 'ListIdentifiers', 'ListRecords',
        'ListMetadataFormats', 'GetRecord');

    private static $listRequestVerbs = array('ListSets', 'ListIdentifiers', 'ListRecords', 'ListMetadataFormats');

    private static $allowedArgumentList = array('verb', 'from', 'until', 'metadataPrefix', 'set',
        'cursor', 'resumptionToken', 'batchSize', 'DBGSESSID', 'identifier');


    public static $defaultBatchSize = 100;
    public static $resumpTokenValidHours = 24;

    // Getters
    public static function getAllowedArgumentList() { return self::$allowedArgumentList; }
    public static function getAllowedVerbList() { return self::$allowedVerbList; }
    public static function getListRequestVerbs() { return self::$listRequestVerbs; }
    public static function getFieldList() { return self::$fieldList; }

    public static function error($request, $oaiErrorCode, $message) {
        throw new OAIError($request, $oaiErrorCode, $message);
    }

    public static function currentTimestamp() {

    }

}