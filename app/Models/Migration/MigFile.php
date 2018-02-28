<?php

namespace App\Models\Migration;

use App\Helpers\FileHelpers;
use App\Helpers\Si4Util;
use App\Models\Entity;
use App\Models\EntityHandleSeq;
use Illuminate\Support\Facades\DB;

class MigFile
{
    private static $fileAttrMap = array(
        "1" => "extent",
        "2" => "format",
        "3" => "date",
        "4" => "description",
        "5" => "rights",
        "6" => "title",
        "7" => "creator",
        "8" => "subject",
        "9" => "publisher",
        "10" => "contributor",
        "11" => "type",
        "12" => "identifier",
        "13" => "source",
        "14" => "language",
        "15" => "relation",
        "16" => "coverage",
    );


    private $glavnaTabelaData;
    private $pubPubData;

    public $fileEntity;


    public function __construct($glavnaTabelaData, $pubPubData) {
        $this->glavnaTabelaData = $glavnaTabelaData;
        $this->pubPubData = $pubPubData;
    }

    public function makeEntity() {
        $struct_type = "file";
        $newEntityId = Si4Util::nextEntityId();

        $this->fileEntity = Entity::findOrNew($newEntityId);
        $this->fileEntity->id = $newEntityId;
        $this->fileEntity->handle_id = "file".$this->pubPubData["FILE_ID"];
        $this->fileEntity->parent = $this->glavnaTabelaData["ID"];
        $this->fileEntity->primary = ""; //$this->glavnaTabelaData["ID"];

        $this->fileEntity->struct_type = $struct_type;
        //$this->fileEntity->fileEntity = "";

        return $this->fileEntity;
    }

    public function makeMetsXml() {
        $xmlComps = array();

        $importUserId = MigUtil::$migImportUserId;
        $importUserName = MigUtil::$migImportUserName;

        $handleId = "file".$this->pubPubData["FILE_ID"];
        $type = "file";
        $timestamp = str_replace(" ", "T", $this->glavnaTabelaData["DATETIME_MODIFIED"]);


        /*
        // $this->pubPubData
        Array (
            [ID] => 15100
            [ZAP_ST] => 1
            [NAZIV] => G5-027-001.jpg
            [STRAN] => 0
            [ATRIBUTI] => [["2","0","image\/jpeg"],["2","0","format digitalne datoteke (opisno)"],["2","1","image\/jpeg"],["1","0","obseg in velikost digitalne datoteke"],["3","1","2013-01-01"],["4","0","Opis vsebine, nastanka, pridobitve itd. digitalne datoteke."],["5","0","Avtorske pravice v povezavi z digitalno datoteko."]]
            [FILE_ID] => 5093
        )
        */

        $pubId = $this->glavnaTabelaData["ID"];
        $fileId = $this->pubPubData["FILE_ID"];
        $fileName = $this->pubPubData["NAZIV"];
        $mimetype = FileHelpers::fileNameMime($fileName);
        $fileSize = 0;

        $NL = "\n";


        $xmlComps[] = <<<HERE
<?xml version="1.0" encoding="UTF-8"?>
HERE;
        $xmlComps[] = <<<HERE

<METS:mets
    xmlns:METS="http://www.loc.gov/METS/"
    xmlns:xlink="http://www.w3.org/TR/xlink"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:dcterms="http://purl.org/dc/terms/"
    xmlns:dcmitype="http://purl.org/dc/dcmitype/"
    xmlns:premis="http://www.loc.gov/premis/v3"
    xmlns:mods="http://www.loc.gov/mods/v3"
    ID="file{$this->fileEntity->handle_id}" TYPE="file"
    OBJID="http://hdl.handle.net/11686/file{$this->fileEntity->handle_id}"
    xsi:schemaLocation="http://www.loc.gov/METS/ http://www.loc.gov/mets/mets.xsd http://purl.org/dc/terms/ http://dublincore.org/schemas/xmls/qdc/dcterms.xsd http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/mods.xsd http://www.loc.gov/premis/v3 https://www.loc.gov/standards/premis/premis.xsd">
HERE;

        $xmlComps[] = <<<HERE
    <METS:metsHdr CREATEDATE="{$timestamp}" LASTMODDATE="{$timestamp}" RECORDSTATUS="Active">
        <METS:agent ROLE="DISSEMINATOR" TYPE="ORGANIZATION">
            <METS:name>SIstory</METS:name>
            <METS:note>http://sistory.si/</METS:note>
        </METS:agent>
        <METS:agent ROLE="CREATOR" ID="{$importUserId}" TYPE="INDIVIDUAL">
            <METS:name>{$importUserName}</METS:name>
        </METS:agent>
    </METS:metsHdr>
HERE;

        $xmlComps[] = <<<HERE
    <METS:dmdSec ID="default.dc">
        <METS:mdWrap MDTYPE="DC" MIMETYPE="text/xml">
            <METS:xmlData>
HERE;

        // 4 tabs (4x4 =16 spaces)
        $tabs = "                ";

        // *** DC fields ***

        $attrList = json_decode($this->pubPubData["ATRIBUTI"], true);
        foreach ($attrList as $attrItem) {
            $attrName = isset(self::$fileAttrMap[$attrItem[0]]) ? self::$fileAttrMap[$attrItem[0]] : null;
            if (!$attrName) continue;
            $attrValue = $attrItem[2];
            $xmlComps[] = $tabs."<dcterms:".$attrName.">".MigUtil::wrapCDATA($attrValue)."</dcterms:".$attrName.">";
        }

        $xmlComps[] = <<<HERE
            </METS:xmlData>
        </METS:mdWrap>
    </METS:dmdSec>
HERE;


        // *** Administrative metadata ***

        $si4description = MigUtil::wrapCDATA("");
        $page = $this->glavnaTabelaData["PAGE"];
        $removedTo = $this->glavnaTabelaData["MOVED_URL"];

        $xmlComps[] = <<<HERE
    <METS:amdSec ID="default.amd">
        <METS:techMD ID="default.premis">
            <METS:mdWrap MDTYPE="PREMIS:OBJECT" MIMETYPE="text/xml">
                <METS:xmlData>
                    <premis:objectIdentifier>
                        <premis:objectIdentifierType>si4</premis:objectIdentifierType>
                        <premis:objectIdentifierValue>{$this->fileEntity->id}</premis:objectIdentifierValue>
                    </premis:objectIdentifier>
                    <premis:objectIdentifier>
                        <premis:objectIdentifierType>Local name</premis:objectIdentifierType>
                        <premis:objectIdentifierValue>{$this->fileEntity->handle_id}</premis:objectIdentifierValue>
                    </premis:objectIdentifier>
                    <premis:objectIdentifier>
                        <premis:objectIdentifierType>hdl</premis:objectIdentifierType>
                        <premis:objectIdentifierValue>http://hdl.handle.net/11686/{$this->fileEntity->handle_id}</premis:objectIdentifierValue>
                    </premis:objectIdentifier>
                    <premis:objectCategory>File</premis:objectCategory>
                </METS:xmlData>
            </METS:mdWrap>
        </METS:techMD>
    </METS:amdSec>
HERE;

        // *** fileSec ***

        $xmlComps[] = <<<HERE
    <METS:fileSec ID="default.file" xmlns:xlink="http://www.w3.org/1999/xlink">
        <METS:fileGrp>
            <METS:file ID="file{$this->fileEntity->handle_id}" OWNERID="{$fileName}" MIMETYPE="{$mimetype}" CREATED="{$timestamp}" SIZE="{$fileSize}" DMDID="default.dc">
                <METS:FLocat LOCTYPE="URL" xlink:href="{$fileName}"/>
            </METS:file>
        </METS:fileGrp>
    </METS:fileSec>
HERE;

        // *** structMap ***

        $xmlComps[] = <<<HERE
    <METS:structMap ID="default.structure" xmlns:xlink="http://www.w3.org/1999/xlink">
        <METS:div TYPE="entity">
            <METS:mptr LOCTYPE="HANDLE" xlink:href="http://hdl.handle.net/11686/{$pubId}"/>
            <METS:div TYPE="file">
                <METS:fptr FILEID="file{$fileId}"/>
            </METS:div>
        </METS:div>
    </METS:structMap>
HERE;

        $xmlComps[] = <<<HERE
</METS:mets>
HERE;

        $result = join($NL, $xmlComps);
        return $result;
    }



    public static function getFileFormatFromJson($strJson) {
        $attrs = json_decode($strJson, true);
        foreach ($attrs as $attr) {
            if ($attr[0] == 2) // see $fileAttrMap
                return $attr[2];
        }
        return "";
    }
}