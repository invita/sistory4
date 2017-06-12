<?php

namespace App\Xsd\Mets\MetsType;

/**
 * Class representing FileSecAType
 */
class FileSecAType
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
     * A sequence of file group elements <fileGrp> can be used group the digital files
     * comprising the content of a METS object either into a flat arrangement or,
     * because each file group element can itself contain one or more file group
     * elements, into a nested (hierarchical) arrangement. In the case where the
     * content files are images of different formats and resolutions, for example, one
     * could group the image content files by format and create a separate <fileGrp>
     * for each image format/resolution such as:
     * -- one <fileGrp> for the thumbnails of the images
     * -- one <fileGrp> for the higher resolution JPEGs of the image 
     * -- one <fileGrp> for the master archival TIFFs of the images 
     * For a text resource with a variety of content file types one might group the
     * content files at the highest level by type, and then use the <fileGrp>
     * element’s nesting capabilities to subdivide a <fileGrp> by format within the
     * type, such as:
     * -- one <fileGrp> for all of the page images with nested <fileGrp> elements for
     * each image format/resolution (tiff, jpeg, gif)
     * -- one <fileGrp> for a PDF version of all the pages of the document 
     * -- one <fileGrp> for a TEI encoded XML version of the entire document or each of
     * its pages.
     * A <fileGrp> may contain zero or more <fileGrp> elements and or <file> elements.
     *
     * @property \App\Xsd\Mets\MetsType\FileSecAType\FileGrpAType[] $fileGrpElName
     */
    private $fileGrpElName = array(
        
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
     * Adds as fileGrpElName
     *
     * A sequence of file group elements <fileGrp> can be used group the digital files
     * comprising the content of a METS object either into a flat arrangement or,
     * because each file group element can itself contain one or more file group
     * elements, into a nested (hierarchical) arrangement. In the case where the
     * content files are images of different formats and resolutions, for example, one
     * could group the image content files by format and create a separate <fileGrp>
     * for each image format/resolution such as:
     * -- one <fileGrp> for the thumbnails of the images
     * -- one <fileGrp> for the higher resolution JPEGs of the image 
     * -- one <fileGrp> for the master archival TIFFs of the images 
     * For a text resource with a variety of content file types one might group the
     * content files at the highest level by type, and then use the <fileGrp>
     * element’s nesting capabilities to subdivide a <fileGrp> by format within the
     * type, such as:
     * -- one <fileGrp> for all of the page images with nested <fileGrp> elements for
     * each image format/resolution (tiff, jpeg, gif)
     * -- one <fileGrp> for a PDF version of all the pages of the document 
     * -- one <fileGrp> for a TEI encoded XML version of the entire document or each of
     * its pages.
     * A <fileGrp> may contain zero or more <fileGrp> elements and or <file> elements.
     *
     * @return self
     * @param \App\Xsd\Mets\MetsType\FileSecAType\FileGrpAType $fileGrpElName
     */
    public function addToFileGrpElName(\App\Xsd\Mets\MetsType\FileSecAType\FileGrpAType $fileGrpElName)
    {
        $this->fileGrpElName[] = $fileGrpElName;
        return $this;
    }

    /**
     * isset fileGrpElName
     *
     * A sequence of file group elements <fileGrp> can be used group the digital files
     * comprising the content of a METS object either into a flat arrangement or,
     * because each file group element can itself contain one or more file group
     * elements, into a nested (hierarchical) arrangement. In the case where the
     * content files are images of different formats and resolutions, for example, one
     * could group the image content files by format and create a separate <fileGrp>
     * for each image format/resolution such as:
     * -- one <fileGrp> for the thumbnails of the images
     * -- one <fileGrp> for the higher resolution JPEGs of the image 
     * -- one <fileGrp> for the master archival TIFFs of the images 
     * For a text resource with a variety of content file types one might group the
     * content files at the highest level by type, and then use the <fileGrp>
     * element’s nesting capabilities to subdivide a <fileGrp> by format within the
     * type, such as:
     * -- one <fileGrp> for all of the page images with nested <fileGrp> elements for
     * each image format/resolution (tiff, jpeg, gif)
     * -- one <fileGrp> for a PDF version of all the pages of the document 
     * -- one <fileGrp> for a TEI encoded XML version of the entire document or each of
     * its pages.
     * A <fileGrp> may contain zero or more <fileGrp> elements and or <file> elements.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFileGrpElName($index)
    {
        return isset($this->fileGrpElName[$index]);
    }

    /**
     * unset fileGrpElName
     *
     * A sequence of file group elements <fileGrp> can be used group the digital files
     * comprising the content of a METS object either into a flat arrangement or,
     * because each file group element can itself contain one or more file group
     * elements, into a nested (hierarchical) arrangement. In the case where the
     * content files are images of different formats and resolutions, for example, one
     * could group the image content files by format and create a separate <fileGrp>
     * for each image format/resolution such as:
     * -- one <fileGrp> for the thumbnails of the images
     * -- one <fileGrp> for the higher resolution JPEGs of the image 
     * -- one <fileGrp> for the master archival TIFFs of the images 
     * For a text resource with a variety of content file types one might group the
     * content files at the highest level by type, and then use the <fileGrp>
     * element’s nesting capabilities to subdivide a <fileGrp> by format within the
     * type, such as:
     * -- one <fileGrp> for all of the page images with nested <fileGrp> elements for
     * each image format/resolution (tiff, jpeg, gif)
     * -- one <fileGrp> for a PDF version of all the pages of the document 
     * -- one <fileGrp> for a TEI encoded XML version of the entire document or each of
     * its pages.
     * A <fileGrp> may contain zero or more <fileGrp> elements and or <file> elements.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFileGrpElName($index)
    {
        unset($this->fileGrpElName[$index]);
    }

    /**
     * Gets as fileGrpElName
     *
     * A sequence of file group elements <fileGrp> can be used group the digital files
     * comprising the content of a METS object either into a flat arrangement or,
     * because each file group element can itself contain one or more file group
     * elements, into a nested (hierarchical) arrangement. In the case where the
     * content files are images of different formats and resolutions, for example, one
     * could group the image content files by format and create a separate <fileGrp>
     * for each image format/resolution such as:
     * -- one <fileGrp> for the thumbnails of the images
     * -- one <fileGrp> for the higher resolution JPEGs of the image 
     * -- one <fileGrp> for the master archival TIFFs of the images 
     * For a text resource with a variety of content file types one might group the
     * content files at the highest level by type, and then use the <fileGrp>
     * element’s nesting capabilities to subdivide a <fileGrp> by format within the
     * type, such as:
     * -- one <fileGrp> for all of the page images with nested <fileGrp> elements for
     * each image format/resolution (tiff, jpeg, gif)
     * -- one <fileGrp> for a PDF version of all the pages of the document 
     * -- one <fileGrp> for a TEI encoded XML version of the entire document or each of
     * its pages.
     * A <fileGrp> may contain zero or more <fileGrp> elements and or <file> elements.
     *
     * @return \App\Xsd\Mets\MetsType\FileSecAType\FileGrpAType[]
     */
    public function getFileGrpElName()
    {
        return $this->fileGrpElName;
    }

    /**
     * Sets a new fileGrpElName
     *
     * A sequence of file group elements <fileGrp> can be used group the digital files
     * comprising the content of a METS object either into a flat arrangement or,
     * because each file group element can itself contain one or more file group
     * elements, into a nested (hierarchical) arrangement. In the case where the
     * content files are images of different formats and resolutions, for example, one
     * could group the image content files by format and create a separate <fileGrp>
     * for each image format/resolution such as:
     * -- one <fileGrp> for the thumbnails of the images
     * -- one <fileGrp> for the higher resolution JPEGs of the image 
     * -- one <fileGrp> for the master archival TIFFs of the images 
     * For a text resource with a variety of content file types one might group the
     * content files at the highest level by type, and then use the <fileGrp>
     * element’s nesting capabilities to subdivide a <fileGrp> by format within the
     * type, such as:
     * -- one <fileGrp> for all of the page images with nested <fileGrp> elements for
     * each image format/resolution (tiff, jpeg, gif)
     * -- one <fileGrp> for a PDF version of all the pages of the document 
     * -- one <fileGrp> for a TEI encoded XML version of the entire document or each of
     * its pages.
     * A <fileGrp> may contain zero or more <fileGrp> elements and or <file> elements.
     *
     * @param \App\Xsd\Mets\MetsType\FileSecAType\FileGrpAType[] $fileGrpElName
     * @return self
     */
    public function setFileGrpElName(array $fileGrpElName)
    {
        $this->fileGrpElName = $fileGrpElName;
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

