<?php

namespace App\Xsd\Mets;

/**
 * Class representing SeqType
 *
 * seqType: Complex Type for Sequences of Files
 *  The seq element should be used to link a div to a set of content files when
 * those files should be played/displayed sequentially to deliver content to a
 * user. Individual <area> subelements within the seq element provide the links to
 * the files or portions thereof.
 * XSD Type: seqType
 */
class SeqType
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
     * @property \App\Xsd\Mets\AreaType[] $areaElName
     */
    private $areaElName = array(
        
    );

    /**
     * @property \App\Xsd\Mets\ParType[] $parElName
     */
    private $parElName = array(
        
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
     * Adds as areaElName
     *
     * @return self
     * @param \App\Xsd\Mets\AreaType $areaElName
     */
    public function addToAreaElName(\App\Xsd\Mets\AreaType $areaElName)
    {
        $this->areaElName[] = $areaElName;
        return $this;
    }

    /**
     * isset areaElName
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAreaElName($index)
    {
        return isset($this->areaElName[$index]);
    }

    /**
     * unset areaElName
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAreaElName($index)
    {
        unset($this->areaElName[$index]);
    }

    /**
     * Gets as areaElName
     *
     * @return \App\Xsd\Mets\AreaType[]
     */
    public function getAreaElName()
    {
        return $this->areaElName;
    }

    /**
     * Sets a new areaElName
     *
     * @param \App\Xsd\Mets\AreaType[] $areaElName
     * @return self
     */
    public function setAreaElName(array $areaElName)
    {
        $this->areaElName = $areaElName;
        return $this;
    }

    /**
     * Adds as parElName
     *
     * @return self
     * @param \App\Xsd\Mets\ParType $parElName
     */
    public function addToParElName(\App\Xsd\Mets\ParType $parElName)
    {
        $this->parElName[] = $parElName;
        return $this;
    }

    /**
     * isset parElName
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetParElName($index)
    {
        return isset($this->parElName[$index]);
    }

    /**
     * unset parElName
     *
     * @param scalar $index
     * @return void
     */
    public function unsetParElName($index)
    {
        unset($this->parElName[$index]);
    }

    /**
     * Gets as parElName
     *
     * @return \App\Xsd\Mets\ParType[]
     */
    public function getParElName()
    {
        return $this->parElName;
    }

    /**
     * Sets a new parElName
     *
     * @param \App\Xsd\Mets\ParType[] $parElName
     * @return self
     */
    public function setParElName(array $parElName)
    {
        $this->parElName = $parElName;
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

