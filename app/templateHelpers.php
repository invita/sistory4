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