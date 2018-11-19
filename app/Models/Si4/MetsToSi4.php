<?php
namespace App\Models\Si4;

use App\Models\MappingGroup;
use App\Models\Si4Field;
use Mockery\CountValidator\Exception;

class MetsToSi4
{
    private $metsXmlString;
    private $metsXmlDoc;
    private $fieldDefs;
    private $mappingGroups;
    private $result;
    public function __construct($metsXmlString) {
        $this->metsXmlString = $metsXmlString;
    }

    public function run() {
        $this->prepare();
        $result = $this->doMapping();

        return $result;
    }

    private function prepare() {
        $this->metsXmlDoc = simplexml_load_string($this->metsXmlString);
        $this->fieldDefs = Si4Field::getSi4Fields();
        $this->mappingGroups = MappingGroup::getMappingGroups();
        $this->result = [];
    }

    private function doMapping() {

        foreach ($this->mappingGroups as $mgName => $mappingGroup) {

            if ($mgName === "Mods") continue;

            $mappingFields = $mappingGroup["fields"];
            $base_xpath = $mappingGroup["base_xpath"];
            $baseXmlElements = $this->metsXmlDoc->xpath($base_xpath);

            foreach ($baseXmlElements as $baseXmlElement) {

                foreach ($mappingFields as $mappingField) {
                    $source_xpath = $mappingField["source_xpath"];
                    $value_xpath = $mappingField["value_xpath"];
                    $lang_xpath = $mappingField["lang_xpath"];
                    $target_field = $mappingField["target_field"];

                    try {

                        $fieldSourceElements = $baseXmlElement->xpath($source_xpath);
                        foreach ($fieldSourceElements as $fieldSourceElement) {

                            try {
                                if (!$value_xpath || $value_xpath === "/") {
                                    $value = (string)$fieldSourceElement;
                                } else {
                                    $valueArray = $fieldSourceElement->xpath($value_xpath);
                                    $value = isset($valueArray[0]) ? (string)$valueArray[0] : "";
                                }

                                if ($lang_xpath) {
                                    $langArray = $fieldSourceElement->xpath($lang_xpath);
                                    $lang = isset($langArray[0]) ? (string)$langArray[0] : "";
                                } else {
                                    $lang = "";
                                }

                                //echo $target_field." -> '" .$value. "', lang: '" .$lang. "'\n";

                                if (!isset($result[$target_field])) $result[$target_field] = [];
                                $fieldResult = [];
                                $fieldResult["value"] = $value;
                                if ($lang) $fieldResult["lang"] = $lang;

                                $result[$target_field][] = $fieldResult;

                            } catch (\Exception $fieldSourceE) {
                                /*
                                echo "fieldSourceE\n";
                                var_dump($fieldSourceElement);
                                echo $fieldSourceE->__toString();
                                */
                            }
                        }

                    } catch (\Exception $mappingFieldE) {
                        /*
                        echo "mappingField Exception\n";
                        var_dump($mappingField);
                        echo $mappingFieldE->__toString();
                        */
                    }
                }
            }

        }

        return $result;
    }
}