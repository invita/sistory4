<?php

namespace App\Xsd\Mets;

/**
 * Class representing BehaviorType
 *
 * behaviorType: Complex Type for Behaviors
 *  A behavior can be used to associate executable behaviors with content in the
 * METS object. A behavior element has an interface definition element that
 * represents an abstract definition of the set of behaviors represented by a
 * particular behavior. A behavior element also has an behavior mechanism which is
 * a module of executable code that implements and runs the behavior defined
 * abstractly by the interface definition.
 * XSD Type: behaviorType
 */
class BehaviorType
{

    /**
     * ID (ID/O): This attribute uniquely identifies the element within the METS
     * document, and would allow the element to be referenced unambiguously from
     * another element or document via an IDREF or an XPTR. In the case of a <behavior>
     * element that applies to a <transformFile> element, the ID value must be present
     * and would be referenced from the transformFile/@TRANSFORMBEHAVIOR attribute. For
     * more information on using ID attributes for internal and external linking see
     * Chapter 4 of the METS Primer.
     *
     * @property string $IDAttrName
     */
    private $IDAttrName = null;

    /**
     * STRUCTID (IDREFS/O): An XML IDREFS attribute used to link a <behavior> to one or
     * more <div> elements within a <structMap> in the METS document. The content to
     * which the STRUCTID points is considered input to the executable behavior
     * mechanism defined for the behavior. If the <behavior> applies to one or more
     * <div> elements, then the STRUCTID attribute must be present.
     *
     * @property string $STRUCTIDAttrName
     */
    private $STRUCTIDAttrName = null;

    /**
     * BTYPE (string/O): The behavior type provides a means of categorizing the related
     * behavior.
     *
     * @property string $BTYPEAttrName
     */
    private $BTYPEAttrName = null;

    /**
     * CREATED (dateTime/O): The dateTime of creation for the behavior.
     *
     * @property \DateTime $CREATEDAttrName
     */
    private $CREATEDAttrName = null;

    /**
     * LABEL (string/O): A text description of the behavior.
     *
     * @property string $LABELAttrName
     */
    private $LABELAttrName = null;

    /**
     * GROUPID (string/O): An identifier that establishes a correspondence between the
     * given behavior and other behaviors, typically used to facilitate versions of
     * behaviors.
     *
     * @property string $GROUPIDAttrName
     */
    private $GROUPIDAttrName = null;

    /**
     * ADMID (IDREFS/O): An optional attribute listing the XML ID values of
     * administrative metadata sections within the METS document pertaining to this
     * behavior.
     *
     * @property string $ADMIDAttrName
     */
    private $ADMIDAttrName = null;

    /**
     * The interface definition <interfaceDef> element contains a pointer to an
     * abstract definition of a single behavior or a set of related behaviors that are
     * associated with the content of a METS object. The interface definition object to
     * which the <interfaceDef> element points using xlink:href could be another
     * digital object, or some other entity, such as a text file which describes the
     * interface or a Web Services Description Language (WSDL) file. Ideally, an
     * interface definition object contains metadata that describes a set of behaviors
     * or methods. It may also contain files that describe the intended usage of the
     * behaviors, and possibly files that represent different expressions of the
     * interface definition.
     *
     * @property \App\Xsd\Mets\ObjectType $interfaceDefElName
     */
    private $interfaceDefElName = null;

    /**
     * A mechanism element <mechanism> contains a pointer to an executable code module
     * that implements a set of behaviors defined by an interface definition. The
     * <mechanism> element will be a pointer to another object (a mechanism object). A
     * mechanism object could be another METS object, or some other entity (e.g., a
     * WSDL file). A mechanism object should contain executable code, pointers to
     * executable code, or specifications for binding to network services (e.g., web
     * services).
     *
     * @property \App\Xsd\Mets\ObjectType $mechanismElName
     */
    private $mechanismElName = null;

    /**
     * Gets as IDAttrName
     *
     * ID (ID/O): This attribute uniquely identifies the element within the METS
     * document, and would allow the element to be referenced unambiguously from
     * another element or document via an IDREF or an XPTR. In the case of a <behavior>
     * element that applies to a <transformFile> element, the ID value must be present
     * and would be referenced from the transformFile/@TRANSFORMBEHAVIOR attribute. For
     * more information on using ID attributes for internal and external linking see
     * Chapter 4 of the METS Primer.
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
     * another element or document via an IDREF or an XPTR. In the case of a <behavior>
     * element that applies to a <transformFile> element, the ID value must be present
     * and would be referenced from the transformFile/@TRANSFORMBEHAVIOR attribute. For
     * more information on using ID attributes for internal and external linking see
     * Chapter 4 of the METS Primer.
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
     * Gets as STRUCTIDAttrName
     *
     * STRUCTID (IDREFS/O): An XML IDREFS attribute used to link a <behavior> to one or
     * more <div> elements within a <structMap> in the METS document. The content to
     * which the STRUCTID points is considered input to the executable behavior
     * mechanism defined for the behavior. If the <behavior> applies to one or more
     * <div> elements, then the STRUCTID attribute must be present.
     *
     * @return string
     */
    public function getSTRUCTIDAttrName()
    {
        return $this->STRUCTIDAttrName;
    }

    /**
     * Sets a new STRUCTIDAttrName
     *
     * STRUCTID (IDREFS/O): An XML IDREFS attribute used to link a <behavior> to one or
     * more <div> elements within a <structMap> in the METS document. The content to
     * which the STRUCTID points is considered input to the executable behavior
     * mechanism defined for the behavior. If the <behavior> applies to one or more
     * <div> elements, then the STRUCTID attribute must be present.
     *
     * @param string $STRUCTIDAttrName
     * @return self
     */
    public function setSTRUCTIDAttrName($STRUCTIDAttrName)
    {
        $this->STRUCTIDAttrName = $STRUCTIDAttrName;
        return $this;
    }

    /**
     * Gets as BTYPEAttrName
     *
     * BTYPE (string/O): The behavior type provides a means of categorizing the related
     * behavior.
     *
     * @return string
     */
    public function getBTYPEAttrName()
    {
        return $this->BTYPEAttrName;
    }

    /**
     * Sets a new BTYPEAttrName
     *
     * BTYPE (string/O): The behavior type provides a means of categorizing the related
     * behavior.
     *
     * @param string $BTYPEAttrName
     * @return self
     */
    public function setBTYPEAttrName($BTYPEAttrName)
    {
        $this->BTYPEAttrName = $BTYPEAttrName;
        return $this;
    }

    /**
     * Gets as CREATEDAttrName
     *
     * CREATED (dateTime/O): The dateTime of creation for the behavior.
     *
     * @return \DateTime
     */
    public function getCREATEDAttrName()
    {
        return $this->CREATEDAttrName;
    }

    /**
     * Sets a new CREATEDAttrName
     *
     * CREATED (dateTime/O): The dateTime of creation for the behavior.
     *
     * @param \DateTime $CREATEDAttrName
     * @return self
     */
    public function setCREATEDAttrName(\DateTime $CREATEDAttrName)
    {
        $this->CREATEDAttrName = $CREATEDAttrName;
        return $this;
    }

    /**
     * Gets as LABELAttrName
     *
     * LABEL (string/O): A text description of the behavior.
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
     * LABEL (string/O): A text description of the behavior.
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
     * Gets as GROUPIDAttrName
     *
     * GROUPID (string/O): An identifier that establishes a correspondence between the
     * given behavior and other behaviors, typically used to facilitate versions of
     * behaviors.
     *
     * @return string
     */
    public function getGROUPIDAttrName()
    {
        return $this->GROUPIDAttrName;
    }

    /**
     * Sets a new GROUPIDAttrName
     *
     * GROUPID (string/O): An identifier that establishes a correspondence between the
     * given behavior and other behaviors, typically used to facilitate versions of
     * behaviors.
     *
     * @param string $GROUPIDAttrName
     * @return self
     */
    public function setGROUPIDAttrName($GROUPIDAttrName)
    {
        $this->GROUPIDAttrName = $GROUPIDAttrName;
        return $this;
    }

    /**
     * Gets as ADMIDAttrName
     *
     * ADMID (IDREFS/O): An optional attribute listing the XML ID values of
     * administrative metadata sections within the METS document pertaining to this
     * behavior.
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
     * ADMID (IDREFS/O): An optional attribute listing the XML ID values of
     * administrative metadata sections within the METS document pertaining to this
     * behavior.
     *
     * @param string $ADMIDAttrName
     * @return self
     */
    public function setADMIDAttrName($ADMIDAttrName)
    {
        $this->ADMIDAttrName = $ADMIDAttrName;
        return $this;
    }

    /**
     * Gets as interfaceDefElName
     *
     * The interface definition <interfaceDef> element contains a pointer to an
     * abstract definition of a single behavior or a set of related behaviors that are
     * associated with the content of a METS object. The interface definition object to
     * which the <interfaceDef> element points using xlink:href could be another
     * digital object, or some other entity, such as a text file which describes the
     * interface or a Web Services Description Language (WSDL) file. Ideally, an
     * interface definition object contains metadata that describes a set of behaviors
     * or methods. It may also contain files that describe the intended usage of the
     * behaviors, and possibly files that represent different expressions of the
     * interface definition.
     *
     * @return \App\Xsd\Mets\ObjectType
     */
    public function getInterfaceDefElName()
    {
        return $this->interfaceDefElName;
    }

    /**
     * Sets a new interfaceDefElName
     *
     * The interface definition <interfaceDef> element contains a pointer to an
     * abstract definition of a single behavior or a set of related behaviors that are
     * associated with the content of a METS object. The interface definition object to
     * which the <interfaceDef> element points using xlink:href could be another
     * digital object, or some other entity, such as a text file which describes the
     * interface or a Web Services Description Language (WSDL) file. Ideally, an
     * interface definition object contains metadata that describes a set of behaviors
     * or methods. It may also contain files that describe the intended usage of the
     * behaviors, and possibly files that represent different expressions of the
     * interface definition.
     *
     * @param \App\Xsd\Mets\ObjectType $interfaceDefElName
     * @return self
     */
    public function setInterfaceDefElName(\App\Xsd\Mets\ObjectType $interfaceDefElName)
    {
        $this->interfaceDefElName = $interfaceDefElName;
        return $this;
    }

    /**
     * Gets as mechanismElName
     *
     * A mechanism element <mechanism> contains a pointer to an executable code module
     * that implements a set of behaviors defined by an interface definition. The
     * <mechanism> element will be a pointer to another object (a mechanism object). A
     * mechanism object could be another METS object, or some other entity (e.g., a
     * WSDL file). A mechanism object should contain executable code, pointers to
     * executable code, or specifications for binding to network services (e.g., web
     * services).
     *
     * @return \App\Xsd\Mets\ObjectType
     */
    public function getMechanismElName()
    {
        return $this->mechanismElName;
    }

    /**
     * Sets a new mechanismElName
     *
     * A mechanism element <mechanism> contains a pointer to an executable code module
     * that implements a set of behaviors defined by an interface definition. The
     * <mechanism> element will be a pointer to another object (a mechanism object). A
     * mechanism object could be another METS object, or some other entity (e.g., a
     * WSDL file). A mechanism object should contain executable code, pointers to
     * executable code, or specifications for binding to network services (e.g., web
     * services).
     *
     * @param \App\Xsd\Mets\ObjectType $mechanismElName
     * @return self
     */
    public function setMechanismElName(\App\Xsd\Mets\ObjectType $mechanismElName)
    {
        $this->mechanismElName = $mechanismElName;
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

