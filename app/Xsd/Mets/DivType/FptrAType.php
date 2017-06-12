<?php

namespace App\Xsd\Mets\DivType;

/**
 * Class representing FptrAType
 */
class FptrAType
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
     * FILEID (IDREF/O): An optional attribute that provides the XML ID identifying the
     * <file> element that links to and/or contains the digital content represented by
     * the <fptr>. A <fptr> element should only have a FILEID attribute value if it
     * does not have a child <area>, <par> or <seq> element. If it has a child element,
     * then the responsibility for pointing to the relevant content falls to this child
     * element or its descendants.
     *
     * @property string $FILEIDAttrName
     */
    private $FILEIDAttrName = null;

    /**
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <fptr>
     * (equivalent to DIDL DII or Digital Item Identifier, a unique external ID).
     *
     * @property string[] $CONTENTIDSAttrName
     */
    private $CONTENTIDSAttrName = null;

    /**
     * The <par> or parallel files element aggregates pointers to files, parts of
     * files, and/or sequences of files or parts of files that must be played or
     * displayed simultaneously to manifest a block of digital content represented by
     * an <fptr> element. This might be the case, for example, with multi-media
     * content, where a still image might have an accompanying audio track that
     * comments on the still image. In this case, a <par> element would aggregate two
     * <area> elements, one of which pointed to the image file and one of which pointed
     * to the audio file that must be played in conjunction with the image. The <area>
     * element associated with the image could be further qualified with SHAPE and
     * COORDS attributes if only a portion of the image file was pertinent and the
     * <area> element associated with the audio file could be further qualified with
     * BETYPE, BEGIN, EXTTYPE, and EXTENT attributes if only a portion of the
     * associated audio file should be played in conjunction with the image.
     *
     * @property \App\Xsd\Mets\ParType $parElName
     */
    private $parElName = null;

    /**
     * The sequence of files element <seq> aggregates pointers to files, parts of files
     * and/or parallel sets of files or parts of files that must be played or displayed
     * sequentially to manifest a block of digital content. This might be the case, for
     * example, if the parent <div> element represented a logical division, such as a
     * diary entry, that spanned multiple pages of a diary and, hence, multiple page
     * image files. In this case, a <seq> element would aggregate multiple,
     * sequentially arranged <area> elements, each of which pointed to one of the image
     * files that must be presented sequentially to manifest the entire diary entry. If
     * the diary entry started in the middle of a page, then the first <area> element
     * (representing the page on which the diary entry starts) might be further
     * qualified, via its SHAPE and COORDS attributes, to specify the specific,
     * pertinent area of the associated image file.
     *
     * @property \App\Xsd\Mets\SeqType $seqElName
     */
    private $seqElName = null;

    /**
     * The area element <area> typically points to content consisting of just a portion
     * or area of a file represented by a <file> element in the <fileSec>. In some
     * contexts, however, the <area> element can also point to content represented by
     * an integral file. A single <area> element would appear as the direct child of a
     * <fptr> element when only a portion of a <file>, rather than an integral <file>,
     * manifested the digital content represented by the <fptr>. Multiple <area>
     * elements would appear as the direct children of a <par> element or a <seq>
     * element when multiple files or parts of files manifested the digital content
     * represented by an <fptr> element. When used in the context of a <par> or <seq>
     * element an <area> element can point either to an integral file or to a segment
     * of a file as necessary.
     *
     * @property \App\Xsd\Mets\AreaType $areaElName
     */
    private $areaElName = null;

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
     * FILEID (IDREF/O): An optional attribute that provides the XML ID identifying the
     * <file> element that links to and/or contains the digital content represented by
     * the <fptr>. A <fptr> element should only have a FILEID attribute value if it
     * does not have a child <area>, <par> or <seq> element. If it has a child element,
     * then the responsibility for pointing to the relevant content falls to this child
     * element or its descendants.
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
     * FILEID (IDREF/O): An optional attribute that provides the XML ID identifying the
     * <file> element that links to and/or contains the digital content represented by
     * the <fptr>. A <fptr> element should only have a FILEID attribute value if it
     * does not have a child <area>, <par> or <seq> element. If it has a child element,
     * then the responsibility for pointing to the relevant content falls to this child
     * element or its descendants.
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
     * Adds as CONTENTIDSAttrName
     *
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <fptr>
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
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <fptr>
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
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <fptr>
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
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <fptr>
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
     * CONTENTIDS (URI/O): Content IDs for the content represented by the <fptr>
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
     * Gets as parElName
     *
     * The <par> or parallel files element aggregates pointers to files, parts of
     * files, and/or sequences of files or parts of files that must be played or
     * displayed simultaneously to manifest a block of digital content represented by
     * an <fptr> element. This might be the case, for example, with multi-media
     * content, where a still image might have an accompanying audio track that
     * comments on the still image. In this case, a <par> element would aggregate two
     * <area> elements, one of which pointed to the image file and one of which pointed
     * to the audio file that must be played in conjunction with the image. The <area>
     * element associated with the image could be further qualified with SHAPE and
     * COORDS attributes if only a portion of the image file was pertinent and the
     * <area> element associated with the audio file could be further qualified with
     * BETYPE, BEGIN, EXTTYPE, and EXTENT attributes if only a portion of the
     * associated audio file should be played in conjunction with the image.
     *
     * @return \App\Xsd\Mets\ParType
     */
    public function getParElName()
    {
        return $this->parElName;
    }

    /**
     * Sets a new parElName
     *
     * The <par> or parallel files element aggregates pointers to files, parts of
     * files, and/or sequences of files or parts of files that must be played or
     * displayed simultaneously to manifest a block of digital content represented by
     * an <fptr> element. This might be the case, for example, with multi-media
     * content, where a still image might have an accompanying audio track that
     * comments on the still image. In this case, a <par> element would aggregate two
     * <area> elements, one of which pointed to the image file and one of which pointed
     * to the audio file that must be played in conjunction with the image. The <area>
     * element associated with the image could be further qualified with SHAPE and
     * COORDS attributes if only a portion of the image file was pertinent and the
     * <area> element associated with the audio file could be further qualified with
     * BETYPE, BEGIN, EXTTYPE, and EXTENT attributes if only a portion of the
     * associated audio file should be played in conjunction with the image.
     *
     * @param \App\Xsd\Mets\ParType $parElName
     * @return self
     */
    public function setParElName(\App\Xsd\Mets\ParType $parElName)
    {
        $this->parElName = $parElName;
        return $this;
    }

    /**
     * Gets as seqElName
     *
     * The sequence of files element <seq> aggregates pointers to files, parts of files
     * and/or parallel sets of files or parts of files that must be played or displayed
     * sequentially to manifest a block of digital content. This might be the case, for
     * example, if the parent <div> element represented a logical division, such as a
     * diary entry, that spanned multiple pages of a diary and, hence, multiple page
     * image files. In this case, a <seq> element would aggregate multiple,
     * sequentially arranged <area> elements, each of which pointed to one of the image
     * files that must be presented sequentially to manifest the entire diary entry. If
     * the diary entry started in the middle of a page, then the first <area> element
     * (representing the page on which the diary entry starts) might be further
     * qualified, via its SHAPE and COORDS attributes, to specify the specific,
     * pertinent area of the associated image file.
     *
     * @return \App\Xsd\Mets\SeqType
     */
    public function getSeqElName()
    {
        return $this->seqElName;
    }

    /**
     * Sets a new seqElName
     *
     * The sequence of files element <seq> aggregates pointers to files, parts of files
     * and/or parallel sets of files or parts of files that must be played or displayed
     * sequentially to manifest a block of digital content. This might be the case, for
     * example, if the parent <div> element represented a logical division, such as a
     * diary entry, that spanned multiple pages of a diary and, hence, multiple page
     * image files. In this case, a <seq> element would aggregate multiple,
     * sequentially arranged <area> elements, each of which pointed to one of the image
     * files that must be presented sequentially to manifest the entire diary entry. If
     * the diary entry started in the middle of a page, then the first <area> element
     * (representing the page on which the diary entry starts) might be further
     * qualified, via its SHAPE and COORDS attributes, to specify the specific,
     * pertinent area of the associated image file.
     *
     * @param \App\Xsd\Mets\SeqType $seqElName
     * @return self
     */
    public function setSeqElName(\App\Xsd\Mets\SeqType $seqElName)
    {
        $this->seqElName = $seqElName;
        return $this;
    }

    /**
     * Gets as areaElName
     *
     * The area element <area> typically points to content consisting of just a portion
     * or area of a file represented by a <file> element in the <fileSec>. In some
     * contexts, however, the <area> element can also point to content represented by
     * an integral file. A single <area> element would appear as the direct child of a
     * <fptr> element when only a portion of a <file>, rather than an integral <file>,
     * manifested the digital content represented by the <fptr>. Multiple <area>
     * elements would appear as the direct children of a <par> element or a <seq>
     * element when multiple files or parts of files manifested the digital content
     * represented by an <fptr> element. When used in the context of a <par> or <seq>
     * element an <area> element can point either to an integral file or to a segment
     * of a file as necessary.
     *
     * @return \App\Xsd\Mets\AreaType
     */
    public function getAreaElName()
    {
        return $this->areaElName;
    }

    /**
     * Sets a new areaElName
     *
     * The area element <area> typically points to content consisting of just a portion
     * or area of a file represented by a <file> element in the <fileSec>. In some
     * contexts, however, the <area> element can also point to content represented by
     * an integral file. A single <area> element would appear as the direct child of a
     * <fptr> element when only a portion of a <file>, rather than an integral <file>,
     * manifested the digital content represented by the <fptr>. Multiple <area>
     * elements would appear as the direct children of a <par> element or a <seq>
     * element when multiple files or parts of files manifested the digital content
     * represented by an <fptr> element. When used in the context of a <par> or <seq>
     * element an <area> element can point either to an integral file or to a segment
     * of a file as necessary.
     *
     * @param \App\Xsd\Mets\AreaType $areaElName
     * @return self
     */
    public function setAreaElName(\App\Xsd\Mets\AreaType $areaElName)
    {
        $this->areaElName = $areaElName;
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

