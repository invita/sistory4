<?php

namespace App\Xsd\Mets\StructLinkType;

/**
 * Class representing SmLinkGrpAType
 */
class SmLinkGrpAType
{

    /**
     * @property string $IDAttrName
     */
    private $IDAttrName = null;

    /**
     * ARCLINKORDER (enumerated string/O): ARCLINKORDER is used to indicate whether the
     * order of the smArcLink elements aggregated by the smLinkGrp element is
     * significant. If the order is significant, then a value of "ordered" should be
     * supplied. Value defaults to "unordered" Note that the ARLINKORDER attribute has
     * no xlink specified meaning.
     *
     * @property string $ARCLINKORDERAttrName
     */
    private $ARCLINKORDERAttrName = null;

    /**
     * @property string $typeAttrName
     */
    private $typeAttrName = null;

    /**
     * @property string $rolePropName
     */
    private $rolePropName = null;

    /**
     * @property string $titlePropName
     */
    private $titlePropName = null;

    /**
     * The structMap locator link element <smLocatorLink> is of xlink:type "locator".
     * It provides a means of identifying a <div> element that will participate in one
     * or more of the links specified by means of <smArcLink> elements within the same
     * <smLinkGrp>. The participating <div> element that is represented by the
     * <smLocatorLink> is identified by means of a URI in the associate xlink:href
     * attribute. The lowest level of this xlink:href URI value should be a fragment
     * identifier that references the ID value that identifies the relevant <div>
     * element. For example, "xlink:href='#div20'" where "div20" is the ID value that
     * identifies the pertinent <div> in the current METS document. Although not
     * required by the xlink specification, an <smLocatorLink> element will typically
     * include an xlink:label attribute in this context, as the <smArcLink> elements
     * will reference these labels to establish the from and to sides of each arc link.
     *
     * @property \App\Xsd\Mets\StructLinkType\SmLinkGrpAType\SmLocatorLinkAType[]
     * $smLocatorLinkElName
     */
    private $smLocatorLinkElName = array(
        
    );

    /**
     * @property \App\Xsd\Mets\StructLinkType\SmLinkGrpAType\SmArcLinkAType[]
     * $smArcLinkElName
     */
    private $smArcLinkElName = array(
        
    );

    /**
     * Gets as IDAttrName
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
     * @param string $IDAttrName
     * @return self
     */
    public function setIDAttrName($IDAttrName)
    {
        $this->IDAttrName = $IDAttrName;
        return $this;
    }

    /**
     * Gets as ARCLINKORDERAttrName
     *
     * ARCLINKORDER (enumerated string/O): ARCLINKORDER is used to indicate whether the
     * order of the smArcLink elements aggregated by the smLinkGrp element is
     * significant. If the order is significant, then a value of "ordered" should be
     * supplied. Value defaults to "unordered" Note that the ARLINKORDER attribute has
     * no xlink specified meaning.
     *
     * @return string
     */
    public function getARCLINKORDERAttrName()
    {
        return $this->ARCLINKORDERAttrName;
    }

    /**
     * Sets a new ARCLINKORDERAttrName
     *
     * ARCLINKORDER (enumerated string/O): ARCLINKORDER is used to indicate whether the
     * order of the smArcLink elements aggregated by the smLinkGrp element is
     * significant. If the order is significant, then a value of "ordered" should be
     * supplied. Value defaults to "unordered" Note that the ARLINKORDER attribute has
     * no xlink specified meaning.
     *
     * @param string $ARCLINKORDERAttrName
     * @return self
     */
    public function setARCLINKORDERAttrName($ARCLINKORDERAttrName)
    {
        $this->ARCLINKORDERAttrName = $ARCLINKORDERAttrName;
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
     * Adds as smLocatorLinkElName
     *
     * The structMap locator link element <smLocatorLink> is of xlink:type "locator".
     * It provides a means of identifying a <div> element that will participate in one
     * or more of the links specified by means of <smArcLink> elements within the same
     * <smLinkGrp>. The participating <div> element that is represented by the
     * <smLocatorLink> is identified by means of a URI in the associate xlink:href
     * attribute. The lowest level of this xlink:href URI value should be a fragment
     * identifier that references the ID value that identifies the relevant <div>
     * element. For example, "xlink:href='#div20'" where "div20" is the ID value that
     * identifies the pertinent <div> in the current METS document. Although not
     * required by the xlink specification, an <smLocatorLink> element will typically
     * include an xlink:label attribute in this context, as the <smArcLink> elements
     * will reference these labels to establish the from and to sides of each arc link.
     *
     * @return self
     * @param \App\Xsd\Mets\StructLinkType\SmLinkGrpAType\SmLocatorLinkAType
     * $smLocatorLinkElName
     */
    public function addToSmLocatorLinkElName(\App\Xsd\Mets\StructLinkType\SmLinkGrpAType\SmLocatorLinkAType $smLocatorLinkElName)
    {
        $this->smLocatorLinkElName[] = $smLocatorLinkElName;
        return $this;
    }

    /**
     * isset smLocatorLinkElName
     *
     * The structMap locator link element <smLocatorLink> is of xlink:type "locator".
     * It provides a means of identifying a <div> element that will participate in one
     * or more of the links specified by means of <smArcLink> elements within the same
     * <smLinkGrp>. The participating <div> element that is represented by the
     * <smLocatorLink> is identified by means of a URI in the associate xlink:href
     * attribute. The lowest level of this xlink:href URI value should be a fragment
     * identifier that references the ID value that identifies the relevant <div>
     * element. For example, "xlink:href='#div20'" where "div20" is the ID value that
     * identifies the pertinent <div> in the current METS document. Although not
     * required by the xlink specification, an <smLocatorLink> element will typically
     * include an xlink:label attribute in this context, as the <smArcLink> elements
     * will reference these labels to establish the from and to sides of each arc link.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSmLocatorLinkElName($index)
    {
        return isset($this->smLocatorLinkElName[$index]);
    }

    /**
     * unset smLocatorLinkElName
     *
     * The structMap locator link element <smLocatorLink> is of xlink:type "locator".
     * It provides a means of identifying a <div> element that will participate in one
     * or more of the links specified by means of <smArcLink> elements within the same
     * <smLinkGrp>. The participating <div> element that is represented by the
     * <smLocatorLink> is identified by means of a URI in the associate xlink:href
     * attribute. The lowest level of this xlink:href URI value should be a fragment
     * identifier that references the ID value that identifies the relevant <div>
     * element. For example, "xlink:href='#div20'" where "div20" is the ID value that
     * identifies the pertinent <div> in the current METS document. Although not
     * required by the xlink specification, an <smLocatorLink> element will typically
     * include an xlink:label attribute in this context, as the <smArcLink> elements
     * will reference these labels to establish the from and to sides of each arc link.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSmLocatorLinkElName($index)
    {
        unset($this->smLocatorLinkElName[$index]);
    }

    /**
     * Gets as smLocatorLinkElName
     *
     * The structMap locator link element <smLocatorLink> is of xlink:type "locator".
     * It provides a means of identifying a <div> element that will participate in one
     * or more of the links specified by means of <smArcLink> elements within the same
     * <smLinkGrp>. The participating <div> element that is represented by the
     * <smLocatorLink> is identified by means of a URI in the associate xlink:href
     * attribute. The lowest level of this xlink:href URI value should be a fragment
     * identifier that references the ID value that identifies the relevant <div>
     * element. For example, "xlink:href='#div20'" where "div20" is the ID value that
     * identifies the pertinent <div> in the current METS document. Although not
     * required by the xlink specification, an <smLocatorLink> element will typically
     * include an xlink:label attribute in this context, as the <smArcLink> elements
     * will reference these labels to establish the from and to sides of each arc link.
     *
     * @return \App\Xsd\Mets\StructLinkType\SmLinkGrpAType\SmLocatorLinkAType[]
     */
    public function getSmLocatorLinkElName()
    {
        return $this->smLocatorLinkElName;
    }

    /**
     * Sets a new smLocatorLinkElName
     *
     * The structMap locator link element <smLocatorLink> is of xlink:type "locator".
     * It provides a means of identifying a <div> element that will participate in one
     * or more of the links specified by means of <smArcLink> elements within the same
     * <smLinkGrp>. The participating <div> element that is represented by the
     * <smLocatorLink> is identified by means of a URI in the associate xlink:href
     * attribute. The lowest level of this xlink:href URI value should be a fragment
     * identifier that references the ID value that identifies the relevant <div>
     * element. For example, "xlink:href='#div20'" where "div20" is the ID value that
     * identifies the pertinent <div> in the current METS document. Although not
     * required by the xlink specification, an <smLocatorLink> element will typically
     * include an xlink:label attribute in this context, as the <smArcLink> elements
     * will reference these labels to establish the from and to sides of each arc link.
     *
     * @param \App\Xsd\Mets\StructLinkType\SmLinkGrpAType\SmLocatorLinkAType[]
     * $smLocatorLinkElName
     * @return self
     */
    public function setSmLocatorLinkElName(array $smLocatorLinkElName)
    {
        $this->smLocatorLinkElName = $smLocatorLinkElName;
        return $this;
    }

    /**
     * Adds as smArcLinkElName
     *
     * @return self
     * @param \App\Xsd\Mets\StructLinkType\SmLinkGrpAType\SmArcLinkAType
     * $smArcLinkElName
     */
    public function addToSmArcLinkElName(\App\Xsd\Mets\StructLinkType\SmLinkGrpAType\SmArcLinkAType $smArcLinkElName)
    {
        $this->smArcLinkElName[] = $smArcLinkElName;
        return $this;
    }

    /**
     * isset smArcLinkElName
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSmArcLinkElName($index)
    {
        return isset($this->smArcLinkElName[$index]);
    }

    /**
     * unset smArcLinkElName
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSmArcLinkElName($index)
    {
        unset($this->smArcLinkElName[$index]);
    }

    /**
     * Gets as smArcLinkElName
     *
     * @return \App\Xsd\Mets\StructLinkType\SmLinkGrpAType\SmArcLinkAType[]
     */
    public function getSmArcLinkElName()
    {
        return $this->smArcLinkElName;
    }

    /**
     * Sets a new smArcLinkElName
     *
     * @param \App\Xsd\Mets\StructLinkType\SmLinkGrpAType\SmArcLinkAType[]
     * $smArcLinkElName
     * @return self
     */
    public function setSmArcLinkElName(array $smArcLinkElName)
    {
        $this->smArcLinkElName = $smArcLinkElName;
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

