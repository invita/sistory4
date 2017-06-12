<?php

namespace App\Xsd\Mets;

/**
 * Class representing StructMapType
 *
 * structMapType: Complex Type for Structural Maps
 *  The structural map (structMap) outlines a hierarchical structure for the
 * original object being encoded, using a series of nested div elements.
 * XSD Type: structMapType
 */
class StructMapType
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
     * TYPE (string/O): Identifies the type of structure represented by the
     * <structMap>. For example, a <structMap> that represented a purely logical or
     * intellectual structure could be assigned a TYPE value of “logical” whereas a
     * <structMap> that represented a purely physical structure could be assigned a
     * TYPE value of “physical”. However, the METS schema neither defines nor
     * requires a common vocabulary for this attribute. A METS profile, however, may
     * well constrain the values for the <structMap> TYPE.
     *
     * @property string $TYPEAttrName
     */
    private $TYPEAttrName = null;

    /**
     * LABEL (string/O): Describes the <structMap> to viewers of the METS document.
     * This would be useful primarily where more than one <structMap> is provided for a
     * single object. A descriptive LABEL value, in that case, could clarify to users
     * the purpose of each of the available structMaps.
     *
     * @property string $LABELAttrName
     */
    private $LABELAttrName = null;

    /**
     * The structural divisions of the hierarchical organization provided by a
     * <structMap> are represented by division <div> elements, which can be nested to
     * any depth. Each <div> element can represent either an intellectual (logical)
     * division or a physical division. Every <div> node in the structural map
     * hierarchy may be connected (via subsidiary <mptr> or <fptr> elements) to content
     * files which represent that div's portion of the whole document.
     *
     * @property \App\Xsd\Mets\DivType $divElName
     */
    private $divElName = null;

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
     * Gets as TYPEAttrName
     *
     * TYPE (string/O): Identifies the type of structure represented by the
     * <structMap>. For example, a <structMap> that represented a purely logical or
     * intellectual structure could be assigned a TYPE value of “logical” whereas a
     * <structMap> that represented a purely physical structure could be assigned a
     * TYPE value of “physical”. However, the METS schema neither defines nor
     * requires a common vocabulary for this attribute. A METS profile, however, may
     * well constrain the values for the <structMap> TYPE.
     *
     * @return string
     */
    public function getTYPEAttrName()
    {
        return $this->TYPEAttrName;
    }

    /**
     * Sets a new TYPEAttrName
     *
     * TYPE (string/O): Identifies the type of structure represented by the
     * <structMap>. For example, a <structMap> that represented a purely logical or
     * intellectual structure could be assigned a TYPE value of “logical” whereas a
     * <structMap> that represented a purely physical structure could be assigned a
     * TYPE value of “physical”. However, the METS schema neither defines nor
     * requires a common vocabulary for this attribute. A METS profile, however, may
     * well constrain the values for the <structMap> TYPE.
     *
     * @param string $TYPEAttrName
     * @return self
     */
    public function setTYPEAttrName($TYPEAttrName)
    {
        $this->TYPEAttrName = $TYPEAttrName;
        return $this;
    }

    /**
     * Gets as LABELAttrName
     *
     * LABEL (string/O): Describes the <structMap> to viewers of the METS document.
     * This would be useful primarily where more than one <structMap> is provided for a
     * single object. A descriptive LABEL value, in that case, could clarify to users
     * the purpose of each of the available structMaps.
     *
     * @return string
     */
    public function getLABELAttrName()
    {
        return $this->LABELAttrName;
    }

    /**
     * Sets a new LABELAttrName
     *
     * LABEL (string/O): Describes the <structMap> to viewers of the METS document.
     * This would be useful primarily where more than one <structMap> is provided for a
     * single object. A descriptive LABEL value, in that case, could clarify to users
     * the purpose of each of the available structMaps.
     *
     * @param string $LABELAttrName
     * @return self
     */
    public function setLABELAttrName($LABELAttrName)
    {
        $this->LABELAttrName = $LABELAttrName;
        return $this;
    }

    /**
     * Gets as divElName
     *
     * The structural divisions of the hierarchical organization provided by a
     * <structMap> are represented by division <div> elements, which can be nested to
     * any depth. Each <div> element can represent either an intellectual (logical)
     * division or a physical division. Every <div> node in the structural map
     * hierarchy may be connected (via subsidiary <mptr> or <fptr> elements) to content
     * files which represent that div's portion of the whole document.
     *
     * @return \App\Xsd\Mets\DivType
     */
    public function getDivElName()
    {
        return $this->divElName;
    }

    /**
     * Sets a new divElName
     *
     * The structural divisions of the hierarchical organization provided by a
     * <structMap> are represented by division <div> elements, which can be nested to
     * any depth. Each <div> element can represent either an intellectual (logical)
     * division or a physical division. Every <div> node in the structural map
     * hierarchy may be connected (via subsidiary <mptr> or <fptr> elements) to content
     * files which represent that div's portion of the whole document.
     *
     * @param \App\Xsd\Mets\DivType $divElName
     * @return self
     */
    public function setDivElName(\App\Xsd\Mets\DivType $divElName)
    {
        $this->divElName = $divElName;
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

