<?php
namespace App\Helpers;

/**
 * Class Si4Util
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class Si4Util {
    public static function getArg($array, $key, $defaultValue = null) {
        if (!is_array($array) || !isset($array[$key])) return $defaultValue;
        return $array[$key];
    }

    public static function pathArg($array, $path, $defaultValue = null) {
        $keys = explode("/", $path);
        foreach ($keys as $key) {
            if (!is_array($array) || !isset($array[$key])) return $defaultValue;
            $array = $array[$key];
        }
        return $array;
    }

}