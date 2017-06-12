<?php

namespace App\Xsd\Mets\StructLinkType\SmLinkGrpAType;

/**
 * Class representing SmArcLinkAType
 *
 * The structMap arc link element <smArcLink> is of xlink:type "arc" It can be used
 * to establish a traversal link between two <div> elements as identified by
 * <smLocatorLink> elements within the same smLinkGrp element. The associated
 * xlink:from and xlink:to attributes identify the from and to sides of the arc
 * link by referencing the xlink:label attribute values on the participating
 * smLocatorLink elements.
 */
class SmArcLinkAType
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
     * @property string $arcrolePropName
     */
    private $arcrolePropName = null;

    /**
     * @property string $titlePropName
     */
    private $titlePropName = null;

    /**
     * @property string $showPropName
     */
    private $showPropName = null;

    /**
     * @property string $actuatePropName
     */
    private $actuatePropName = null;

    /**
     * @property string $fromPropName
     */
    private $fromPropName = null;

    /**
     * @property string $toPropName
     */
    private $toPropName = null;

    /**
     * ARCTYPE (string/O):The ARCTYPE attribute provides a means of specifying the
     * relationship between the <div> elements participating in the arc link, and hence
     * the purpose or role of the link. While it can be considered analogous to the
     * xlink:arcrole attribute, its type is a simple string, rather than anyURI.
     * ARCTYPE has no xlink specified meaning, and the xlink:arcrole attribute should
     * be used instead of or in addition to the ARCTYPE attribute when full xlink
     * compliance is desired with respect to specifying the role or purpose of the arc
     * link.
     *
     * @property string $ARCTYPEAttrName
     */
    private $ARCTYPEAttrName = null;

    /**
     * ADMID (IDREFS/O): Contains the ID attribute values identifying the <sourceMD>,
     * <techMD>, <digiprovMD> and/or <rightsMD> elements within the <amdSec> of the
     * METS document that contain or link to administrative metadata pertaining to
     * <smArcLink>. Typically the <smArcLink> ADMID attribute would be used to identify
     * one or more <sourceMD> and/or <techMD> elements that refine or clarify the
     * relationship between the xlink:from and xlink:to sides of the arc. For more
     * information on using METS IDREFS and IDREF type attributes for internal linking,
     * see Chapter 4 of the METS Primer.
     *
     * @property string $ADMIDAttrName
     */
    private $ADMIDAttrName = null;

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
     * Gets as arcrolePropName
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
     * Gets as showPropName
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
     * @return string
     */
    public function getActuatePropName()
    {
        return $this->actuatePropName;
    }

    /**
     * Sets a new actuatePropName
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
     * Gets as fromPropName
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
     * @param string $fromPropName
     * @return self
     */
    public function setFromPropName($fromPropName)
    {
        $this->fromPropName = $fromPropName;
        return $this;
    }

    /**
     * Gets as toPropName
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
     * @param string $toPropName
     * @return self
     */
    public function setToPropName($toPropName)
    {
        $this->toPropName = $toPropName;
        return $this;
    }

    /**
     * Gets as ARCTYPEAttrName
     *
     * ARCTYPE (string/O):The ARCTYPE attribute provides a means of specifying the
     * relationship between the <div> elements participating in the arc link, and hence
     * the purpose or role of the link. While it can be considered analogous to the
     * xlink:arcrole attribute, its type is a simple string, rather than anyURI.
     * ARCTYPE has no xlink specified meaning, and the xlink:arcrole attribute should
     * be used instead of or in addition to the ARCTYPE attribute when full xlink
     * compliance is desired with respect to specifying the role or purpose of the arc
     * link.
     *
     * @return string
     */
    public function getARCTYPEAttrName()
    {
        return $this->ARCTYPEAttrName;
    }

    /**
     * Sets a new ARCTYPEAttrName
     *
     * ARCTYPE (string/O):The ARCTYPE attribute provides a means of specifying the
     * relationship between the <div> elements participating in the arc link, and hence
     * the purpose or role of the link. While it can be considered analogous to the
     * xlink:arcrole attribute, its type is a simple string, rather than anyURI.
     * ARCTYPE has no xlink specified meaning, and the xlink:arcrole attribute should
     * be used instead of or in addition to the ARCTYPE attribute when full xlink
     * compliance is desired with respect to specifying the role or purpose of the arc
     * link.
     *
     * @param string $ARCTYPEAttrName
     * @return self
     */
    public function setARCTYPEAttrName($ARCTYPEAttrName)
    {
        $this->ARCTYPEAttrName = $ARCTYPEAttrName;
        return $this;
    }

    /**
     * Gets as ADMIDAttrName
     *
     * ADMID (IDREFS/O): Contains the ID attribute values identifying the <sourceMD>,
     * <techMD>, <digiprovMD> and/or <rightsMD> elements within the <amdSec> of the
     * METS document that contain or link to administrative metadata pertaining to
     * <smArcLink>. Typically the <smArcLink> ADMID attribute would be used to identify
     * one or more <sourceMD> and/or <techMD> elements that refine or clarify the
     * relationship between the xlink:from and xlink:to sides of the arc. For more
     * information on using METS IDREFS and IDREF type attributes for internal linking,
     * see Chapter 4 of the METS Primer.
     *
     * @return string
     */
    public function getADMIDAttrName()
    {
        return $this->ADMIDAttrName;
    }

    /**
     * Sets a new ADMIDAttrName
     *
     * ADMID (IDREFS/O): Contains the ID attribute values identifying the <sourceMD>,
     * <techMD>, <digiprovMD> and/or <rightsMD> elements within the <amdSec> of the
     * METS document that contain or link to administrative metadata pertaining to
     * <smArcLink>. Typically the <smArcLink> ADMID attribute would be used to identify
     * one or more <sourceMD> and/or <techMD> elements that refine or clarify the
     * relationship between the xlink:from and xlink:to sides of the arc. For more
     * information on using METS IDREFS and IDREF type attributes for internal linking,
     * see Chapter 4 of the METS Primer.
     *
     * @param string $ADMIDAttrName
     * @return self
     */
    public function setADMIDAttrName($ADMIDAttrName)
    {
        $this->ADMIDAttrName = $ADMIDAttrName;
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

