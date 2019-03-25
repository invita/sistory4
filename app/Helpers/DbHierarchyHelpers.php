<?php
namespace App\Helpers;

use App\Models\Entity;

class DbHierarchyHelpers
{
    private static $handleToParentMap = null;
    private static function loadHierarchyMap() {
        if (!self::$handleToParentMap) self::$handleToParentMap = [];
        $entities = Entity::all(["id", "handle_id", "parent"]);
        foreach ($entities as $entity) {
            //$id = $entity->id;
            $handle_id = $entity->handle_id;
            $parent = $entity->parent;
            if (!$parent) $parent = null;
            self::$handleToParentMap[$handle_id] = $parent;
        }
    }

    public static function clearHierarchyCache() {
        self::$handleToParentMap = null;
    }

    public static function getParentHierarchyHandles($handle_id) {
        if (!self::$handleToParentMap) self::loadHierarchyMap();

        $result = [];
        if ($handle_id && self::$handleToParentMap[$handle_id]) {
            $last = self::$handleToParentMap[$handle_id];
            $cyclicPreventCounter = 0;
            while ($last && $cyclicPreventCounter < 25) {
                array_unshift($result, $last);
                $last = self::$handleToParentMap[$last];
                $cyclicPreventCounter++;
            }
        }

        return $result;
    }

}