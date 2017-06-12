<?php

namespace App\Xsd\Mets\MdSecType;

/**
 * Class representing MdRefAType
 */
class MdRefAType
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
     * LOCTYPE (string/R): Specifies the locator type used in the xlink:href attribute.
     * Valid values for LOCTYPE are: 
     *  ARK
     *  URN
     *  URL
     *  PURL
     *  HANDLE
     *  DOI
     *  OTHER
     *
     * @property string $LOCTYPEAttrName
     */
    private $LOCTYPEAttrName = null;

    /**
     * OTHERLOCTYPE (string/O): Specifies the locator type when the value OTHER is used
     * in the LOCTYPE attribute. Although optional, it is strongly recommended when
     * OTHER is used.
     *
     * @property string $OTHERLOCTYPEAttrName
     */
    private $OTHERLOCTYPEAttrName = null;

    /**
     * @property string $typeAttrName
     */
    private $typeAttrName = null;

    /**
     * @property string $hrefPropName
     */
    private $hrefPropName = null;

    /**
     * @property string $rolePropName
     */
    private $rolePropName = null;

    /**
     * @property string $arcrolePropName
     */
    private $arcrolePropName = null;

    /**
     * @property string $titlePropName
     */
    private $titlePropName = null;

    /**
     * @property string $showPropName
     */
    private $showPropName = null;

    /**
     * @property string $actuatePropName
     */
    private $actuatePropName = null;

    /**
     * MDTYPE (string/R): Is used to indicate the type of the associated metadata. It
     * must have one of the following values:
     * MARC: any form of MARC record
     * MODS: metadata in the Library of Congress MODS format
     * EAD: Encoded Archival Description finding aid
     * DC: Dublin Core
     * NISOIMG: NISO Technical Metadata for Digital Still Images
     * LC-AV: technical metadata specified in the Library of Congress A/V prototyping
     * project
     * VRA: Visual Resources Association Core
     * TEIHDR: Text Encoding Initiative Header
     * DDI: Data Documentation Initiative
     * FGDC: Federal Geographic Data Committee metadata
     * LOM: Learning Object Model
     * PREMIS: PREservation Metadata: Implementation Strategies
     * PREMIS:OBJECT: PREMIS Object entiry
     * PREMIS:AGENT: PREMIS Agent entity
     * PREMIS:RIGHTS: PREMIS Rights entity
     * PREMIS:EVENT: PREMIS Event entity
     * TEXTMD: textMD Technical metadata for text
     * METSRIGHTS: Rights Declaration Schema
     * ISO 19115:2003 NAP: North American Profile of ISO 19115:2003 descriptive
     * metadata
     * EAC-CPF: Encoded Archival Context - Corporate Bodies, Persons, and Families
     * LIDO: Lightweight Information Describing Objects
     * OTHER: metadata in a format not specified above
     *
     * @property string $MDTYPEAttrName
     */
    private $MDTYPEAttrName = null;

    /**
     * OTHERMDTYPE (string/O): Specifies the form of metadata in use when the value
     * OTHER is indicated in the MDTYPE attribute.
     *
     * @property string $OTHERMDTYPEAttrName
     */
    private $OTHERMDTYPEAttrName = null;

    /**
     * MDTYPEVERSION(string/O): Provides a means for recording the version of the type
     * of metadata (as recorded in the MDTYPE or OTHERMDTYPE attribute) that is being
     * used. This may represent the version of the underlying data dictionary or
     * metadata model rather than a schema version.
     *
     * @property string $MDTYPEVERSIONAttrName
     */
    private $MDTYPEVERSIONAttrName = null;

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
     * LABEL (string/O): Provides a label to display to the viewer of the METS document
     * that identifies the associated metadata.
     *
     * @property string $LABELAttrName
     */
    private $LABELAttrName = null;

    /**
     * XPTR (string/O): Locates the point within a file to which the <mdRef> element
     * refers, if applicable.
     *
     * @property string $XPTRAttrName
     */
    private $XPTRAttrName = null;

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
     * Gets as LOCTYPEAttrName
     *
     * LOCTYPE (string/R): Specifies the locator type used in the xlink:href attribute.
     * Valid values for LOCTYPE are: 
     *  ARK
     *  URN
     *  URL
     *  PURL
     *  HANDLE
     *  DOI
     *  OTHER
     *
     * @return string
     */
    public function getLOCTYPEAttrName()
    {
        return $this->LOCTYPEAttrName;
    }

    /**
     * Sets a new LOCTYPEAttrName
     *
     * LOCTYPE (string/R): Specifies the locator type used in the xlink:href attribute.
     * Valid values for LOCTYPE are: 
     *  ARK
     *  URN
     *  URL
     *  PURL
     *  HANDLE
     *  DOI
     *  OTHER
     *
     * @param string $LOCTYPEAttrName
     * @return self
     */
    public function setLOCTYPEAttrName($LOCTYPEAttrName)
    {
        $this->LOCTYPEAttrName = $LOCTYPEAttrName;
        return $this;
    }

    /**
     * Gets as OTHERLOCTYPEAttrName
     *
     * OTHERLOCTYPE (string/O): Specifies the locator type when the value OTHER is used
     * in the LOCTYPE attribute. Although optional, it is strongly recommended when
     * OTHER is used.
     *
     * @return string
     */
    public function getOTHERLOCTYPEAttrName()
    {
        return $this->OTHERLOCTYPEAttrName;
    }

    /**
     * Sets a new OTHERLOCTYPEAttrName
     *
     * OTHERLOCTYPE (string/O): Specifies the locator type when the value OTHER is used
     * in the LOCTYPE attribute. Although optional, it is strongly recommended when
     * OTHER is used.
     *
     * @param string $OTHERLOCTYPEAttrName
     * @return self
     */
    public function setOTHERLOCTYPEAttrName($OTHERLOCTYPEAttrName)
    {
        $this->OTHERLOCTYPEAttrName = $OTHERLOCTYPEAttrName;
        return $this;
    }

    /**
     * Gets as typeAttrName
     *
     * @return string
     */
    public function getTypeAttrName()
    {
        return $this->typeAttrName;
    }

    /**
     * Sets a new typeAttrName
     *
     * @param string $typeAttrName
     * @return self
     */
    public function setTypeAttrName($typeAttrName)
    {
        $this->typeAttrName = $typeAttrName;
        return $this;
    }

    /**
     * Gets as hrefPropName
     *
     * @return string
     */
    public function getHrefPropName()
    {
        return $this->hrefPropName;
    }

    /**
     * Sets a new hrefPropName
     *
     * @param string $hrefPropName
     * @return self
     */
    public function setHrefPropName($hrefPropName)
    {
        $this->hrefPropName = $hrefPropName;
        return $this;
    }

    /**
     * Gets as rolePropName
     *
     * @return string
     */
    public function getRolePropName()
    {
        return $this->rolePropName;
    }

    /**
     * Sets a new rolePropName
     *
     * @param string $rolePropName
     * @return self
     */
    public function setRolePropName($rolePropName)
    {
        $this->rolePropName = $rolePropName;
        return $this;
    }

    /**
     * Gets as arcrolePropName
     *
     * @return string
     */
    public function getArcrolePropName()
    {
        return $this->arcrolePropName;
    }

    /**
     * Sets a new arcrolePropName
     *
     * @param string $arcrolePropName
     * @return self
     */
    public function setArcrolePropName($arcrolePropName)
    {
        $this->arcrolePropName = $arcrolePropName;
        return $this;
    }

    /**
     * Gets as titlePropName
     *
     * @return string
     */
    public function getTitlePropName()
    {
        return $this->titlePropName;
    }

    /**
     * Sets a new titlePropName
     *
     * @param string $titlePropName
     * @return self
     */
    public function setTitlePropName($titlePropName)
    {
        $this->titlePropName = $titlePropName;
        return $this;
    }

    /**
     * Gets as showPropName
     *
     * @return string
     */
    public function getShowPropName()
    {
        return $this->showPropName;
    }

    /**
     * Sets a new showPropName
     *
     * @param string $showPropName
     * @return self
     */
    public function setShowPropName($showPropName)
    {
        $this->showPropName = $showPropName;
        return $this;
    }

    /**
     * Gets as actuatePropName
     *
     * @return string
     */
    public function getActuatePropName()
    {
        return $this->actuatePropName;
    }

    /**
     * Sets a new actuatePropName
     *
     * @param string $actuatePropName
     * @return self
     */
    public function setActuatePropName($actuatePropName)
    {
        $this->actuatePropName = $actuatePropName;
        return $this;
    }

    /**
     * Gets as MDTYPEAttrName
     *
     * MDTYPE (string/R): Is used to indicate the type of the associated metadata. It
     * must have one of the following values:
     * MARC: any form of MARC record
     * MODS: metadata in the Library of Congress MODS format
     * EAD: Encoded Archival Description finding aid
     * DC: Dublin Core
     * NISOIMG: NISO Technical Metadata for Digital Still Images
     * LC-AV: technical metadata specified in the Library of Congress A/V prototyping
     * project
     * VRA: Visual Resources Association Core
     * TEIHDR: Text Encoding Initiative Header
     * DDI: Data Documentation Initiative
     * FGDC: Federal Geographic Data Committee metadata
     * LOM: Learning Object Model
     * PREMIS: PREservation Metadata: Implementation Strategies
     * PREMIS:OBJECT: PREMIS Object entiry
     * PREMIS:AGENT: PREMIS Agent entity
     * PREMIS:RIGHTS: PREMIS Rights entity
     * PREMIS:EVENT: PREMIS Event entity
     * TEXTMD: textMD Technical metadata for text
     * METSRIGHTS: Rights Declaration Schema
     * ISO 19115:2003 NAP: North American Profile of ISO 19115:2003 descriptive
     * metadata
     * EAC-CPF: Encoded Archival Context - Corporate Bodies, Persons, and Families
     * LIDO: Lightweight Information Describing Objects
     * OTHER: metadata in a format not specified above
     *
     * @return string
     */
    public function getMDTYPEAttrName()
    {
        return $this->MDTYPEAttrName;
    }

    /**
     * Sets a new MDTYPEAttrName
     *
     * MDTYPE (string/R): Is used to indicate the type of the associated metadata. It
     * must have one of the following values:
     * MARC: any form of MARC record
     * MODS: metadata in the Library of Congress MODS format
     * EAD: Encoded Archival Description finding aid
     * DC: Dublin Core
     * NISOIMG: NISO Technical Metadata for Digital Still Images
     * LC-AV: technical metadata specified in the Library of Congress A/V prototyping
     * project
     * VRA: Visual Resources Association Core
     * TEIHDR: Text Encoding Initiative Header
     * DDI: Data Documentation Initiative
     * FGDC: Federal Geographic Data Committee metadata
     * LOM: Learning Object Model
     * PREMIS: PREservation Metadata: Implementation Strategies
     * PREMIS:OBJECT: PREMIS Object entiry
     * PREMIS:AGENT: PREMIS Agent entity
     * PREMIS:RIGHTS: PREMIS Rights entity
     * PREMIS:EVENT: PREMIS Event entity
     * TEXTMD: textMD Technical metadata for text
     * METSRIGHTS: Rights Declaration Schema
     * ISO 19115:2003 NAP: North American Profile of ISO 19115:2003 descriptive
     * metadata
     * EAC-CPF: Encoded Archival Context - Corporate Bodies, Persons, and Families
     * LIDO: Lightweight Information Describing Objects
     * OTHER: metadata in a format not specified above
     *
     * @param string $MDTYPEAttrName
     * @return self
     */
    public function setMDTYPEAttrName($MDTYPEAttrName)
    {
        $this->MDTYPEAttrName = $MDTYPEAttrName;
        return $this;
    }

    /**
     * Gets as OTHERMDTYPEAttrName
     *
     * OTHERMDTYPE (string/O): Specifies the form of metadata in use when the value
     * OTHER is indicated in the MDTYPE attribute.
     *
     * @return string
     */
    public function getOTHERMDTYPEAttrName()
    {
        return $this->OTHERMDTYPEAttrName;
    }

    /**
     * Sets a new OTHERMDTYPEAttrName
     *
     * OTHERMDTYPE (string/O): Specifies the form of metadata in use when the value
     * OTHER is indicated in the MDTYPE attribute.
     *
     * @param string $OTHERMDTYPEAttrName
     * @return self
     */
    public function setOTHERMDTYPEAttrName($OTHERMDTYPEAttrName)
    {
        $this->OTHERMDTYPEAttrName = $OTHERMDTYPEAttrName;
        return $this;
    }

    /**
     * Gets as MDTYPEVERSIONAttrName
     *
     * MDTYPEVERSION(string/O): Provides a means for recording the version of the type
     * of metadata (as recorded in the MDTYPE or OTHERMDTYPE attribute) that is being
     * used. This may represent the version of the underlying data dictionary or
     * metadata model rather than a schema version.
     *
     * @return string
     */
    public function getMDTYPEVERSIONAttrName()
    {
        return $this->MDTYPEVERSIONAttrName;
    }

    /**
     * Sets a new MDTYPEVERSIONAttrName
     *
     * MDTYPEVERSION(string/O): Provides a means for recording the version of the type
     * of metadata (as recorded in the MDTYPE or OTHERMDTYPE attribute) that is being
     * used. This may represent the version of the underlying data dictionary or
     * metadata model rather than a schema version.
     *
     * @param string $MDTYPEVERSIONAttrName
     * @return self
     */
    public function setMDTYPEVERSIONAttrName($MDTYPEVERSIONAttrName)
    {
        $this->MDTYPEVERSIONAttrName = $MDTYPEVERSIONAttrName;
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
     * Gets as LABELAttrName
     *
     * LABEL (string/O): Provides a label to display to the viewer of the METS document
     * that identifies the associated metadata.
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
     * LABEL (string/O): Provides a label to display to the viewer of the METS document
     * that identifies the associated metadata.
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
     * Gets as XPTRAttrName
     *
     * XPTR (string/O): Locates the point within a file to which the <mdRef> element
     * refers, if applicable.
     *
     * @return string
     */
    public function getXPTRAttrName()
    {
        return $this->XPTRAttrName;
    }

    /**
     * Sets a new XPTRAttrName
     *
     * XPTR (string/O): Locates the point within a file to which the <mdRef> element
     * refers, if applicable.
     *
     * @param string $XPTRAttrName
     * @return self
     */
    public function setXPTRAttrName($XPTRAttrName)
    {
        $this->XPTRAttrName = $XPTRAttrName;
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

