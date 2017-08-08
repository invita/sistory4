<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\EntityImport;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\EntityType;
use App\Models\Relation;
use Elasticsearch\ClientBuilder;
use \Illuminate\Http\Request;
use Elasticsearch;

class UploadController extends Controller
{
    public function entity(Request $request)
    {
        $entityType = EntityType::find($request->input("entity_type_id"));
        $file = $request->file("file");

        $entity = Entity::createFromUpload($entityType, $file);

        return ["status" => true, "data" => $entity->data, "error" =>  null];
    }

    public function showContent(Request $request)
    {
        $file = $request->file("file");
        return ["status" => true, "data" => file_get_contents($file->getPathname()), "error" =>  null];
    }

    public function import(Request $request)
    {
        $status = true;
        $error = null;
        $file = $request->file("file");

        $data = "";

        $archiv = new \ZipArchive();
        $archiv->open($file, \ZipArchive::CREATE);

        for($i = 0; $i < $archiv->numFiles; $i++)
        {
            $filePath = $archiv->getNameIndex($i);
            $fileName = basename($filePath);
            //echo "filePath: ".$filePath.", fileName: ".$fileName."\n";
            if (substr($fileName, 0, 1) == ".") {
                // Skip file names starting with .
                continue;
            }

            $content = $archiv->getFromIndex($i);
            if (!$content) {
                // Skip empty content files (mostly directories)
                continue;
            }

            //echo $fileName.", content: "; print_r($content); echo "\n";

            if ($fileName == "mets.xml") {
                // echo $fileName.", content:\n"; print_r($content); echo "\n";
                EntityImport::importEntity($content);
            }
        }

        $archiv->close();

        return ["status" => $status, "data" => $data, "error" =>  $error];
    }
}