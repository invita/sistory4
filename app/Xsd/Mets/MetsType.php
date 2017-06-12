<?php

namespace App\Xsd\Mets;

/**
 * Class representing MetsType
 *
 * metsType: Complex Type for METS Sections
 *  A METS document consists of seven possible subsidiary sections: metsHdr (METS
 * document header), dmdSec (descriptive metadata section), amdSec (administrative
 * metadata section), fileGrp (file inventory group), structLink (structural map
 * linking), structMap (structural map) and behaviorSec (behaviors section).
 * XSD Type: metsType
 */
class MetsType
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
     * OBJID (string/O): Is the primary identifier assigned to the METS object as a
     * whole. Although this attribute is not required, it is strongly recommended. This
     * identifier is used to tag the entire METS object to external systems, in
     * contrast with the ID identifier.
     *
     * @property string $OBJIDAttrName
     */
    private $OBJIDAttrName = null;

    /**
     * LABEL (string/O): Is a simple title string used to identify the object/entity
     * being described in the METS document for the user.
     *
     * @property string $LABELAttrName
     */
    private $LABELAttrName = null;

    /**
     * TYPE (string/O): Specifies the class or type of the object, e.g.: book, journal,
     * stereograph, dataset, video, etc.
     *
     * @property string $TYPEAttrName
     */
    private $TYPEAttrName = null;

    /**
     * PROFILE (string/O): Indicates to which of the registered profile(s) the METS
     * document conforms. For additional information about PROFILES see Chapter 5 of
     * the METS Primer.
     *
     * @property string $PROFILEAttrName
     */
    private $PROFILEAttrName = null;

    /**
     * The mets header element <metsHdr> captures metadata about the METS document
     * itself, not the digital object the METS document encodes. Although it records a
     * more limited set of metadata, it is very similar in function and purpose to the
     * headers employed in other schema such as the Text Encoding Initiative (TEI) or
     * in the Encoded Archival Description (EAD).
     *
     * @property \App\Xsd\Mets\MetsType\MetsHdrAType $metsHdrElName
     */
    private $metsHdrElName = null;

    /**
     * A descriptive metadata section <dmdSec> records descriptive metadata pertaining
     * to the METS object as a whole or one of its components. The <dmdSec> element
     * conforms to same generic datatype as the <techMD>, <rightsMD>, <sourceMD> and
     * <digiprovMD> elements, and supports the same sub-elements and attributes. A
     * descriptive metadata element can either wrap the metadata (mdWrap) or reference
     * it in an external location (mdRef) or both. METS allows multiple <dmdSec>
     * elements; and descriptive metadata can be associated with any METS element that
     * supports a DMDID attribute. Descriptive metadata can be expressed according to
     * many current description standards (i.e., MARC, MODS, Dublin Core, TEI Header,
     * EAD, VRA, FGDC, DDI) or a locally produced XML schema.
     *
     * @property \App\Xsd\Mets\MdSecType[] $dmdSecElName
     */
    private $dmdSecElName = array(
        
    );

    /**
     * The administrative metadata section <amdSec> contains the administrative
     * metadata pertaining to the digital object, its components and any original
     * source material from which the digital object is derived. The <amdSec> is
     * separated into four sub-sections that accommodate technical metadata (techMD),
     * intellectual property rights (rightsMD), analog/digital source metadata
     * (sourceMD), and digital provenance metadata (digiprovMD). Each of these
     * subsections can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. Multiple instances of the <amdSec> element can occur
     * within a METS document and multiple instances of its subsections can occur in
     * one <amdSec> element. This allows considerable flexibility in the structuring of
     * the administrative metadata. METS does not define a vocabulary or syntax for
     * encoding administrative metadata. Administrative metadata can be expressed
     * within the amdSec sub-elements according to many current community defined
     * standards, or locally produced XML schemas.
     *
     * @property \App\Xsd\Mets\AmdSecType[] $amdSecElName
     */
    private $amdSecElName = array(
        
    );

    /**
     * The overall purpose of the content file section element <fileSec> is to provide
     * an inventory of and the location for the content files that comprise the digital
     * object being described in the METS document.
     *
     * @property \App\Xsd\Mets\MetsType\FileSecAType $fileSecElName
     */
    private $fileSecElName = null;

    /**
     * The structural map section <structMap> is the heart of a METS document. It
     * provides a means for organizing the digital content represented by the <file>
     * elements in the <fileSec> of the METS document into a coherent hierarchical
     * structure. Such a hierarchical structure can be presented to users to facilitate
     * their comprehension and navigation of the digital content. It can further be
     * applied to any purpose requiring an understanding of the structural relationship
     * of the content files or parts of the content files. The organization may be
     * specified to any level of granularity (intellectual and or physical) that is
     * desired. Since the <structMap> element is repeatable, more than one organization
     * can be applied to the digital content represented by the METS document. The
     * hierarchical structure specified by a <structMap> is encoded as a tree of nested
     * <div> elements. A <div> element may directly point to content via child file
     * pointer <fptr> elements (if the content is represented in the <fileSec<) or
     * child METS pointer <mptr> elements (if the content is represented by an external
     * METS document). The <fptr> element may point to a single whole <file> element
     * that manifests its parent <div<, or to part of a <file> that manifests its
     * <div<. It can also point to multiple files or parts of files that must be
     * played/displayed either in sequence or in parallel to reveal its structural
     * division. In addition to providing a means for organizing content, the
     * <structMap> provides a mechanism for linking content at any hierarchical level
     * with relevant descriptive and administrative metadata.
     *
     * @property \App\Xsd\Mets\StructMapType[] $structMapElName
     */
    private $structMapElName = array(
        
    );

    /**
     * The structural link section element <structLink> allows for the specification of
     * hyperlinks between the different components of a METS structure that are
     * delineated in a structural map. This element is a container for a single,
     * repeatable element, <smLink> which indicates a hyperlink between two nodes in
     * the structural map. The <structLink> section in the METS document is identified
     * using its XML ID attributes.
     *
     * @property \App\Xsd\Mets\MetsType\StructLinkAType $structLinkElName
     */
    private $structLinkElName = null;

    /**
     * A behavior section element <behaviorSec> associates executable behaviors with
     * content in the METS document by means of a repeatable behavior <behavior>
     * element. This element has an interface definition <interfaceDef> element that
     * represents an abstract definition of the set of behaviors represented by a
     * particular behavior section. A <behavior> element also has a <mechanism> element
     * which is used to point to a module of executable code that implements and runs
     * the behavior defined by the interface definition. The <behaviorSec> element,
     * which is repeatable as well as nestable, can be used to group individual
     * behaviors within the structure of the METS document. Such grouping can be useful
     * for organizing families of behaviors together or to indicate other relationships
     * between particular behaviors.
     *
     * @property \App\Xsd\Mets\BehaviorSecType[] $behaviorSecElName
     */
    private $behaviorSecElName = array(
        
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
     * Gets as OBJIDAttrName
     *
     * OBJID (string/O): Is the primary identifier assigned to the METS object as a
     * whole. Although this attribute is not required, it is strongly recommended. This
     * identifier is used to tag the entire METS object to external systems, in
     * contrast with the ID identifier.
     *
     * @return string
     */
    public function getOBJIDAttrName()
    {
        return $this->OBJIDAttrName;
    }

    /**
     * Sets a new OBJIDAttrName
     *
     * OBJID (string/O): Is the primary identifier assigned to the METS object as a
     * whole. Although this attribute is not required, it is strongly recommended. This
     * identifier is used to tag the entire METS object to external systems, in
     * contrast with the ID identifier.
     *
     * @param string $OBJIDAttrName
     * @return self
     */
    public function setOBJIDAttrName($OBJIDAttrName)
    {
        $this->OBJIDAttrName = $OBJIDAttrName;
        return $this;
    }

    /**
     * Gets as LABELAttrName
     *
     * LABEL (string/O): Is a simple title string used to identify the object/entity
     * being described in the METS document for the user.
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
     * LABEL (string/O): Is a simple title string used to identify the object/entity
     * being described in the METS document for the user.
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
     * Gets as TYPEAttrName
     *
     * TYPE (string/O): Specifies the class or type of the object, e.g.: book, journal,
     * stereograph, dataset, video, etc.
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
     * TYPE (string/O): Specifies the class or type of the object, e.g.: book, journal,
     * stereograph, dataset, video, etc.
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
     * Gets as PROFILEAttrName
     *
     * PROFILE (string/O): Indicates to which of the registered profile(s) the METS
     * document conforms. For additional information about PROFILES see Chapter 5 of
     * the METS Primer.
     *
     * @return string
     */
    public function getPROFILEAttrName()
    {
        return $this->PROFILEAttrName;
    }

    /**
     * Sets a new PROFILEAttrName
     *
     * PROFILE (string/O): Indicates to which of the registered profile(s) the METS
     * document conforms. For additional information about PROFILES see Chapter 5 of
     * the METS Primer.
     *
     * @param string $PROFILEAttrName
     * @return self
     */
    public function setPROFILEAttrName($PROFILEAttrName)
    {
        $this->PROFILEAttrName = $PROFILEAttrName;
        return $this;
    }

    /**
     * Gets as metsHdrElName
     *
     * The mets header element <metsHdr> captures metadata about the METS document
     * itself, not the digital object the METS document encodes. Although it records a
     * more limited set of metadata, it is very similar in function and purpose to the
     * headers employed in other schema such as the Text Encoding Initiative (TEI) or
     * in the Encoded Archival Description (EAD).
     *
     * @return \App\Xsd\Mets\MetsType\MetsHdrAType
     */
    public function getMetsHdrElName()
    {
        return $this->metsHdrElName;
    }

    /**
     * Sets a new metsHdrElName
     *
     * The mets header element <metsHdr> captures metadata about the METS document
     * itself, not the digital object the METS document encodes. Although it records a
     * more limited set of metadata, it is very similar in function and purpose to the
     * headers employed in other schema such as the Text Encoding Initiative (TEI) or
     * in the Encoded Archival Description (EAD).
     *
     * @param \App\Xsd\Mets\MetsType\MetsHdrAType $metsHdrElName
     * @return self
     */
    public function setMetsHdrElName(\App\Xsd\Mets\MetsType\MetsHdrAType $metsHdrElName)
    {
        $this->metsHdrElName = $metsHdrElName;
        return $this;
    }

    /**
     * Adds as dmdSecElName
     *
     * A descriptive metadata section <dmdSec> records descriptive metadata pertaining
     * to the METS object as a whole or one of its components. The <dmdSec> element
     * conforms to same generic datatype as the <techMD>, <rightsMD>, <sourceMD> and
     * <digiprovMD> elements, and supports the same sub-elements and attributes. A
     * descriptive metadata element can either wrap the metadata (mdWrap) or reference
     * it in an external location (mdRef) or both. METS allows multiple <dmdSec>
     * elements; and descriptive metadata can be associated with any METS element that
     * supports a DMDID attribute. Descriptive metadata can be expressed according to
     * many current description standards (i.e., MARC, MODS, Dublin Core, TEI Header,
     * EAD, VRA, FGDC, DDI) or a locally produced XML schema.
     *
     * @return self
     * @param \App\Xsd\Mets\MdSecType $dmdSecElName
     */
    public function addToDmdSecElName(\App\Xsd\Mets\MdSecType $dmdSecElName)
    {
        $this->dmdSecElName[] = $dmdSecElName;
        return $this;
    }

    /**
     * isset dmdSecElName
     *
     * A descriptive metadata section <dmdSec> records descriptive metadata pertaining
     * to the METS object as a whole or one of its components. The <dmdSec> element
     * conforms to same generic datatype as the <techMD>, <rightsMD>, <sourceMD> and
     * <digiprovMD> elements, and supports the same sub-elements and attributes. A
     * descriptive metadata element can either wrap the metadata (mdWrap) or reference
     * it in an external location (mdRef) or both. METS allows multiple <dmdSec>
     * elements; and descriptive metadata can be associated with any METS element that
     * supports a DMDID attribute. Descriptive metadata can be expressed according to
     * many current description standards (i.e., MARC, MODS, Dublin Core, TEI Header,
     * EAD, VRA, FGDC, DDI) or a locally produced XML schema.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDmdSecElName($index)
    {
        return isset($this->dmdSecElName[$index]);
    }

    /**
     * unset dmdSecElName
     *
     * A descriptive metadata section <dmdSec> records descriptive metadata pertaining
     * to the METS object as a whole or one of its components. The <dmdSec> element
     * conforms to same generic datatype as the <techMD>, <rightsMD>, <sourceMD> and
     * <digiprovMD> elements, and supports the same sub-elements and attributes. A
     * descriptive metadata element can either wrap the metadata (mdWrap) or reference
     * it in an external location (mdRef) or both. METS allows multiple <dmdSec>
     * elements; and descriptive metadata can be associated with any METS element that
     * supports a DMDID attribute. Descriptive metadata can be expressed according to
     * many current description standards (i.e., MARC, MODS, Dublin Core, TEI Header,
     * EAD, VRA, FGDC, DDI) or a locally produced XML schema.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDmdSecElName($index)
    {
        unset($this->dmdSecElName[$index]);
    }

    /**
     * Gets as dmdSecElName
     *
     * A descriptive metadata section <dmdSec> records descriptive metadata pertaining
     * to the METS object as a whole or one of its components. The <dmdSec> element
     * conforms to same generic datatype as the <techMD>, <rightsMD>, <sourceMD> and
     * <digiprovMD> elements, and supports the same sub-elements and attributes. A
     * descriptive metadata element can either wrap the metadata (mdWrap) or reference
     * it in an external location (mdRef) or both. METS allows multiple <dmdSec>
     * elements; and descriptive metadata can be associated with any METS element that
     * supports a DMDID attribute. Descriptive metadata can be expressed according to
     * many current description standards (i.e., MARC, MODS, Dublin Core, TEI Header,
     * EAD, VRA, FGDC, DDI) or a locally produced XML schema.
     *
     * @return \App\Xsd\Mets\MdSecType[]
     */
    public function getDmdSecElName()
    {
        return $this->dmdSecElName;
    }

    /**
     * Sets a new dmdSecElName
     *
     * A descriptive metadata section <dmdSec> records descriptive metadata pertaining
     * to the METS object as a whole or one of its components. The <dmdSec> element
     * conforms to same generic datatype as the <techMD>, <rightsMD>, <sourceMD> and
     * <digiprovMD> elements, and supports the same sub-elements and attributes. A
     * descriptive metadata element can either wrap the metadata (mdWrap) or reference
     * it in an external location (mdRef) or both. METS allows multiple <dmdSec>
     * elements; and descriptive metadata can be associated with any METS element that
     * supports a DMDID attribute. Descriptive metadata can be expressed according to
     * many current description standards (i.e., MARC, MODS, Dublin Core, TEI Header,
     * EAD, VRA, FGDC, DDI) or a locally produced XML schema.
     *
     * @param \App\Xsd\Mets\MdSecType[] $dmdSecElName
     * @return self
     */
    public function setDmdSecElName(array $dmdSecElName)
    {
        $this->dmdSecElName = $dmdSecElName;
        return $this;
    }

    /**
     * Adds as amdSecElName
     *
     * The administrative metadata section <amdSec> contains the administrative
     * metadata pertaining to the digital object, its components and any original
     * source material from which the digital object is derived. The <amdSec> is
     * separated into four sub-sections that accommodate technical metadata (techMD),
     * intellectual property rights (rightsMD), analog/digital source metadata
     * (sourceMD), and digital provenance metadata (digiprovMD). Each of these
     * subsections can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. Multiple instances of the <amdSec> element can occur
     * within a METS document and multiple instances of its subsections can occur in
     * one <amdSec> element. This allows considerable flexibility in the structuring of
     * the administrative metadata. METS does not define a vocabulary or syntax for
     * encoding administrative metadata. Administrative metadata can be expressed
     * within the amdSec sub-elements according to many current community defined
     * standards, or locally produced XML schemas.
     *
     * @return self
     * @param \App\Xsd\Mets\AmdSecType $amdSecElName
     */
    public function addToAmdSecElName(\App\Xsd\Mets\AmdSecType $amdSecElName)
    {
        $this->amdSecElName[] = $amdSecElName;
        return $this;
    }

    /**
     * isset amdSecElName
     *
     * The administrative metadata section <amdSec> contains the administrative
     * metadata pertaining to the digital object, its components and any original
     * source material from which the digital object is derived. The <amdSec> is
     * separated into four sub-sections that accommodate technical metadata (techMD),
     * intellectual property rights (rightsMD), analog/digital source metadata
     * (sourceMD), and digital provenance metadata (digiprovMD). Each of these
     * subsections can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. Multiple instances of the <amdSec> element can occur
     * within a METS document and multiple instances of its subsections can occur in
     * one <amdSec> element. This allows considerable flexibility in the structuring of
     * the administrative metadata. METS does not define a vocabulary or syntax for
     * encoding administrative metadata. Administrative metadata can be expressed
     * within the amdSec sub-elements according to many current community defined
     * standards, or locally produced XML schemas.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAmdSecElName($index)
    {
        return isset($this->amdSecElName[$index]);
    }

    /**
     * unset amdSecElName
     *
     * The administrative metadata section <amdSec> contains the administrative
     * metadata pertaining to the digital object, its components and any original
     * source material from which the digital object is derived. The <amdSec> is
     * separated into four sub-sections that accommodate technical metadata (techMD),
     * intellectual property rights (rightsMD), analog/digital source metadata
     * (sourceMD), and digital provenance metadata (digiprovMD). Each of these
     * subsections can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. Multiple instances of the <amdSec> element can occur
     * within a METS document and multiple instances of its subsections can occur in
     * one <amdSec> element. This allows considerable flexibility in the structuring of
     * the administrative metadata. METS does not define a vocabulary or syntax for
     * encoding administrative metadata. Administrative metadata can be expressed
     * within the amdSec sub-elements according to many current community defined
     * standards, or locally produced XML schemas.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAmdSecElName($index)
    {
        unset($this->amdSecElName[$index]);
    }

    /**
     * Gets as amdSecElName
     *
     * The administrative metadata section <amdSec> contains the administrative
     * metadata pertaining to the digital object, its components and any original
     * source material from which the digital object is derived. The <amdSec> is
     * separated into four sub-sections that accommodate technical metadata (techMD),
     * intellectual property rights (rightsMD), analog/digital source metadata
     * (sourceMD), and digital provenance metadata (digiprovMD). Each of these
     * subsections can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. Multiple instances of the <amdSec> element can occur
     * within a METS document and multiple instances of its subsections can occur in
     * one <amdSec> element. This allows considerable flexibility in the structuring of
     * the administrative metadata. METS does not define a vocabulary or syntax for
     * encoding administrative metadata. Administrative metadata can be expressed
     * within the amdSec sub-elements according to many current community defined
     * standards, or locally produced XML schemas.
     *
     * @return \App\Xsd\Mets\AmdSecType[]
     */
    public function getAmdSecElName()
    {
        return $this->amdSecElName;
    }

    /**
     * Sets a new amdSecElName
     *
     * The administrative metadata section <amdSec> contains the administrative
     * metadata pertaining to the digital object, its components and any original
     * source material from which the digital object is derived. The <amdSec> is
     * separated into four sub-sections that accommodate technical metadata (techMD),
     * intellectual property rights (rightsMD), analog/digital source metadata
     * (sourceMD), and digital provenance metadata (digiprovMD). Each of these
     * subsections can either wrap the metadata (mdWrap) or reference it in an external
     * location (mdRef) or both. Multiple instances of the <amdSec> element can occur
     * within a METS document and multiple instances of its subsections can occur in
     * one <amdSec> element. This allows considerable flexibility in the structuring of
     * the administrative metadata. METS does not define a vocabulary or syntax for
     * encoding administrative metadata. Administrative metadata can be expressed
     * within the amdSec sub-elements according to many current community defined
     * standards, or locally produced XML schemas.
     *
     * @param \App\Xsd\Mets\AmdSecType[] $amdSecElName
     * @return self
     */
    public function setAmdSecElName(array $amdSecElName)
    {
        $this->amdSecElName = $amdSecElName;
        return $this;
    }

    /**
     * Gets as fileSecElName
     *
     * The overall purpose of the content file section element <fileSec> is to provide
     * an inventory of and the location for the content files that comprise the digital
     * object being described in the METS document.
     *
     * @return \App\Xsd\Mets\MetsType\FileSecAType
     */
    public function getFileSecElName()
    {
        return $this->fileSecElName;
    }

    /**
     * Sets a new fileSecElName
     *
     * The overall purpose of the content file section element <fileSec> is to provide
     * an inventory of and the location for the content files that comprise the digital
     * object being described in the METS document.
     *
     * @param \App\Xsd\Mets\MetsType\FileSecAType $fileSecElName
     * @return self
     */
    public function setFileSecElName(\App\Xsd\Mets\MetsType\FileSecAType $fileSecElName)
    {
        $this->fileSecElName = $fileSecElName;
        return $this;
    }

    /**
     * Adds as structMapElName
     *
     * The structural map section <structMap> is the heart of a METS document. It
     * provides a means for organizing the digital content represented by the <file>
     * elements in the <fileSec> of the METS document into a coherent hierarchical
     * structure. Such a hierarchical structure can be presented to users to facilitate
     * their comprehension and navigation of the digital content. It can further be
     * applied to any purpose requiring an understanding of the structural relationship
     * of the content files or parts of the content files. The organization may be
     * specified to any level of granularity (intellectual and or physical) that is
     * desired. Since the <structMap> element is repeatable, more than one organization
     * can be applied to the digital content represented by the METS document. The
     * hierarchical structure specified by a <structMap> is encoded as a tree of nested
     * <div> elements. A <div> element may directly point to content via child file
     * pointer <fptr> elements (if the content is represented in the <fileSec<) or
     * child METS pointer <mptr> elements (if the content is represented by an external
     * METS document). The <fptr> element may point to a single whole <file> element
     * that manifests its parent <div<, or to part of a <file> that manifests its
     * <div<. It can also point to multiple files or parts of files that must be
     * played/displayed either in sequence or in parallel to reveal its structural
     * division. In addition to providing a means for organizing content, the
     * <structMap> provides a mechanism for linking content at any hierarchical level
     * with relevant descriptive and administrative metadata.
     *
     * @return self
     * @param \App\Xsd\Mets\StructMapType $structMapElName
     */
    public function addToStructMapElName(\App\Xsd\Mets\StructMapType $structMapElName)
    {
        $this->structMapElName[] = $structMapElName;
        return $this;
    }

    /**
     * isset structMapElName
     *
     * The structural map section <structMap> is the heart of a METS document. It
     * provides a means for organizing the digital content represented by the <file>
     * elements in the <fileSec> of the METS document into a coherent hierarchical
     * structure. Such a hierarchical structure can be presented to users to facilitate
     * their comprehension and navigation of the digital content. It can further be
     * applied to any purpose requiring an understanding of the structural relationship
     * of the content files or parts of the content files. The organization may be
     * specified to any level of granularity (intellectual and or physical) that is
     * desired. Since the <structMap> element is repeatable, more than one organization
     * can be applied to the digital content represented by the METS document. The
     * hierarchical structure specified by a <structMap> is encoded as a tree of nested
     * <div> elements. A <div> element may directly point to content via child file
     * pointer <fptr> elements (if the content is represented in the <fileSec<) or
     * child METS pointer <mptr> elements (if the content is represented by an external
     * METS document). The <fptr> element may point to a single whole <file> element
     * that manifests its parent <div<, or to part of a <file> that manifests its
     * <div<. It can also point to multiple files or parts of files that must be
     * played/displayed either in sequence or in parallel to reveal its structural
     * division. In addition to providing a means for organizing content, the
     * <structMap> provides a mechanism for linking content at any hierarchical level
     * with relevant descriptive and administrative metadata.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetStructMapElName($index)
    {
        return isset($this->structMapElName[$index]);
    }

    /**
     * unset structMapElName
     *
     * The structural map section <structMap> is the heart of a METS document. It
     * provides a means for organizing the digital content represented by the <file>
     * elements in the <fileSec> of the METS document into a coherent hierarchical
     * structure. Such a hierarchical structure can be presented to users to facilitate
     * their comprehension and navigation of the digital content. It can further be
     * applied to any purpose requiring an understanding of the structural relationship
     * of the content files or parts of the content files. The organization may be
     * specified to any level of granularity (intellectual and or physical) that is
     * desired. Since the <structMap> element is repeatable, more than one organization
     * can be applied to the digital content represented by the METS document. The
     * hierarchical structure specified by a <structMap> is encoded as a tree of nested
     * <div> elements. A <div> element may directly point to content via child file
     * pointer <fptr> elements (if the content is represented in the <fileSec<) or
     * child METS pointer <mptr> elements (if the content is represented by an external
     * METS document). The <fptr> element may point to a single whole <file> element
     * that manifests its parent <div<, or to part of a <file> that manifests its
     * <div<. It can also point to multiple files or parts of files that must be
     * played/displayed either in sequence or in parallel to reveal its structural
     * division. In addition to providing a means for organizing content, the
     * <structMap> provides a mechanism for linking content at any hierarchical level
     * with relevant descriptive and administrative metadata.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetStructMapElName($index)
    {
        unset($this->structMapElName[$index]);
    }

    /**
     * Gets as structMapElName
     *
     * The structural map section <structMap> is the heart of a METS document. It
     * provides a means for organizing the digital content represented by the <file>
     * elements in the <fileSec> of the METS document into a coherent hierarchical
     * structure. Such a hierarchical structure can be presented to users to facilitate
     * their comprehension and navigation of the digital content. It can further be
     * applied to any purpose requiring an understanding of the structural relationship
     * of the content files or parts of the content files. The organization may be
     * specified to any level of granularity (intellectual and or physical) that is
     * desired. Since the <structMap> element is repeatable, more than one organization
     * can be applied to the digital content represented by the METS document. The
     * hierarchical structure specified by a <structMap> is encoded as a tree of nested
     * <div> elements. A <div> element may directly point to content via child file
     * pointer <fptr> elements (if the content is represented in the <fileSec<) or
     * child METS pointer <mptr> elements (if the content is represented by an external
     * METS document). The <fptr> element may point to a single whole <file> element
     * that manifests its parent <div<, or to part of a <file> that manifests its
     * <div<. It can also point to multiple files or parts of files that must be
     * played/displayed either in sequence or in parallel to reveal its structural
     * division. In addition to providing a means for organizing content, the
     * <structMap> provides a mechanism for linking content at any hierarchical level
     * with relevant descriptive and administrative metadata.
     *
     * @return \App\Xsd\Mets\StructMapType[]
     */
    public function getStructMapElName()
    {
        return $this->structMapElName;
    }

    /**
     * Sets a new structMapElName
     *
     * The structural map section <structMap> is the heart of a METS document. It
     * provides a means for organizing the digital content represented by the <file>
     * elements in the <fileSec> of the METS document into a coherent hierarchical
     * structure. Such a hierarchical structure can be presented to users to facilitate
     * their comprehension and navigation of the digital content. It can further be
     * applied to any purpose requiring an understanding of the structural relationship
     * of the content files or parts of the content files. The organization may be
     * specified to any level of granularity (intellectual and or physical) that is
     * desired. Since the <structMap> element is repeatable, more than one organization
     * can be applied to the digital content represented by the METS document. The
     * hierarchical structure specified by a <structMap> is encoded as a tree of nested
     * <div> elements. A <div> element may directly point to content via child file
     * pointer <fptr> elements (if the content is represented in the <fileSec<) or
     * child METS pointer <mptr> elements (if the content is represented by an external
     * METS document). The <fptr> element may point to a single whole <file> element
     * that manifests its parent <div<, or to part of a <file> that manifests its
     * <div<. It can also point to multiple files or parts of files that must be
     * played/displayed either in sequence or in parallel to reveal its structural
     * division. In addition to providing a means for organizing content, the
     * <structMap> provides a mechanism for linking content at any hierarchical level
     * with relevant descriptive and administrative metadata.
     *
     * @param \App\Xsd\Mets\StructMapType[] $structMapElName
     * @return self
     */
    public function setStructMapElName(array $structMapElName)
    {
        $this->structMapElName = $structMapElName;
        return $this;
    }

    /**
     * Gets as structLinkElName
     *
     * The structural link section element <structLink> allows for the specification of
     * hyperlinks between the different components of a METS structure that are
     * delineated in a structural map. This element is a container for a single,
     * repeatable element, <smLink> which indicates a hyperlink between two nodes in
     * the structural map. The <structLink> section in the METS document is identified
     * using its XML ID attributes.
     *
     * @return \App\Xsd\Mets\MetsType\StructLinkAType
     */
    public function getStructLinkElName()
    {
        return $this->structLinkElName;
    }

    /**
     * Sets a new structLinkElName
     *
     * The structural link section element <structLink> allows for the specification of
     * hyperlinks between the different components of a METS structure that are
     * delineated in a structural map. This element is a container for a single,
     * repeatable element, <smLink> which indicates a hyperlink between two nodes in
     * the structural map. The <structLink> section in the METS document is identified
     * using its XML ID attributes.
     *
     * @param \App\Xsd\Mets\MetsType\StructLinkAType $structLinkElName
     * @return self
     */
    public function setStructLinkElName(\App\Xsd\Mets\MetsType\StructLinkAType $structLinkElName)
    {
        $this->structLinkElName = $structLinkElName;
        return $this;
    }

    /**
     * Adds as behaviorSecElName
     *
     * A behavior section element <behaviorSec> associates executable behaviors with
     * content in the METS document by means of a repeatable behavior <behavior>
     * element. This element has an interface definition <interfaceDef> element that
     * represents an abstract definition of the set of behaviors represented by a
     * particular behavior section. A <behavior> element also has a <mechanism> element
     * which is used to point to a module of executable code that implements and runs
     * the behavior defined by the interface definition. The <behaviorSec> element,
     * which is repeatable as well as nestable, can be used to group individual
     * behaviors within the structure of the METS document. Such grouping can be useful
     * for organizing families of behaviors together or to indicate other relationships
     * between particular behaviors.
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
     * A behavior section element <behaviorSec> associates executable behaviors with
     * content in the METS document by means of a repeatable behavior <behavior>
     * element. This element has an interface definition <interfaceDef> element that
     * represents an abstract definition of the set of behaviors represented by a
     * particular behavior section. A <behavior> element also has a <mechanism> element
     * which is used to point to a module of executable code that implements and runs
     * the behavior defined by the interface definition. The <behaviorSec> element,
     * which is repeatable as well as nestable, can be used to group individual
     * behaviors within the structure of the METS document. Such grouping can be useful
     * for organizing families of behaviors together or to indicate other relationships
     * between particular behaviors.
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
     * A behavior section element <behaviorSec> associates executable behaviors with
     * content in the METS document by means of a repeatable behavior <behavior>
     * element. This element has an interface definition <interfaceDef> element that
     * represents an abstract definition of the set of behaviors represented by a
     * particular behavior section. A <behavior> element also has a <mechanism> element
     * which is used to point to a module of executable code that implements and runs
     * the behavior defined by the interface definition. The <behaviorSec> element,
     * which is repeatable as well as nestable, can be used to group individual
     * behaviors within the structure of the METS document. Such grouping can be useful
     * for organizing families of behaviors together or to indicate other relationships
     * between particular behaviors.
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
     * A behavior section element <behaviorSec> associates executable behaviors with
     * content in the METS document by means of a repeatable behavior <behavior>
     * element. This element has an interface definition <interfaceDef> element that
     * represents an abstract definition of the set of behaviors represented by a
     * particular behavior section. A <behavior> element also has a <mechanism> element
     * which is used to point to a module of executable code that implements and runs
     * the behavior defined by the interface definition. The <behaviorSec> element,
     * which is repeatable as well as nestable, can be used to group individual
     * behaviors within the structure of the METS document. Such grouping can be useful
     * for organizing families of behaviors together or to indicate other relationships
     * between particular behaviors.
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
     * A behavior section element <behaviorSec> associates executable behaviors with
     * content in the METS document by means of a repeatable behavior <behavior>
     * element. This element has an interface definition <interfaceDef> element that
     * represents an abstract definition of the set of behaviors represented by a
     * particular behavior section. A <behavior> element also has a <mechanism> element
     * which is used to point to a module of executable code that implements and runs
     * the behavior defined by the interface definition. The <behaviorSec> element,
     * which is repeatable as well as nestable, can be used to group individual
     * behaviors within the structure of the METS document. Such grouping can be useful
     * for organizing families of behaviors together or to indicate other relationships
     * between particular behaviors.
     *
     * @param \App\Xsd\Mets\BehaviorSecType[] $behaviorSecElName
     * @return self
     */
    public function setBehaviorSecElName(array $behaviorSecElName)
    {
        $this->behaviorSecElName = $behaviorSecElName;
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

