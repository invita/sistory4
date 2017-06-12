<?php

namespace App\Xsd\Mets;

/**
 * Class representing MdSecType
 *
 * mdSecType: Complex Type for Metadata Sections
 *  A generic framework for pointing to/including metadata within a METS document,
 * a la Warwick Framework.
 * XSD Type: mdSecType
 */
class MdSecType
{

    /**
     * ID (ID/R): This attribute uniquely identifies the element within the METS
     * document, and would allow the element to be referenced unambiguously from
     * another element or document via an IDREF or an XPTR. The ID attribute on the
     * <dmdSec>, <techMD>, <sourceMD>, <rightsMD> and <digiprovMD> elements (which are
     * all of mdSecType) is required, and its value should be referenced from one or
     * more DMDID attributes (when the ID identifies a <dmdSec> element) or ADMID
     * attributes (when the ID identifies a <techMD>, <sourceMD>, <rightsMD> or
     * <digiprovMD> element) that are associated with other elements in the METS
     * document. The following elements support references to a <dmdSec> via a DMDID
     * attribute: <file>, <stream>, <div>. The following elements support references to
     * <techMD>, <sourceMD>, <rightsMD> and <digiprovMD> elements via an ADMID
     * attribute: <metsHdr>, <dmdSec>, <techMD>, <sourceMD>, <rightsMD>, <digiprovMD>,
     * <fileGrp>, <file>, <stream>, <div>, <area>, <behavior>. For more information on
     * using ID attributes for internal and external linking see Chapter 4 of the METS
     * Primer.
     *
     * @property string $IDAttrName
     */
    private $IDAttrName = null;

    /**
     * GROUPID (string/O): This identifier is used to indicate that different metadata
     * sections may be considered as part of a group. Two metadata sections with the
     * same GROUPID value are to be considered part of the same group. For example this
     * facility might be used to group changed versions of the same metadata if
     * previous versions are maintained in a file for tracking purposes.
     *
     * @property string $GROUPIDAttrName
     */
    private $GROUPIDAttrName = null;

    /**
     * ADMID (IDREFS/O): Contains the ID attribute values of the <digiprovMD>,
     * <techMD>, <sourceMD> and/or <rightsMD> elements within the <amdSec> of the METS
     * document that contain administrative metadata pertaining to the current
     * mdSecType element. Typically used in this context to reference preservation
     * metadata (digiprovMD) which applies to the current metadata. For more
     * information on using METS IDREFS and IDREF type attributes for internal linking,
     * see Chapter 4 of the METS Primer.
     *
     * @property string $ADMIDAttrName
     */
    private $ADMIDAttrName = null;

    /**
     * CREATED (dateTime/O): Specifies the date and time of creation for the metadata.
     *
     * @property \DateTime $CREATEDAttrName
     */
    private $CREATEDAttrName = null;

    /**
     * STATUS (string/O): Indicates the status of this metadata (e.g., superseded,
     * current, etc.).
     *
     * @property string $STATUSAttrName
     */
    private $STATUSAttrName = null;

    /**
     * The metadata reference element <mdRef> element is a generic element used
     * throughout the METS schema to provide a pointer to metadata which resides
     * outside the METS document. NB: <mdRef> is an empty element. The location of the
     * metadata must be recorded in the xlink:href attribute, supplemented by the XPTR
     * attribute as needed.
     *
     * @property \App\Xsd\Mets\MdSecType\MdRefAType $mdRefElName
     */
    private $mdRefElName = null;

    /**
     * A metadata wrapper element <mdWrap> provides a wrapper around metadata embedded
     * within a METS document. The element is repeatable. Such metadata can be in one
     * of two forms: 1) XML-encoded metadata, with the XML-encoding identifying itself
     * as belonging to a namespace other than the METS document namespace. 2) Any
     * arbitrary binary or textual form, PROVIDED that the metadata is Base64 encoded
     * and wrapped in a <binData> element within the internal descriptive metadata
     * element.
     *
     * @property \App\Xsd\Mets\MdSecType\MdWrapAType $mdWrapElName
     */
    private $mdWrapElName = null;

    /**
     * Gets as IDAttrName
     *
     * ID (ID/R): This attribute uniquely identifies the element within the METS
     * document, and would allow the element to be referenced unambiguously from
     * another element or document via an IDREF or an XPTR. The ID attribute on the
     * <dmdSec>, <techMD>, <sourceMD>, <rightsMD> and <digiprovMD> elements (which are
     * all of mdSecType) is required, and its value should be referenced from one or
     * more DMDID attributes (when the ID identifies a <dmdSec> element) or ADMID
     * attributes (when the ID identifies a <techMD>, <sourceMD>, <rightsMD> or
     * <digiprovMD> element) that are associated with other elements in the METS
     * document. The following elements support references to a <dmdSec> via a DMDID
     * attribute: <file>, <stream>, <div>. The following elements support references to
     * <techMD>, <sourceMD>, <rightsMD> and <digiprovMD> elements via an ADMID
     * attribute: <metsHdr>, <dmdSec>, <techMD>, <sourceMD>, <rightsMD>, <digiprovMD>,
     * <fileGrp>, <file>, <stream>, <div>, <area>, <behavior>. For more information on
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
     * ID (ID/R): This attribute uniquely identifies the element within the METS
     * document, and would allow the element to be referenced unambiguously from
     * another element or document via an IDREF or an XPTR. The ID attribute on the
     * <dmdSec>, <techMD>, <sourceMD>, <rightsMD> and <digiprovMD> elements (which are
     * all of mdSecType) is required, and its value should be referenced from one or
     * more DMDID attributes (when the ID identifies a <dmdSec> element) or ADMID
     * attributes (when the ID identifies a <techMD>, <sourceMD>, <rightsMD> or
     * <digiprovMD> element) that are associated with other elements in the METS
     * document. The following elements support references to a <dmdSec> via a DMDID
     * attribute: <file>, <stream>, <div>. The following elements support references to
     * <techMD>, <sourceMD>, <rightsMD> and <digiprovMD> elements via an ADMID
     * attribute: <metsHdr>, <dmdSec>, <techMD>, <sourceMD>, <rightsMD>, <digiprovMD>,
     * <fileGrp>, <file>, <stream>, <div>, <area>, <behavior>. For more information on
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
     * Gets as GROUPIDAttrName
     *
     * GROUPID (string/O): This identifier is used to indicate that different metadata
     * sections may be considered as part of a group. Two metadata sections with the
     * same GROUPID value are to be considered part of the same group. For example this
     * facility might be used to group changed versions of the same metadata if
     * previous versions are maintained in a file for tracking purposes.
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
     * GROUPID (string/O): This identifier is used to indicate that different metadata
     * sections may be considered as part of a group. Two metadata sections with the
     * same GROUPID value are to be considered part of the same group. For example this
     * facility might be used to group changed versions of the same metadata if
     * previous versions are maintained in a file for tracking purposes.
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
     * ADMID (IDREFS/O): Contains the ID attribute values of the <digiprovMD>,
     * <techMD>, <sourceMD> and/or <rightsMD> elements within the <amdSec> of the METS
     * document that contain administrative metadata pertaining to the current
     * mdSecType element. Typically used in this context to reference preservation
     * metadata (digiprovMD) which applies to the current metadata. For more
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
     * ADMID (IDREFS/O): Contains the ID attribute values of the <digiprovMD>,
     * <techMD>, <sourceMD> and/or <rightsMD> elements within the <amdSec> of the METS
     * document that contain administrative metadata pertaining to the current
     * mdSecType element. Typically used in this context to reference preservation
     * metadata (digiprovMD) which applies to the current metadata. For more
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

    /**
     * Gets as CREATEDAttrName
     *
     * CREATED (dateTime/O): Specifies the date and time of creation for the metadata.
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
     * CREATED (dateTime/O): Specifies the date and time of creation for the metadata.
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
     * Gets as STATUSAttrName
     *
     * STATUS (string/O): Indicates the status of this metadata (e.g., superseded,
     * current, etc.).
     *
     * @return string
     */
    public function getSTATUSAttrName()
    {
        return $this->STATUSAttrName;
    }

    /**
     * Sets a new STATUSAttrName
     *
     * STATUS (string/O): Indicates the status of this metadata (e.g., superseded,
     * current, etc.).
     *
     * @param string $STATUSAttrName
     * @return self
     */
    public function setSTATUSAttrName($STATUSAttrName)
    {
        $this->STATUSAttrName = $STATUSAttrName;
        return $this;
    }

    /**
     * Gets as mdRefElName
     *
     * The metadata reference element <mdRef> element is a generic element used
     * throughout the METS schema to provide a pointer to metadata which resides
     * outside the METS document. NB: <mdRef> is an empty element. The location of the
     * metadata must be recorded in the xlink:href attribute, supplemented by the XPTR
     * attribute as needed.
     *
     * @return \App\Xsd\Mets\MdSecType\MdRefAType
     */
    public function getMdRefElName()
    {
        return $this->mdRefElName;
    }

    /**
     * Sets a new mdRefElName
     *
     * The metadata reference element <mdRef> element is a generic element used
     * throughout the METS schema to provide a pointer to metadata which resides
     * outside the METS document. NB: <mdRef> is an empty element. The location of the
     * metadata must be recorded in the xlink:href attribute, supplemented by the XPTR
     * attribute as needed.
     *
     * @param \App\Xsd\Mets\MdSecType\MdRefAType $mdRefElName
     * @return self
     */
    public function setMdRefElName(\App\Xsd\Mets\MdSecType\MdRefAType $mdRefElName)
    {
        $this->mdRefElName = $mdRefElName;
        return $this;
    }

    /**
     * Gets as mdWrapElName
     *
     * A metadata wrapper element <mdWrap> provides a wrapper around metadata embedded
     * within a METS document. The element is repeatable. Such metadata can be in one
     * of two forms: 1) XML-encoded metadata, with the XML-encoding identifying itself
     * as belonging to a namespace other than the METS document namespace. 2) Any
     * arbitrary binary or textual form, PROVIDED that the metadata is Base64 encoded
     * and wrapped in a <binData> element within the internal descriptive metadata
     * element.
     *
     * @return \App\Xsd\Mets\MdSecType\MdWrapAType
     */
    public function getMdWrapElName()
    {
        return $this->mdWrapElName;
    }

    /**
     * Sets a new mdWrapElName
     *
     * A metadata wrapper element <mdWrap> provides a wrapper around metadata embedded
     * within a METS document. The element is repeatable. Such metadata can be in one
     * of two forms: 1) XML-encoded metadata, with the XML-encoding identifying itself
     * as belonging to a namespace other than the METS document namespace. 2) Any
     * arbitrary binary or textual form, PROVIDED that the metadata is Base64 encoded
     * and wrapped in a <binData> element within the internal descriptive metadata
     * element.
     *
     * @param \App\Xsd\Mets\MdSecType\MdWrapAType $mdWrapElName
     * @return self
     */
    public function setMdWrapElName(\App\Xsd\Mets\MdSecType\MdWrapAType $mdWrapElName)
    {
        $this->mdWrapElName = $mdWrapElName;
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

