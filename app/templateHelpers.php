<?php

function first($array) {
    if (is_array($array) && isset($array[0])) {
        return $array[0];
    }
    return "";
}

function si4config($key = null, $default = null) {
    return config("si4sites.".env("SI4_SITE").".".$key, $default);
}

function translateSi4Field($si4FieldName) {
    $fieldDefs = \App\Models\Si4Field::getSi4FieldsArray();
    if (!isset($fieldDefs[$si4FieldName])) return "(undefined field ".$si4FieldName.")";
    $translateKey = $fieldDefs[$si4FieldName]["translate_key"];
    return __('fe.'.$translateKey);
}