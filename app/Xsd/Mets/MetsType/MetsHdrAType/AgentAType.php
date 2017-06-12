<?php

namespace App\Xsd\Mets\MetsType\MetsHdrAType;

/**
 * Class representing AgentAType
 */
class AgentAType
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
     * ROLE (string/R): Specifies the function of the agent with respect to the METS
     * record. The allowed values are:
     * CREATOR: The person(s) or institution(s) responsible for the METS document.
     * EDITOR: The person(s) or institution(s) that prepares the metadata for encoding.
     * ARCHIVIST: The person(s) or institution(s) responsible for the
     * document/collection.
     * PRESERVATION: The person(s) or institution(s) responsible for preservation
     * functions.
     * DISSEMINATOR: The person(s) or institution(s) responsible for dissemination
     * functions.
     * CUSTODIAN: The person(s) or institution(s) charged with the oversight of a
     * document/collection.
     * IPOWNER: Intellectual Property Owner: The person(s) or institution holding
     * copyright, trade or service marks or other intellectual property rights for the
     * object.
     * OTHER: Use OTHER if none of the preceding values pertains and clarify the type
     * and location specifier being used in the OTHERROLE attribute (see below).
     *
     * @property string $ROLEAttrName
     */
    private $ROLEAttrName = null;

    /**
     * OTHERROLE (string/O): Denotes a role not contained in the allowed values set if
     * OTHER is indicated in the ROLE attribute.
     *
     * @property string $OTHERROLEAttrName
     */
    private $OTHERROLEAttrName = null;

    /**
     * TYPE (string/O): is used to specify the type of AGENT. It must be one of the
     * following values:
     * INDIVIDUAL: Use if an individual has served as the agent.
     * ORGANIZATION: Use if an institution, corporate body, association, non-profit
     * enterprise, government, religious body, etc. has served as the agent.
     * OTHER: Use OTHER if none of the preceding values pertain and clarify the type of
     * agent specifier being used in the OTHERTYPE attribute
     *
     * @property string $TYPEAttrName
     */
    private $TYPEAttrName = null;

    /**
     * OTHERTYPE (string/O): Specifies the type of agent when the value OTHER is
     * indicated in the TYPE attribute.
     *
     * @property string $OTHERTYPEAttrName
     */
    private $OTHERTYPEAttrName = null;

    /**
     * The element <name> can be used to record the full name of the document agent.
     *
     * @property string $nameElName
     */
    private $nameElName = null;

    /**
     * The <note> element can be used to record any additional information regarding
     * the agent's activities with respect to the METS document.
     *
     * @property string[] $noteElName
     */
    private $noteElName = array(
        
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
     * Gets as ROLEAttrName
     *
     * ROLE (string/R): Specifies the function of the agent with respect to the METS
     * record. The allowed values are:
     * CREATOR: The person(s) or institution(s) responsible for the METS document.
     * EDITOR: The person(s) or institution(s) that prepares the metadata for encoding.
     * ARCHIVIST: The person(s) or institution(s) responsible for the
     * document/collection.
     * PRESERVATION: The person(s) or institution(s) responsible for preservation
     * functions.
     * DISSEMINATOR: The person(s) or institution(s) responsible for dissemination
     * functions.
     * CUSTODIAN: The person(s) or institution(s) charged with the oversight of a
     * document/collection.
     * IPOWNER: Intellectual Property Owner: The person(s) or institution holding
     * copyright, trade or service marks or other intellectual property rights for the
     * object.
     * OTHER: Use OTHER if none of the preceding values pertains and clarify the type
     * and location specifier being used in the OTHERROLE attribute (see below).
     *
     * @return string
     */
    public function getROLEAttrName()
    {
        return $this->ROLEAttrName;
    }

    /**
     * Sets a new ROLEAttrName
     *
     * ROLE (string/R): Specifies the function of the agent with respect to the METS
     * record. The allowed values are:
     * CREATOR: The person(s) or institution(s) responsible for the METS document.
     * EDITOR: The person(s) or institution(s) that prepares the metadata for encoding.
     * ARCHIVIST: The person(s) or institution(s) responsible for the
     * document/collection.
     * PRESERVATION: The person(s) or institution(s) responsible for preservation
     * functions.
     * DISSEMINATOR: The person(s) or institution(s) responsible for dissemination
     * functions.
     * CUSTODIAN: The person(s) or institution(s) charged with the oversight of a
     * document/collection.
     * IPOWNER: Intellectual Property Owner: The person(s) or institution holding
     * copyright, trade or service marks or other intellectual property rights for the
     * object.
     * OTHER: Use OTHER if none of the preceding values pertains and clarify the type
     * and location specifier being used in the OTHERROLE attribute (see below).
     *
     * @param string $ROLEAttrName
     * @return self
     */
    public function setROLEAttrName($ROLEAttrName)
    {
        $this->ROLEAttrName = $ROLEAttrName;
        return $this;
    }

    /**
     * Gets as OTHERROLEAttrName
     *
     * OTHERROLE (string/O): Denotes a role not contained in the allowed values set if
     * OTHER is indicated in the ROLE attribute.
     *
     * @return string
     */
    public function getOTHERROLEAttrName()
    {
        return $this->OTHERROLEAttrName;
    }

    /**
     * Sets a new OTHERROLEAttrName
     *
     * OTHERROLE (string/O): Denotes a role not contained in the allowed values set if
     * OTHER is indicated in the ROLE attribute.
     *
     * @param string $OTHERROLEAttrName
     * @return self
     */
    public function setOTHERROLEAttrName($OTHERROLEAttrName)
    {
        $this->OTHERROLEAttrName = $OTHERROLEAttrName;
        return $this;
    }

    /**
     * Gets as TYPEAttrName
     *
     * TYPE (string/O): is used to specify the type of AGENT. It must be one of the
     * following values:
     * INDIVIDUAL: Use if an individual has served as the agent.
     * ORGANIZATION: Use if an institution, corporate body, association, non-profit
     * enterprise, government, religious body, etc. has served as the agent.
     * OTHER: Use OTHER if none of the preceding values pertain and clarify the type of
     * agent specifier being used in the OTHERTYPE attribute
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
     * TYPE (string/O): is used to specify the type of AGENT. It must be one of the
     * following values:
     * INDIVIDUAL: Use if an individual has served as the agent.
     * ORGANIZATION: Use if an institution, corporate body, association, non-profit
     * enterprise, government, religious body, etc. has served as the agent.
     * OTHER: Use OTHER if none of the preceding values pertain and clarify the type of
     * agent specifier being used in the OTHERTYPE attribute
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
     * Gets as OTHERTYPEAttrName
     *
     * OTHERTYPE (string/O): Specifies the type of agent when the value OTHER is
     * indicated in the TYPE attribute.
     *
     * @return string
     */
    public function getOTHERTYPEAttrName()
    {
        return $this->OTHERTYPEAttrName;
    }

    /**
     * Sets a new OTHERTYPEAttrName
     *
     * OTHERTYPE (string/O): Specifies the type of agent when the value OTHER is
     * indicated in the TYPE attribute.
     *
     * @param string $OTHERTYPEAttrName
     * @return self
     */
    public function setOTHERTYPEAttrName($OTHERTYPEAttrName)
    {
        $this->OTHERTYPEAttrName = $OTHERTYPEAttrName;
        return $this;
    }

    /**
     * Gets as nameElName
     *
     * The element <name> can be used to record the full name of the document agent.
     *
     * @return string
     */
    public function getNameElName()
    {
        return $this->nameElName;
    }

    /**
     * Sets a new nameElName
     *
     * The element <name> can be used to record the full name of the document agent.
     *
     * @param string $nameElName
     * @return self
     */
    public function setNameElName($nameElName)
    {
        $this->nameElName = $nameElName;
        return $this;
    }

    /**
     * Adds as noteElName
     *
     * The <note> element can be used to record any additional information regarding
     * the agent's activities with respect to the METS document.
     *
     * @return self
     * @param string $noteElName
     */
    public function addToNoteElName($noteElName)
    {
        $this->noteElName[] = $noteElName;
        return $this;
    }

    /**
     * isset noteElName
     *
     * The <note> element can be used to record any additional information regarding
     * the agent's activities with respect to the METS document.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetNoteElName($index)
    {
        return isset($this->noteElName[$index]);
    }

    /**
     * unset noteElName
     *
     * The <note> element can be used to record any additional information regarding
     * the agent's activities with respect to the METS document.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetNoteElName($index)
    {
        unset($this->noteElName[$index]);
    }

    /**
     * Gets as noteElName
     *
     * The <note> element can be used to record any additional information regarding
     * the agent's activities with respect to the METS document.
     *
     * @return string[]
     */
    public function getNoteElName()
    {
        return $this->noteElName;
    }

    /**
     * Sets a new noteElName
     *
     * The <note> element can be used to record any additional information regarding
     * the agent's activities with respect to the METS document.
     *
     * @param string[] $noteElName
     * @return self
     */
    public function setNoteElName(array $noteElName)
    {
        $this->noteElName = $noteElName;
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

