<?php
namespace App\Helpers;
use App\Models\Entity;

/**
 * Class Si4Util
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class Si4Util {
    public static function getInt($array, $key, $defaultValue = 0) {
        return intval(self::getArg($array, $key, $defaultValue));
    }
    public static function getArg($array, $key, $defaultValue = null) {
        if (!is_array($array) || !isset($array[$key])) return $defaultValue;
        return $array[$key];
    }

    public static function pathArg($array, $path, $defaultValue = null) {
        $keys = explode("/", $path);
        foreach ($keys as $key) {
            if (/* !is_array($array) || */ !isset($array[$key])) return $defaultValue;
            $array = $array[$key];
        }
        return $array;
    }

    public static function nextEntityId() {
        $lastEntityId = Entity::select("id")->orderBy('id', 'desc')->pluck("id");
        if (!$lastEntityId || !isset($lastEntityId[0])) return 1;
        return $lastEntityId[0] ? intval($lastEntityId[0]) + 1 : 1;
    }

}