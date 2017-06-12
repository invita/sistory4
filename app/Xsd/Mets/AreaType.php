<?php

namespace App\Xsd\Mets;

/**
 * Class representing AreaType
 *
 * areaType: Complex Type for Area Linking
 *  The area element provides for more sophisticated linking between a div element
 * and content files representing that div, be they text, image, audio, or video
 * files. An area element can link a div to a point within a file, to a
 * one-dimension segment of a file (e.g., text segment, image line, audio/video
 * clip), or a two-dimensional section of a file (e.g, subsection of an image, or a
 * subsection of the video display of a video file. The area element has no
 * content; all information is recorded within its various attributes.
 * XSD Type: areaType
 */
class AreaType
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
     * FILEID (IDREF/R): An attribute which provides the XML ID value that identifies
     * the <file> element in the <fileSec> that then points to and/or contains the
     * digital content represented by the <area> element. It must contain an ID value
     * represented in an ID attribute associated with a <file> element in the <fileSec>
     * element in the same METS document.
     *
     * @property string $FILEIDAttrName
     */
    private $FILEIDAttrName = null;

    /**
     * SHAPE (string/O): An attribute that can be used as in HTML to define the shape
     * of the relevant area within the content file pointed to by the <area> element.
     * Typically this would be used with image content (still image or video frame)
     * when only a portion of an integal image map pertains. If SHAPE is specified then
     * COORDS must also be present. SHAPE should be used in conjunction with COORDS in
     * the manner defined for the shape and coords attributes on an HTML4 <area>
     * element. SHAPE must contain one of the following values: 
     * RECT 
     * CIRCLE
     * POLY
     *
     * @property string $SHAPEAttrName
     */
    private $SHAPEAttrName = null;

    /**
     * COORDS (string/O): Specifies the coordinates in an image map for the shape of
     * the pertinent area as specified in the SHAPE attribute. While technically
     * optional, SHAPE and COORDS must both appear together to define the relevant area
     * of image content. COORDS should be used in conjunction with SHAPE in the manner
     * defined for the COORDs and SHAPE attributes on an HTML4 <area> element. COORDS
     * must be a comma delimited string of integer value pairs representing coordinates
     * (plus radius in the case of CIRCLE) within an image map. Number of coordinates
     * pairs depends on shape: RECT: x1, y1, x2, y2; CIRC: x1, y1; POLY: x1, y1, x2,
     * y2, x3, y3 . . .
     *
     * @property string $COORDSAttrName
     */
    private $COORDSAttrName = null;

    /**
     * BEGIN (string/O): An attribute that specifies the point in the content file
     * where the relevant section of content begins. It can be used in conjunction with
     * either the END attribute or the EXTENT attribute as a means of defining the
     * relevant portion of the referenced file precisely. It can only be interpreted
     * meaningfully in conjunction with the BETYPE or EXTTYPE, which specify the kind
     * of beginning/ending point values or beginning/extent values that are being used.
     * The BEGIN attribute can be used with or without a companion END or EXTENT
     * element. In this case, the end of the content file is assumed to be the end
     * point.
     *
     * @property string $BEGINAttrName
     */
    private $BEGINAttrName = null;

    /**
     * END (string/O): An attribute that specifies the point in the content file where
     * the relevant section of content ends. It can only be interpreted meaningfully in
     * conjunction with the BETYPE, which specifies the kind of ending point values
     * being used. Typically the END attribute would only appear in conjunction with a
     * BEGIN element.
     *
     * @property string $ENDAttrName
     */
    private $ENDAttrName = null;

    /**
     * BETYPE: Begin/End Type.
     *  BETYPE (string/O): An attribute that specifies the kind of BEGIN and/or END
     * values that are being used. For example, if BYTE is specified, then the BEGIN
     * and END point values represent the byte offsets into a file. If IDREF is
     * specified, then the BEGIN element specifies the ID value that identifies the
     * element in a structured text file where the relevant section of the file begins;
     * and the END value (if present) would specify the ID value that identifies the
     * element with which the relevant section of the file ends. Must be one of the
     * following values: 
     * BYTE
     * IDREF
     * SMIL
     * MIDI
     * SMPTE-25
     * SMPTE-24
     * SMPTE-DF30
     * SMPTE-NDF30
     * SMPTE-DF29.97
     * SMPTE-NDF29.97
     * TIME
     * TCF
     * XPTR
     *
     * @property string $BETYPEAttrName
     */
    private $BETYPEAttrName = null;

    /**
     * EXTENT (string/O): An attribute that specifies the extent of the relevant
     * section of the content file. Can only be interpreted meaningfully in conjunction
     * with the EXTTYPE which specifies the kind of value that is being used. Typically
     * the EXTENT attribute would only appear in conjunction with a BEGIN element and
     * would not be used if the BEGIN point represents an IDREF.
     *
     * @property string $EXTENTAttrName
     */
    private $EXTENTAttrName = null;

    /**
     * EXTTYPE (string/O): An attribute that specifies the kind of EXTENT values that
     * are being used. For example if BYTE is specified then EXTENT would represent a
     * byte count. If TIME is specified the EXTENT would represent a duration of time.
     * EXTTYPE must be one of the following values: 
     * BYTE
     * SMIL
     * MIDI
     * SMPTE-25
     * SMPTE-24
     * SMPTE-DF30
     * SMPTE-NDF30
     * SMPTE-DF29.97
     * SMPTE-NDF29.97
     * TIME
     * TCF.
     *
     * @property string $EXTTYPEAttrName
     */
    private $EXTTYPEAttrName = null;

    /**
     * ADMID (IDREFS/O): Contains the ID attribute values identifying the <rightsMD>,
     * <sourceMD>, <techMD> and/or <digiprovMD> elements within the <amdSec> of the
     * METS document that contain or link to administrative metadata pertaining to the
     * content represented by the <area> element. Typically the <area> ADMID attribute
     * would be used to identify the <rightsMD> element or elements that pertain to the
     * <area>, but it could be used anytime there was a need to link an <area> with
     * pertinent administrative metadata. For more information on using METS IDREFS and
     * IDREF type attributes for internal linking, see Chapter 4 of the METS Primer
     *
     * @property string $ADMIDAttrName
     */
    private $ADMIDAttrName = null;

    /**
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <area>
     * (equivalent to DIDL DII or Digital Item Identifier, a unique external ID).
     *
     * @property string[] $CONTENTIDSAttrName
     */
    private $CONTENTIDSAttrName = null;

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
     * Gets as FILEIDAttrName
     *
     * FILEID (IDREF/R): An attribute which provides the XML ID value that identifies
     * the <file> element in the <fileSec> that then points to and/or contains the
     * digital content represented by the <area> element. It must contain an ID value
     * represented in an ID attribute associated with a <file> element in the <fileSec>
     * element in the same METS document.
     *
     * @return string
     */
    public function getFILEIDAttrName()
    {
        return $this->FILEIDAttrName;
    }

    /**
     * Sets a new FILEIDAttrName
     *
     * FILEID (IDREF/R): An attribute which provides the XML ID value that identifies
     * the <file> element in the <fileSec> that then points to and/or contains the
     * digital content represented by the <area> element. It must contain an ID value
     * represented in an ID attribute associated with a <file> element in the <fileSec>
     * element in the same METS document.
     *
     * @param string $FILEIDAttrName
     * @return self
     */
    public function setFILEIDAttrName($FILEIDAttrName)
    {
        $this->FILEIDAttrName = $FILEIDAttrName;
        return $this;
    }

    /**
     * Gets as SHAPEAttrName
     *
     * SHAPE (string/O): An attribute that can be used as in HTML to define the shape
     * of the relevant area within the content file pointed to by the <area> element.
     * Typically this would be used with image content (still image or video frame)
     * when only a portion of an integal image map pertains. If SHAPE is specified then
     * COORDS must also be present. SHAPE should be used in conjunction with COORDS in
     * the manner defined for the shape and coords attributes on an HTML4 <area>
     * element. SHAPE must contain one of the following values: 
     * RECT 
     * CIRCLE
     * POLY
     *
     * @return string
     */
    public function getSHAPEAttrName()
    {
        return $this->SHAPEAttrName;
    }

    /**
     * Sets a new SHAPEAttrName
     *
     * SHAPE (string/O): An attribute that can be used as in HTML to define the shape
     * of the relevant area within the content file pointed to by the <area> element.
     * Typically this would be used with image content (still image or video frame)
     * when only a portion of an integal image map pertains. If SHAPE is specified then
     * COORDS must also be present. SHAPE should be used in conjunction with COORDS in
     * the manner defined for the shape and coords attributes on an HTML4 <area>
     * element. SHAPE must contain one of the following values: 
     * RECT 
     * CIRCLE
     * POLY
     *
     * @param string $SHAPEAttrName
     * @return self
     */
    public function setSHAPEAttrName($SHAPEAttrName)
    {
        $this->SHAPEAttrName = $SHAPEAttrName;
        return $this;
    }

    /**
     * Gets as COORDSAttrName
     *
     * COORDS (string/O): Specifies the coordinates in an image map for the shape of
     * the pertinent area as specified in the SHAPE attribute. While technically
     * optional, SHAPE and COORDS must both appear together to define the relevant area
     * of image content. COORDS should be used in conjunction with SHAPE in the manner
     * defined for the COORDs and SHAPE attributes on an HTML4 <area> element. COORDS
     * must be a comma delimited string of integer value pairs representing coordinates
     * (plus radius in the case of CIRCLE) within an image map. Number of coordinates
     * pairs depends on shape: RECT: x1, y1, x2, y2; CIRC: x1, y1; POLY: x1, y1, x2,
     * y2, x3, y3 . . .
     *
     * @return string
     */
    public function getCOORDSAttrName()
    {
        return $this->COORDSAttrName;
    }

    /**
     * Sets a new COORDSAttrName
     *
     * COORDS (string/O): Specifies the coordinates in an image map for the shape of
     * the pertinent area as specified in the SHAPE attribute. While technically
     * optional, SHAPE and COORDS must both appear together to define the relevant area
     * of image content. COORDS should be used in conjunction with SHAPE in the manner
     * defined for the COORDs and SHAPE attributes on an HTML4 <area> element. COORDS
     * must be a comma delimited string of integer value pairs representing coordinates
     * (plus radius in the case of CIRCLE) within an image map. Number of coordinates
     * pairs depends on shape: RECT: x1, y1, x2, y2; CIRC: x1, y1; POLY: x1, y1, x2,
     * y2, x3, y3 . . .
     *
     * @param string $COORDSAttrName
     * @return self
     */
    public function setCOORDSAttrName($COORDSAttrName)
    {
        $this->COORDSAttrName = $COORDSAttrName;
        return $this;
    }

    /**
     * Gets as BEGINAttrName
     *
     * BEGIN (string/O): An attribute that specifies the point in the content file
     * where the relevant section of content begins. It can be used in conjunction with
     * either the END attribute or the EXTENT attribute as a means of defining the
     * relevant portion of the referenced file precisely. It can only be interpreted
     * meaningfully in conjunction with the BETYPE or EXTTYPE, which specify the kind
     * of beginning/ending point values or beginning/extent values that are being used.
     * The BEGIN attribute can be used with or without a companion END or EXTENT
     * element. In this case, the end of the content file is assumed to be the end
     * point.
     *
     * @return string
     */
    public function getBEGINAttrName()
    {
        return $this->BEGINAttrName;
    }

    /**
     * Sets a new BEGINAttrName
     *
     * BEGIN (string/O): An attribute that specifies the point in the content file
     * where the relevant section of content begins. It can be used in conjunction with
     * either the END attribute or the EXTENT attribute as a means of defining the
     * relevant portion of the referenced file precisely. It can only be interpreted
     * meaningfully in conjunction with the BETYPE or EXTTYPE, which specify the kind
     * of beginning/ending point values or beginning/extent values that are being used.
     * The BEGIN attribute can be used with or without a companion END or EXTENT
     * element. In this case, the end of the content file is assumed to be the end
     * point.
     *
     * @param string $BEGINAttrName
     * @return self
     */
    public function setBEGINAttrName($BEGINAttrName)
    {
        $this->BEGINAttrName = $BEGINAttrName;
        return $this;
    }

    /**
     * Gets as ENDAttrName
     *
     * END (string/O): An attribute that specifies the point in the content file where
     * the relevant section of content ends. It can only be interpreted meaningfully in
     * conjunction with the BETYPE, which specifies the kind of ending point values
     * being used. Typically the END attribute would only appear in conjunction with a
     * BEGIN element.
     *
     * @return string
     */
    public function getENDAttrName()
    {
        return $this->ENDAttrName;
    }

    /**
     * Sets a new ENDAttrName
     *
     * END (string/O): An attribute that specifies the point in the content file where
     * the relevant section of content ends. It can only be interpreted meaningfully in
     * conjunction with the BETYPE, which specifies the kind of ending point values
     * being used. Typically the END attribute would only appear in conjunction with a
     * BEGIN element.
     *
     * @param string $ENDAttrName
     * @return self
     */
    public function setENDAttrName($ENDAttrName)
    {
        $this->ENDAttrName = $ENDAttrName;
        return $this;
    }

    /**
     * Gets as BETYPEAttrName
     *
     * BETYPE: Begin/End Type.
     *  BETYPE (string/O): An attribute that specifies the kind of BEGIN and/or END
     * values that are being used. For example, if BYTE is specified, then the BEGIN
     * and END point values represent the byte offsets into a file. If IDREF is
     * specified, then the BEGIN element specifies the ID value that identifies the
     * element in a structured text file where the relevant section of the file begins;
     * and the END value (if present) would specify the ID value that identifies the
     * element with which the relevant section of the file ends. Must be one of the
     * following values: 
     * BYTE
     * IDREF
     * SMIL
     * MIDI
     * SMPTE-25
     * SMPTE-24
     * SMPTE-DF30
     * SMPTE-NDF30
     * SMPTE-DF29.97
     * SMPTE-NDF29.97
     * TIME
     * TCF
     * XPTR
     *
     * @return string
     */
    public function getBETYPEAttrName()
    {
        return $this->BETYPEAttrName;
    }

    /**
     * Sets a new BETYPEAttrName
     *
     * BETYPE: Begin/End Type.
     *  BETYPE (string/O): An attribute that specifies the kind of BEGIN and/or END
     * values that are being used. For example, if BYTE is specified, then the BEGIN
     * and END point values represent the byte offsets into a file. If IDREF is
     * specified, then the BEGIN element specifies the ID value that identifies the
     * element in a structured text file where the relevant section of the file begins;
     * and the END value (if present) would specify the ID value that identifies the
     * element with which the relevant section of the file ends. Must be one of the
     * following values: 
     * BYTE
     * IDREF
     * SMIL
     * MIDI
     * SMPTE-25
     * SMPTE-24
     * SMPTE-DF30
     * SMPTE-NDF30
     * SMPTE-DF29.97
     * SMPTE-NDF29.97
     * TIME
     * TCF
     * XPTR
     *
     * @param string $BETYPEAttrName
     * @return self
     */
    public function setBETYPEAttrName($BETYPEAttrName)
    {
        $this->BETYPEAttrName = $BETYPEAttrName;
        return $this;
    }

    /**
     * Gets as EXTENTAttrName
     *
     * EXTENT (string/O): An attribute that specifies the extent of the relevant
     * section of the content file. Can only be interpreted meaningfully in conjunction
     * with the EXTTYPE which specifies the kind of value that is being used. Typically
     * the EXTENT attribute would only appear in conjunction with a BEGIN element and
     * would not be used if the BEGIN point represents an IDREF.
     *
     * @return string
     */
    public function getEXTENTAttrName()
    {
        return $this->EXTENTAttrName;
    }

    /**
     * Sets a new EXTENTAttrName
     *
     * EXTENT (string/O): An attribute that specifies the extent of the relevant
     * section of the content file. Can only be interpreted meaningfully in conjunction
     * with the EXTTYPE which specifies the kind of value that is being used. Typically
     * the EXTENT attribute would only appear in conjunction with a BEGIN element and
     * would not be used if the BEGIN point represents an IDREF.
     *
     * @param string $EXTENTAttrName
     * @return self
     */
    public function setEXTENTAttrName($EXTENTAttrName)
    {
        $this->EXTENTAttrName = $EXTENTAttrName;
        return $this;
    }

    /**
     * Gets as EXTTYPEAttrName
     *
     * EXTTYPE (string/O): An attribute that specifies the kind of EXTENT values that
     * are being used. For example if BYTE is specified then EXTENT would represent a
     * byte count. If TIME is specified the EXTENT would represent a duration of time.
     * EXTTYPE must be one of the following values: 
     * BYTE
     * SMIL
     * MIDI
     * SMPTE-25
     * SMPTE-24
     * SMPTE-DF30
     * SMPTE-NDF30
     * SMPTE-DF29.97
     * SMPTE-NDF29.97
     * TIME
     * TCF.
     *
     * @return string
     */
    public function getEXTTYPEAttrName()
    {
        return $this->EXTTYPEAttrName;
    }

    /**
     * Sets a new EXTTYPEAttrName
     *
     * EXTTYPE (string/O): An attribute that specifies the kind of EXTENT values that
     * are being used. For example if BYTE is specified then EXTENT would represent a
     * byte count. If TIME is specified the EXTENT would represent a duration of time.
     * EXTTYPE must be one of the following values: 
     * BYTE
     * SMIL
     * MIDI
     * SMPTE-25
     * SMPTE-24
     * SMPTE-DF30
     * SMPTE-NDF30
     * SMPTE-DF29.97
     * SMPTE-NDF29.97
     * TIME
     * TCF.
     *
     * @param string $EXTTYPEAttrName
     * @return self
     */
    public function setEXTTYPEAttrName($EXTTYPEAttrName)
    {
        $this->EXTTYPEAttrName = $EXTTYPEAttrName;
        return $this;
    }

    /**
     * Gets as ADMIDAttrName
     *
     * ADMID (IDREFS/O): Contains the ID attribute values identifying the <rightsMD>,
     * <sourceMD>, <techMD> and/or <digiprovMD> elements within the <amdSec> of the
     * METS document that contain or link to administrative metadata pertaining to the
     * content represented by the <area> element. Typically the <area> ADMID attribute
     * would be used to identify the <rightsMD> element or elements that pertain to the
     * <area>, but it could be used anytime there was a need to link an <area> with
     * pertinent administrative metadata. For more information on using METS IDREFS and
     * IDREF type attributes for internal linking, see Chapter 4 of the METS Primer
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
     * content represented by the <area> element. Typically the <area> ADMID attribute
     * would be used to identify the <rightsMD> element or elements that pertain to the
     * <area>, but it could be used anytime there was a need to link an <area> with
     * pertinent administrative metadata. For more information on using METS IDREFS and
     * IDREF type attributes for internal linking, see Chapter 4 of the METS Primer
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
     * Adds as CONTENTIDSAttrName
     *
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <area>
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
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <area>
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
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <area>
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
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <area>
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
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <area>
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

