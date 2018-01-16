<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\EntityImport;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Relation;
use Elasticsearch\ClientBuilder;
use \Illuminate\Http\Request;
use Elasticsearch;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function entity(Request $request)
    {
        $structType = $request->input("struct_type");
        $file = $request->file("file");

        $entity = Entity::createFromUpload($structType, $file);

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

        $archive = new \ZipArchive();
        $archive->open($file, \ZipArchive::CREATE);

        for($i = 0; $i < $archive->numFiles; $i++)
        {
            $filePath = $archive->getNameIndex($i);
            $fileName = basename($filePath);
            //echo "filePath: ".$filePath.", fileName: ".$fileName."\n";
            if (substr($fileName, 0, 1) == ".") {
                // Skip file names starting with .
                continue;
            }

            $content = $archive->getFromIndex($i);
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

        $archive->close();

        return ["status" => $status, "data" => $data, "error" =>  $error];
    }

    public function uploadFile(Request $request) {
        $status = true;
        $error = null;

        $realFileName = $request->file->getClientOriginalName();
        $fileExplode = explode(".", $realFileName);
        $ext = end($fileExplode);
        $today = date("Y-m-d.H-i-s."); // 2018-01-15--23-21-15-
        $tempFileName = $today.random_int(1000000, 9999999).".".$ext;

        $request->file->storeAs('public/temp', $tempFileName);

        $data = [
            "tempFileName" => $tempFileName,
            "realFileName" => $realFileName,
            "url" => "/storage/preview/?path=temp/".$tempFileName
        ];
        return ["status" => $status, "data" => $data, "error" =>  $error];
    }
}