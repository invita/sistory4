<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;

/**
 * Class FileHelpers
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class FileHelpers
{

    // $handleId -> file's parent handle_id
    public static function getPreviewUrl($handleId, $structType, $fileName) {
        $storageName = self::getStorageName($handleId, $fileName);
        return "/storage/preview?path=".$storageName;
    }

    // $handleId -> file's parent handle_id
    public static function getThumbUrl($handleId, $structType, $fileName) {
        $publicStorageName = self::getPublicStorageName($handleId, $fileName.SI4_THUMB_FILE_POSTFIX);
        if (Storage::exists($publicStorageName)) {
            return self::getPreviewUrl($handleId, $structType, $fileName.SI4_THUMB_FILE_POSTFIX);
        }
        //echo "File not exist {$handleId} {$fileName} {$publicStorageName}\n";
        return self::getDefaultThumbForStructType($structType);
    }
    public static function getDefaultThumbForStructType($structType) {
        switch ($structType) {
            case "collection": return "/sites/".env("SI4_SITE")."/img/structType/collection.png";
            case "entity": return "/sites/".env("SI4_SITE")."/img/structType/entity.png";
            case "file": default: return "/sites/".env("SI4_SITE")."/img/structType/file.png";
        }
    }

    public static function getDefaultThumbForUse($useName) {
        switch ($useName) {
            case "youtube": return "/sites/".env("SI4_SITE")."/img/structType/youtube.png";
        }
    }

    public static function getPublicStorageName($handleId, $fileName) {
        return "public/".self::getStorageName($handleId, $fileName);
    }

    public static function getStorageName($handleId, $fileName) {
        $prefix = substr($handleId, 0, 4);
        if ($prefix == "file") {
            $type = "file";
            $num = intval(str_replace("file", "", $handleId));
        } else if ($prefix == "menu") {
            $type = "menu";
            $num = intval(str_replace("menu", "", $handleId));
        } else if (is_numeric($handleId)) {
            $type = "entity";
            $num = intval($handleId);
        } else {
            return "unknownStorage";
        }

        $idNS = self::getIdNamespace($num);
        return $type."/".$idNS."/".$handleId."/".$fileName;
    }

    public static function getIdNamespace($id) {
        if($id <= 1000) return '1-1000';
        return floor($id/1000).'001-'.(floor($id/1000) + 1).'000';
    }



    public static $mimeTypes = [
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    ];

    public static function fileNameMime($fileName) {
        $comps = explode(".", $fileName);
        $ext = array_pop($comps);
        $extLower = strtolower($ext);
        if (isset(self::$mimeTypes[$extLower])) return self::$mimeTypes[$extLower];
        return "";
    }
}