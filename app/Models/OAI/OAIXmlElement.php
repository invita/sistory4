<?php
namespace App\Models\OAI;

class OAIXmlElement {

    public $tagName;
    public $attrs = array();
    public $value = "";

    private $children = array();

    public function __construct($tagName) {
        $this->tagName = $tagName;
    }

    public function setAttribute($attrName, $attrValue) {
        $this->attrs[$attrName] = $attrValue;
    }
    public function setValue($value) {
        $this->value = $value;
    }
    public function setContentXml($content) {
        $n = OAIXmlOutput::$newLine;
        $t = OAIXmlOutput::$tabStr;
        $contentExplode = explode($n, $content);
        foreach ($contentExplode as $i => $cl) $contentExplode[$i] = $t.$cl;
        $content = join($n, $contentExplode);

        $this->setValue(OAIXmlOutput::$newLine.$content.OAIXmlOutput::$newLine);
    }

    public function appendChild($childEl){
        if (!$childEl instanceof self) return;
        $this->children[] = $childEl;
    }

    public function appendTo($parentEl){
        if (!$parentEl instanceof self) return;
        $parentEl->appendChild($this);
    }

    public function forcePath($path) {
        $result = $this;
        if (!$path) return $result;

        $pathComps = explode("/", $path);
        foreach ($pathComps as $pathComp) {
            $compFound = false;
            foreach ($result->children as $existingChild) {
                if (!$existingChild instanceof self) continue;
                if ($existingChild->tagName == $pathComp) {
                    $result = $existingChild;
                    $compFound = true;
                    break;
                }
            }

            if (!$compFound) {
                $compChild = new OAIXmlElement($pathComp);
                $compChild->appendTo($result);
                $result = $compChild;
            }
        }
        return $result;
    }

    public function toXml() {
        $xml = "";
        if (count($this->children)){
            $n = OAIXmlOutput::$newLine;
            $t = OAIXmlOutput::$tabStr;
            $xml .= $this->_xmlStart().$n;
            foreach ($this->children as $child){
                $childXml = $child->toXml();
                $childLineExplode = explode($n, $childXml);
                foreach ($childLineExplode as $i => $cl) $childLineExplode[$i] = $t.$cl;
                $childXml = join($n, $childLineExplode);
                $xml .= $childXml.$n;
            }
            $xml .= $this->_xmlEnd();
        } else {
            $xml .= $this->_xmlStart().$this->value.$this->_xmlEnd();
        }
        return $xml;
    }

    private function _xmlStart() {
        $attrsStr = "";
        foreach ($this->attrs as $attrName => $attrValue) {
            $attrsStr .= ' '.$attrName.'="'.$attrValue.'"';
        }
        $xml = '<'.$this->tagName.$attrsStr.'>';
        return $xml;
    }
    private function _xmlEnd() {
        $xml = '</'.$this->tagName.'>';
        return $xml;
    }
}
