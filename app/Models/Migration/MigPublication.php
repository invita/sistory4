<?php

namespace App\Models\Migration;

use App\Helpers\Si4Util;
use App\Models\Entity;
use App\Models\EntityHandleSeq;
use Illuminate\Support\Facades\DB;

class MigPublication
{
    private $id;
    private $data;

    public $entity;
    public $files;

    public $children;
    public $primary;

    public function __construct($id) {
        $this->id = $id;
        $this->data = array();
        $this->entity = null;
        $this->files = array();
    }

    public function getData($tableName = null) {
        if ($tableName) return isset($this->data[$tableName]) ? $this->data[$tableName] : array();
        return $this->data;
    }

    public function readAll() {
        $migPubDb = MigUtil::$migDbName;

        $tables = ["PUB_GLAVNA_TABELA",
            "PUB_ARHIVSKI_EL", "PUB_AUDIENCE", "PUB_AUDIOVIS_EL", "PUB_COLLECTION", "PUB_CONTRIBUTOR",
            "PUB_COVERAGE", "PUB_CREATOR", "PUB_CREATORS", "PUB_CREATOR_V2", "PUB_DATE", "PUB_DESCRIPTION",
            "PUB_DESCRIPTION_ENG", "PUB_DESCRIPTION_INT", "PUB_DESCRIPTION_SLO", "PUB_FORMAT", "PUB_IDENTIFIER",
            "PUB_IMAGE", "PUB_KNJIZNI_EL", "PUB_LANGUAGE", "PUB_LINKS", "PUB_OTHER",
            "PUB_PUBLICATION", "PUB_PUBLISHER", "PUB_RELATION", "PUB_RIGHTS", "PUB_RIGHTS_V2", "PUB_SIF_IMT_FORMAT",
            "PUB_SIF_LANGUAGE", "PUB_SIF_TAGS", "PUB_SIF_TYPE", "PUB_SIF_TYPE_DC", "PUB_SIGNATURA", "PUB_SLIKOVNI_EL",
            "PUB_SOURCE", "PUB_SUBJECT", "PUB_SUBJECT_ENG", "PUB_SUBJECT_SLO", "PUB_TAGS_V3", "PUB_TITLE",
            "PUB_TITLE_ENG", "PUB_TITLE_SLO", "PUB_TYPE_V2"];

        foreach ($tables as $tableName) {
            $tableData = DB::select("SELECT * FROM {$migPubDb}.{$tableName} WHERE ID = {$this->id}");
            $this->data[$tableName] = [];
            foreach($tableData as $rowIdx => $tableRow) {
                $tableRowArr = [];
                foreach ($tableRow as $trKey => $trVal) $tableRowArr[$trKey] = $trVal;
                $this->data[$tableName][$rowIdx] = $tableRowArr;
            }
        }

        // Get children
        $childData = DB::select("SELECT * FROM {$migPubDb}.PUB_GLAVNA_TABELA WHERE RELATION = {$this->id} ORDER BY CHILD_ORDER");
        $this->children = [];
        foreach($childData as $rowIdx => $child) {
            $tableRowArr = [];
            foreach ($child as $cKey => $cVal) $tableRowArr[$cKey] = $cVal;
            $this->children[$rowIdx] = $tableRowArr;
        }

        // Find primary
        $relation = $this->getData("PUB_GLAVNA_TABELA")[0]["RELATION"];
        if ($relation) {

            while (true) {
                $parentData = DB::select("SELECT * FROM {$migPubDb}.PUB_GLAVNA_TABELA WHERE ID = {$relation}");
                $nextRelation = $parentData[0]->RELATION;
                if (!$nextRelation) {
                    $this->primary = [];
                    foreach ($parentData[0] as $pKey => $pVal) $this->primary[$pKey] = $pVal;
                    break;
                } else {
                    $relation = $nextRelation;
                }
            }
        }
    }

    public function makeEntity() {
        $struct_type = "entity";
        $newEntityId = Si4Util::nextEntityId();

        $this->entity = Entity::findOrNew($newEntityId);
        $this->entity->id = $newEntityId;
        //$this->entity->handle_id = EntityHandleSeq::nextNumSeq($struct_type);
        $this->entity->handle_id = $this->getData("PUB_GLAVNA_TABELA")[0]["ID"];

        $parent = "";
        if ($this->getData("PUB_GLAVNA_TABELA")[0]["RELATION"]) {
            $parent = $this->getData("PUB_GLAVNA_TABELA")[0]["RELATION"];
        } else if ($this->getData("PUB_GLAVNA_TABELA")[0]["MENU_ID"]) {
            $parent = "menu".$this->getData("PUB_GLAVNA_TABELA")[0]["MENU_ID"];
        }

        $this->entity->parent = $parent;
        $this->entity->primary = $this->primary ? $this->primary["ID"] : "";

        $this->entity->struct_type = $struct_type;

        $hasParent = $this->getData("PUB_GLAVNA_TABELA")[0]["RELATION"] ? true : false;
        $this->entity->entity_type = $hasParent ? "dependant" : "primary";

        return $this->entity;
    }


    /*
        [ID] => 15100
        [ZAP_ST] => 1
        [NAZIV] => G5-027-001.jpg
        [STRAN] => 0
        [ATRIBUTI] => [["2","0","image\/jpeg"],["2","0","format digitalne datoteke (opisno)"],["2","1","image\/jpeg"],["1","0","obseg in velikost digitalne datoteke"],["3","1","2013-01-01"],["4","0","Opis vsebine, nastanka, pridobitve itd. digitalne datoteke."],["5","0","Avtorske pravice v povezavi z digitalno datoteko."]]
        [FILE_ID] => 5093
    */
    public function makeFiles() {

        $pubData = $this->getData("PUB_PUBLICATION");
        foreach ($pubData as $idx => $pubPub) {

            $migFile = new MigFile($this->getData("PUB_GLAVNA_TABELA")[0], $pubPub);
            $fileEntity = $migFile->makeEntity();
            $fileEntity->data = $migFile->makeMetsXml();


            // $pub["NAZIV"]
            // $pub["STRAN"]
            // $pub["ATRIBUTI"]
            // $pub["FILE_ID"]

            /*
            $attrList = json_decode($pub["ATRIBUTI"], true);
            foreach ($attrList as $attrItem) {
                $attrName = isset($fileAttrMap[$attrItem[0]]) ? $fileAttrMap[$attrItem[0]] : null;
                if (!$attrName) continue;
                $attrValue = $attrItem[2];

            }
            */

        }

    }

    public function makeMetsXml() {
        $xmlComps = array();

        $importUserId = MigUtil::$migImportUserId;
        $importUserName = MigUtil::$migImportUserName;

        $handleId = "entity".$this->getData("PUB_GLAVNA_TABELA")[0]["ID"];
        $type = "entity";
        $timestamp = str_replace(" ", "T", $this->getData("PUB_GLAVNA_TABELA")[0]["DATETIME_MODIFIED"]);

        $migPubDb = MigUtil::$migDbName;
        $pubSifLangArr = DB::select("SELECT ID, KODA FROM {$migPubDb}.PUB_SIF_LANGUAGE WHERE LANGUAGE = 'en'");
        $langMap = array();
        foreach ($pubSifLangArr as $pubSifLang) {
            $langMap[$pubSifLang->ID] = $pubSifLang->KODA;
        }
        $xmlSifLangAttr = function($langId) use($langMap) {
            if ($langId && isset($langMap[$langId])) return ' xml:lang="'.$langMap[$langId].'"';
            return '';
        };

        $langMap2to3 = array("sl" => "slv", "en" => "eng");
        $xmlSifLangAttr23 = function($langId2) use($langMap2to3) {
            if ($langId2 && isset($langMap2to3[$langId2])) return ' xml:lang="'.$langMap2to3[$langId2].'"';
            return '';
        };

        $pubSifTypeArr = DB::select("SELECT ID, NAZIV FROM {$migPubDb}.PUB_SIF_TYPE_DC");
        $typeDcMap = array();
        foreach ($pubSifTypeArr as $pubSifType) {
            $typeDcMap[$pubSifType->ID] = $pubSifType->NAZIV;
        }

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
	xmlns:premis="http://www.loc.gov/standards/premis/v1"
	xmlns:mods="http://www.loc.gov/mods/v3"
	xmlns:entity="http://sistory.si/schema/sistory/v3/{$type}"
	ID="{$handleId}" TYPE="{$type}"
	OBJID="http://hdl.handle.net/11686/{$handleId}"
	xsi:schemaLocation="http://www.loc.gov/METS/ http://www.loc.gov/mets/mets.xsd http://purl.org/dc/terms/ http://dublincore.org/schemas/xmls/qdc/dcterms.xsd http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-0.xsd http://www.loc.gov/standards/premis/v1 http://www.loc.gov/standards/premis/v1/PREMIS-v1-1.xsd">
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
        // title
        foreach ($this->getData("PUB_TITLE") as $idx => $row) {
            $rowComp = $tabs."<dcterms:title".$xmlSifLangAttr($row["LANGUAGE_ID"]).">";
            $rowComp .= MigUtil::wrapCDATA($row["NAZIV"]);
            $rowComp .= "</dcterms:title>";
            $xmlComps[] = $rowComp;
        }

        // creator
        foreach ($this->getData("PUB_CREATOR") as $idx => $row) {
            $rowComp = $tabs."<dcterms:creator>";
            $rowComp .= MigUtil::wrapCDATA($row["PRIIMEK"].", ".$row["IME"]);
            $rowComp .= "</dcterms:creator>";
            $xmlComps[] = $rowComp;
        }
        foreach ($this->getData("PUB_CREATOR_V2") as $idx => $row) {
            $rowComp = $tabs."<dcterms:creator".$xmlSifLangAttr($row["LANGUAGE_ID"]).">";
            $rowComp .= MigUtil::wrapCDATA($row["CONTENT"]);
            $rowComp .= "</dcterms:creator>";
            $xmlComps[] = $rowComp;
        }

        // contributor
        foreach ($this->getData("PUB_CONTRIBUTOR") as $idx => $row) {
            $rowComp = $tabs."<dcterms:contributor>";
            $rowComp .= MigUtil::wrapCDATA($row["NAZIV"]);
            $rowComp .= "</dcterms:contributor>";
            $xmlComps[] = $rowComp;
        }

        // date
        $xmlComps[] = $tabs."<dcterms:date>".MigUtil::wrapCDATA($this->getData("PUB_GLAVNA_TABELA")[0]["YEAR"])."</dcterms:date>";

        // description
        foreach ($this->getData("PUB_DESCRIPTION") as $idx => $row) {
            $rowComp = $tabs."<dcterms:description".$xmlSifLangAttr($row["LANGUAGE_ID"]).">";
            $rowComp .= MigUtil::wrapCDATA($row["CONTENT"]);
            $rowComp .= "</dcterms:description>";
            $xmlComps[] = $rowComp;
        }
        foreach ($this->getData("PUB_DESCRIPTION_INT") as $idx => $row) {
            $rowComp = $tabs."<dcterms:description".$xmlSifLangAttr23($row["LANGUAGE_ID"]).">";
            $rowComp .= MigUtil::wrapCDATA($row["CONTENT"]);
            $rowComp .= "</dcterms:description>";
            $xmlComps[] = $rowComp;
        }

        // format
        foreach ($this->getData("PUB_FORMAT") as $idx => $row) {
            $rowComp = $tabs."<dcterms:format>";
            $rowComp .= MigUtil::wrapCDATA($row["VALUE"]);
            $rowComp .= "</dcterms:format>";
            $xmlComps[] = $rowComp;
        }

        // coverage
        foreach ($this->getData("PUB_COVERAGE") as $idx => $row) {
            $rowComp = $tabs."<dcterms:coverage>";
            $rowComp .= MigUtil::wrapCDATA($row["NAZIV"]);
            $rowComp .= "</dcterms:coverage>";
            $xmlComps[] = $rowComp;
        }

        // language
        foreach ($this->getData("PUB_LANGUAGE") as $idx => $row) {
            $rowComp = $tabs."<dcterms:language>";
            if (!$row["LANGUAGE"] || isset($langMap[$row["LANGUAGE"]])) continue;
            $rowComp .= $langMap[$row["LANGUAGE"]];
            $rowComp .= "</dcterms:language>";
            $xmlComps[] = $rowComp;
        }

        // identifier
        foreach ($this->getData("PUB_IDENTIFIER") as $idx => $row) {
            $rowComp = $tabs."<dcterms:identifier>";
            $rowComp .= MigUtil::wrapCDATA($row["VALUE"]);
            $rowComp .= "</dcterms:identifier>";
            $xmlComps[] = $rowComp;
        }

        // publisher
        foreach ($this->getData("PUB_PUBLISHER") as $idx => $row) {
            $rowComp = $tabs."<dcterms:publisher>";
            $rowComp .= MigUtil::wrapCDATA($row["NAZIV"]);
            $rowComp .= "</dcterms:publisher>";
            $xmlComps[] = $rowComp;
        }

        // relation
        foreach ($this->getData("PUB_RELATION") as $idx => $row) {
            $rowComp = $tabs."<dcterms:relation".$xmlSifLangAttr($row["LANGUAGE"]).">";
            $rowComp .= MigUtil::wrapCDATA($row["VALUE"]);
            $rowComp .= "</dcterms:relation>";
            $xmlComps[] = $rowComp;
        }

        // rights
        foreach ($this->getData("PUB_RIGHTS_V2") as $idx => $row) {
            $rowComp = $tabs."<dcterms:rights".$xmlSifLangAttr($row["LANGUAGE"]).">";
            $rowComp .= MigUtil::wrapCDATA($row["NAZIV"]);
            $rowComp .= "</dcterms:rights>";
            $xmlComps[] = $rowComp;
        }

        // source
        foreach ($this->getData("PUB_SOURCE") as $idx => $row) {
            $rowComp = $tabs."<dcterms:source".$xmlSifLangAttr($row["LANGUAGE"]).">";
            $rowComp .= MigUtil::wrapCDATA($row["NAZIV"]);
            $rowComp .= "</dcterms:source>";
            $xmlComps[] = $rowComp;
        }

        // subject
        foreach ($this->getData("PUB_SUBJECT") as $idx => $row) {
            $rowComp = $tabs."<dcterms:subject".$xmlSifLangAttr($row["LANGUAGE_ID"]).">";
            $rowComp .= MigUtil::wrapCDATA($row["NAZIV"]);
            $rowComp .= "</dcterms:subject>";
            $xmlComps[] = $rowComp;
        }

        // type
        foreach ($this->getData("PUB_TYPE_V2") as $idx => $row) {
            if ($row["TYPE_ID"] != "2") continue; // Only DC type
            $rowComp = $tabs."<dcterms:type>";
            $val = isset($typeDcMap[$row["TYPE_VALUE"]]) ? $typeDcMap[$row["TYPE_VALUE"]] : "";
            $rowComp .= MigUtil::wrapCDATA($val);
            $rowComp .= "</dcterms:type>";
            $xmlComps[] = $rowComp;
        }

        $xmlComps[] = <<<HERE
            </METS:xmlData>
        </METS:mdWrap>
    </METS:dmdSec>
HERE;


        // *** Administrative metadata ***

        $si4description = MigUtil::wrapCDATA("");
        $page = $this->getData("PUB_GLAVNA_TABELA")[0]["PAGE"];
        $removedTo = $this->getData("PUB_GLAVNA_TABELA")[0]["MOVED_URL"];

        $xmlComps[] = <<<HERE
    <METS:amdSec ID="default.amd">
        <METS:techMD ID="default.premis">
            <METS:mdWrap MDTYPE="PREMIS:OBJECT" MIMETYPE="text/xml">
                <METS:xmlData>
                    <premis:objectIdentifier>
                        <premis:objectIdentifierType>si4</premis:objectIdentifierType>
                        <premis:objectIdentifierValue>{$this->entity->id}</premis:objectIdentifierValue>
                    </premis:objectIdentifier>
                    <premis:objectIdentifier>
                        <premis:objectIdentifierType>Local name</premis:objectIdentifierType>
                        <premis:objectIdentifierValue>{$this->entity->handle_id}</premis:objectIdentifierValue>
                    </premis:objectIdentifier>
                    <premis:objectIdentifier>
                        <premis:objectIdentifierType>hdl</premis:objectIdentifierType>
                        <premis:objectIdentifierValue>http://hdl.handle.net/11686/{$this->entity->handle_id}</premis:objectIdentifierValue>
                    </premis:objectIdentifier>
                    <premis:objectCategory>Entity</premis:objectCategory>
                </METS:xmlData>
            </METS:mdWrap>
        </METS:techMD>
        <METS:techMD ID="default.si4">
            <METS:mdWrap MDTYPE="OTHER" OTHERMDTYPE="ENTITY" MIMETYPE="text/xml">
                <METS:xmlData xmlns:entity="http://sistory.si/schema/si4/entity" xsi:schemaLocation="http://sistory.si/schema/si4/entity /schema/si4/entity.1.0.xsd">
                    <entity:description xml:lang="slv">{$si4description}</entity:description>
                    <entity:page>{$page}</entity:page>
                    <entity:removedTo>{$removedTo}</entity:removedTo>
                </METS:xmlData>
            </METS:mdWrap>
        </METS:techMD>
    </METS:amdSec>
HERE;


        // *** fileSec ***

        $xmlComps[] = <<<HERE
    <METS:fileSec ID="default.file" xmlns:xlink="http://www.w3.org/1999/xlink">
HERE;

        // Non HTML files
        $htmlFilesExist = false;
        $htmlFormats = ["text/html", "application/json", "application/xml"];
        $xmlComps[] = <<<HERE
        <METS:fileGrp USE="DEFAULT">
HERE;
        foreach ($this->getData("PUB_PUBLICATION") as $idx => $row) {
            //print_r($row);
            //$attrs = json_decode($row["ATRIBUTI"], true);
            $format = MigFile::getFileFormatFromJson($row["ATRIBUTI"]);
            if (in_array($format, $htmlFormats)) {
                $htmlFilesExist = true;
                continue;
            }

            $fileId = $row["FILE_ID"];
            $fileName = $row["NAZIV"];
            $rowComp = <<<HERE
            <METS:file ID="file{$fileId}" OWNERID="{$fileName}">
                <METS:FLocat LOCTYPE="HANDLE" xlink:href="https://hdl.handle.net/11686/file{$fileId}"/>
            </METS:file>
HERE;
            $xmlComps[] = $rowComp;
        }
        $xmlComps[] = <<<HERE
        </METS:fileGrp>
HERE;


        // HTML files
        if ($htmlFilesExist) {
            $xmlComps[] = <<<HERE
        <METS:fileGrp USE="HTML">
HERE;
            foreach ($this->getData("PUB_PUBLICATION") as $idx => $row) {
                $format = MigFile::getFileFormatFromJson($row["ATRIBUTI"]);
                if (!in_array($format, $htmlFormats)) continue;

                $fileId = $row["FILE_ID"];
                $fileName = $row["NAZIV"];
                $rowComp = <<<HERE
            <METS:file ID="file{$fileId}" OWNERID="{$fileName}">
                <METS:FLocat LOCTYPE="HANDLE" xlink:href="https://hdl.handle.net/11686/file{$fileId}"/>
            </METS:file>
HERE;
                $xmlComps[] = $rowComp;
            }
            $xmlComps[] = <<<HERE
        </METS:fileGrp>
HERE;
        }

        $xmlComps[] = <<<HERE
    </METS:fileSec>
HERE;


        // *** structMap ***
        $menuId = $this->getData("PUB_GLAVNA_TABELA")[0]["MENU_ID"];
        $xmlComps[] = <<<HERE
    <METS:structMap ID="default.structure" TYPE="primary" xmlns:xlink="http://www.w3.org/1999/xlink">
        <METS:div TYPE="collection">
            <METS:mptr LOCTYPE="HANDLE" xlink:href="http://hdl.handle.net/11686/menu{$menuId}"/>
            <METS:div TYPE="entity" DMDID="default.dc" ADMID="default.amd">
HERE;

        // structMap files
        foreach ($this->getData("PUB_PUBLICATION") as $idx => $row) {
            $fileId = $row["FILE_ID"];
            $rowComp = <<<HERE
                <METS:fptr FILEID="file{$fileId}"/>
HERE;
            $xmlComps[] = $rowComp;
        }

        // structMap children
        foreach ($this->children as $idx => $row) {
            $childId = $row["ID"];
            $rowComp = <<<HERE
                <METS:div TYPE="entity">
                    <METS:mptr LOCTYPE="HANDLE" xlink:href="http://hdl.handle.net/11686/{$childId}"/>
                </METS:div>
HERE;
            $xmlComps[] = $rowComp;
        }



        $xmlComps[] = <<<HERE
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

}