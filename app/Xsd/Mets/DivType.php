<?php

namespace App\Xsd\Mets;

/**
 * Class representing DivType
 *
 * divType: Complex Type for Divisions
 *  The METS standard represents a document structurally as a series of nested div
 * elements, that is, as a hierarchy (e.g., a book, which is composed of chapters,
 * which are composed of subchapters, which are composed of text). Every div node
 * in the structural map hierarchy may be connected (via subsidiary mptr or fptr
 * elements) to content files which represent that div's portion of the whole
 * document.
 *
 * SPECIAL NOTE REGARDING DIV ATTRIBUTE VALUES:
 * to clarify the differences between the ORDER, ORDERLABEL, and LABEL attributes
 * for the <div> element, imagine a text with 10 roman numbered pages followed by
 * 10 arabic numbered pages. Page iii would have an ORDER of "3", an ORDERLABEL of
 * "iii" and a LABEL of "Page iii", while page 3 would have an ORDER of "13", an
 * ORDERLABEL of "3" and a LABEL of "Page 3".
 * XSD Type: divType
 */
class DivType
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
     * ORDER (integer/O): A representation of the element's order among its siblings
     * (e.g., its absolute, numeric sequence). For an example, and clarification of the
     * distinction between ORDER and ORDERLABEL, see the description of the ORDERLABEL
     * attribute.
     *
     * @property integer $ORDERAttrName
     */
    private $ORDERAttrName = null;

    /**
     * ORDERLABEL (string/O): A representation of the element's order among its
     * siblings (e.g., “xii”), or of any non-integer native numbering system. It is
     * presumed that this value will still be machine actionable (e.g., it would
     * support ‘go to page ___’ function), and it should not be used as a
     * replacement/substitute for the LABEL attribute. To understand the differences
     * between ORDER, ORDERLABEL and LABEL, imagine a text with 10 roman numbered pages
     * followed by 10 arabic numbered pages. Page iii would have an ORDER of “3”,
     * an ORDERLABEL of “iii” and a LABEL of “Page iii”, while page 3 would
     * have an ORDER of “13”, an ORDERLABEL of “3” and a LABEL of “Page 3”.
     *
     * @property string $ORDERLABELAttrName
     */
    private $ORDERLABELAttrName = null;

    /**
     * LABEL (string/O): An attribute used, for example, to identify a <div> to an end
     * user viewing the document. Thus a hierarchical arrangement of the <div> LABEL
     * values could provide a table of contents to the digital content represented by a
     * METS document and facilitate the users’ navigation of the digital object. Note
     * that a <div> LABEL should be specific to its level in the structural map. In the
     * case of a book with chapters, the book <div> LABEL should have the book title
     * and the chapter <div>; LABELs should have the individual chapter titles, rather
     * than having the chapter <div> LABELs combine both book title and chapter title .
     * For further of the distinction between LABEL and ORDERLABEL see the description
     * of the ORDERLABEL attribute.
     *
     * @property string $LABELAttrName
     */
    private $LABELAttrName = null;

    /**
     * DMDID (IDREFS/O): Contains the ID attribute values identifying the <dmdSec>,
     * elements in the METS document that contain or link to descriptive metadata
     * pertaining to the structural division represented by the current <div> element.
     * For more information on using METS IDREFS and IDREF type attributes for internal
     * linking, see Chapter 4 of the METS Primer.
     *
     * @property string $DMDIDAttrName
     */
    private $DMDIDAttrName = null;

    /**
     * ADMID (IDREFS/O): Contains the ID attribute values identifying the <rightsMD>,
     * <sourceMD>, <techMD> and/or <digiprovMD> elements within the <amdSec> of the
     * METS document that contain or link to administrative metadata pertaining to the
     * structural division represented by the <div> element. Typically the <div> ADMID
     * attribute would be used to identify the <rightsMD> element or elements that
     * pertain to the <div>, but it could be used anytime there was a need to link a
     * <div> with pertinent administrative metadata. For more information on using METS
     * IDREFS and IDREF type attributes for internal linking, see Chapter 4 of the METS
     * Primer.
     *
     * @property string $ADMIDAttrName
     */
    private $ADMIDAttrName = null;

    /**
     * TYPE (string/O): An attribute that specifies the type of structural division
     * that the <div> element represents. Possible <div> TYPE attribute values include:
     * chapter, article, page, track, segment, section etc. METS places no constraints
     * on the possible TYPE values. Suggestions for controlled vocabularies for TYPE
     * may be found on the METS website.
     *
     * @property string $TYPEAttrName
     */
    private $TYPEAttrName = null;

    /**
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <div>
     * (equivalent to DIDL DII or Digital Item Identifier, a unique external ID).
     *
     * @property string[] $CONTENTIDSAttrName
     */
    private $CONTENTIDSAttrName = null;

    /**
     * xlink:label - an xlink label to be referred to by an smLink element
     *
     * @property string $labelPropName
     */
    private $labelPropName = null;

    /**
     * Like the <fptr> element, the METS pointer element <mptr> represents digital
     * content that manifests its parent <div> element. Unlike the <fptr>, which either
     * directly or indirectly points to content represented in the <fileSec> of the
     * parent METS document, the <mptr> element points to content represented by an
     * external METS document. Thus, this element allows multiple discrete and separate
     * METS documents to be organized at a higher level by a separate METS document.
     * For example, METS documents representing the individual issues in the series of
     * a journal could be grouped together and organized by a higher level METS
     * document that represents the entire journal series. Each of the <div> elements
     * in the <structMap> of the METS document representing the journal series would
     * point to a METS document representing an issue. It would do so via a child
     * <mptr> element. Thus the <mptr> element gives METS users considerable
     * flexibility in managing the depth of the <structMap> hierarchy of individual
     * METS documents. The <mptr> element points to an external METS document by means
     * of an xlink:href attribute and associated XLink attributes.
     *
     * @property \App\Xsd\Mets\DivType\MptrAType[] $mptrElName
     */
    private $mptrElName = array(
        
    );

    /**
     * The <fptr> or file pointer element represents digital content that manifests its
     * parent <div> element. The content represented by an <fptr> element must consist
     * of integral files or parts of files that are represented by <file> elements in
     * the <fileSec>. Via its FILEID attribute, an <fptr> may point directly to a
     * single integral <file> element that manifests a structural division. However, an
     * <fptr> element may also govern an <area> element, a <par>, or a <seq> which in
     * turn would point to the relevant file or files. A child <area> element can point
     * to part of a <file> that manifests a division, while the <par> and <seq>
     * elements can point to multiple files or parts of files that together manifest a
     * division. More than one <fptr> element can be associated with a <div> element.
     * Typically sibling <fptr> elements represent alternative versions, or
     * manifestations, of the same content
     *
     * @property \App\Xsd\Mets\DivType\FptrAType[] $fptrElName
     */
    private $fptrElName = array(
        
    );

    /**
     * @property \App\Xsd\Mets\DivType[] $divElName
     */
    private $divElName = array(
        
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
     * Gets as ORDERAttrName
     *
     * ORDER (integer/O): A representation of the element's order among its siblings
     * (e.g., its absolute, numeric sequence). For an example, and clarification of the
     * distinction between ORDER and ORDERLABEL, see the description of the ORDERLABEL
     * attribute.
     *
     * @return integer
     */
    public function getORDERAttrName()
    {
        return $this->ORDERAttrName;
    }

    /**
     * Sets a new ORDERAttrName
     *
     * ORDER (integer/O): A representation of the element's order among its siblings
     * (e.g., its absolute, numeric sequence). For an example, and clarification of the
     * distinction between ORDER and ORDERLABEL, see the description of the ORDERLABEL
     * attribute.
     *
     * @param integer $ORDERAttrName
     * @return self
     */
    public function setORDERAttrName($ORDERAttrName)
    {
        $this->ORDERAttrName = $ORDERAttrName;
        return $this;
    }

    /**
     * Gets as ORDERLABELAttrName
     *
     * ORDERLABEL (string/O): A representation of the element's order among its
     * siblings (e.g., “xii”), or of any non-integer native numbering system. It is
     * presumed that this value will still be machine actionable (e.g., it would
     * support ‘go to page ___’ function), and it should not be used as a
     * replacement/substitute for the LABEL attribute. To understand the differences
     * between ORDER, ORDERLABEL and LABEL, imagine a text with 10 roman numbered pages
     * followed by 10 arabic numbered pages. Page iii would have an ORDER of “3”,
     * an ORDERLABEL of “iii” and a LABEL of “Page iii”, while page 3 would
     * have an ORDER of “13”, an ORDERLABEL of “3” and a LABEL of “Page 3”.
     *
     * @return string
     */
    public function getORDERLABELAttrName()
    {
        return $this->ORDERLABELAttrName;
    }

    /**
     * Sets a new ORDERLABELAttrName
     *
     * ORDERLABEL (string/O): A representation of the element's order among its
     * siblings (e.g., “xii”), or of any non-integer native numbering system. It is
     * presumed that this value will still be machine actionable (e.g., it would
     * support ‘go to page ___’ function), and it should not be used as a
     * replacement/substitute for the LABEL attribute. To understand the differences
     * between ORDER, ORDERLABEL and LABEL, imagine a text with 10 roman numbered pages
     * followed by 10 arabic numbered pages. Page iii would have an ORDER of “3”,
     * an ORDERLABEL of “iii” and a LABEL of “Page iii”, while page 3 would
     * have an ORDER of “13”, an ORDERLABEL of “3” and a LABEL of “Page 3”.
     *
     * @param string $ORDERLABELAttrName
     * @return self
     */
    public function setORDERLABELAttrName($ORDERLABELAttrName)
    {
        $this->ORDERLABELAttrName = $ORDERLABELAttrName;
        return $this;
    }

    /**
     * Gets as LABELAttrName
     *
     * LABEL (string/O): An attribute used, for example, to identify a <div> to an end
     * user viewing the document. Thus a hierarchical arrangement of the <div> LABEL
     * values could provide a table of contents to the digital content represented by a
     * METS document and facilitate the users’ navigation of the digital object. Note
     * that a <div> LABEL should be specific to its level in the structural map. In the
     * case of a book with chapters, the book <div> LABEL should have the book title
     * and the chapter <div>; LABELs should have the individual chapter titles, rather
     * than having the chapter <div> LABELs combine both book title and chapter title .
     * For further of the distinction between LABEL and ORDERLABEL see the description
     * of the ORDERLABEL attribute.
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
     * LABEL (string/O): An attribute used, for example, to identify a <div> to an end
     * user viewing the document. Thus a hierarchical arrangement of the <div> LABEL
     * values could provide a table of contents to the digital content represented by a
     * METS document and facilitate the users’ navigation of the digital object. Note
     * that a <div> LABEL should be specific to its level in the structural map. In the
     * case of a book with chapters, the book <div> LABEL should have the book title
     * and the chapter <div>; LABELs should have the individual chapter titles, rather
     * than having the chapter <div> LABELs combine both book title and chapter title .
     * For further of the distinction between LABEL and ORDERLABEL see the description
     * of the ORDERLABEL attribute.
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
     * Gets as DMDIDAttrName
     *
     * DMDID (IDREFS/O): Contains the ID attribute values identifying the <dmdSec>,
     * elements in the METS document that contain or link to descriptive metadata
     * pertaining to the structural division represented by the current <div> element.
     * For more information on using METS IDREFS and IDREF type attributes for internal
     * linking, see Chapter 4 of the METS Primer.
     *
     * @return string
     */
    public function getDMDIDAttrName()
    {
        return $this->DMDIDAttrName;
    }

    /**
     * Sets a new DMDIDAttrName
     *
     * DMDID (IDREFS/O): Contains the ID attribute values identifying the <dmdSec>,
     * elements in the METS document that contain or link to descriptive metadata
     * pertaining to the structural division represented by the current <div> element.
     * For more information on using METS IDREFS and IDREF type attributes for internal
     * linking, see Chapter 4 of the METS Primer.
     *
     * @param string $DMDIDAttrName
     * @return self
     */
    public function setDMDIDAttrName($DMDIDAttrName)
    {
        $this->DMDIDAttrName = $DMDIDAttrName;
        return $this;
    }

    /**
     * Gets as ADMIDAttrName
     *
     * ADMID (IDREFS/O): Contains the ID attribute values identifying the <rightsMD>,
     * <sourceMD>, <techMD> and/or <digiprovMD> elements within the <amdSec> of the
     * METS document that contain or link to administrative metadata pertaining to the
     * structural division represented by the <div> element. Typically the <div> ADMID
     * attribute would be used to identify the <rightsMD> element or elements that
     * pertain to the <div>, but it could be used anytime there was a need to link a
     * <div> with pertinent administrative metadata. For more information on using METS
     * IDREFS and IDREF type attributes for internal linking, see Chapter 4 of the METS
     * Primer.
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
     * ADMID (IDREFS/O): Contains the ID attribute values identifying the <rightsMD>,
     * <sourceMD>, <techMD> and/or <digiprovMD> elements within the <amdSec> of the
     * METS document that contain or link to administrative metadata pertaining to the
     * structural division represented by the <div> element. Typically the <div> ADMID
     * attribute would be used to identify the <rightsMD> element or elements that
     * pertain to the <div>, but it could be used anytime there was a need to link a
     * <div> with pertinent administrative metadata. For more information on using METS
     * IDREFS and IDREF type attributes for internal linking, see Chapter 4 of the METS
     * Primer.
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
     * Gets as TYPEAttrName
     *
     * TYPE (string/O): An attribute that specifies the type of structural division
     * that the <div> element represents. Possible <div> TYPE attribute values include:
     * chapter, article, page, track, segment, section etc. METS places no constraints
     * on the possible TYPE values. Suggestions for controlled vocabularies for TYPE
     * may be found on the METS website.
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
     * TYPE (string/O): An attribute that specifies the type of structural division
     * that the <div> element represents. Possible <div> TYPE attribute values include:
     * chapter, article, page, track, segment, section etc. METS places no constraints
     * on the possible TYPE values. Suggestions for controlled vocabularies for TYPE
     * may be found on the METS website.
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
     * Adds as CONTENTIDSAttrName
     *
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <div>
     * (equivalent to DIDL DII or Digital Item Identifier, a unique external ID).
     *
     * @return self
     * @param string $CONTENTIDSAttrName
     */
    public function addToCONTENTIDSAttrName($CONTENTIDSAttrName)
    {
        $this->CONTENTIDSAttrName[] = $CONTENTIDSAttrName;
        return $this;
    }

    /**
     * isset CONTENTIDSAttrName
     *
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <div>
     * (equivalent to DIDL DII or Digital Item Identifier, a unique external ID).
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCONTENTIDSAttrName($index)
    {
        return isset($this->CONTENTIDSAttrName[$index]);
    }

    /**
     * unset CONTENTIDSAttrName
     *
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <div>
     * (equivalent to DIDL DII or Digital Item Identifier, a unique external ID).
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCONTENTIDSAttrName($index)
    {
        unset($this->CONTENTIDSAttrName[$index]);
    }

    /**
     * Gets as CONTENTIDSAttrName
     *
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <div>
     * (equivalent to DIDL DII or Digital Item Identifier, a unique external ID).
     *
     * @return string[]
     */
    public function getCONTENTIDSAttrName()
    {
        return $this->CONTENTIDSAttrName;
    }

    /**
     * Sets a new CONTENTIDSAttrName
     *
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <div>
     * (equivalent to DIDL DII or Digital Item Identifier, a unique external ID).
     *
     * @param string[] $CONTENTIDSAttrName
     * @return self
     */
    public function setCONTENTIDSAttrName(array $CONTENTIDSAttrName)
    {
        $this->CONTENTIDSAttrName = $CONTENTIDSAttrName;
        return $this;
    }

    /**
     * Gets as labelPropName
     *
     * xlink:label - an xlink label to be referred to by an smLink element
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
     * xlink:label - an xlink label to be referred to by an smLink element
     *
     * @param string $labelPropName
     * @return self
     */
    public function setLabelPropName($labelPropName)
    {
        $this->labelPropName = $labelPropName;
        return $this;
    }

    /**
     * Adds as mptrElName
     *
     * Like the <fptr> element, the METS pointer element <mptr> represents digital
     * content that manifests its parent <div> element. Unlike the <fptr>, which either
     * directly or indirectly points to content represented in the <fileSec> of the
     * parent METS document, the <mptr> element points to content represented by an
     * external METS document. Thus, this element allows multiple discrete and separate
     * METS documents to be organized at a higher level by a separate METS document.
     * For example, METS documents representing the individual issues in the series of
     * a journal could be grouped together and organized by a higher level METS
     * document that represents the entire journal series. Each of the <div> elements
     * in the <structMap> of the METS document representing the journal series would
     * point to a METS document representing an issue. It would do so via a child
     * <mptr> element. Thus the <mptr> element gives METS users considerable
     * flexibility in managing the depth of the <structMap> hierarchy of individual
     * METS documents. The <mptr> element points to an external METS document by means
     * of an xlink:href attribute and associated XLink attributes.
     *
     * @return self
     * @param \App\Xsd\Mets\DivType\MptrAType $mptrElName
     */
    public function addToMptrElName(\App\Xsd\Mets\DivType\MptrAType $mptrElName)
    {
        $this->mptrElName[] = $mptrElName;
        return $this;
    }

    /**
     * isset mptrElName
     *
     * Like the <fptr> element, the METS pointer element <mptr> represents digital
     * content that manifests its parent <div> element. Unlike the <fptr>, which either
     * directly or indirectly points to content represented in the <fileSec> of the
     * parent METS document, the <mptr> element points to content represented by an
     * external METS document. Thus, this element allows multiple discrete and separate
     * METS documents to be organized at a higher level by a separate METS document.
     * For example, METS documents representing the individual issues in the series of
     * a journal could be grouped together and organized by a higher level METS
     * document that represents the entire journal series. Each of the <div> elements
     * in the <structMap> of the METS document representing the journal series would
     * point to a METS document representing an issue. It would do so via a child
     * <mptr> element. Thus the <mptr> element gives METS users considerable
     * flexibility in managing the depth of the <structMap> hierarchy of individual
     * METS documents. The <mptr> element points to an external METS document by means
     * of an xlink:href attribute and associated XLink attributes.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetMptrElName($index)
    {
        return isset($this->mptrElName[$index]);
    }

    /**
     * unset mptrElName
     *
     * Like the <fptr> element, the METS pointer element <mptr> represents digital
     * content that manifests its parent <div> element. Unlike the <fptr>, which either
     * directly or indirectly points to content represented in the <fileSec> of the
     * parent METS document, the <mptr> element points to content represented by an
     * external METS document. Thus, this element allows multiple discrete and separate
     * METS documents to be organized at a higher level by a separate METS document.
     * For example, METS documents representing the individual issues in the series of
     * a journal could be grouped together and organized by a higher level METS
     * document that represents the entire journal series. Each of the <div> elements
     * in the <structMap> of the METS document representing the journal series would
     * point to a METS document representing an issue. It would do so via a child
     * <mptr> element. Thus the <mptr> element gives METS users considerable
     * flexibility in managing the depth of the <structMap> hierarchy of individual
     * METS documents. The <mptr> element points to an external METS document by means
     * of an xlink:href attribute and associated XLink attributes.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetMptrElName($index)
    {
        unset($this->mptrElName[$index]);
    }

    /**
     * Gets as mptrElName
     *
     * Like the <fptr> element, the METS pointer element <mptr> represents digital
     * content that manifests its parent <div> element. Unlike the <fptr>, which either
     * directly or indirectly points to content represented in the <fileSec> of the
     * parent METS document, the <mptr> element points to content represented by an
     * external METS document. Thus, this element allows multiple discrete and separate
     * METS documents to be organized at a higher level by a separate METS document.
     * For example, METS documents representing the individual issues in the series of
     * a journal could be grouped together and organized by a higher level METS
     * document that represents the entire journal series. Each of the <div> elements
     * in the <structMap> of the METS document representing the journal series would
     * point to a METS document representing an issue. It would do so via a child
     * <mptr> element. Thus the <mptr> element gives METS users considerable
     * flexibility in managing the depth of the <structMap> hierarchy of individual
     * METS documents. The <mptr> element points to an external METS document by means
     * of an xlink:href attribute and associated XLink attributes.
     *
     * @return \App\Xsd\Mets\DivType\MptrAType[]
     */
    public function getMptrElName()
    {
        return $this->mptrElName;
    }

    /**
     * Sets a new mptrElName
     *
     * Like the <fptr> element, the METS pointer element <mptr> represents digital
     * content that manifests its parent <div> element. Unlike the <fptr>, which either
     * directly or indirectly points to content represented in the <fileSec> of the
     * parent METS document, the <mptr> element points to content represented by an
     * external METS document. Thus, this element allows multiple discrete and separate
     * METS documents to be organized at a higher level by a separate METS document.
     * For example, METS documents representing the individual issues in the series of
     * a journal could be grouped together and organized by a higher level METS
     * document that represents the entire journal series. Each of the <div> elements
     * in the <structMap> of the METS document representing the journal series would
     * point to a METS document representing an issue. It would do so via a child
     * <mptr> element. Thus the <mptr> element gives METS users considerable
     * flexibility in managing the depth of the <structMap> hierarchy of individual
     * METS documents. The <mptr> element points to an external METS document by means
     * of an xlink:href attribute and associated XLink attributes.
     *
     * @param \App\Xsd\Mets\DivType\MptrAType[] $mptrElName
     * @return self
     */
    public function setMptrElName(array $mptrElName)
    {
        $this->mptrElName = $mptrElName;
        return $this;
    }

    /**
     * Adds as fptrElName
     *
     * The <fptr> or file pointer element represents digital content that manifests its
     * parent <div> element. The content represented by an <fptr> element must consist
     * of integral files or parts of files that are represented by <file> elements in
     * the <fileSec>. Via its FILEID attribute, an <fptr> may point directly to a
     * single integral <file> element that manifests a structural division. However, an
     * <fptr> element may also govern an <area> element, a <par>, or a <seq> which in
     * turn would point to the relevant file or files. A child <area> element can point
     * to part of a <file> that manifests a division, while the <par> and <seq>
     * elements can point to multiple files or parts of files that together manifest a
     * division. More than one <fptr> element can be associated with a <div> element.
     * Typically sibling <fptr> elements represent alternative versions, or
     * manifestations, of the same content
     *
     * @return self
     * @param \App\Xsd\Mets\DivType\FptrAType $fptrElName
     */
    public function addToFptrElName(\App\Xsd\Mets\DivType\FptrAType $fptrElName)
    {
        $this->fptrElName[] = $fptrElName;
        return $this;
    }

    /**
     * isset fptrElName
     *
     * The <fptr> or file pointer element represents digital content that manifests its
     * parent <div> element. The content represented by an <fptr> element must consist
     * of integral files or parts of files that are represented by <file> elements in
     * the <fileSec>. Via its FILEID attribute, an <fptr> may point directly to a
     * single integral <file> element that manifests a structural division. However, an
     * <fptr> element may also govern an <area> element, a <par>, or a <seq> which in
     * turn would point to the relevant file or files. A child <area> element can point
     * to part of a <file> that manifests a division, while the <par> and <seq>
     * elements can point to multiple files or parts of files that together manifest a
     * division. More than one <fptr> element can be associated with a <div> element.
     * Typically sibling <fptr> elements represent alternative versions, or
     * manifestations, of the same content
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFptrElName($index)
    {
        return isset($this->fptrElName[$index]);
    }

    /**
     * unset fptrElName
     *
     * The <fptr> or file pointer element represents digital content that manifests its
     * parent <div> element. The content represented by an <fptr> element must consist
     * of integral files or parts of files that are represented by <file> elements in
     * the <fileSec>. Via its FILEID attribute, an <fptr> may point directly to a
     * single integral <file> element that manifests a structural division. However, an
     * <fptr> element may also govern an <area> element, a <par>, or a <seq> which in
     * turn would point to the relevant file or files. A child <area> element can point
     * to part of a <file> that manifests a division, while the <par> and <seq>
     * elements can point to multiple files or parts of files that together manifest a
     * division. More than one <fptr> element can be associated with a <div> element.
     * Typically sibling <fptr> elements represent alternative versions, or
     * manifestations, of the same content
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFptrElName($index)
    {
        unset($this->fptrElName[$index]);
    }

    /**
     * Gets as fptrElName
     *
     * The <fptr> or file pointer element represents digital content that manifests its
     * parent <div> element. The content represented by an <fptr> element must consist
     * of integral files or parts of files that are represented by <file> elements in
     * the <fileSec>. Via its FILEID attribute, an <fptr> may point directly to a
     * single integral <file> element that manifests a structural division. However, an
     * <fptr> element may also govern an <area> element, a <par>, or a <seq> which in
     * turn would point to the relevant file or files. A child <area> element can point
     * to part of a <file> that manifests a division, while the <par> and <seq>
     * elements can point to multiple files or parts of files that together manifest a
     * division. More than one <fptr> element can be associated with a <div> element.
     * Typically sibling <fptr> elements represent alternative versions, or
     * manifestations, of the same content
     *
     * @return \App\Xsd\Mets\DivType\FptrAType[]
     */
    public function getFptrElName()
    {
        return $this->fptrElName;
    }

    /**
     * Sets a new fptrElName
     *
     * The <fptr> or file pointer element represents digital content that manifests its
     * parent <div> element. The content represented by an <fptr> element must consist
     * of integral files or parts of files that are represented by <file> elements in
     * the <fileSec>. Via its FILEID attribute, an <fptr> may point directly to a
     * single integral <file> element that manifests a structural division. However, an
     * <fptr> element may also govern an <area> element, a <par>, or a <seq> which in
     * turn would point to the relevant file or files. A child <area> element can point
     * to part of a <file> that manifests a division, while the <par> and <seq>
     * elements can point to multiple files or parts of files that together manifest a
     * division. More than one <fptr> element can be associated with a <div> element.
     * Typically sibling <fptr> elements represent alternative versions, or
     * manifestations, of the same content
     *
     * @param \App\Xsd\Mets\DivType\FptrAType[] $fptrElName
     * @return self
     */
    public function setFptrElName(array $fptrElName)
    {
        $this->fptrElName = $fptrElName;
        return $this;
    }

    /**
     * Adds as divElName
     *
     * @return self
     * @param \App\Xsd\Mets\DivType $divElName
     */
    public function addToDivElName(\App\Xsd\Mets\DivType $divElName)
    {
        $this->divElName[] = $divElName;
        return $this;
    }

    /**
     * isset divElName
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDivElName($index)
    {
        return isset($this->divElName[$index]);
    }

    /**
     * unset divElName
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDivElName($index)
    {
        unset($this->divElName[$index]);
    }

    /**
     * Gets as divElName
     *
     * @return \App\Xsd\Mets\DivType[]
     */
    public function getDivElName()
    {
        return $this->divElName;
    }

    /**
     * Sets a new divElName
     *
     * @param \App\Xsd\Mets\DivType[] $divElName
     * @return self
     */
    public function setDivElName(array $divElName)
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

