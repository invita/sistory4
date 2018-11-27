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
    public static function exportEntitiesMets($entityList, $pathPrefix = "entities") {

        $status = true;
        $error = null;
        //$fileName = "entities.zip";
        $fileName = tempnam("/tmp", "si4.zip");

        // Create zip
        $archive = new \ZipArchive();
        $archive->open($fileName, \ZipArchive::CREATE);

        // Iterate entityList
        foreach ($entityList["data"] as $entity) {

            // Calculate parentsPath
            $parentsPath = "";
            if ($entity["parent"]) {
                $hierarchy = EntitySelect::selectEntityHierarchy([
                    "handle_id" => $entity["handle_id"],
                    "entity" => $entity
                ]);

                $parents = $hierarchy["data"]["parents"];
                foreach ($parents as $parent) {
                    if ($parentsPath) $parentsPath .= "/";
                    $parentsPath .= $parent["handle_id"];
                }
            }
            if ($parentsPath) $parentsPath = "/".$parentsPath;

            // Add xml into zip using the parent hierarchy path
            $xml = $entity["xmlData"];
            $archive->addFromString($pathPrefix.$parentsPath."/".$entity["handle_id"]."/mets.xml", $xml);
        }

        // Save the zip
        $archive->setArchiveComment('Created '.date('Y-M-d'));
        $archive->close();

        return $fileName;

    }

    public static function exportEntitiesCsv($entityList, $columns) {

        $status = true;
        $error = null;
        //$fileName = "entities.zip";
        $fileName = tempnam("/tmp", "si4.csv");

        $result = "";
        $NL = "\n";
        $quote = "\"";


        $lineData = [];
        foreach ($columns as $colName => $colText) {
            $lineData[] = $quote.$colText.$quote;
        }
        $result .= join(",", $lineData).$NL;

        foreach ($entityList["data"] as $entity) {
            $lineData = [];
            foreach ($columns as $colName => $colText) {
                $lineData[] = $quote.$entity[$colName].$quote;
            }
            $result .= join(",", $lineData).$NL;
        }

        file_put_contents($fileName, $result);
        return $fileName;

    }
}