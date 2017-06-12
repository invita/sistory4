<?php

namespace App\Xsd\Mets;

/**
 * Class representing AmdSecType
 *
 * amdSecType: Complex Type for Administrative Metadata Sections
 *  The administrative metadata section consists of four possible subsidiary
 * sections: techMD (technical metadata for text/image/audio/video files), rightsMD
 * (intellectual property rights metadata), sourceMD (analog/digital source
 * metadata), and digiprovMD (digital provenance metadata, that is, the history of
 * migrations/translations performed on a digital library object from it's original
 * digital capture/encoding).
 * XSD Type: amdSecType
 */
class AmdSecType
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
     * A technical metadata element <techMD> records technical metadata about a
     * component of the METS object, such as a digital content file. The <techMD>
     * element conforms to same generic datatype as the <dmdSec>, <rightsMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A technical metadata element can either wrap the metadata (mdWrap)
     * or reference it in an external location (mdRef) or both. METS allows multiple
     * <techMD> elements; and technical metadata can be associated with any METS
     * element that supports an ADMID attribute. Technical metadata can be expressed
     * according to many current technical description standards (such as MIX and
     * textMD) or a locally produced XML schema.
     *
     * @property \App\Xsd\Mets\MdSecType[] $techMDElName
     */
    private $techMDElName = array(
        
    );

    /**
     * An intellectual property rights metadata element <rightsMD> records information
     * about copyright and licensing pertaining to a component of the METS object. The
     * <rightsMD> element conforms to same generic datatype as the <dmdSec>, <techMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A rights metadata element can either wrap the metadata (mdWrap) or
     * reference it in an external location (mdRef) or both. METS allows multiple
     * <rightsMD> elements; and rights metadata can be associated with any METS element
     * that supports an ADMID attribute. Rights metadata can be expressed according
     * current rights description standards (such as CopyrightMD and
     * rightsDeclarationMD) or a locally produced XML schema.
     *
     * @property \App\Xsd\Mets\MdSecType[] $rightsMDElName
     */
    private $rightsMDElName = array(
        
    );

    /**
     * A source metadata element <sourceMD> records descriptive and administrative
     * metadata about the source format or media of a component of the METS object such
     * as a digital content file. It is often used for discovery, data administration
     * or preservation of the digital object. The <sourceMD> element conforms to same
     * generic datatype as the <dmdSec>, <techMD>, <rightsMD>, and <digiprovMD>
     * elements, and supports the same sub-elements and attributes. A source metadata
     * element can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. METS allows multiple <sourceMD> elements; and source
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Source metadata can be expressed according to current source
     * description standards (such as PREMIS) or a locally produced XML schema.
     *
     * @property \App\Xsd\Mets\MdSecType[] $sourceMDElName
     */
    private $sourceMDElName = array(
        
    );

    /**
     * A digital provenance metadata element <digiprovMD> can be used to record any
     * preservation-related actions taken on the various files which comprise a digital
     * object (e.g., those subsequent to the initial digitization of the files such as
     * transformation or migrations) or, in the case of born digital materials, the
     * files’ creation. In short, digital provenance should be used to record
     * information that allows both archival/library staff and scholars to understand
     * what modifications have been made to a digital object and/or its constituent
     * parts during its life cycle. This information can then be used to judge how
     * those processes might have altered or corrupted the object’s ability to
     * accurately represent the original item. One might, for example, record master
     * derivative relationships and the process by which those derivations have been
     * created. Or the <digiprovMD> element could contain information regarding the
     * migration/transformation of a file from its original digitization (e.g., OCR,
     * TEI, etc.,)to its current incarnation as a digital object (e.g., JPEG2000). The
     * <digiprovMD> element conforms to same generic datatype as the <dmdSec>,
     * <techMD>, <rightsMD>, and <sourceMD> elements, and supports the same
     * sub-elements and attributes. A digital provenance metadata element can either
     * wrap the metadata (mdWrap) or reference it in an external location (mdRef) or
     * both. METS allows multiple <digiprovMD> elements; and digital provenance
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Digital provenance metadata can be expressed according to current
     * digital provenance description standards (such as PREMIS) or a locally produced
     * XML schema.
     *
     * @property \App\Xsd\Mets\MdSecType[] $digiprovMDElName
     */
    private $digiprovMDElName = array(
        
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
     * Adds as techMDElName
     *
     * A technical metadata element <techMD> records technical metadata about a
     * component of the METS object, such as a digital content file. The <techMD>
     * element conforms to same generic datatype as the <dmdSec>, <rightsMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A technical metadata element can either wrap the metadata (mdWrap)
     * or reference it in an external location (mdRef) or both. METS allows multiple
     * <techMD> elements; and technical metadata can be associated with any METS
     * element that supports an ADMID attribute. Technical metadata can be expressed
     * according to many current technical description standards (such as MIX and
     * textMD) or a locally produced XML schema.
     *
     * @return self
     * @param \App\Xsd\Mets\MdSecType $techMDElName
     */
    public function addToTechMDElName(\App\Xsd\Mets\MdSecType $techMDElName)
    {
        $this->techMDElName[] = $techMDElName;
        return $this;
    }

    /**
     * isset techMDElName
     *
     * A technical metadata element <techMD> records technical metadata about a
     * component of the METS object, such as a digital content file. The <techMD>
     * element conforms to same generic datatype as the <dmdSec>, <rightsMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A technical metadata element can either wrap the metadata (mdWrap)
     * or reference it in an external location (mdRef) or both. METS allows multiple
     * <techMD> elements; and technical metadata can be associated with any METS
     * element that supports an ADMID attribute. Technical metadata can be expressed
     * according to many current technical description standards (such as MIX and
     * textMD) or a locally produced XML schema.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTechMDElName($index)
    {
        return isset($this->techMDElName[$index]);
    }

    /**
     * unset techMDElName
     *
     * A technical metadata element <techMD> records technical metadata about a
     * component of the METS object, such as a digital content file. The <techMD>
     * element conforms to same generic datatype as the <dmdSec>, <rightsMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A technical metadata element can either wrap the metadata (mdWrap)
     * or reference it in an external location (mdRef) or both. METS allows multiple
     * <techMD> elements; and technical metadata can be associated with any METS
     * element that supports an ADMID attribute. Technical metadata can be expressed
     * according to many current technical description standards (such as MIX and
     * textMD) or a locally produced XML schema.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTechMDElName($index)
    {
        unset($this->techMDElName[$index]);
    }

    /**
     * Gets as techMDElName
     *
     * A technical metadata element <techMD> records technical metadata about a
     * component of the METS object, such as a digital content file. The <techMD>
     * element conforms to same generic datatype as the <dmdSec>, <rightsMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A technical metadata element can either wrap the metadata (mdWrap)
     * or reference it in an external location (mdRef) or both. METS allows multiple
     * <techMD> elements; and technical metadata can be associated with any METS
     * element that supports an ADMID attribute. Technical metadata can be expressed
     * according to many current technical description standards (such as MIX and
     * textMD) or a locally produced XML schema.
     *
     * @return \App\Xsd\Mets\MdSecType[]
     */
    public function getTechMDElName()
    {
        return $this->techMDElName;
    }

    /**
     * Sets a new techMDElName
     *
     * A technical metadata element <techMD> records technical metadata about a
     * component of the METS object, such as a digital content file. The <techMD>
     * element conforms to same generic datatype as the <dmdSec>, <rightsMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A technical metadata element can either wrap the metadata (mdWrap)
     * or reference it in an external location (mdRef) or both. METS allows multiple
     * <techMD> elements; and technical metadata can be associated with any METS
     * element that supports an ADMID attribute. Technical metadata can be expressed
     * according to many current technical description standards (such as MIX and
     * textMD) or a locally produced XML schema.
     *
     * @param \App\Xsd\Mets\MdSecType[] $techMDElName
     * @return self
     */
    public function setTechMDElName(array $techMDElName)
    {
        $this->techMDElName = $techMDElName;
        return $this;
    }

    /**
     * Adds as rightsMDElName
     *
     * An intellectual property rights metadata element <rightsMD> records information
     * about copyright and licensing pertaining to a component of the METS object. The
     * <rightsMD> element conforms to same generic datatype as the <dmdSec>, <techMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A rights metadata element can either wrap the metadata (mdWrap) or
     * reference it in an external location (mdRef) or both. METS allows multiple
     * <rightsMD> elements; and rights metadata can be associated with any METS element
     * that supports an ADMID attribute. Rights metadata can be expressed according
     * current rights description standards (such as CopyrightMD and
     * rightsDeclarationMD) or a locally produced XML schema.
     *
     * @return self
     * @param \App\Xsd\Mets\MdSecType $rightsMDElName
     */
    public function addToRightsMDElName(\App\Xsd\Mets\MdSecType $rightsMDElName)
    {
        $this->rightsMDElName[] = $rightsMDElName;
        return $this;
    }

    /**
     * isset rightsMDElName
     *
     * An intellectual property rights metadata element <rightsMD> records information
     * about copyright and licensing pertaining to a component of the METS object. The
     * <rightsMD> element conforms to same generic datatype as the <dmdSec>, <techMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A rights metadata element can either wrap the metadata (mdWrap) or
     * reference it in an external location (mdRef) or both. METS allows multiple
     * <rightsMD> elements; and rights metadata can be associated with any METS element
     * that supports an ADMID attribute. Rights metadata can be expressed according
     * current rights description standards (such as CopyrightMD and
     * rightsDeclarationMD) or a locally produced XML schema.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetRightsMDElName($index)
    {
        return isset($this->rightsMDElName[$index]);
    }

    /**
     * unset rightsMDElName
     *
     * An intellectual property rights metadata element <rightsMD> records information
     * about copyright and licensing pertaining to a component of the METS object. The
     * <rightsMD> element conforms to same generic datatype as the <dmdSec>, <techMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A rights metadata element can either wrap the metadata (mdWrap) or
     * reference it in an external location (mdRef) or both. METS allows multiple
     * <rightsMD> elements; and rights metadata can be associated with any METS element
     * that supports an ADMID attribute. Rights metadata can be expressed according
     * current rights description standards (such as CopyrightMD and
     * rightsDeclarationMD) or a locally produced XML schema.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetRightsMDElName($index)
    {
        unset($this->rightsMDElName[$index]);
    }

    /**
     * Gets as rightsMDElName
     *
     * An intellectual property rights metadata element <rightsMD> records information
     * about copyright and licensing pertaining to a component of the METS object. The
     * <rightsMD> element conforms to same generic datatype as the <dmdSec>, <techMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A rights metadata element can either wrap the metadata (mdWrap) or
     * reference it in an external location (mdRef) or both. METS allows multiple
     * <rightsMD> elements; and rights metadata can be associated with any METS element
     * that supports an ADMID attribute. Rights metadata can be expressed according
     * current rights description standards (such as CopyrightMD and
     * rightsDeclarationMD) or a locally produced XML schema.
     *
     * @return \App\Xsd\Mets\MdSecType[]
     */
    public function getRightsMDElName()
    {
        return $this->rightsMDElName;
    }

    /**
     * Sets a new rightsMDElName
     *
     * An intellectual property rights metadata element <rightsMD> records information
     * about copyright and licensing pertaining to a component of the METS object. The
     * <rightsMD> element conforms to same generic datatype as the <dmdSec>, <techMD>,
     * <sourceMD> and <digiprovMD> elements, and supports the same sub-elements and
     * attributes. A rights metadata element can either wrap the metadata (mdWrap) or
     * reference it in an external location (mdRef) or both. METS allows multiple
     * <rightsMD> elements; and rights metadata can be associated with any METS element
     * that supports an ADMID attribute. Rights metadata can be expressed according
     * current rights description standards (such as CopyrightMD and
     * rightsDeclarationMD) or a locally produced XML schema.
     *
     * @param \App\Xsd\Mets\MdSecType[] $rightsMDElName
     * @return self
     */
    public function setRightsMDElName(array $rightsMDElName)
    {
        $this->rightsMDElName = $rightsMDElName;
        return $this;
    }

    /**
     * Adds as sourceMDElName
     *
     * A source metadata element <sourceMD> records descriptive and administrative
     * metadata about the source format or media of a component of the METS object such
     * as a digital content file. It is often used for discovery, data administration
     * or preservation of the digital object. The <sourceMD> element conforms to same
     * generic datatype as the <dmdSec>, <techMD>, <rightsMD>, and <digiprovMD>
     * elements, and supports the same sub-elements and attributes. A source metadata
     * element can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. METS allows multiple <sourceMD> elements; and source
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Source metadata can be expressed according to current source
     * description standards (such as PREMIS) or a locally produced XML schema.
     *
     * @return self
     * @param \App\Xsd\Mets\MdSecType $sourceMDElName
     */
    public function addToSourceMDElName(\App\Xsd\Mets\MdSecType $sourceMDElName)
    {
        $this->sourceMDElName[] = $sourceMDElName;
        return $this;
    }

    /**
     * isset sourceMDElName
     *
     * A source metadata element <sourceMD> records descriptive and administrative
     * metadata about the source format or media of a component of the METS object such
     * as a digital content file. It is often used for discovery, data administration
     * or preservation of the digital object. The <sourceMD> element conforms to same
     * generic datatype as the <dmdSec>, <techMD>, <rightsMD>, and <digiprovMD>
     * elements, and supports the same sub-elements and attributes. A source metadata
     * element can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. METS allows multiple <sourceMD> elements; and source
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Source metadata can be expressed according to current source
     * description standards (such as PREMIS) or a locally produced XML schema.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSourceMDElName($index)
    {
        return isset($this->sourceMDElName[$index]);
    }

    /**
     * unset sourceMDElName
     *
     * A source metadata element <sourceMD> records descriptive and administrative
     * metadata about the source format or media of a component of the METS object such
     * as a digital content file. It is often used for discovery, data administration
     * or preservation of the digital object. The <sourceMD> element conforms to same
     * generic datatype as the <dmdSec>, <techMD>, <rightsMD>, and <digiprovMD>
     * elements, and supports the same sub-elements and attributes. A source metadata
     * element can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. METS allows multiple <sourceMD> elements; and source
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Source metadata can be expressed according to current source
     * description standards (such as PREMIS) or a locally produced XML schema.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSourceMDElName($index)
    {
        unset($this->sourceMDElName[$index]);
    }

    /**
     * Gets as sourceMDElName
     *
     * A source metadata element <sourceMD> records descriptive and administrative
     * metadata about the source format or media of a component of the METS object such
     * as a digital content file. It is often used for discovery, data administration
     * or preservation of the digital object. The <sourceMD> element conforms to same
     * generic datatype as the <dmdSec>, <techMD>, <rightsMD>, and <digiprovMD>
     * elements, and supports the same sub-elements and attributes. A source metadata
     * element can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. METS allows multiple <sourceMD> elements; and source
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Source metadata can be expressed according to current source
     * description standards (such as PREMIS) or a locally produced XML schema.
     *
     * @return \App\Xsd\Mets\MdSecType[]
     */
    public function getSourceMDElName()
    {
        return $this->sourceMDElName;
    }

    /**
     * Sets a new sourceMDElName
     *
     * A source metadata element <sourceMD> records descriptive and administrative
     * metadata about the source format or media of a component of the METS object such
     * as a digital content file. It is often used for discovery, data administration
     * or preservation of the digital object. The <sourceMD> element conforms to same
     * generic datatype as the <dmdSec>, <techMD>, <rightsMD>, and <digiprovMD>
     * elements, and supports the same sub-elements and attributes. A source metadata
     * element can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. METS allows multiple <sourceMD> elements; and source
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Source metadata can be expressed according to current source
     * description standards (such as PREMIS) or a locally produced XML schema.
     *
     * @param \App\Xsd\Mets\MdSecType[] $sourceMDElName
     * @return self
     */
    public function setSourceMDElName(array $sourceMDElName)
    {
        $this->sourceMDElName = $sourceMDElName;
        return $this;
    }

    /**
     * Adds as digiprovMDElName
     *
     * A digital provenance metadata element <digiprovMD> can be used to record any
     * preservation-related actions taken on the various files which comprise a digital
     * object (e.g., those subsequent to the initial digitization of the files such as
     * transformation or migrations) or, in the case of born digital materials, the
     * files’ creation. In short, digital provenance should be used to record
     * information that allows both archival/library staff and scholars to understand
     * what modifications have been made to a digital object and/or its constituent
     * parts during its life cycle. This information can then be used to judge how
     * those processes might have altered or corrupted the object’s ability to
     * accurately represent the original item. One might, for example, record master
     * derivative relationships and the process by which those derivations have been
     * created. Or the <digiprovMD> element could contain information regarding the
     * migration/transformation of a file from its original digitization (e.g., OCR,
     * TEI, etc.,)to its current incarnation as a digital object (e.g., JPEG2000). The
     * <digiprovMD> element conforms to same generic datatype as the <dmdSec>,
     * <techMD>, <rightsMD>, and <sourceMD> elements, and supports the same
     * sub-elements and attributes. A digital provenance metadata element can either
     * wrap the metadata (mdWrap) or reference it in an external location (mdRef) or
     * both. METS allows multiple <digiprovMD> elements; and digital provenance
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Digital provenance metadata can be expressed according to current
     * digital provenance description standards (such as PREMIS) or a locally produced
     * XML schema.
     *
     * @return self
     * @param \App\Xsd\Mets\MdSecType $digiprovMDElName
     */
    public function addToDigiprovMDElName(\App\Xsd\Mets\MdSecType $digiprovMDElName)
    {
        $this->digiprovMDElName[] = $digiprovMDElName;
        return $this;
    }

    /**
     * isset digiprovMDElName
     *
     * A digital provenance metadata element <digiprovMD> can be used to record any
     * preservation-related actions taken on the various files which comprise a digital
     * object (e.g., those subsequent to the initial digitization of the files such as
     * transformation or migrations) or, in the case of born digital materials, the
     * files’ creation. In short, digital provenance should be used to record
     * information that allows both archival/library staff and scholars to understand
     * what modifications have been made to a digital object and/or its constituent
     * parts during its life cycle. This information can then be used to judge how
     * those processes might have altered or corrupted the object’s ability to
     * accurately represent the original item. One might, for example, record master
     * derivative relationships and the process by which those derivations have been
     * created. Or the <digiprovMD> element could contain information regarding the
     * migration/transformation of a file from its original digitization (e.g., OCR,
     * TEI, etc.,)to its current incarnation as a digital object (e.g., JPEG2000). The
     * <digiprovMD> element conforms to same generic datatype as the <dmdSec>,
     * <techMD>, <rightsMD>, and <sourceMD> elements, and supports the same
     * sub-elements and attributes. A digital provenance metadata element can either
     * wrap the metadata (mdWrap) or reference it in an external location (mdRef) or
     * both. METS allows multiple <digiprovMD> elements; and digital provenance
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Digital provenance metadata can be expressed according to current
     * digital provenance description standards (such as PREMIS) or a locally produced
     * XML schema.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDigiprovMDElName($index)
    {
        return isset($this->digiprovMDElName[$index]);
    }

    /**
     * unset digiprovMDElName
     *
     * A digital provenance metadata element <digiprovMD> can be used to record any
     * preservation-related actions taken on the various files which comprise a digital
     * object (e.g., those subsequent to the initial digitization of the files such as
     * transformation or migrations) or, in the case of born digital materials, the
     * files’ creation. In short, digital provenance should be used to record
     * information that allows both archival/library staff and scholars to understand
     * what modifications have been made to a digital object and/or its constituent
     * parts during its life cycle. This information can then be used to judge how
     * those processes might have altered or corrupted the object’s ability to
     * accurately represent the original item. One might, for example, record master
     * derivative relationships and the process by which those derivations have been
     * created. Or the <digiprovMD> element could contain information regarding the
     * migration/transformation of a file from its original digitization (e.g., OCR,
     * TEI, etc.,)to its current incarnation as a digital object (e.g., JPEG2000). The
     * <digiprovMD> element conforms to same generic datatype as the <dmdSec>,
     * <techMD>, <rightsMD>, and <sourceMD> elements, and supports the same
     * sub-elements and attributes. A digital provenance metadata element can either
     * wrap the metadata (mdWrap) or reference it in an external location (mdRef) or
     * both. METS allows multiple <digiprovMD> elements; and digital provenance
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Digital provenance metadata can be expressed according to current
     * digital provenance description standards (such as PREMIS) or a locally produced
     * XML schema.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDigiprovMDElName($index)
    {
        unset($this->digiprovMDElName[$index]);
    }

    /**
     * Gets as digiprovMDElName
     *
     * A digital provenance metadata element <digiprovMD> can be used to record any
     * preservation-related actions taken on the various files which comprise a digital
     * object (e.g., those subsequent to the initial digitization of the files such as
     * transformation or migrations) or, in the case of born digital materials, the
     * files’ creation. In short, digital provenance should be used to record
     * information that allows both archival/library staff and scholars to understand
     * what modifications have been made to a digital object and/or its constituent
     * parts during its life cycle. This information can then be used to judge how
     * those processes might have altered or corrupted the object’s ability to
     * accurately represent the original item. One might, for example, record master
     * derivative relationships and the process by which those derivations have been
     * created. Or the <digiprovMD> element could contain information regarding the
     * migration/transformation of a file from its original digitization (e.g., OCR,
     * TEI, etc.,)to its current incarnation as a digital object (e.g., JPEG2000). The
     * <digiprovMD> element conforms to same generic datatype as the <dmdSec>,
     * <techMD>, <rightsMD>, and <sourceMD> elements, and supports the same
     * sub-elements and attributes. A digital provenance metadata element can either
     * wrap the metadata (mdWrap) or reference it in an external location (mdRef) or
     * both. METS allows multiple <digiprovMD> elements; and digital provenance
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Digital provenance metadata can be expressed according to current
     * digital provenance description standards (such as PREMIS) or a locally produced
     * XML schema.
     *
     * @return \App\Xsd\Mets\MdSecType[]
     */
    public function getDigiprovMDElName()
    {
        return $this->digiprovMDElName;
    }

    /**
     * Sets a new digiprovMDElName
     *
     * A digital provenance metadata element <digiprovMD> can be used to record any
     * preservation-related actions taken on the various files which comprise a digital
     * object (e.g., those subsequent to the initial digitization of the files such as
     * transformation or migrations) or, in the case of born digital materials, the
     * files’ creation. In short, digital provenance should be used to record
     * information that allows both archival/library staff and scholars to understand
     * what modifications have been made to a digital object and/or its constituent
     * parts during its life cycle. This information can then be used to judge how
     * those processes might have altered or corrupted the object’s ability to
     * accurately represent the original item. One might, for example, record master
     * derivative relationships and the process by which those derivations have been
     * created. Or the <digiprovMD> element could contain information regarding the
     * migration/transformation of a file from its original digitization (e.g., OCR,
     * TEI, etc.,)to its current incarnation as a digital object (e.g., JPEG2000). The
     * <digiprovMD> element conforms to same generic datatype as the <dmdSec>,
     * <techMD>, <rightsMD>, and <sourceMD> elements, and supports the same
     * sub-elements and attributes. A digital provenance metadata element can either
     * wrap the metadata (mdWrap) or reference it in an external location (mdRef) or
     * both. METS allows multiple <digiprovMD> elements; and digital provenance
     * metadata can be associated with any METS element that supports an ADMID
     * attribute. Digital provenance metadata can be expressed according to current
     * digital provenance description standards (such as PREMIS) or a locally produced
     * XML schema.
     *
     * @param \App\Xsd\Mets\MdSecType[] $digiprovMDElName
     * @return self
     */
    public function setDigiprovMDElName(array $digiprovMDElName)
    {
        $this->digiprovMDElName = $digiprovMDElName;
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

