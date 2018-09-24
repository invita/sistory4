<?php
namespace App\Models\OAI;

class OAIXmlOutput {

    public static $tabStr = "\t";
    public static $newLine = "\n";

    private $tabCursor = 0;

    private $xml;

    public static function wrapInCDATA($dataStr) {
        // SISTORY:ID:1540, not UTF-8 !?


        /*
        $suspChars = array('<', '>', '"', '&');
        if ($dataStr) {
            foreach ($suspChars as $sc){
                if (strpos($dataStr, $sc) !== false){
                    $dataStr = "<![CDATA[" .$dataStr. "]]>";
                    break;
                }
            }
        }
        */
        //$encodingOk = mb_check_encoding($dataStr, "UTF-8");
        //return $encodingOk ? "ok" : "fail";
        //print_r($encodingOk); die();
        //if (!$encodingOk) $dataStr = "";

        // Remove UTF-8 invisible characters
        $dataStr = $string = preg_replace('/[^\P{C}\n ]+/u', '', $dataStr);

        $dataStr = "<![CDATA[" .$dataStr. "]]>";
        return $dataStr;
    }

    public function __construct(){
    }

    public function startOutput() {
        $xmlVersion = "1.0";
        $encoding = "UTF-8";
        $this->outputLine('<?xml version="'.$xmlVersion.'" encoding="'.$encoding.'"?>');
        $this->OAI_PMH_headerStart();
    }

    public function endOutput() {
        $this->OAI_PMH_headerEnd();
    }

    public function outputLine($string) {
        $this->xml .= $this->tabs().$string.self::$newLine;
    }

    public function outputXml($string) {
        $lines = explode(self::$newLine, $string);
        foreach ($lines as $i => $line) $lines[$i] = $this->tabs().$line;
        $string = join(self::$newLine, $lines).self::$newLine;
        $this->xml .= $string;
    }

    public function render() {
        header("Content-Type: text/xml");
        //header("Content-Type: text/plain");
        echo $this->xml;
    }

    public function renderJson() {
        header("Content-Type: application/json");
        $json = $this->xmlToJsonObject($this->xml);
        echo $json;
    }

    public function xmlToJsonObject($xml) {

        $regexTagOpen = "/(<[a-z\\_]+?)(:)([a-z\\_]+?[>\\s])/i";
        $regexTagClose = "/(<\\/[a-z\\_]+?)(:)([a-z\\_]+?[>\\s])/i";
        $cData = "/(<!\\[CDATA\\[)([\\s\\S]*?)(\\]\\]>)/";

        $xml = preg_replace($regexTagOpen, "$1_$3", $xml);
        $xml = preg_replace($regexTagClose, "$1_$3", $xml);
        $xml = preg_replace($cData, "$2", $xml);

        $xmlObject = simplexml_load_string($xml);
        return json_encode($xmlObject);
    }


    private function OAI_PMH_headerStart(){
        $xmlns = "http://www.openarchives.org/OAI/2.0/";
        $xsi = "http://www.w3.org/2001/XMLSchema-instance";
        $schemaLocation = "http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd";
        $this->outputTagStart('<OAI-PMH xmlns="'.$xmlns.'" xmlns:xsi="'.$xsi.'" xsi:schemaLocation="'.$schemaLocation.'">');
    }

    private function OAI_PMH_headerEnd(){
        $this->outputTagEnd('</OAI-PMH>');
    }

    private function tabs() {
        return str_repeat(self::$tabStr, $this->tabCursor);
    }

    private function outputTagStart($string){
        $this->xml .= $this->tabs().$string.self::$newLine;
        $this->tabCursor += 1;
    }

    private function outputTagEnd($string){
        $this->tabCursor -= 1;
        $this->xml .= $this->tabs().$string.self::$newLine;
    }

    public function getXml() {
        return $this->xml;
    }

}
