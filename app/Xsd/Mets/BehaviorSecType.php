<?php

namespace App\Xsd\Mets;

/**
 * Class representing BehaviorSecType
 *
 * behaviorSecType: Complex Type for Behavior Sections
 *  Behaviors are executable code which can be associated with parts of a METS
 * object. The behaviorSec element is used to group individual behaviors within a
 * hierarchical structure. Such grouping can be useful to organize families of
 * behaviors together or to indicate other relationships between particular
 * behaviors.
 * XSD Type: behaviorSecType
 */
class BehaviorSecType
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
     * CREATED (dateTime/O): Specifies the date and time of creation for the
     * <behaviorSec>
     *
     * @property \DateTime $CREATEDAttrName
     */
    private $CREATEDAttrName = null;

    /**
     * LABEL (string/O): A text description of the behavior section.
     *
     * @property string $LABELAttrName
     */
    private $LABELAttrName = null;

    /**
     * @property \App\Xsd\Mets\BehaviorSecType[] $behaviorSecElName
     */
    private $behaviorSecElName = array(
        
    );

    /**
     * A behavior element <behavior> can be used to associate executable behaviors with
     * content in the METS document. This element has an interface definition
     * <interfaceDef> element that represents an abstract definition of a set of
     * behaviors represented by a particular behavior. A <behavior> element also has a
     * behavior mechanism <mechanism> element, a module of executable code that
     * implements and runs the behavior defined abstractly by the interface definition.
     *
     * @property \App\Xsd\Mets\BehaviorType[] $behaviorElName
     */
    private $behaviorElName = array(
        
    );

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
     * Gets as CREATEDAttrName
     *
     * CREATED (dateTime/O): Specifies the date and time of creation for the
     * <behaviorSec>
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
     * CREATED (dateTime/O): Specifies the date and time of creation for the
     * <behaviorSec>
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
     * LABEL (string/O): A text description of the behavior section.
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
     * LABEL (string/O): A text description of the behavior section.
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
     * Adds as behaviorSecElName
     *
     * @return self
     * @param \App\Xsd\Mets\BehaviorSecType $behaviorSecElName
     */
    public function addToBehaviorSecElName(\App\Xsd\Mets\BehaviorSecType $behaviorSecElName)
    {
        $this->behaviorSecElName[] = $behaviorSecElName;
        return $this;
    }

    /**
     * isset behaviorSecElName
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBehaviorSecElName($index)
    {
        return isset($this->behaviorSecElName[$index]);
    }

    /**
     * unset behaviorSecElName
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBehaviorSecElName($index)
    {
        unset($this->behaviorSecElName[$index]);
    }

    /**
     * Gets as behaviorSecElName
     *
     * @return \App\Xsd\Mets\BehaviorSecType[]
     */
    public function getBehaviorSecElName()
    {
        return $this->behaviorSecElName;
    }

    /**
     * Sets a new behaviorSecElName
     *
     * @param \App\Xsd\Mets\BehaviorSecType[] $behaviorSecElName
     * @return self
     */
    public function setBehaviorSecElName(array $behaviorSecElName)
    {
        $this->behaviorSecElName = $behaviorSecElName;
        return $this;
    }

    /**
     * Adds as behaviorElName
     *
     * A behavior element <behavior> can be used to associate executable behaviors with
     * content in the METS document. This element has an interface definition
     * <interfaceDef> element that represents an abstract definition of a set of
     * behaviors represented by a particular behavior. A <behavior> element also has a
     * behavior mechanism <mechanism> element, a module of executable code that
     * implements and runs the behavior defined abstractly by the interface definition.
     *
     * @return self
     * @param \App\Xsd\Mets\BehaviorType $behaviorElName
     */
    public function addToBehaviorElName(\App\Xsd\Mets\BehaviorType $behaviorElName)
    {
        $this->behaviorElName[] = $behaviorElName;
        return $this;
    }

    /**
     * isset behaviorElName
     *
     * A behavior element <behavior> can be used to associate executable behaviors with
     * content in the METS document. This element has an interface definition
     * <interfaceDef> element that represents an abstract definition of a set of
     * behaviors represented by a particular behavior. A <behavior> element also has a
     * behavior mechanism <mechanism> element, a module of executable code that
     * implements and runs the behavior defined abstractly by the interface definition.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBehaviorElName($index)
    {
        return isset($this->behaviorElName[$index]);
    }

    /**
     * unset behaviorElName
     *
     * A behavior element <behavior> can be used to associate executable behaviors with
     * content in the METS document. This element has an interface definition
     * <interfaceDef> element that represents an abstract definition of a set of
     * behaviors represented by a particular behavior. A <behavior> element also has a
     * behavior mechanism <mechanism> element, a module of executable code that
     * implements and runs the behavior defined abstractly by the interface definition.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBehaviorElName($index)
    {
        unset($this->behaviorElName[$index]);
    }

    /**
     * Gets as behaviorElName
     *
     * A behavior element <behavior> can be used to associate executable behaviors with
     * content in the METS document. This element has an interface definition
     * <interfaceDef> element that represents an abstract definition of a set of
     * behaviors represented by a particular behavior. A <behavior> element also has a
     * behavior mechanism <mechanism> element, a module of executable code that
     * implements and runs the behavior defined abstractly by the interface definition.
     *
     * @return \App\Xsd\Mets\BehaviorType[]
     */
    public function getBehaviorElName()
    {
        return $this->behaviorElName;
    }

    /**
     * Sets a new behaviorElName
     *
     * A behavior element <behavior> can be used to associate executable behaviors with
     * content in the METS document. This element has an interface definition
     * <interfaceDef> element that represents an abstract definition of a set of
     * behaviors represented by a particular behavior. A <behavior> element also has a
     * behavior mechanism <mechanism> element, a module of executable code that
     * implements and runs the behavior defined abstractly by the interface definition.
     *
     * @param \App\Xsd\Mets\BehaviorType[] $behaviorElName
     * @return self
     */
    public function setBehaviorElName(array $behaviorElName)
    {
        $this->behaviorElName = $behaviorElName;
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

