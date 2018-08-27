<?php

function first($array) {
    if (is_array($array) && isset($array[0])) {
        return $array[0];
    }
    return "";
}
