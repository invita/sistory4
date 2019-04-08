<?php
namespace App\Helpers;

/**
 * Class ImageHelpers
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 *
 * In other words: IIIF Helpers (International Image Interoperability Framework)
 */
class ImageHelpers
{
    public static function imageUrl(
        $identifier,
        $region = "full",
        $size = "full",
        $rotation = "0",
        $quality = "default",
        $format = "jpg"
    ) {
        if (!$identifier || !is_string($identifier)) return "";
        $identifierUri = str_replace("/", "%2F", $identifier);
        return env('IIIF_URL') ."/". $identifierUri ."/". $region ."/". $size ."/". $rotation ."/". $quality .".". $format;
    }

    // Used for main thumbs (entity details main thumb)
    public static function getMainThumbUrl($handleId, $fileName) {
        $imgIdentifier = "entity/".FileHelpers::getIdNamespace($handleId)."/".$handleId."/".$fileName;
        return self::imageUrl($imgIdentifier, "full", "230,");
    }

    // Used for small thumbs (entity files)
    public static function getSmallThumbUrl($handleId, $fileName) {
        $imgIdentifier = "entity/".FileHelpers::getIdNamespace($handleId)."/".$handleId."/".$fileName;
        return self::imageUrl($imgIdentifier, "full", "100,");
    }
}