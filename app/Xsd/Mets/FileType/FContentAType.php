<?php

namespace App\Xsd\Mets\FileType;

/**
 * Class representing FContentAType
 */
class FContentAType
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
     * USE (string/O): A tagging attribute to indicate the intended use of the specific
     * copy of the file represented by the <FContent> element (e.g., service master,
     * archive master). A USE attribute can be expressed at the<fileGrp> level, the
     * <file> level, the <FLocat> level and/or the <FContent> level. A USE attribute
     * value at the <fileGrp> level should pertain to all of the files in the
     * <fileGrp>. A USE attribute at the <file> level should pertain to all copies of
     * the file as represented by subsidiary <FLocat> and/or <FContent> elements. A USE
     * attribute at the <FLocat> or <FContent> level pertains to the particular copy of
     * the file that is either referenced (<FLocat>) or wrapped (<FContent>).
     *
     * @property string $USEAttrName
     */
    private $USEAttrName = null;

    /**
     * A binary data wrapper element <binData> is used to contain a Base64 encoded
     * file.
     *
     * @property \App\Xsd\AnySimpleTypeHandler $binDataElName
     */
    private $binDataElName = null;

    /**
     * An xml data wrapper element <xmlData> is used to contain an XML encoded file.
     * The content of an <xmlData> element can be in any namespace or in no namespace.
     * As permitted by the XML Schema Standard, the processContents attribute value for
     * the metadata in an <xmlData> element is set to “lax”. Therefore, if the
     * source schema and its location are identified by means of an xsi:schemaLocation
     * attribute, then an XML processor will validate the elements for which it can
     * find declarations. If a source schema is not identified, or cannot be found at
     * the specified schemaLocation, then an XML validator will check for
     * well-formedness, but otherwise skip over the elements appearing in the <xmlData>
     * element.
     *
     * @property \App\Xsd\Mets\FileType\FContentAType\XmlDataAType $xmlDataElName
     */
    private $xmlDataElName = null;

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
     * Gets as USEAttrName
     *
     * USE (string/O): A tagging attribute to indicate the intended use of the specific
     * copy of the file represented by the <FContent> element (e.g., service master,
     * archive master). A USE attribute can be expressed at the<fileGrp> level, the
     * <file> level, the <FLocat> level and/or the <FContent> level. A USE attribute
     * value at the <fileGrp> level should pertain to all of the files in the
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
     * USE (string/O): A tagging attribute to indicate the intended use of the specific
     * copy of the file represented by the <FContent> element (e.g., service master,
     * archive master). A USE attribute can be expressed at the<fileGrp> level, the
     * <file> level, the <FLocat> level and/or the <FContent> level. A USE attribute
     * value at the <fileGrp> level should pertain to all of the files in the
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
     * Gets as binDataElName
     *
     * A binary data wrapper element <binData> is used to contain a Base64 encoded
     * file.
     *
     * @return \App\Xsd\AnySimpleTypeHandler
     */
    public function getBinDataElName()
    {
        return $this->binDataElName;
    }

    /**
     * Sets a new binDataElName
     *
     * A binary data wrapper element <binData> is used to contain a Base64 encoded
     * file.
     *
     * @param \App\Xsd\AnySimpleTypeHandler $binDataElName
     * @return self
     */
    public function setBinDataElName(\App\Xsd\AnySimpleTypeHandler $binDataElName)
    {
        $this->binDataElName = $binDataElName;
        return $this;
    }

    /**
     * Gets as xmlDataElName
     *
     * An xml data wrapper element <xmlData> is used to contain an XML encoded file.
     * The content of an <xmlData> element can be in any namespace or in no namespace.
     * As permitted by the XML Schema Standard, the processContents attribute value for
     * the metadata in an <xmlData> element is set to “lax”. Therefore, if the
     * source schema and its location are identified by means of an xsi:schemaLocation
     * attribute, then an XML processor will validate the elements for which it can
     * find declarations. If a source schema is not identified, or cannot be found at
     * the specified schemaLocation, then an XML validator will check for
     * well-formedness, but otherwise skip over the elements appearing in the <xmlData>
     * element.
     *
     * @return \App\Xsd\Mets\FileType\FContentAType\XmlDataAType
     */
    public function getXmlDataElName()
    {
        return $this->xmlDataElName;
    }

    /**
     * Sets a new xmlDataElName
     *
     * An xml data wrapper element <xmlData> is used to contain an XML encoded file.
     * The content of an <xmlData> element can be in any namespace or in no namespace.
     * As permitted by the XML Schema Standard, the processContents attribute value for
     * the metadata in an <xmlData> element is set to “lax”. Therefore, if the
     * source schema and its location are identified by means of an xsi:schemaLocation
     * attribute, then an XML processor will validate the elements for which it can
     * find declarations. If a source schema is not identified, or cannot be found at
     * the specified schemaLocation, then an XML validator will check for
     * well-formedness, but otherwise skip over the elements appearing in the <xmlData>
     * element.
     *
     * @param \App\Xsd\Mets\FileType\FContentAType\XmlDataAType $xmlDataElName
     * @return self
     */
    public function setXmlDataElName(\App\Xsd\Mets\FileType\FContentAType\XmlDataAType $xmlDataElName)
    {
        $this->xmlDataElName = $xmlDataElName;
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

