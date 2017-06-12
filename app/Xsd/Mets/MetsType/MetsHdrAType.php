<?php

namespace App\Xsd\Mets\MetsType;

/**
 * Class representing MetsHdrAType
 */
class MetsHdrAType
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
     * ADMID (IDREFS/O): Contains the ID attribute values of the <techMD>, <sourceMD>,
     * <rightsMD> and/or <digiprovMD> elements within the <amdSec> of the METS document
     * that contain administrative metadata pertaining to the METS document itself. For
     * more information on using METS IDREFS and IDREF type attributes for internal
     * linking, see Chapter 4 of the METS Primer.
     *
     * @property string $ADMIDAttrName
     */
    private $ADMIDAttrName = null;

    /**
     * CREATEDATE (dateTime/O): Records the date/time the METS document was created.
     *
     * @property \DateTime $CREATEDATEAttrName
     */
    private $CREATEDATEAttrName = null;

    /**
     * LASTMODDATE (dateTime/O): Is used to indicate the date/time the METS document
     * was last modified.
     *
     * @property \DateTime $LASTMODDATEAttrName
     */
    private $LASTMODDATEAttrName = null;

    /**
     * RECORDSTATUS (string/O): Specifies the status of the METS document. It is used
     * for internal processing purposes.
     *
     * @property string $RECORDSTATUSAttrName
     */
    private $RECORDSTATUSAttrName = null;

    /**
     * agent: 
     *  The agent element <agent> provides for various parties and their roles with
     * respect to the METS record to be documented.
     *
     * @property \App\Xsd\Mets\MetsType\MetsHdrAType\AgentAType[] $agentElName
     */
    private $agentElName = array(
        
    );

    /**
     * The alternative record identifier element <altRecordID> allows one to use
     * alternative record identifier values for the digital object represented by the
     * METS document; the primary record identifier is stored in the OBJID attribute in
     * the root <mets> element.
     *
     * @property \App\Xsd\Mets\MetsType\MetsHdrAType\AltRecordIDAType[]
     * $altRecordIDElName
     */
    private $altRecordIDElName = array(
        
    );

    /**
     * The metsDocument identifier element <metsDocumentID> allows a unique identifier
     * to be assigned to the METS document itself. This may be different from the OBJID
     * attribute value in the root <mets> element, which uniquely identifies the entire
     * digital object represented by the METS document.
     *
     * @property \App\Xsd\Mets\MetsType\MetsHdrAType\MetsDocumentIDAType
     * $metsDocumentIDElName
     */
    private $metsDocumentIDElName = null;

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
     * Gets as ADMIDAttrName
     *
     * ADMID (IDREFS/O): Contains the ID attribute values of the <techMD>, <sourceMD>,
     * <rightsMD> and/or <digiprovMD> elements within the <amdSec> of the METS document
     * that contain administrative metadata pertaining to the METS document itself. For
     * more information on using METS IDREFS and IDREF type attributes for internal
     * linking, see Chapter 4 of the METS Primer.
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
     * that contain administrative metadata pertaining to the METS document itself. For
     * more information on using METS IDREFS and IDREF type attributes for internal
     * linking, see Chapter 4 of the METS Primer.
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
     * Gets as CREATEDATEAttrName
     *
     * CREATEDATE (dateTime/O): Records the date/time the METS document was created.
     *
     * @return \DateTime
     */
    public function getCREATEDATEAttrName()
    {
        return $this->CREATEDATEAttrName;
    }

    /**
     * Sets a new CREATEDATEAttrName
     *
     * CREATEDATE (dateTime/O): Records the date/time the METS document was created.
     *
     * @param \DateTime $CREATEDATEAttrName
     * @return self
     */
    public function setCREATEDATEAttrName(\DateTime $CREATEDATEAttrName)
    {
        $this->CREATEDATEAttrName = $CREATEDATEAttrName;
        return $this;
    }

    /**
     * Gets as LASTMODDATEAttrName
     *
     * LASTMODDATE (dateTime/O): Is used to indicate the date/time the METS document
     * was last modified.
     *
     * @return \DateTime
     */
    public function getLASTMODDATEAttrName()
    {
        return $this->LASTMODDATEAttrName;
    }

    /**
     * Sets a new LASTMODDATEAttrName
     *
     * LASTMODDATE (dateTime/O): Is used to indicate the date/time the METS document
     * was last modified.
     *
     * @param \DateTime $LASTMODDATEAttrName
     * @return self
     */
    public function setLASTMODDATEAttrName(\DateTime $LASTMODDATEAttrName)
    {
        $this->LASTMODDATEAttrName = $LASTMODDATEAttrName;
        return $this;
    }

    /**
     * Gets as RECORDSTATUSAttrName
     *
     * RECORDSTATUS (string/O): Specifies the status of the METS document. It is used
     * for internal processing purposes.
     *
     * @return string
     */
    public function getRECORDSTATUSAttrName()
    {
        return $this->RECORDSTATUSAttrName;
    }

    /**
     * Sets a new RECORDSTATUSAttrName
     *
     * RECORDSTATUS (string/O): Specifies the status of the METS document. It is used
     * for internal processing purposes.
     *
     * @param string $RECORDSTATUSAttrName
     * @return self
     */
    public function setRECORDSTATUSAttrName($RECORDSTATUSAttrName)
    {
        $this->RECORDSTATUSAttrName = $RECORDSTATUSAttrName;
        return $this;
    }

    /**
     * Adds as agentElName
     *
     * agent: 
     *  The agent element <agent> provides for various parties and their roles with
     * respect to the METS record to be documented.
     *
     * @return self
     * @param \App\Xsd\Mets\MetsType\MetsHdrAType\AgentAType $agentElName
     */
    public function addToAgentElName(\App\Xsd\Mets\MetsType\MetsHdrAType\AgentAType $agentElName)
    {
        $this->agentElName[] = $agentElName;
        return $this;
    }

    /**
     * isset agentElName
     *
     * agent: 
     *  The agent element <agent> provides for various parties and their roles with
     * respect to the METS record to be documented.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAgentElName($index)
    {
        return isset($this->agentElName[$index]);
    }

    /**
     * unset agentElName
     *
     * agent: 
     *  The agent element <agent> provides for various parties and their roles with
     * respect to the METS record to be documented.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAgentElName($index)
    {
        unset($this->agentElName[$index]);
    }

    /**
     * Gets as agentElName
     *
     * agent: 
     *  The agent element <agent> provides for various parties and their roles with
     * respect to the METS record to be documented.
     *
     * @return \App\Xsd\Mets\MetsType\MetsHdrAType\AgentAType[]
     */
    public function getAgentElName()
    {
        return $this->agentElName;
    }

    /**
     * Sets a new agentElName
     *
     * agent: 
     *  The agent element <agent> provides for various parties and their roles with
     * respect to the METS record to be documented.
     *
     * @param \App\Xsd\Mets\MetsType\MetsHdrAType\AgentAType[] $agentElName
     * @return self
     */
    public function setAgentElName(array $agentElName)
    {
        $this->agentElName = $agentElName;
        return $this;
    }

    /**
     * Adds as altRecordIDElName
     *
     * The alternative record identifier element <altRecordID> allows one to use
     * alternative record identifier values for the digital object represented by the
     * METS document; the primary record identifier is stored in the OBJID attribute in
     * the root <mets> element.
     *
     * @return self
     * @param \App\Xsd\Mets\MetsType\MetsHdrAType\AltRecordIDAType $altRecordIDElName
     */
    public function addToAltRecordIDElName(\App\Xsd\Mets\MetsType\MetsHdrAType\AltRecordIDAType $altRecordIDElName)
    {
        $this->altRecordIDElName[] = $altRecordIDElName;
        return $this;
    }

    /**
     * isset altRecordIDElName
     *
     * The alternative record identifier element <altRecordID> allows one to use
     * alternative record identifier values for the digital object represented by the
     * METS document; the primary record identifier is stored in the OBJID attribute in
     * the root <mets> element.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAltRecordIDElName($index)
    {
        return isset($this->altRecordIDElName[$index]);
    }

    /**
     * unset altRecordIDElName
     *
     * The alternative record identifier element <altRecordID> allows one to use
     * alternative record identifier values for the digital object represented by the
     * METS document; the primary record identifier is stored in the OBJID attribute in
     * the root <mets> element.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAltRecordIDElName($index)
    {
        unset($this->altRecordIDElName[$index]);
    }

    /**
     * Gets as altRecordIDElName
     *
     * The alternative record identifier element <altRecordID> allows one to use
     * alternative record identifier values for the digital object represented by the
     * METS document; the primary record identifier is stored in the OBJID attribute in
     * the root <mets> element.
     *
     * @return \App\Xsd\Mets\MetsType\MetsHdrAType\AltRecordIDAType[]
     */
    public function getAltRecordIDElName()
    {
        return $this->altRecordIDElName;
    }

    /**
     * Sets a new altRecordIDElName
     *
     * The alternative record identifier element <altRecordID> allows one to use
     * alternative record identifier values for the digital object represented by the
     * METS document; the primary record identifier is stored in the OBJID attribute in
     * the root <mets> element.
     *
     * @param \App\Xsd\Mets\MetsType\MetsHdrAType\AltRecordIDAType[] $altRecordIDElName
     * @return self
     */
    public function setAltRecordIDElName(array $altRecordIDElName)
    {
        $this->altRecordIDElName = $altRecordIDElName;
        return $this;
    }

    /**
     * Gets as metsDocumentIDElName
     *
     * The metsDocument identifier element <metsDocumentID> allows a unique identifier
     * to be assigned to the METS document itself. This may be different from the OBJID
     * attribute value in the root <mets> element, which uniquely identifies the entire
     * digital object represented by the METS document.
     *
     * @return \App\Xsd\Mets\MetsType\MetsHdrAType\MetsDocumentIDAType
     */
    public function getMetsDocumentIDElName()
    {
        return $this->metsDocumentIDElName;
    }

    /**
     * Sets a new metsDocumentIDElName
     *
     * The metsDocument identifier element <metsDocumentID> allows a unique identifier
     * to be assigned to the METS document itself. This may be different from the OBJID
     * attribute value in the root <mets> element, which uniquely identifies the entire
     * digital object represented by the METS document.
     *
     * @param \App\Xsd\Mets\MetsType\MetsHdrAType\MetsDocumentIDAType
     * $metsDocumentIDElName
     * @return self
     */
    public function setMetsDocumentIDElName(\App\Xsd\Mets\MetsType\MetsHdrAType\MetsDocumentIDAType $metsDocumentIDElName)
    {
        $this->metsDocumentIDElName = $metsDocumentIDElName;
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

