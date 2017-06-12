<?php

namespace App\Xsd\Mets\StructLinkType;

/**
 * Class representing SmLinkAType
 */
class SmLinkAType
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
     * xlink:arcrole - the role of the link, as per the xlink specification. See
     * http://www.w3.org/TR/xlink/
     *
     * @property string $arcrolePropName
     */
    private $arcrolePropName = null;

    /**
     * xlink:title - a title for the link (if needed), as per the xlink specification.
     * See http://www.w3.org/TR/xlink/
     *
     * @property string $titlePropName
     */
    private $titlePropName = null;

    /**
     * xlink:show - see the xlink specification at http://www.w3.org/TR/xlink/
     *
     * @property string $showPropName
     */
    private $showPropName = null;

    /**
     * xlink:actuate - see the xlink specification at http://www.w3.org/TR/xlink/
     *
     * @property string $actuatePropName
     */
    private $actuatePropName = null;

    /**
     * xlink:to - the value of the label for the element in the structMap you are
     * linking to.
     *
     * @property string $toPropName
     */
    private $toPropName = null;

    /**
     * xlink:from - the value of the label for the element in the structMap you are
     * linking from.
     *
     * @property string $fromPropName
     */
    private $fromPropName = null;

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
     * Gets as arcrolePropName
     *
     * xlink:arcrole - the role of the link, as per the xlink specification. See
     * http://www.w3.org/TR/xlink/
     *
     * @return string
     */
    public function getArcrolePropName()
    {
        return $this->arcrolePropName;
    }

    /**
     * Sets a new arcrolePropName
     *
     * xlink:arcrole - the role of the link, as per the xlink specification. See
     * http://www.w3.org/TR/xlink/
     *
     * @param string $arcrolePropName
     * @return self
     */
    public function setArcrolePropName($arcrolePropName)
    {
        $this->arcrolePropName = $arcrolePropName;
        return $this;
    }

    /**
     * Gets as titlePropName
     *
     * xlink:title - a title for the link (if needed), as per the xlink specification.
     * See http://www.w3.org/TR/xlink/
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
     * xlink:title - a title for the link (if needed), as per the xlink specification.
     * See http://www.w3.org/TR/xlink/
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
     * Gets as showPropName
     *
     * xlink:show - see the xlink specification at http://www.w3.org/TR/xlink/
     *
     * @return string
     */
    public function getShowPropName()
    {
        return $this->showPropName;
    }

    /**
     * Sets a new showPropName
     *
     * xlink:show - see the xlink specification at http://www.w3.org/TR/xlink/
     *
     * @param string $showPropName
     * @return self
     */
    public function setShowPropName($showPropName)
    {
        $this->showPropName = $showPropName;
        return $this;
    }

    /**
     * Gets as actuatePropName
     *
     * xlink:actuate - see the xlink specification at http://www.w3.org/TR/xlink/
     *
     * @return string
     */
    public function getActuatePropName()
    {
        return $this->actuatePropName;
    }

    /**
     * Sets a new actuatePropName
     *
     * xlink:actuate - see the xlink specification at http://www.w3.org/TR/xlink/
     *
     * @param string $actuatePropName
     * @return self
     */
    public function setActuatePropName($actuatePropName)
    {
        $this->actuatePropName = $actuatePropName;
        return $this;
    }

    /**
     * Gets as toPropName
     *
     * xlink:to - the value of the label for the element in the structMap you are
     * linking to.
     *
     * @return string
     */
    public function getToPropName()
    {
        return $this->toPropName;
    }

    /**
     * Sets a new toPropName
     *
     * xlink:to - the value of the label for the element in the structMap you are
     * linking to.
     *
     * @param string $toPropName
     * @return self
     */
    public function setToPropName($toPropName)
    {
        $this->toPropName = $toPropName;
        return $this;
    }

    /**
     * Gets as fromPropName
     *
     * xlink:from - the value of the label for the element in the structMap you are
     * linking from.
     *
     * @return string
     */
    public function getFromPropName()
    {
        return $this->fromPropName;
    }

    /**
     * Sets a new fromPropName
     *
     * xlink:from - the value of the label for the element in the structMap you are
     * linking from.
     *
     * @param string $fromPropName
     * @return self
     */
    public function setFromPropName($fromPropName)
    {
        $this->fromPropName = $fromPropName;
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

