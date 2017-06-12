<?php

namespace App\Xsd\Mets;

/**
 * Class representing FileGrpType
 *
 * fileGrpType: Complex Type for File Groups
 *  The file group is used to cluster all of the digital files composing a digital
 * library object in a hierarchical arrangement (fileGrp is recursively defined to
 * enable the creation of the hierarchy). Any file group may contain zero or more
 * file elements. File elements in turn can contain one or more FLocat elements (a
 * pointer to a file containing content for this object) and/or a FContent element
 * (the contents of the file, in either XML or Base64 encoding).
 * XSD Type: fileGrpType
 */
class FileGrpType
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
     * VERSDATE (dateTime/O): An optional dateTime attribute specifying the date this
     * version/fileGrp of the digital object was created.
     *
     * @property \DateTime $VERSDATEAttrName
     */
    private $VERSDATEAttrName = null;

    /**
     * ADMID (IDREF/O): Contains the ID attribute values of the <techMD>, <sourceMD>,
     * <rightsMD> and/or <digiprovMD> elements within the <amdSec> of the METS document
     * applicable to all of the files in a particular file group. For more information
     * on using METS IDREFS and IDREF type attributes for internal linking, see Chapter
     * 4 of the METS Primer.
     *
     * @property string $ADMIDAttrName
     */
    private $ADMIDAttrName = null;

    /**
     * USE (string/O): A tagging attribute to indicate the intended use of files within
     * this file group (e.g., master, reference, thumbnails for image files). A USE
     * attribute can be expressed at the<fileGrp> level, the <file> level, the <FLocat>
     * level and/or the <FContent> level. A USE attribute value at the <fileGrp> level
     * should pertain to all of the files in the <fileGrp>. A USE attribute at the
     * <file> level should pertain to all copies of the file as represented by
     * subsidiary <FLocat> and/or <FContent> elements. A USE attribute at the <FLocat>
     * or <FContent> level pertains to the particular copy of the file that is either
     * referenced (<FLocat>) or wrapped (<FContent>).
     *
     * @property string $USEAttrName
     */
    private $USEAttrName = null;

    /**
     * @property \App\Xsd\Mets\FileGrpType[] $fileGrpElName
     */
    private $fileGrpElName = array(
        
    );

    /**
     * The file element <file> provides access to the content files for the digital
     * object being described by the METS document. A <file> element may contain one or
     * more <FLocat> elements which provide pointers to a content file and/or a
     * <FContent> element which wraps an encoded version of the file. Embedding files
     * using <FContent> can be a valuable feature for exchanging digital objects
     * between repositories or for archiving versions of digital objects for off-site
     * storage. All <FLocat> and <FContent> elements should identify and/or contain
     * identical copies of a single file. The <file> element is recursive, thus
     * allowing sub-files or component files of a larger file to be listed in the
     * inventory. Alternatively, by using the <stream> element, a smaller component of
     * a file or of a related file can be placed within a <file> element. Finally, by
     * using the <transformFile> element, it is possible to include within a <file>
     * element a different version of a file that has undergone a transformation for
     * some reason, such as format migration.
     *
     * @property \App\Xsd\Mets\FileType[] $fileElName
     */
    private $fileElName = array(
        
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
     * Gets as VERSDATEAttrName
     *
     * VERSDATE (dateTime/O): An optional dateTime attribute specifying the date this
     * version/fileGrp of the digital object was created.
     *
     * @return \DateTime
     */
    public function getVERSDATEAttrName()
    {
        return $this->VERSDATEAttrName;
    }

    /**
     * Sets a new VERSDATEAttrName
     *
     * VERSDATE (dateTime/O): An optional dateTime attribute specifying the date this
     * version/fileGrp of the digital object was created.
     *
     * @param \DateTime $VERSDATEAttrName
     * @return self
     */
    public function setVERSDATEAttrName(\DateTime $VERSDATEAttrName)
    {
        $this->VERSDATEAttrName = $VERSDATEAttrName;
        return $this;
    }

    /**
     * Gets as ADMIDAttrName
     *
     * ADMID (IDREF/O): Contains the ID attribute values of the <techMD>, <sourceMD>,
     * <rightsMD> and/or <digiprovMD> elements within the <amdSec> of the METS document
     * applicable to all of the files in a particular file group. For more information
     * on using METS IDREFS and IDREF type attributes for internal linking, see Chapter
     * 4 of the METS Primer.
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
     * ADMID (IDREF/O): Contains the ID attribute values of the <techMD>, <sourceMD>,
     * <rightsMD> and/or <digiprovMD> elements within the <amdSec> of the METS document
     * applicable to all of the files in a particular file group. For more information
     * on using METS IDREFS and IDREF type attributes for internal linking, see Chapter
     * 4 of the METS Primer.
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
     * Gets as USEAttrName
     *
     * USE (string/O): A tagging attribute to indicate the intended use of files within
     * this file group (e.g., master, reference, thumbnails for image files). A USE
     * attribute can be expressed at the<fileGrp> level, the <file> level, the <FLocat>
     * level and/or the <FContent> level. A USE attribute value at the <fileGrp> level
     * should pertain to all of the files in the <fileGrp>. A USE attribute at the
     * <file> level should pertain to all copies of the file as represented by
     * subsidiary <FLocat> and/or <FContent> elements. A USE attribute at the <FLocat>
     * or <FContent> level pertains to the particular copy of the file that is either
     * referenced (<FLocat>) or wrapped (<FContent>).
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
     * USE (string/O): A tagging attribute to indicate the intended use of files within
     * this file group (e.g., master, reference, thumbnails for image files). A USE
     * attribute can be expressed at the<fileGrp> level, the <file> level, the <FLocat>
     * level and/or the <FContent> level. A USE attribute value at the <fileGrp> level
     * should pertain to all of the files in the <fileGrp>. A USE attribute at the
     * <file> level should pertain to all copies of the file as represented by
     * subsidiary <FLocat> and/or <FContent> elements. A USE attribute at the <FLocat>
     * or <FContent> level pertains to the particular copy of the file that is either
     * referenced (<FLocat>) or wrapped (<FContent>).
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
     * Adds as fileGrpElName
     *
     * @return self
     * @param \App\Xsd\Mets\FileGrpType $fileGrpElName
     */
    public function addToFileGrpElName(\App\Xsd\Mets\FileGrpType $fileGrpElName)
    {
        $this->fileGrpElName[] = $fileGrpElName;
        return $this;
    }

    /**
     * isset fileGrpElName
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
     * @return \App\Xsd\Mets\FileGrpType[]
     */
    public function getFileGrpElName()
    {
        return $this->fileGrpElName;
    }

    /**
     * Sets a new fileGrpElName
     *
     * @param \App\Xsd\Mets\FileGrpType[] $fileGrpElName
     * @return self
     */
    public function setFileGrpElName(array $fileGrpElName)
    {
        $this->fileGrpElName = $fileGrpElName;
        return $this;
    }

    /**
     * Adds as fileElName
     *
     * The file element <file> provides access to the content files for the digital
     * object being described by the METS document. A <file> element may contain one or
     * more <FLocat> elements which provide pointers to a content file and/or a
     * <FContent> element which wraps an encoded version of the file. Embedding files
     * using <FContent> can be a valuable feature for exchanging digital objects
     * between repositories or for archiving versions of digital objects for off-site
     * storage. All <FLocat> and <FContent> elements should identify and/or contain
     * identical copies of a single file. The <file> element is recursive, thus
     * allowing sub-files or component files of a larger file to be listed in the
     * inventory. Alternatively, by using the <stream> element, a smaller component of
     * a file or of a related file can be placed within a <file> element. Finally, by
     * using the <transformFile> element, it is possible to include within a <file>
     * element a different version of a file that has undergone a transformation for
     * some reason, such as format migration.
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
     * The file element <file> provides access to the content files for the digital
     * object being described by the METS document. A <file> element may contain one or
     * more <FLocat> elements which provide pointers to a content file and/or a
     * <FContent> element which wraps an encoded version of the file. Embedding files
     * using <FContent> can be a valuable feature for exchanging digital objects
     * between repositories or for archiving versions of digital objects for off-site
     * storage. All <FLocat> and <FContent> elements should identify and/or contain
     * identical copies of a single file. The <file> element is recursive, thus
     * allowing sub-files or component files of a larger file to be listed in the
     * inventory. Alternatively, by using the <stream> element, a smaller component of
     * a file or of a related file can be placed within a <file> element. Finally, by
     * using the <transformFile> element, it is possible to include within a <file>
     * element a different version of a file that has undergone a transformation for
     * some reason, such as format migration.
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
     * The file element <file> provides access to the content files for the digital
     * object being described by the METS document. A <file> element may contain one or
     * more <FLocat> elements which provide pointers to a content file and/or a
     * <FContent> element which wraps an encoded version of the file. Embedding files
     * using <FContent> can be a valuable feature for exchanging digital objects
     * between repositories or for archiving versions of digital objects for off-site
     * storage. All <FLocat> and <FContent> elements should identify and/or contain
     * identical copies of a single file. The <file> element is recursive, thus
     * allowing sub-files or component files of a larger file to be listed in the
     * inventory. Alternatively, by using the <stream> element, a smaller component of
     * a file or of a related file can be placed within a <file> element. Finally, by
     * using the <transformFile> element, it is possible to include within a <file>
     * element a different version of a file that has undergone a transformation for
     * some reason, such as format migration.
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
     * The file element <file> provides access to the content files for the digital
     * object being described by the METS document. A <file> element may contain one or
     * more <FLocat> elements which provide pointers to a content file and/or a
     * <FContent> element which wraps an encoded version of the file. Embedding files
     * using <FContent> can be a valuable feature for exchanging digital objects
     * between repositories or for archiving versions of digital objects for off-site
     * storage. All <FLocat> and <FContent> elements should identify and/or contain
     * identical copies of a single file. The <file> element is recursive, thus
     * allowing sub-files or component files of a larger file to be listed in the
     * inventory. Alternatively, by using the <stream> element, a smaller component of
     * a file or of a related file can be placed within a <file> element. Finally, by
     * using the <transformFile> element, it is possible to include within a <file>
     * element a different version of a file that has undergone a transformation for
     * some reason, such as format migration.
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
     * The file element <file> provides access to the content files for the digital
     * object being described by the METS document. A <file> element may contain one or
     * more <FLocat> elements which provide pointers to a content file and/or a
     * <FContent> element which wraps an encoded version of the file. Embedding files
     * using <FContent> can be a valuable feature for exchanging digital objects
     * between repositories or for archiving versions of digital objects for off-site
     * storage. All <FLocat> and <FContent> elements should identify and/or contain
     * identical copies of a single file. The <file> element is recursive, thus
     * allowing sub-files or component files of a larger file to be listed in the
     * inventory. Alternatively, by using the <stream> element, a smaller component of
     * a file or of a related file can be placed within a <file> element. Finally, by
     * using the <transformFile> element, it is possible to include within a <file>
     * element a different version of a file that has undergone a transformation for
     * some reason, such as format migration.
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

