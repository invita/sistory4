<?php
namespace App\Helpers;

/**
 * Class DcHelpers
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class DcHelpers {

    public static function getDcPresentationParser() {
        return function($inputName, $inputValue) {
            if ($inputValue === null) return "";
            if (is_string($inputValue)) return $inputValue;
            if (is_array($inputValue)) {
                return join("<br/>", array_map(function($e) { return "<span>".$e."</span>"; }, $inputValue));
            }
            return print_r($inputValue, true);
        };
    }

    public static function getDcFirstFileNameParser() {
        return function($inputName, $inputValue) {
            return isset($inputValue[0]["ownerId"]) ? $inputValue[0]["ownerId"] : "";
        };
    }
}