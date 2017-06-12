<?php

namespace App\Xsd\Mets\StructLinkType\SmLinkGrpAType;

/**
 * Class representing SmLocatorLinkAType
 */
class SmLocatorLinkAType
{

    /**
     * ID (ID/O): This attribute uniquely identifies the element within the METS
     * document, and would allow the element to be referenced unambiguously from
     * another element or document via an IDREF or an XPTR. For more information on
     * using ID attributes for internal and external linking see Chapter 4 of the METS
     * Primer.
     *
     * @property string $IDAttrName
     */
    private $IDAttrName = null;

    /**
     * @property string $typeAttrName
     */
    private $typeAttrName = null;

    /**
     * @property string $hrefPropName
     */
    private $hrefPropName = null;

    /**
     * @property string $rolePropName
     */
    private $rolePropName = null;

    /**
     * @property string $titlePropName
     */
    private $titlePropName = null;

    /**
     * @property string $labelPropName
     */
    private $labelPropName = null;

    /**
     * Gets as IDAttrName
     *
     * ID (ID/O): This attribute uniquely identifies the element within the METS
     * document, and would allow the element to be referenced unambiguously from
     * another element or document via an IDREF or an XPTR. For more information on
     * using ID attributes for internal and external linking see Chapter 4 of the METS
     * Primer.
     *
     * @return string
     */
    public function getIDAttrName()
    {
        return $this->IDAttrName;
    }

    /**
     * Sets a new IDAttrName
     *
     * ID (ID/O): This attribute uniquely identifies the element within the METS
     * document, and would allow the element to be referenced unambiguously from
     * another element or document via an IDREF or an XPTR. For more information on
     * using ID attributes for internal and external linking see Chapter 4 of the METS
     * Primer.
     *
     * @param string $IDAttrName
     * @return self
     */
    public function setIDAttrName($IDAttrName)
    {
        $this->IDAttrName = $IDAttrName;
        return $this;
    }

    /**
     * Gets as typeAttrName
     *
     * @return string
     */
    public function getTypeAttrName()
    {
        return $this->typeAttrName;
    }

    /**
     * Sets a new typeAttrName
     *
     * @param string $typeAttrName
     * @return self
     */
    public function setTypeAttrName($typeAttrName)
    {
        $this->typeAttrName = $typeAttrName;
        return $this;
    }

    /**
     * Gets as hrefPropName
     *
     * @return string
     */
    public function getHrefPropName()
    {
        return $this->hrefPropName;
    }

    /**
     * Sets a new hrefPropName
     *
     * @param string $hrefPropName
     * @return self
     */
    public function setHrefPropName($hrefPropName)
    {
        $this->hrefPropName = $hrefPropName;
        return $this;
    }

    /**
     * Gets as rolePropName
     *
     * @return string
     */
    public function getRolePropName()
    {
        return $this->rolePropName;
    }

    /**
     * Sets a new rolePropName
     *
     * @param string $rolePropName
     * @return self
     */
    public function setRolePropName($rolePropName)
    {
        $this->rolePropName = $rolePropName;
        return $this;
    }

    /**
     * Gets as titlePropName
     *
     * @return string
     */
    public function getTitlePropName()
    {
        return $this->titlePropName;
    }

    /**
     * Sets a new titlePropName
     *
     * @param string $titlePropName
     * @return self
     */
    public function setTitlePropName($titlePropName)
    {
        $this->titlePropName = $titlePropName;
        return $this;
    }

    /**
     * Gets as labelPropName
     *
     * @return string
     */
    public function getLabelPropName()
    {
        return $this->labelPropName;
    }

    /**
     * Sets a new labelPropName
     *
     * @param string $labelPropName
     * @return self
     */
    public function setLabelPropName($labelPropName)
    {
        $this->labelPropName = $labelPropName;
        return $this;
    }

    public function toArray()
    {
        $handleItem = function($item){
            if(is_object($item)){
                if(method_exists($item, "toArray")){
                    return $item->toArray();
                } elseif($item instanceOf \DateTime){
                    return $item->format('Y-m-d\TH:i:s\Z');
                }
                return (string)$item;
            }

            return $item;
        };

        $array = [];
        $methods = get_class_methods($this);
        foreach ($methods as $method){
            if(substr($method, 0, 3) == "get"){
                $var = $this->{$method}();
                $key = substr($method, 3);
                if(is_array($var)){
                    $array[$key] = [];
                    foreach ($var as $k => $itm){
                        $array[$key][$k] = $handleItem($itm);
                    }
                } else {
                    $array[$key] = $handleItem($var);
                }
            }
        }

        return $array;
    }


}

