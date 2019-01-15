<?php
namespace App\Models\OAI;

use App\Helpers\ElasticHelpers;
use App\Helpers\Si4Util;

class OAIRecord {

    public $identifier;
    public $id;
    public $dataElastic;

    public static function getRecordsArray($request, $filters = array()) {

        $result = [];
        $cursor = $request->resumptionToken->getCursor();
        $batchSize = $request->resumptionToken->getBatchSize();

        $elasticQuery = [ "match_all" => ["boost" => 1] ];

        $listDataElastic = ElasticHelpers::search($elasticQuery, $cursor, $batchSize);
        $totalHits = Si4Util::pathArg($listDataElastic, "hits/total", 0);
        $hits = ElasticHelpers::elasticResultToAssocArray($listDataElastic);

        $request->resumptionToken->setCompleteListSize($totalHits);
        $request->resumptionToken->incrementCursor();
        $request->resumptionToken->save();

        //print_r($hits);

        $c = 0;
        foreach ($hits as $hit) {
            //print_r($hit);
            $c++; if ($c > $batchSize) break;
            $result[] = self::fromElastic($hit);
        }

        return $result;
    }

    public static function getMetadataRecord($identifier) {

        $identifier = str_replace("/".si4config("handlePrefix")."/", "", $identifier);

        $result = null;
        $listDataElastic = ElasticHelpers::searchByHandleArray([$identifier]);
        if (count($listDataElastic)) {
            $result = self::fromElastic($listDataElastic[array_keys($listDataElastic)[0]]);
        }

        return $result;
    }

    public function __construct($identifier, $id, $dataElastic) {
        $this->identifier = $identifier;
        $this->id = $id;
        $this->dataElastic = $dataElastic;
    }

    public static function fromElastic($dataElastic) {
        $handle_id = Si4Util::pathArg($dataElastic, "_source/handle_id", null);
        $identifier = "/".si4config("handlePrefix")."/".$handle_id;
        $record = new OAIRecord($identifier, $handle_id, $dataElastic);
        return $record;
    }



    public function toXml() {
        $record = new OAIXmlElement("record");

        $headerXml = $this->headerToXml();
        $metadataXml = $this->metadataToXml();

        $n = OAIXmlOutput::$newLine;
        $record->setContentXml($headerXml.$n.$metadataXml);

        return $record->toXml();
    }

    public function headerToXml() {
        $header = new OAIXmlElement("header");

        // identifier
        $identifier = new OAIXmlElement("identifier");
        $identifier->setValue($this->identifier);

        // datestamp
        $datestamp = new OAIXmlElement("datestamp");
        $date = Si4Util::pathArg($this->dataElastic, "_source/data/createdAt", "");
        $datestamp->setValue($date);

        $header->appendChild($identifier);
        $header->appendChild($datestamp);

        return $header->toXml();
    }

    public function metadataToXml() {

        $mdPrefixData = OAIHelper::getMetadataPrefix("oai_dc");
        $mdPrefixHandlerClass = Si4Util::getArg($mdPrefixData, "handler", null);
        if ($mdPrefixHandlerClass) {
            // Call prefix handler
            $mdPrefixHandler = new $mdPrefixHandlerClass();
            $metadata = $mdPrefixHandler->metadataToXml($this);
        } else {
            // Dummy metadata
            $metadata = new OAIXmlElement("metadata");
            $metadata->setAttribute("metadataPrefix", "unimplemented");
        }

        //$metadata = new OAIXmlElement("metadata");
        //$metadata->setValue(OAIXmlOutput::wrapInCDATA(print_r($this->dataElastic,true)));

        //echo "<pre>";
        //unset($this->dataElastic["_source"]["xml"]); print_r($this->dataElastic);
        //echo "</pre>";
        //die();

        /*
        $metadata = new OAIXmlElement("metadata");

        $oai_dc = new OAIXmlElement("oai_dc:dc");
        $oai_dc->setValue($this->identifier);
        $oai_dc->setAttribute("xmlns:oai_dc", "http://www.openarchives.org/OAI/2.0/oai_dc/");
        $oai_dc->setAttribute("xmlns:dc", "http://purl.org/dc/elements/1.1/");
        $oai_dc->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $oai_dc->setAttribute("xsi:schemaLocation", "http://www.openarchives.org/OAI/2.0/oai_dc.xsd");
        $oai_dc->appendTo($metadata);
        */

        //print_r($this->data["IDENTIFIER_V2"]);
        //die();


        /*
        $dcFields = $this->parseMetadataFields();

        foreach ($dcFields as $idx => $dcField){

            $tagName = $dcField["tagName"];
            $tag = new OAIXmlElement($tagName);
            foreach ($dcField as $attrName => $attrValue){
                if ($attrName == "tagName") continue;
                if ($attrName == "value") { $tag->setValue($attrValue); continue; }

                if ($attrName == "xml:lang") $attrValue = $this->getLangCode($attrValue);

                $tag->setAttribute($attrName, $attrValue);
            }

            $tag->appendTo($oai_dc);
        }
        */

        return $metadata->toXml();
    }


    /*
    public $identifier;
    public $ID;
    private $data;
    private $metadataPrefix;
    private $langId;

    static $valueSeparator = ", ";

    private $setOutputAllMetadata = false;
    public function setOutputAllMetadata($bool) {
        $this->setOutputAllMetadata = $bool;
    }

    public function __construct($identifier) {
        $this->identifier = $identifier;
        $this->metadataPrefix = "oai_dc";
        $this->langId = "sl";
    }

    public static function getMetadataRecord($identifier) {
        $inst = new self($identifier);
        $inst->ID = \Publikacije_Model_Pub_Publication_Urn::parse($identifier);
        if (!$inst->ID) return null;

        $pubExists = \Publikacije_Model_Pub_Publication_Publication::idExists($inst->ID);
        if (!$pubExists) return null;

        $inst->data = \Publikacije_Model_Pub_Publication_Data::getById($inst->ID);

        return $inst;
    }

    public static function getRecordArray($request = null, $filters = array()) {

        $db = Zend_Registry::get("mysql");

        $conditions = array();
        $where = "";
        $limit = "";

        if ($request) {

            if ($request->from) {
                //print_r($request->from->toSqlString()); die();
                $fromGranularity = $request->from->getGranularity();
                if ($fromGranularity == OAIDate::GRANULARITY_SHORT)
                    $conditions[] = "DATE(DATETIME_MODIFIED) >= {$db->quote($request->from->toSqlDateString())}";
                else
                    $conditions[] = "DATETIME_MODIFIED >= {$db->quote($request->from->toSqlString())}";
            }

            if ($request->until) {
                $untilGranularity = $request->until->getGranularity();
                if ($untilGranularity == OAIDate::GRANULARITY_SHORT)
                    $conditions[] = "DATE(DATETIME_MODIFIED) <= {$db->quote($request->until->toSqlDateString())}";
                else
                    $conditions[] = "DATETIME_MODIFIED <= {$db->quote($request->until->toSqlString())}";
            }

            $limit = "LIMIT ".$request->resumptionToken->cursor.", ".$request->resumptionToken->batchSize;
        }

        if (count($conditions)) $where = "WHERE ". join(" AND ", $conditions);

        $query = "SELECT ID FROM PUB_GLAVNA_TABELA {$where} ORDER BY ID DESC {$limit}";

        //echo $query; die();

        $pubArray = $db->fetchAll($query);

        $result = array();
        foreach ($pubArray as $pub){
            $pubId = $pub["ID"];
            //$pubUrn = "SISTORY:ID:".$pubId;
            $pubUrn = "11686/".$pubId;
            $record = self::getMetadataRecord($pubUrn);
            $result[] = $record;
        }

        // Update resumptionToken
        $query = "SELECT COUNT(*) FROM PUB_GLAVNA_TABELA {$where}";
        $count = $db->fetchOne($query);

        $request->resumptionToken->completeListSize = $count;
        $request->resumptionToken->save($request);

        return $result;
    }

    public function toXml() {
        $record = new OAIXmlElement("record");

        $headerXml = $this->headerToXml();
        $metadataXml = $this->metadataToXml();

        $n = OAIXmlOutput::$newLine;
        $record->setContentXml($headerXml.$n.$metadataXml);

        return $record->toXml();
    }

    public function headerToXml() {
        $header = new OAIXmlElement("header");

        // identifier
        $identifier = new OAIXmlElement("identifier");
        $identifier->setValue($this->identifier);

        // datestamp
        $datestamp = new OAIXmlElement("datestamp");
        $date = OAIDate::fromSqlString($this->data["DATETIME_MODIFIED"]);
        $datestamp->setValue($date);

        // [setSpec]
        // TODO: [optional element] setSpec -> Set membership for selective harvesting

        $header->appendChild($identifier);
        $header->appendChild($datestamp);

        return $header->toXml();
    }

    public function metadataToXml() {
        $metadata = new OAIXmlElement("metadata");

        $oai_dc = new OAIXmlElement("oai_dc:dc");
        $oai_dc->setValue($this->identifier);
        $oai_dc->setAttribute("xmlns:oai_dc", "http://www.openarchives.org/OAI/2.0/oai_dc/");
        $oai_dc->setAttribute("xmlns:dc", "http://purl.org/dc/elements/1.1/");
        $oai_dc->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $oai_dc->setAttribute("xsi:schemaLocation", "http://www.openarchives.org/OAI/2.0/oai_dc.xsd");
        $oai_dc->appendTo($metadata);

        //print_r($this->data["IDENTIFIER_V2"]);
        //die();

        $dcFields = $this->parseMetadataFields();

        foreach ($dcFields as $idx => $dcField){

            $tagName = $dcField["tagName"];
            $tag = new OAIXmlElement($tagName);
            foreach ($dcField as $attrName => $attrValue){
                if ($attrName == "tagName") continue;
                if ($attrName == "value") { $tag->setValue($attrValue); continue; }

                if ($attrName == "xml:lang") $attrValue = $this->getLangCode($attrValue);

                $tag->setAttribute($attrName, $attrValue);
            }

            $tag->appendTo($oai_dc);
        }

        return $metadata->toXml();
    }

    private function parseMetadataFields(){
        $result = array();

        //print_r($this->data); die();

        // dc:title
        foreach($this->data["TITLE_V2"] as $idx => $line){
            $result[] = array(
                "tagName" => "dc:title",
                //"xml:lang" => $line[1],
                "value" => OAIXmlOutput::wrapInCDATA($line[2])
            );
        }

        // SistoryAvtor -> dc:creator
        foreach($this->data["CREATOR"] as $idx => $line){
            $result[] = array(
                "tagName" => "dc:creator",
                //"xml:lang" => $line[0],
                "value" => $line["IME"] . " " . $line["PRIIMEK"]
            );
        }

        // dc:creator
        foreach($this->data["CREATOR_V2"] as $idx => $line){
            $result[] = array(
                "tagName" => "dc:creator",
                //"xml:lang" => $line[0],
                "value" => OAIXmlOutput::wrapInCDATA($line[1])
            );
        }

        // dc:subject
        foreach($this->data["SUBJECT_V2"] as $idx => $line){
            $result[] = array(
                "tagName" => "dc:subject",
                //"xml:lang" => $line[0],
                "value" => OAIXmlOutput::wrapInCDATA($line[2])
            );
        }

        // dc:description
        foreach($this->data["DESCRIPTION_V2"] as $idx => $line){
            $result[] = array(
                "tagName" => "dc:description",
                //"xml:lang" => $line[1],
                "value" => OAIXmlOutput::wrapInCDATA($line[2])
            );
        }

        // dc:publisher
        foreach($this->data["PUBLISHER"] as $idx => $line){
            $result[] = array(
                "tagName" => "dc:publisher",
                "value" => OAIXmlOutput::wrapInCDATA($line)
            );
        }

        // dc:contributor
        foreach($this->data["CONTRIBUTOR"] as $idx => $line){
            $result[] = array(
                "tagName" => "dc:contributor",
                "value" => OAIXmlOutput::wrapInCDATA($line)
            );
        }

        // dc:date
        foreach($this->data["DATE_V2"] as $idx => $line){
            if ($line[0] != 1) continue;
            $result[] = array(
                "tagName" => "dc:date",
                "value" => OAIXmlOutput::wrapInCDATA($line[2])
            );
        }

        // dc:type
        $typesDC = Publikacije_Model_Pub_Publication_Publication::getSolrTypesDC();
        foreach($this->data["TYPE_V2"] as $idx => $line){
            if ($line[0] != 2) continue;
            $result[] = array(
                "tagName" => "dc:type",
                "value" => $typesDC[$line[1]]
            );
        }

        // dc:format
        foreach($this->data["FORMAT_V2"] as $idx => $line){
            if ($line[0] != 1) continue;
            $result[] = array(
                "tagName" => "dc:format",
                "value" => OAIXmlOutput::wrapInCDATA($line[2])
            );
        }

        // dc:identifier
        $result[] = array(
            "tagName" => "dc:identifier",
            "value" => OAIXmlOutput::wrapInCDATA("http://{$_SERVER['HTTP_HOST']}/{$this->identifier}")
        );

        // dc:source
        foreach($this->data["SOURCE_V2"] as $idx => $line){
            //if ($line[0] != 3) continue;
            $r = array(
                "tagName" => "dc:source",
                "value" => OAIXmlOutput::wrapInCDATA($line[3])
            );

            // optional xml:lang
            //if ($line[1] == 1) $r["xml:lang"] = $line[2];

            $result[] = $r;
        }

        // dc:language
        foreach($this->data["LANGUAGE_V2"] as $idx => $line){
            $result[] = array(
                "tagName" => "dc:language",
                //"xml:lang" => $line[1],
                "value" => $this->getLangCode($line[1])
                //"value" => $line[1]
            );
        }

        // dc:relation
        foreach($this->data["RELATION_V2"] as $idx => $line){
            if ($line[0] != 1) continue;
            $result[] = array(
                "tagName" => "dc:relation",
                "value" => OAIXmlOutput::wrapInCDATA($line[3])
            );
        }

        // if no dc:relation, use MENU_SLO instead
        if (!isset($this->data["RELATION_V2"]) || !$this->data["RELATION_V2"] ||
            !isset($this->data["RELATION_V2"][0]))
        {
            $menuId = $this->data["MENU_SLO"];
            $menuData = Menu_Model_Nav_Menus::getOne($menuId);
            $menuTitle = $menuData["TITLE"];

            $result[] = array(
                "tagName" => "dc:relation",
                "value" => $menuTitle
            );
        }


        // dc:coverage
        foreach($this->data["COVERAGE_V2"] as $idx => $line){
            if ($line[0] != 1) continue;
            $result[] = array(
                "tagName" => "dc:coverage",
                //"xml:lang" => $line[1],
                "value" => OAIXmlOutput::wrapInCDATA($line[2])
            );
        }

        // dc:rights
        foreach($this->data["RIGHTS_V2"] as $idx => $line){
            if ($line[0] != 1) continue;
            $result[] = array(
                "tagName" => "dc:rights",
                "value" => OAIXmlOutput::wrapInCDATA($line[3])
            );
        }

        // if no dc:rights, find link from SistoryAvtorskePravice
        if (!isset($this->data["RIGHTS_V2"]) || !$this->data["RIGHTS_V2"] ||
            !isset($this->data["RIGHTS_V2"][0]))
        {
            //<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.5/si/">
            $rights = $this->data["RIGHTS"];
            if ($rights && strlen($rights) > 0){

                $matches = array();
                //preg_match('/\<a\ rel=\"license\"\ href=\"*\"\>/', $rights, $matches);
                preg_match('/\<a.*?rel\=\"license\".*?href\=\"https?:\/\/.*?\".*?\>/', $rights, $matches);

                $afterMatchSearch = 'href="';
                $endsWith = '"';
                foreach ($matches as $idx => $match) {
                    // <a rel="license" href="http://creativecommons.org/publicdomain/mark/1.0/">
                    //                  |pos1                                                  |pos2

                    $pos1 = strpos($match, $afterMatchSearch);
                    if (!$pos1) {
                        $matches[$idx] = "Neznana";
                        continue;
                    }
                    $link = substr($match, $pos1 + strlen($afterMatchSearch));
                    // $link = http://creativecommons.org/publicdomain/mark/1.0/">

                    $pos2 = strpos($link, $endsWith);
                    if (!$pos2) {
                        $matches[$idx] = "Neznana";
                        continue;
                    }

                    $link = substr($link, 0, $pos2);
                    $matches[$idx] = $link;

                }

                if ($matches && isset($matches[0])){
                    $link = $matches[0];
                    $result[] = array(
                        "tagName" => "dc:rights",
                        "value" => OAIXmlOutput::wrapInCDATA($link)
                    );
                }
            }
        }


        // Additional metadata only visible when requesting JSON version
        if ($this->setOutputAllMetadata) {

            //print_r($this->data["HTML"]);

            // dc:collection
            foreach($this->data["COLLECTION"] as $idx => $line){
                $result[] = array(
                    "tagName" => "dc:collection",
                    "value" => OAIXmlOutput::wrapInCDATA($line)
                );
            }

            // dc:audience
            foreach($this->data["AUDIENCE_V2"] as $idx => $line){
                $result[] = array(
                    "tagName" => "dc:audience",
                    "value" => OAIXmlOutput::wrapInCDATA($line[2])
                );
            }

            // dc:other
            foreach($this->data["OTHER_V2"] as $idx => $line){
                $result[] = array(
                    "tagName" => "dc:other",
                    "value" => OAIXmlOutput::wrapInCDATA($line[2])
                );
            }

            // dc:page
            if ($this->data["PAGE"]) {
                $result[] = array(
                    "tagName" => "dc:page",
                    "value" => OAIXmlOutput::wrapInCDATA($this->data["PAGE"])
                );
            }

            // dc:link
            if ($this->data["LINKS"]) {
                $result[] = array(
                    "tagName" => "dc:link",
                    "value" => OAIXmlOutput::wrapInCDATA($this->data["LINKS"])
                );
            }

            // dc:video
            if ($this->data["HTML"]) {
                $result[] = array(
                    "tagName" => "dc:video",
                    "value" => OAIXmlOutput::wrapInCDATA(htmlentities($this->data["HTML"]))
                );
            }

            // dc:movedUrl
            if ($this->data["MOVED_URL"]) {
                $result[] = array(
                    "tagName" => "dc:movedUrl",
                    "value" => OAIXmlOutput::wrapInCDATA($this->data["MOVED_URL"])
                );
            }

            // dc:archiveElements
            foreach($this->data["ARHIVSKI_EL_V2"] as $idx => $line){
                $result[] = array(
                    "tagName" => "dc:other",
                    "value" => OAIXmlOutput::wrapInCDATA($line[1])
                );
            }

            // dc:librarianElements
            foreach($this->data["KNJIZNI_EL_V2"] as $idx => $line){
                $result[] = array(
                    "tagName" => "dc:librarianElements",
                    "value" => OAIXmlOutput::wrapInCDATA($line[1])
                );
            }

            // dc:photoElements
            foreach($this->data["SLIKOVNI_EL_V2"] as $idx => $line){
                $result[] = array(
                    "tagName" => "dc:photoElements",
                    "value" => OAIXmlOutput::wrapInCDATA($line[1])
                );
            }

            // dc:audioVisualElements
            foreach($this->data["AUDIOVIS_EL_V2"] as $idx => $line){
                $result[] = array(
                    "tagName" => "dc:audioVisualElements",
                    "value" => OAIXmlOutput::wrapInCDATA($line[1])
                );
            }

        }

        return $result;
    }


    private function getLangCode($langId) {
        return Publikacije_Model_Pub_Publication_Language::getCode($langId);
    }
    private function getLangName($langId) {
        return Publikacije_Model_Pub_Publication_Language::getNaziv($langId);
    }

    */

}

