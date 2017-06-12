<?php

namespace App\Xsd\Mets;

/**
 * Class representing StructLinkType
 *
 * structLinkType: Complex Type for Structural Map Linking
 *  The Structural Map Linking section allows for the specification of hyperlinks
 * between different components of a METS structure delineated in a structural map.
 * structLink contains a single, repeatable element, smLink. Each smLink element
 * indicates a hyperlink between two nodes in the structMap. The structMap nodes
 * recorded in smLink are identified using their XML ID attribute values.
 * XSD Type: structLinkType
 */
class StructLinkType
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
     * The Structural Map Link element <smLink> identifies a hyperlink between two
     * nodes in the structural map. You would use <smLink>, for instance, to note the
     * existence of hypertext links between web pages, if you wished to record those
     * links within METS. NOTE: <smLink> is an empty element. The location of the
     * <smLink> element to which the <smLink> element is pointing MUST be stored in the
     * xlink:href attribute.
     *
     * @property \App\Xsd\Mets\StructLinkType\SmLinkAType[] $smLinkElName
     */
    private $smLinkElName = array(
        
    );

    /**
     * The structMap link group element <smLinkGrp> provides an implementation of
     * xlink:extendLink, and provides xlink compliant mechanisms for establishing
     * xlink:arcLink type links between 2 or more <div> elements in <structMap>
     * element(s) occurring within the same METS document or different METS documents.
     * The smLinkGrp could be used as an alternative to the <smLink> element to
     * establish a one-to-one link between <div> elements in the same METS document in
     * a fully xlink compliant manner. However, it can also be used to establish
     * one-to-many or many-to-many links between <div> elements. For example, if a METS
     * document contains two <structMap> elements, one of which represents a purely
     * logical structure and one of which represents a purely physical structure, the
     * <smLinkGrp> element would provide a means of mapping a <div> representing a
     * logical entity (for example, a newspaper article) with multiple <div> elements
     * in the physical <structMap> representing the physical areas that together
     * comprise the logical entity (for example, the <div> elements representing the
     * page areas that together comprise the newspaper article).
     *
     * @property \App\Xsd\Mets\StructLinkType\SmLinkGrpAType[] $smLinkGrpElName
     */
    private $smLinkGrpElName = array(
        
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
     * Adds as smLinkElName
     *
     * The Structural Map Link element <smLink> identifies a hyperlink between two
     * nodes in the structural map. You would use <smLink>, for instance, to note the
     * existence of hypertext links between web pages, if you wished to record those
     * links within METS. NOTE: <smLink> is an empty element. The location of the
     * <smLink> element to which the <smLink> element is pointing MUST be stored in the
     * xlink:href attribute.
     *
     * @return self
     * @param \App\Xsd\Mets\StructLinkType\SmLinkAType $smLinkElName
     */
    public function addToSmLinkElName(\App\Xsd\Mets\StructLinkType\SmLinkAType $smLinkElName)
    {
        $this->smLinkElName[] = $smLinkElName;
        return $this;
    }

    /**
     * isset smLinkElName
     *
     * The Structural Map Link element <smLink> identifies a hyperlink between two
     * nodes in the structural map. You would use <smLink>, for instance, to note the
     * existence of hypertext links between web pages, if you wished to record those
     * links within METS. NOTE: <smLink> is an empty element. The location of the
     * <smLink> element to which the <smLink> element is pointing MUST be stored in the
     * xlink:href attribute.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSmLinkElName($index)
    {
        return isset($this->smLinkElName[$index]);
    }

    /**
     * unset smLinkElName
     *
     * The Structural Map Link element <smLink> identifies a hyperlink between two
     * nodes in the structural map. You would use <smLink>, for instance, to note the
     * existence of hypertext links between web pages, if you wished to record those
     * links within METS. NOTE: <smLink> is an empty element. The location of the
     * <smLink> element to which the <smLink> element is pointing MUST be stored in the
     * xlink:href attribute.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSmLinkElName($index)
    {
        unset($this->smLinkElName[$index]);
    }

    /**
     * Gets as smLinkElName
     *
     * The Structural Map Link element <smLink> identifies a hyperlink between two
     * nodes in the structural map. You would use <smLink>, for instance, to note the
     * existence of hypertext links between web pages, if you wished to record those
     * links within METS. NOTE: <smLink> is an empty element. The location of the
     * <smLink> element to which the <smLink> element is pointing MUST be stored in the
     * xlink:href attribute.
     *
     * @return \App\Xsd\Mets\StructLinkType\SmLinkAType[]
     */
    public function getSmLinkElName()
    {
        return $this->smLinkElName;
    }

    /**
     * Sets a new smLinkElName
     *
     * The Structural Map Link element <smLink> identifies a hyperlink between two
     * nodes in the structural map. You would use <smLink>, for instance, to note the
     * existence of hypertext links between web pages, if you wished to record those
     * links within METS. NOTE: <smLink> is an empty element. The location of the
     * <smLink> element to which the <smLink> element is pointing MUST be stored in the
     * xlink:href attribute.
     *
     * @param \App\Xsd\Mets\StructLinkType\SmLinkAType[] $smLinkElName
     * @return self
     */
    public function setSmLinkElName(array $smLinkElName)
    {
        $this->smLinkElName = $smLinkElName;
        return $this;
    }

    /**
     * Adds as smLinkGrpElName
     *
     * The structMap link group element <smLinkGrp> provides an implementation of
     * xlink:extendLink, and provides xlink compliant mechanisms for establishing
     * xlink:arcLink type links between 2 or more <div> elements in <structMap>
     * element(s) occurring within the same METS document or different METS documents.
     * The smLinkGrp could be used as an alternative to the <smLink> element to
     * establish a one-to-one link between <div> elements in the same METS document in
     * a fully xlink compliant manner. However, it can also be used to establish
     * one-to-many or many-to-many links between <div> elements. For example, if a METS
     * document contains two <structMap> elements, one of which represents a purely
     * logical structure and one of which represents a purely physical structure, the
     * <smLinkGrp> element would provide a means of mapping a <div> representing a
     * logical entity (for example, a newspaper article) with multiple <div> elements
     * in the physical <structMap> representing the physical areas that together
     * comprise the logical entity (for example, the <div> elements representing the
     * page areas that together comprise the newspaper article).
     *
     * @return self
     * @param \App\Xsd\Mets\StructLinkType\SmLinkGrpAType $smLinkGrpElName
     */
    public function addToSmLinkGrpElName(\App\Xsd\Mets\StructLinkType\SmLinkGrpAType $smLinkGrpElName)
    {
        $this->smLinkGrpElName[] = $smLinkGrpElName;
        return $this;
    }

    /**
     * isset smLinkGrpElName
     *
     * The structMap link group element <smLinkGrp> provides an implementation of
     * xlink:extendLink, and provides xlink compliant mechanisms for establishing
     * xlink:arcLink type links between 2 or more <div> elements in <structMap>
     * element(s) occurring within the same METS document or different METS documents.
     * The smLinkGrp could be used as an alternative to the <smLink> element to
     * establish a one-to-one link between <div> elements in the same METS document in
     * a fully xlink compliant manner. However, it can also be used to establish
     * one-to-many or many-to-many links between <div> elements. For example, if a METS
     * document contains two <structMap> elements, one of which represents a purely
     * logical structure and one of which represents a purely physical structure, the
     * <smLinkGrp> element would provide a means of mapping a <div> representing a
     * logical entity (for example, a newspaper article) with multiple <div> elements
     * in the physical <structMap> representing the physical areas that together
     * comprise the logical entity (for example, the <div> elements representing the
     * page areas that together comprise the newspaper article).
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSmLinkGrpElName($index)
    {
        return isset($this->smLinkGrpElName[$index]);
    }

    /**
     * unset smLinkGrpElName
     *
     * The structMap link group element <smLinkGrp> provides an implementation of
     * xlink:extendLink, and provides xlink compliant mechanisms for establishing
     * xlink:arcLink type links between 2 or more <div> elements in <structMap>
     * element(s) occurring within the same METS document or different METS documents.
     * The smLinkGrp could be used as an alternative to the <smLink> element to
     * establish a one-to-one link between <div> elements in the same METS document in
     * a fully xlink compliant manner. However, it can also be used to establish
     * one-to-many or many-to-many links between <div> elements. For example, if a METS
     * document contains two <structMap> elements, one of which represents a purely
     * logical structure and one of which represents a purely physical structure, the
     * <smLinkGrp> element would provide a means of mapping a <div> representing a
     * logical entity (for example, a newspaper article) with multiple <div> elements
     * in the physical <structMap> representing the physical areas that together
     * comprise the logical entity (for example, the <div> elements representing the
     * page areas that together comprise the newspaper article).
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSmLinkGrpElName($index)
    {
        unset($this->smLinkGrpElName[$index]);
    }

    /**
     * Gets as smLinkGrpElName
     *
     * The structMap link group element <smLinkGrp> provides an implementation of
     * xlink:extendLink, and provides xlink compliant mechanisms for establishing
     * xlink:arcLink type links between 2 or more <div> elements in <structMap>
     * element(s) occurring within the same METS document or different METS documents.
     * The smLinkGrp could be used as an alternative to the <smLink> element to
     * establish a one-to-one link between <div> elements in the same METS document in
     * a fully xlink compliant manner. However, it can also be used to establish
     * one-to-many or many-to-many links between <div> elements. For example, if a METS
     * document contains two <structMap> elements, one of which represents a purely
     * logical structure and one of which represents a purely physical structure, the
     * <smLinkGrp> element would provide a means of mapping a <div> representing a
     * logical entity (for example, a newspaper article) with multiple <div> elements
     * in the physical <structMap> representing the physical areas that together
     * comprise the logical entity (for example, the <div> elements representing the
     * page areas that together comprise the newspaper article).
     *
     * @return \App\Xsd\Mets\StructLinkType\SmLinkGrpAType[]
     */
    public function getSmLinkGrpElName()
    {
        return $this->smLinkGrpElName;
    }

    /**
     * Sets a new smLinkGrpElName
     *
     * The structMap link group element <smLinkGrp> provides an implementation of
     * xlink:extendLink, and provides xlink compliant mechanisms for establishing
     * xlink:arcLink type links between 2 or more <div> elements in <structMap>
     * element(s) occurring within the same METS document or different METS documents.
     * The smLinkGrp could be used as an alternative to the <smLink> element to
     * establish a one-to-one link between <div> elements in the same METS document in
     * a fully xlink compliant manner. However, it can also be used to establish
     * one-to-many or many-to-many links between <div> elements. For example, if a METS
     * document contains two <structMap> elements, one of which represents a purely
     * logical structure and one of which represents a purely physical structure, the
     * <smLinkGrp> element would provide a means of mapping a <div> representing a
     * logical entity (for example, a newspaper article) with multiple <div> elements
     * in the physical <structMap> representing the physical areas that together
     * comprise the logical entity (for example, the <div> elements representing the
     * page areas that together comprise the newspaper article).
     *
     * @param \App\Xsd\Mets\StructLinkType\SmLinkGrpAType[] $smLinkGrpElName
     * @return self
     */
    public function setSmLinkGrpElName(array $smLinkGrpElName)
    {
        $this->smLinkGrpElName = $smLinkGrpElName;
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

