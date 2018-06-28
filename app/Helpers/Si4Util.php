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


    // SimpleXmlElement helpers

    public static function xmlElementGetData($simpleXmlEl) {
        $namespaces = array_keys($simpleXmlEl->getNamespaces());
        $namespaces[] = ""; // Add empty namespace

        $nsPrefix = count($namespaces) ? $namespaces[0].":" : "";

        $attributes = [];
        foreach ($namespaces as $ns) {
            foreach ($simpleXmlEl->attributes($ns, true) as $attrKey => $attrVal) {
                $attributes[$attrKey] = (string)$attrVal;
            }
        }

        $children = [];
        foreach ($namespaces as $ns) {
            foreach ($simpleXmlEl->children($ns, true) as $child) {
                $children[] = $child;
            }
        }


        $result = [
            "name" => $nsPrefix.$simpleXmlEl->getName(),
            "attributes" => $attributes,
            "children" => $children,
        ];
        return $result;
    }

    public static function xmlElementDump($simpleXmlEl, $maxDepth = 1, $curDepth = 0) {

        $elData = self::xmlElementGetData($simpleXmlEl);

        if ($curDepth < $maxDepth) {
            foreach ($elData["children"] as $idx => $child) {
                $elData["children"][$idx] = self::xmlElementDump($child, $maxDepth, $curDepth +1);
            }
        } else {
            $childCount = count($elData["children"]);
            $elData["children"] = $childCount;
        }

        return $elData;
    }

}