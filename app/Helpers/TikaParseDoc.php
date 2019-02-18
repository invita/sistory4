<?php

namespace App\Helpers;

use Vaites\ApacheTika\Client;

class TikaParseDoc
{
    private static $tikaClient;
    private static function getTikaClient() {
        if (!self::$tikaClient) {
            $tikaPath = base_path()."/lib/tika-app-1.18.jar";
            self::$tikaClient = Client::make($tikaPath);
        }
        return self::$tikaClient;
    }

    private static function getFullPath($fileName) {
        return storage_path('app')."/".$fileName;
    }

    public static function parseText($fileName) {

        $tikaClient = self::getTikaClient();
        $fullFileName = self::getFullPath($fileName);

        print_r("Extracting ".$fullFileName);
        $text = $tikaClient->getText($fullFileName);
        //echo "text: "; print_r($text); echo "\n";

        return $text;


        /*
        $language = $tikaClient->getLanguage($fullFileName);
        echo "language: "; print_r($language); echo "\n";

        $metadata = $tikaClient->getMetadata($fullFileName);
        echo "metadata: "; print_r($metadata); echo "\n";

        $html = $tikaClient->getHTML($fullFileName);
        echo "html: "; print_r($html); echo "\n";
        */

    }
}