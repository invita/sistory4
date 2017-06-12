<?php

namespace App\Xsd\Mets;

/**
 * Class representing FileType
 *
 * fileType: Complex Type for Files
 *  The file element provides access to content files for a METS object. A file
 * element may contain one or more FLocat elements, which provide pointers to a
 * content file, and/or an FContent element, which wraps an encoded version of the
 * file. Note that ALL FLocat and FContent elements underneath a single file
 * element should identify/contain identical copies of a single file.
 * XSD Type: fileType
 */
class FileType
{

    /**
     * ID (ID/R): This attribute uniquely identifies the element within the METS
     * document, and would allow the element to be referenced unambiguously from
     * another element or document via an IDREF or an XPTR. Typically, the ID attribute
     * value on a <file> element would be referenced from one or more FILEID attributes
     * (which are of type IDREF) on <fptr>and/or <area> elements within the
     * <structMap>. Such references establish links between structural divisions (<div>
     * elements) and the specific content files or parts of content files that manifest
     * them. For more information on using ID attributes for internal and external
     * linking see Chapter 4 of the METS Primer.
     *
     * @property string $IDAttrName
     */
    private $IDAttrName = null;

    /**
     * SEQ (integer/O): Indicates the sequence of this <file> relative to the others in
     * its <fileGrp>.
     *
     * @property integer $SEQAttrName
     */
    private $SEQAttrName = null;

    /**
     * MIMETYPE (string/O): The IANA MIME media type for the associated file or wrapped
     * content. Some values for this attribute can be found on the IANA website.
     *
     * @property string $MIMETYPEAttrName
     */
    private $MIMETYPEAttrName = null;

    /**
     * SIZE (long/O): Specifies the size in bytes of the associated file or wrapped
     * content.
     *
     * @property integer $SIZEAttrName
     */
    private $SIZEAttrName = null;

    /**
     * CREATED (dateTime/O): Specifies the date and time of creation for the associated
     * file or wrapped content.
     *
     * @property \DateTime $CREATEDAttrName
     */
    private $CREATEDAttrName = null;

    /**
     * CHECKSUM (string/O): Provides a checksum value for the associated file or
     * wrapped content.
     *
     * @property string $CHECKSUMAttrName
     */
    private $CHECKSUMAttrName = null;

    /**
     * CHECKSUMTYPE (enumerated string/O): Specifies the checksum algorithm used to
     * produce the value contained in the CHECKSUM attribute. CHECKSUMTYPE must contain
     * one of the following values:
     *  Adler-32
     *  CRC32
     *  HAVAL
     *  MD5
     *  MNP
     *  SHA-1
     *  SHA-256
     *  SHA-384
     *  SHA-512
     *  TIGER
     *  WHIRLPOOL
     *
     * @property string $CHECKSUMTYPEAttrName
     */
    private $CHECKSUMTYPEAttrName = null;

    /**
     * OWNERID (string/O): A unique identifier assigned to the file by its owner. This
     * may be a URI which differs from the URI used to retrieve the file.
     *
     * @property string $OWNERIDAttrName
     */
    private $OWNERIDAttrName = null;

    /**
     * ADMID (IDREFS/O): Contains the ID attribute values of the <techMD>, <sourceMD>,
     * <rightsMD> and/or <digiprovMD> elements within the <amdSec> of the METS document
     * that contain administrative metadata pertaining to the file. For more
     * information on using METS IDREFS and IDREF type attributes for internal linking,
     * see Chapter 4 of the METS Primer.
     *
     * @property string $ADMIDAttrName
     */
    private $ADMIDAttrName = null;

    /**
     * DMDID (IDREFS/O): Contains the ID attribute values identifying the <dmdSec>,
     * elements in the METS document that contain or link to descriptive metadata
     * pertaining to the content file represented by the current <file> element. For
     * more information on using METS IDREFS and IDREF type attributes for internal
     * linking, see Chapter 4 of the METS Primer.
     *
     * @property string $DMDIDAttrName
     */
    private $DMDIDAttrName = null;

    /**
     * GROUPID (string/O): An identifier that establishes a correspondence between this
     * file and files in other file groups. Typically, this will be used to associate a
     * master file in one file group with the derivative files made from it in other
     * file groups.
     *
     * @property string $GROUPIDAttrName
     */
    private $GROUPIDAttrName = null;

    /**
     * USE (string/O): A tagging attribute to indicate the intended use of all copies
     * of the file aggregated by the <file> element (e.g., master, reference,
     * thumbnails for image files). A USE attribute can be expressed at the<fileGrp>
     * level, the <file> level, the <FLocat> level and/or the <FContent> level. A USE
     * attribute value at the <fileGrp> level should pertain to all of the files in the
     * <fileGrp>. A USE attribute at the <file> level should pertain to all copies of
     * the file as represented by subsidiary <FLocat> and/or <FContent> elements. A USE
     * attribute at the <FLocat> or <FContent> level pertains to the particular copy of
     * the file that is either referenced (<FLocat>) or wrapped (<FContent>).
     *
     * @property string $USEAttrName
     */
    private $USEAttrName = null;

    /**
     * BEGIN (string/O): An attribute that specifies the point in the parent <file>
     * where the current <file> begins. When used in conjunction with a <file> element,
     * this attribute is only meaningful when this element is nested, and its parent
     * <file> element represents a container file. It can be used in conjunction with
     * the END attribute as a means of defining the location of the current file within
     * its parent file. However, the BEGIN attribute can be used with or without a
     * companion END attribute. When no END attribute is specified, the end of the
     * parent file is assumed also to be the end point of the current file. The BEGIN
     * and END attributes can only be interpreted meaningfully in conjunction with a
     * BETYPE attribute, which specifies the kind of beginning/ending point values that
     * are being used.
     *
     * @property string $BEGINAttrName
     */
    private $BEGINAttrName = null;

    /**
     * END (string/O): An attribute that specifies the point in the parent <file> where
     * the current, nested <file> ends. It can only be interpreted meaningfully in
     * conjunction with the BETYPE, which specifies the kind of ending point values
     * being used. Typically the END attribute would only appear in conjunction with a
     * BEGIN attribute.
     *
     * @property string $ENDAttrName
     */
    private $ENDAttrName = null;

    /**
     * BETYPE: Begin/End Type.
     *  BETYPE (string/O): An attribute that specifies the kind of BEGIN and/or END
     * values that are being used. Currently BYTE is the only valid value that can be
     * used in conjunction with nested <file> or <stream> elements.
     *
     * @property string $BETYPEAttrName
     */
    private $BETYPEAttrName = null;

    /**
     * The file location element <FLocat> provides a pointer to the location of a
     * content file. It uses the XLink reference syntax to provide linking information
     * indicating the actual location of the content file, along with other attributes
     * specifying additional linking information. NOTE: <FLocat> is an empty element.
     * The location of the resource pointed to MUST be stored in the xlink:href
     * attribute.
     *
     * @property \App\Xsd\Mets\FileType\FLocatAType[] $FLocatElName
     */
    private $FLocatElName = array(
        
    );

    /**
     * The file content element <FContent> is used to identify a content file contained
     * internally within a METS document. The content file must be either Base64
     * encoded and contained within the subsidiary <binData> wrapper element, or
     * consist of XML information and be contained within the subsidiary <xmlData>
     * wrapper element.
     *
     * @property \App\Xsd\Mets\FileType\FContentAType $FContentElName
     */
    private $FContentElName = null;

    /**
     * A component byte stream element <stream> may be composed of one or more
     * subsidiary streams. An MPEG4 file, for example, might contain separate audio and
     * video streams, each of which is associated with technical metadata. The
     * repeatable <stream> element provides a mechanism to record the existence of
     * separate data streams within a particular file, and the opportunity to associate
     * <dmdSec> and <amdSec> with those subsidiary data streams if desired.
     *
     * @property \App\Xsd\AnyTypeHandler[] $streamElName
     */
    private $streamElName = array(
        
    );

    /**
     * The transform file element <transformFile> provides a means to access any
     * subsidiary files listed below a <file> element by indicating the steps required
     * to "unpack" or transform the subsidiary files. This element is repeatable and
     * might provide a link to a <behavior> in the <behaviorSec> that performs the
     * transformation.
     *
     * @property \App\Xsd\AnyTypeHandler[] $transformFileElName
     */
    private $transformFileElName = array(
        
    );

    /**
     * @property \App\Xsd\Mets\FileType[] $fileElName
     */
    private $fileElName = array(
        
    );

    /**
     * Gets as IDAttrName
     *
     * ID (ID/R): This attribute uniquely identifies the element within the METS
     * document, and would allow the element to be referenced unambiguously from
     * another element or document via an IDREF or an XPTR. Typically, the ID attribute
     * value on a <file> element would be referenced from one or more FILEID attributes
     * (which are of type IDREF) on <fptr>and/or <area> elements within the
     * <structMap>. Such references establish links between structural divisions (<div>
     * elements) and the specific content files or parts of content files that manifest
     * them. For more information on using ID attributes for internal and external
     * linking see Chapter 4 of the METS Primer.
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
     * another element or document via an IDREF or an XPTR. Typically, the ID attribute
     * value on a <file> element would be referenced from one or more FILEID attributes
     * (which are of type IDREF) on <fptr>and/or <area> elements within the
     * <structMap>. Such references establish links between structural divisions (<div>
     * elements) and the specific content files or parts of content files that manifest
     * them. For more information on using ID attributes for internal and external
     * linking see Chapter 4 of the METS Primer.
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
     * Gets as SEQAttrName
     *
     * SEQ (integer/O): Indicates the sequence of this <file> relative to the others in
     * its <fileGrp>.
     *
     * @return integer
     */
    public function getSEQAttrName()
    {
        return $this->SEQAttrName;
    }

    /**
     * Sets a new SEQAttrName
     *
     * SEQ (integer/O): Indicates the sequence of this <file> relative to the others in
     * its <fileGrp>.
     *
     * @param integer $SEQAttrName
     * @return self
     */
    public function setSEQAttrName($SEQAttrName)
    {
        $this->SEQAttrName = $SEQAttrName;
        return $this;
    }

    /**
     * Gets as MIMETYPEAttrName
     *
     * MIMETYPE (string/O): The IANA MIME media type for the associated file or wrapped
     * content. Some values for this attribute can be found on the IANA website.
     *
     * @return string
     */
    public function getMIMETYPEAttrName()
    {
        return $this->MIMETYPEAttrName;
    }

    /**
     * Sets a new MIMETYPEAttrName
     *
     * MIMETYPE (string/O): The IANA MIME media type for the associated file or wrapped
     * content. Some values for this attribute can be found on the IANA website.
     *
     * @param string $MIMETYPEAttrName
     * @return self
     */
    public function setMIMETYPEAttrName($MIMETYPEAttrName)
    {
        $this->MIMETYPEAttrName = $MIMETYPEAttrName;
        return $this;
    }

    /**
     * Gets as SIZEAttrName
     *
     * SIZE (long/O): Specifies the size in bytes of the associated file or wrapped
     * content.
     *
     * @return integer
     */
    public function getSIZEAttrName()
    {
        return $this->SIZEAttrName;
    }

    /**
     * Sets a new SIZEAttrName
     *
     * SIZE (long/O): Specifies the size in bytes of the associated file or wrapped
     * content.
     *
     * @param integer $SIZEAttrName
     * @return self
     */
    public function setSIZEAttrName($SIZEAttrName)
    {
        $this->SIZEAttrName = $SIZEAttrName;
        return $this;
    }

    /**
     * Gets as CREATEDAttrName
     *
     * CREATED (dateTime/O): Specifies the date and time of creation for the associated
     * file or wrapped content.
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
     * CREATED (dateTime/O): Specifies the date and time of creation for the associated
     * file or wrapped content.
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
     * Gets as CHECKSUMAttrName
     *
     * CHECKSUM (string/O): Provides a checksum value for the associated file or
     * wrapped content.
     *
     * @return string
     */
    public function getCHECKSUMAttrName()
    {
        return $this->CHECKSUMAttrName;
    }

    /**
     * Sets a new CHECKSUMAttrName
     *
     * CHECKSUM (string/O): Provides a checksum value for the associated file or
     * wrapped content.
     *
     * @param string $CHECKSUMAttrName
     * @return self
     */
    public function setCHECKSUMAttrName($CHECKSUMAttrName)
    {
        $this->CHECKSUMAttrName = $CHECKSUMAttrName;
        return $this;
    }

    /**
     * Gets as CHECKSUMTYPEAttrName
     *
     * CHECKSUMTYPE (enumerated string/O): Specifies the checksum algorithm used to
     * produce the value contained in the CHECKSUM attribute. CHECKSUMTYPE must contain
     * one of the following values:
     *  Adler-32
     *  CRC32
     *  HAVAL
     *  MD5
     *  MNP
     *  SHA-1
     *  SHA-256
     *  SHA-384
     *  SHA-512
     *  TIGER
     *  WHIRLPOOL
     *
     * @return string
     */
    public function getCHECKSUMTYPEAttrName()
    {
        return $this->CHECKSUMTYPEAttrName;
    }

    /**
     * Sets a new CHECKSUMTYPEAttrName
     *
     * CHECKSUMTYPE (enumerated string/O): Specifies the checksum algorithm used to
     * produce the value contained in the CHECKSUM attribute. CHECKSUMTYPE must contain
     * one of the following values:
     *  Adler-32
     *  CRC32
     *  HAVAL
     *  MD5
     *  MNP
     *  SHA-1
     *  SHA-256
     *  SHA-384
     *  SHA-512
     *  TIGER
     *  WHIRLPOOL
     *
     * @param string $CHECKSUMTYPEAttrName
     * @return self
     */
    public function setCHECKSUMTYPEAttrName($CHECKSUMTYPEAttrName)
    {
        $this->CHECKSUMTYPEAttrName = $CHECKSUMTYPEAttrName;
        return $this;
    }

    /**
     * Gets as OWNERIDAttrName
     *
     * OWNERID (string/O): A unique identifier assigned to the file by its owner. This
     * may be a URI which differs from the URI used to retrieve the file.
     *
     * @return string
     */
    public function getOWNERIDAttrName()
    {
        return $this->OWNERIDAttrName;
    }

    /**
     * Sets a new OWNERIDAttrName
     *
     * OWNERID (string/O): A unique identifier assigned to the file by its owner. This
     * may be a URI which differs from the URI used to retrieve the file.
     *
     * @param string $OWNERIDAttrName
     * @return self
     */
    public function setOWNERIDAttrName($OWNERIDAttrName)
    {
        $this->OWNERIDAttrName = $OWNERIDAttrName;
        return $this;
    }

    /**
     * Gets as ADMIDAttrName
     *
     * ADMID (IDREFS/O): Contains the ID attribute values of the <techMD>, <sourceMD>,
     * <rightsMD> and/or <digiprovMD> elements within the <amdSec> of the METS document
     * that contain administrative metadata pertaining to the file. For more
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
     * ADMID (IDREFS/O): Contains the ID attribute values of the <techMD>, <sourceMD>,
     * <rightsMD> and/or <digiprovMD> elements within the <amdSec> of the METS document
     * that contain administrative metadata pertaining to the file. For more
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
     * Gets as DMDIDAttrName
     *
     * DMDID (IDREFS/O): Contains the ID attribute values identifying the <dmdSec>,
     * elements in the METS document that contain or link to descriptive metadata
     * pertaining to the content file represented by the current <file> element. For
     * more information on using METS IDREFS and IDREF type attributes for internal
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
     * pertaining to the content file represented by the current <file> element. For
     * more information on using METS IDREFS and IDREF type attributes for internal
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
     * Gets as GROUPIDAttrName
     *
     * GROUPID (string/O): An identifier that establishes a correspondence between this
     * file and files in other file groups. Typically, this will be used to associate a
     * master file in one file group with the derivative files made from it in other
     * file groups.
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
     * GROUPID (string/O): An identifier that establishes a correspondence between this
     * file and files in other file groups. Typically, this will be used to associate a
     * master file in one file group with the derivative files made from it in other
     * file groups.
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
     * Gets as USEAttrName
     *
     * USE (string/O): A tagging attribute to indicate the intended use of all copies
     * of the file aggregated by the <file> element (e.g., master, reference,
     * thumbnails for image files). A USE attribute can be expressed at the<fileGrp>
     * level, the <file> level, the <FLocat> level and/or the <FContent> level. A USE
     * attribute value at the <fileGrp> level should pertain to all of the files in the
     * <fileGrp>. A USE attribute at the <file> level should pertain to all copies of
     * the file as represented by subsidiary <FLocat> and/or <FContent> elements. A USE
     * attribute at the <FLocat> or <FContent> level pertains to the particular copy of
     * the file that is either referenced (<FLocat>) or wrapped (<FContent>).
     *
     * @return string
     */
    public function getUSEAttrName()
    {
        return $this->USEAttrName;
    }

    /**
     * Sets a new USEAttrName
     *
     * USE (string/O): A tagging attribute to indicate the intended use of all copies
     * of the file aggregated by the <file> element (e.g., master, reference,
     * thumbnails for image files). A USE attribute can be expressed at the<fileGrp>
     * level, the <file> level, the <FLocat> level and/or the <FContent> level. A USE
     * attribute value at the <fileGrp> level should pertain to all of the files in the
     * <fileGrp>. A USE attribute at the <file> level should pertain to all copies of
     * the file as represented by subsidiary <FLocat> and/or <FContent> elements. A USE
     * attribute at the <FLocat> or <FContent> level pertains to the particular copy of
     * the file that is either referenced (<FLocat>) or wrapped (<FContent>).
     *
     * @param string $USEAttrName
     * @return self
     */
    public function setUSEAttrName($USEAttrName)
    {
        $this->USEAttrName = $USEAttrName;
        return $this;
    }

    /**
     * Gets as BEGINAttrName
     *
     * BEGIN (string/O): An attribute that specifies the point in the parent <file>
     * where the current <file> begins. When used in conjunction with a <file> element,
     * this attribute is only meaningful when this element is nested, and its parent
     * <file> element represents a container file. It can be used in conjunction with
     * the END attribute as a means of defining the location of the current file within
     * its parent file. However, the BEGIN attribute can be used with or without a
     * companion END attribute. When no END attribute is specified, the end of the
     * parent file is assumed also to be the end point of the current file. The BEGIN
     * and END attributes can only be interpreted meaningfully in conjunction with a
     * BETYPE attribute, which specifies the kind of beginning/ending point values that
     * are being used.
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
     * BEGIN (string/O): An attribute that specifies the point in the parent <file>
     * where the current <file> begins. When used in conjunction with a <file> element,
     * this attribute is only meaningful when this element is nested, and its parent
     * <file> element represents a container file. It can be used in conjunction with
     * the END attribute as a means of defining the location of the current file within
     * its parent file. However, the BEGIN attribute can be used with or without a
     * companion END attribute. When no END attribute is specified, the end of the
     * parent file is assumed also to be the end point of the current file. The BEGIN
     * and END attributes can only be interpreted meaningfully in conjunction with a
     * BETYPE attribute, which specifies the kind of beginning/ending point values that
     * are being used.
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
     * END (string/O): An attribute that specifies the point in the parent <file> where
     * the current, nested <file> ends. It can only be interpreted meaningfully in
     * conjunction with the BETYPE, which specifies the kind of ending point values
     * being used. Typically the END attribute would only appear in conjunction with a
     * BEGIN attribute.
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
     * END (string/O): An attribute that specifies the point in the parent <file> where
     * the current, nested <file> ends. It can only be interpreted meaningfully in
     * conjunction with the BETYPE, which specifies the kind of ending point values
     * being used. Typically the END attribute would only appear in conjunction with a
     * BEGIN attribute.
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
     * values that are being used. Currently BYTE is the only valid value that can be
     * used in conjunction with nested <file> or <stream> elements.
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
     * values that are being used. Currently BYTE is the only valid value that can be
     * used in conjunction with nested <file> or <stream> elements.
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
     * Adds as FLocatElName
     *
     * The file location element <FLocat> provides a pointer to the location of a
     * content file. It uses the XLink reference syntax to provide linking information
     * indicating the actual location of the content file, along with other attributes
     * specifying additional linking information. NOTE: <FLocat> is an empty element.
     * The location of the resource pointed to MUST be stored in the xlink:href
     * attribute.
     *
     * @return self
     * @param \App\Xsd\Mets\FileType\FLocatAType $FLocatElName
     */
    public function addToFLocatElName(\App\Xsd\Mets\FileType\FLocatAType $FLocatElName)
    {
        $this->FLocatElName[] = $FLocatElName;
        return $this;
    }

    /**
     * isset FLocatElName
     *
     * The file location element <FLocat> provides a pointer to the location of a
     * content file. It uses the XLink reference syntax to provide linking information
     * indicating the actual location of the content file, along with other attributes
     * specifying additional linking information. NOTE: <FLocat> is an empty element.
     * The location of the resource pointed to MUST be stored in the xlink:href
     * attribute.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFLocatElName($index)
    {
        return isset($this->FLocatElName[$index]);
    }

    /**
     * unset FLocatElName
     *
     * The file location element <FLocat> provides a pointer to the location of a
     * content file. It uses the XLink reference syntax to provide linking information
     * indicating the actual location of the content file, along with other attributes
     * specifying additional linking information. NOTE: <FLocat> is an empty element.
     * The location of the resource pointed to MUST be stored in the xlink:href
     * attribute.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFLocatElName($index)
    {
        unset($this->FLocatElName[$index]);
    }

    /**
     * Gets as FLocatElName
     *
     * The file location element <FLocat> provides a pointer to the location of a
     * content file. It uses the XLink reference syntax to provide linking information
     * indicating the actual location of the content file, along with other attributes
     * specifying additional linking information. NOTE: <FLocat> is an empty element.
     * The location of the resource pointed to MUST be stored in the xlink:href
     * attribute.
     *
     * @return \App\Xsd\Mets\FileType\FLocatAType[]
     */
    public function getFLocatElName()
    {
        return $this->FLocatElName;
    }

    /**
     * Sets a new FLocatElName
     *
     * The file location element <FLocat> provides a pointer to the location of a
     * content file. It uses the XLink reference syntax to provide linking information
     * indicating the actual location of the content file, along with other attributes
     * specifying additional linking information. NOTE: <FLocat> is an empty element.
     * The location of the resource pointed to MUST be stored in the xlink:href
     * attribute.
     *
     * @param \App\Xsd\Mets\FileType\FLocatAType[] $FLocatElName
     * @return self
     */
    public function setFLocatElName(array $FLocatElName)
    {
        $this->FLocatElName = $FLocatElName;
        return $this;
    }

    /**
     * Gets as FContentElName
     *
     * The file content element <FContent> is used to identify a content file contained
     * internally within a METS document. The content file must be either Base64
     * encoded and contained within the subsidiary <binData> wrapper element, or
     * consist of XML information and be contained within the subsidiary <xmlData>
     * wrapper element.
     *
     * @return \App\Xsd\Mets\FileType\FContentAType
     */
    public function getFContentElName()
    {
        return $this->FContentElName;
    }

    /**
     * Sets a new FContentElName
     *
     * The file content element <FContent> is used to identify a content file contained
     * internally within a METS document. The content file must be either Base64
     * encoded and contained within the subsidiary <binData> wrapper element, or
     * consist of XML information and be contained within the subsidiary <xmlData>
     * wrapper element.
     *
     * @param \App\Xsd\Mets\FileType\FContentAType $FContentElName
     * @return self
     */
    public function setFContentElName(\App\Xsd\Mets\FileType\FContentAType $FContentElName)
    {
        $this->FContentElName = $FContentElName;
        return $this;
    }

    /**
     * Adds as streamElName
     *
     * A component byte stream element <stream> may be composed of one or more
     * subsidiary streams. An MPEG4 file, for example, might contain separate audio and
     * video streams, each of which is associated with technical metadata. The
     * repeatable <stream> element provides a mechanism to record the existence of
     * separate data streams within a particular file, and the opportunity to associate
     * <dmdSec> and <amdSec> with those subsidiary data streams if desired.
     *
     * @return self
     * @param \App\Xsd\AnyTypeHandler $streamElName
     */
    public function addToStreamElName(\App\Xsd\AnyTypeHandler $streamElName)
    {
        $this->streamElName[] = $streamElName;
        return $this;
    }

    /**
     * isset streamElName
     *
     * A component byte stream element <stream> may be composed of one or more
     * subsidiary streams. An MPEG4 file, for example, might contain separate audio and
     * video streams, each of which is associated with technical metadata. The
     * repeatable <stream> element provides a mechanism to record the existence of
     * separate data streams within a particular file, and the opportunity to associate
     * <dmdSec> and <amdSec> with those subsidiary data streams if desired.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetStreamElName($index)
    {
        return isset($this->streamElName[$index]);
    }

    /**
     * unset streamElName
     *
     * A component byte stream element <stream> may be composed of one or more
     * subsidiary streams. An MPEG4 file, for example, might contain separate audio and
     * video streams, each of which is associated with technical metadata. The
     * repeatable <stream> element provides a mechanism to record the existence of
     * separate data streams within a particular file, and the opportunity to associate
     * <dmdSec> and <amdSec> with those subsidiary data streams if desired.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetStreamElName($index)
    {
        unset($this->streamElName[$index]);
    }

    /**
     * Gets as streamElName
     *
     * A component byte stream element <stream> may be composed of one or more
     * subsidiary streams. An MPEG4 file, for example, might contain separate audio and
     * video streams, each of which is associated with technical metadata. The
     * repeatable <stream> element provides a mechanism to record the existence of
     * separate data streams within a particular file, and the opportunity to associate
     * <dmdSec> and <amdSec> with those subsidiary data streams if desired.
     *
     * @return \App\Xsd\AnyTypeHandler[]
     */
    public function getStreamElName()
    {
        return $this->streamElName;
    }

    /**
     * Sets a new streamElName
     *
     * A component byte stream element <stream> may be composed of one or more
     * subsidiary streams. An MPEG4 file, for example, might contain separate audio and
     * video streams, each of which is associated with technical metadata. The
     * repeatable <stream> element provides a mechanism to record the existence of
     * separate data streams within a particular file, and the opportunity to associate
     * <dmdSec> and <amdSec> with those subsidiary data streams if desired.
     *
     * @param \App\Xsd\AnyTypeHandler $streamElName
     * @return self
     */
    public function setStreamElName(array $streamElName)
    {
        $this->streamElName = $streamElName;
        return $this;
    }

    /**
     * Adds as transformFileElName
     *
     * The transform file element <transformFile> provides a means to access any
     * subsidiary files listed below a <file> element by indicating the steps required
     * to "unpack" or transform the subsidiary files. This element is repeatable and
     * might provide a link to a <behavior> in the <behaviorSec> that performs the
     * transformation.
     *
     * @return self
     * @param \App\Xsd\AnyTypeHandler $transformFileElName
     */
    public function addToTransformFileElName(\App\Xsd\AnyTypeHandler $transformFileElName)
    {
        $this->transformFileElName[] = $transformFileElName;
        return $this;
    }

    /**
     * isset transformFileElName
     *
     * The transform file element <transformFile> provides a means to access any
     * subsidiary files listed below a <file> element by indicating the steps required
     * to "unpack" or transform the subsidiary files. This element is repeatable and
     * might provide a link to a <behavior> in the <behaviorSec> that performs the
     * transformation.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTransformFileElName($index)
    {
        return isset($this->transformFileElName[$index]);
    }

    /**
     * unset transformFileElName
     *
     * The transform file element <transformFile> provides a means to access any
     * subsidiary files listed below a <file> element by indicating the steps required
     * to "unpack" or transform the subsidiary files. This element is repeatable and
     * might provide a link to a <behavior> in the <behaviorSec> that performs the
     * transformation.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTransformFileElName($index)
    {
        unset($this->transformFileElName[$index]);
    }

    /**
     * Gets as transformFileElName
     *
     * The transform file element <transformFile> provides a means to access any
     * subsidiary files listed below a <file> element by indicating the steps required
     * to "unpack" or transform the subsidiary files. This element is repeatable and
     * might provide a link to a <behavior> in the <behaviorSec> that performs the
     * transformation.
     *
     * @return \App\Xsd\AnyTypeHandler[]
     */
    public function getTransformFileElName()
    {
        return $this->transformFileElName;
    }

    /**
     * Sets a new transformFileElName
     *
     * The transform file element <transformFile> provides a means to access any
     * subsidiary files listed below a <file> element by indicating the steps required
     * to "unpack" or transform the subsidiary files. This element is repeatable and
     * might provide a link to a <behavior> in the <behaviorSec> that performs the
     * transformation.
     *
     * @param \App\Xsd\AnyTypeHandler $transformFileElName
     * @return self
     */
    public function setTransformFileElName(array $transformFileElName)
    {
        $this->transformFileElName = $transformFileElName;
        return $this;
    }

    /**
     * Adds as fileElName
     *
     * @return self
     * @param \App\Xsd\Mets\FileType $fileElName
     */
    public function addToFileElName(\App\Xsd\Mets\FileType $fileElName)
    {
        $this->fileElName[] = $fileElName;
        return $this;
    }

    /**
     * isset fileElName
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFileElName($index)
    {
        return isset($this->fileElName[$index]);
    }

    /**
     * unset fileElName
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFileElName($index)
    {
        unset($this->fileElName[$index]);
    }

    /**
     * Gets as fileElName
     *
     * @return \App\Xsd\Mets\FileType[]
     */
    public function getFileElName()
    {
        return $this->fileElName;
    }

    /**
     * Sets a new fileElName
     *
     * @param \App\Xsd\Mets\FileType[] $fileElName
     * @return self
     */
    public function setFileElName(array $fileElName)
    {
        $this->fileElName = $fileElName;
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

