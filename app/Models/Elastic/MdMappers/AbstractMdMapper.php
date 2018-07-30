<?php

namespace App\Models\Elastic\MdMappers;

abstract class AbstractMdMapper
{
    // Inherited mapper classes define which <METS:mdWrap MDTYPE="?"> cases to handle
    // by overriding and returning the desired string in mdTypeToHandle function
    abstract function mdTypeToHandle();

    // Implement mapXmlData to map xmlData into array of si4 fields
    abstract function mapXmlData($xmlData);
}