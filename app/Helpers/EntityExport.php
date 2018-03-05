<?php
namespace App\Helpers;
use App\Models\Entity;
use Illuminate\Support\Facades\Artisan;

/**
 * Class EntityExport
 *
 * @package  Sistory4
 * @author   Matic Vrscaj
 */
class EntityExport
{
    public static function exportEntities($entityList) {

        //print_r($entityList["data"][0]);

        $status = true;
        $error = null;
        //$fileName = "entities.zip";
        $fileName = tempnam("/tmp", "si4.zip");
        $pathPrefix = "entities/";

        $archive = new \ZipArchive();
        $archive->open($fileName, \ZipArchive::CREATE);

        foreach ($entityList["data"] as $entity) {
            $handle_id = $entity["handle_id"];

            $xml = $entity["xmlData"];
            $archive->addFromString($pathPrefix.$handle_id."/mets.xml", $xml);
        }

        $archive->setArchiveComment('Created '.date('Y-M-d'));
        $archive->close();

        return $fileName;

    }
}