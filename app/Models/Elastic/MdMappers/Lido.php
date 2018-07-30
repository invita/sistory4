<?php

namespace App\Models\Elastic\MdMappers;

class Lido extends AbstractMdMapper
{
    function mdTypeToHandle() {
        return "lido";
    }

    function mapXmlData($xmlData) {
        $result = [];

        // TODO: Map Lido to si4

        return $result;
    }
}