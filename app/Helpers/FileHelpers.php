<?php
namespace App\Helpers;

/**
 * Class FileHelpers
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class FileHelpers
{
    public static function getPreviewUrl($entityId, $fileName) {
        $idNS = self::getIdNamespace($entityId);
        $storageName = "entity/".$idNS."/".$entityId."/".$fileName;
        return "/storage/preview/?path=".$storageName;
    }

    public static function getStorageName($entityId, $fileName) {
        $idNS = self::getIdNamespace($entityId);
        return "public/entity/".$idNS."/".$entityId."/".$fileName;
    }

    public static function getIdNamespace($id)
    {
        if($id <= 1000) return '1-1000';
        return floor($id/1000).'001-'.(floor($id/1000) + 1).'000';
    }

}