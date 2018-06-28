<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityImport;
use App\Helpers\FileHelpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Elastic\EntityNotIndexedException;
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


    private static $relevantPathStarts = null;
    private function isRelevantPathComponent($cpcName) {
        $rootCollectionName = env("SI4_ELASTIC_ROOT_COLLECTION");
        $topMenuName = env("SI4_ELASTIC_TOP_MENU_COLLECTION");
        $botMenuName = env("SI4_ELASTIC_BOTTOM_MENU_COLLECTION");

        if (self::$relevantPathStarts == null) {
            self::$relevantPathStarts = [
                env("SI4_ELASTIC_ROOT_COLLECTION"),
                env("SI4_ELASTIC_TOP_MENU_COLLECTION"),
                env("SI4_ELASTIC_BOTTOM_MENU_COLLECTION"),
                "file",
                "menu"
            ];
        }

        // Numeric directory (handle_id of entities) is relevant
        if (is_numeric($cpcName)) return true;

        // If path component starts with any of self::$relevantPathStarts, this component is relevant
        foreach (self::$relevantPathStarts as $relevantPathStart) {
            if (substr($cpcName, 0, strlen($relevantPathStart)) == $relevantPathStart) return true;
        }

        // Otherwise this component is not relevant
        return false;
    }

    private function zipGetInfo($fileName) {
        $archive = new \ZipArchive();
        $archive->open($fileName, \ZipArchive::CREATE);
        $result = [
            "fileList" => [],
            "commonPath" => "",
        ];

        $fileList = [];
        $commonPathComponents = null;

        for($i = 0; $i < $archive->numFiles; $i++)
        {
            $filePath = $archive->getNameIndex($i);

            $pathExplode = explode("/", $filePath);
            $fileName = array_pop($pathExplode);

            // Skip file names starting with .
            if (substr($fileName, 0, 1) == ".") continue;

            $content = $archive->getFromIndex($i);

            // Skip empty content files (mostly directories)
            if (!$content) continue;

            // File is relevant
            $fileList[] = $filePath;
            //echo $filePath."\n";

            // Find commonPath
            if (strtolower($fileName) == "mets.xml") {
                if (!$commonPathComponents) {
                    $commonPathComponents = $pathExplode;
                    foreach ($commonPathComponents as $cpcIdx => $cpcName) {
                        // Condition to throw component out from commonPath
                        if ($this->isRelevantPathComponent($cpcName)) {
                            $commonPathComponents = array_slice($commonPathComponents, 0, $cpcIdx);
                        }
                    }
                } else {
                    foreach ($commonPathComponents as $cpcIdx => $cpcName) {
                        // Condition to throw component out from commonPath
                        if (!isset($pathExplode[$cpcIdx]) ||
                            $pathExplode[$cpcIdx] != $cpcName ||
                            $this->isRelevantPathComponent($cpcName)
                        ) {
                            $commonPathComponents = array_slice($commonPathComponents, 0, $cpcIdx);
                        }
                    }
                }
            }

        }
        $archive->close();

        $result["commonPath"] = join("/", $commonPathComponents ? $commonPathComponents : []);
        if ($result["commonPath"]) $result["commonPath"] .= "/";

        // Sort file list, so shortest path is first.
        // First put everything into buckets (one bucket for each count of slashes it's items have)
        $sortBuckets = [];
        $maxBucket = 0;
        foreach ($fileList as $path) {
            $numOfSlashes = substr_count($path, "/");
            if (!isset($sortBuckets[$numOfSlashes]))
                $sortBuckets[$numOfSlashes] = [];
            $sortBuckets[$numOfSlashes][] = $path;
            if ($numOfSlashes > $maxBucket) $maxBucket = $numOfSlashes;
        }
        // Sort the items in each bucket and append
        $result["fileList"] = [];
        for ($i = 0; $i < $maxBucket +1; $i++) {
            if (isset($sortBuckets[$i])) {
                sort($sortBuckets[$i]);
                $result["fileList"] = array_merge($result["fileList"], $sortBuckets[$i]);
            }
        }

        //print_r($result);
        return $result;
    }

    public function importCheck(Request $request) {
        ini_set('max_execution_time', env("SI4_MAX_EXECUTION_TIME_EXT_OPTS"));
        ini_set('post_max_size', env("SI4_MAX_POST_SIZE_EXT_OPTS"));
        ini_set('upload_max_filesize', env("SI4_MAX_POST_SIZE_EXT_OPTS"));

        $file = $request->file("file");
        //print_r($file);
        $status = true;
        $error = null;
        $data = [
            "collections" => 0,
            "entities" => 0,
            "files" => 0,
            "unknown" => 0,
        ];

        $now = date('Y-m-d_H-i', time());
        $copyToName = $now."_".$file->getClientOriginalName();
        $pathName = $file->storeAs("imports", $copyToName);

        $zipInfo = $this->zipGetInfo($file->getPathname());
        //print_r($fileList);

        $archive = new \ZipArchive();
        $archive->open($file->getPathname(), \ZipArchive::CREATE);

        foreach ($zipInfo["fileList"] as $zipFile) {
            $pathExplode = explode("/", $zipFile);
            $fileName = array_pop($pathExplode);

            if (strtolower($fileName) == "mets.xml") {
                $content = $archive->getFromName($zipFile);
                $xmlDoc = simplexml_load_string($content);
                $structType = $xmlDoc['TYPE'];
                switch ($structType) {
                    case "collection": $data["collections"]++; break;
                    case "entity": $data["entities"]++; break;
                    case "file": $data["files"]++; break;
                    default: $data["unknown"]++; break;
                }
            }
        }

        $archive->close();

        return ["status" => $status, "error" =>  $error, "pathName" => $pathName, "data" => $data];
    }

    public function import(Request $request)
    {
        ini_set('max_execution_time', env("SI4_MAX_EXECUTION_TIME_EXT_OPTS"));
        ini_set('post_max_size', env("SI4_MAX_POST_SIZE_EXT_OPTS"));
        ini_set('upload_max_filesize', env("SI4_MAX_POST_SIZE_EXT_OPTS"));

        $postJson = json_decode(file_get_contents("php://input"), true);
        $uploadedFile = Si4Util::getArg($postJson, "uploadedFile");

        if (!$uploadedFile)
            return ["status" => false, "data" => [], "error" =>  "Missing parameter uploadedFile"];
        if (!Storage::exists($uploadedFile))
            return ["status" => false, "data" => [], "error" =>  "File not found"];

        $zipFileName = storage_path('app')."/".$uploadedFile;
        $errors = [];
        $data = [
            "importCount" => 0,
            "replacedCount" => 0,
        ];

        $depthLevel = 0;
        $importedEntitiesByLevel = [];
        $importCount = 0;

        $zipInfo = $this->zipGetInfo($zipFileName);
        //print_r($zipInfo);

        $archive = new \ZipArchive();
        $archive->open($zipFileName, \ZipArchive::CREATE);

        foreach ($zipInfo["fileList"] as $zipFile) {


            //echo "zipFile ".$zipFile."\n";

            // Remove common path...
            if ($zipInfo["commonPath"])
                $zipFileShort = substr($zipFile, strlen($zipInfo["commonPath"]));
            else
                $zipFileShort = $zipFile;

            //echo "zipFileShort ".$zipFileShort."\n";

            $pathExplode = explode("/", $zipFileShort);
            $fileName = array_pop($pathExplode);
            //$currentZipPath = substr($zipFile, 0, strlen($zipFile) - strlen($fileName));
            //echo "currentZipPath: ".$currentZipPath."\n";

            //print_r($pathExplode);
            //echo "fileName ".$fileName."\n";

            $handleId = array_pop($pathExplode);
            $depthLevel = count($pathExplode);

            $parentHandleId = array_pop($pathExplode);
            if (!$parentHandleId) $parentHandleId = "";
            $content = $archive->getFromName($zipFile);
            //echo "handleId: ".$handleId.", parentHandleId: ".$parentHandleId."\n";

            if (strtolower($fileName) == "mets.xml") {
                // Process mets.xml and import entity


                $importResult = EntityImport::importEntity($handleId, $parentHandleId, $content);
                $entity = $importResult["entity"];
                $importCount += 1;

                $importedEntitiesByLevel[$depthLevel][] = $entity->id;

                if ($importResult["replaced"]) $data["replacedCount"] += 1;

            } else {
                // Not mets.xml, copy file to appropriate path

                $destStorageName = FileHelpers::getPublicStorageName($parentHandleId, $fileName);
                if (Storage::exists($destStorageName)) Storage::delete($destStorageName);
                Storage::put($destStorageName, $content);
                //echo "Put file ".$destStorageName."\n";
            }

            //echo "\n";
        }

        $archive->close();

        // Remember imported entities by hierarchy depth level (children have higher value than their parents)
        $levels = array_keys($importedEntitiesByLevel);
        sort($levels);

        foreach ($levels as $level) {
            foreach ($importedEntitiesByLevel[$level] as $insertId) {
                try {
                    EntityImport::postImportEntity($insertId);
                } catch (EntityNotIndexedException $eNotIndexed) {
                    $errors[] = $eNotIndexed->getMessage();
                }
            }

            // Because Elastic is not real time, we need to refresh index for each insert depth level
            // It is important that i.e. "parent" menus can be found in index before indexing it's child entities
            if (count($importedEntitiesByLevel[$level]))
                ElasticHelpers::refreshIndex();
        }

        $data["importCount"] = $importCount;
        return ["status" => true, "data" => $data, "errors" => $errors];
    }

    public function uploadFile(Request $request) {
        ini_set('max_execution_time', env("SI4_MAX_EXECUTION_TIME_EXT_OPTS"));
        ini_set('post_max_size', env("SI4_MAX_POST_SIZE_EXT_OPTS"));
        ini_set('upload_max_filesize', env("SI4_MAX_POST_SIZE_EXT_OPTS"));

        $status = true;
        $error = null;

        $realFileName = $request->file->getClientOriginalName();
        $fileExplode = explode(".", $realFileName);
        $ext = end($fileExplode);
        $today = date("Y-m-d.H-i-s."); // 2018-01-15--23-21-15-
        $tempFileName = $today.random_int(1000000, 9999999).".".$ext;

        $tempStorageName = "public/temp/".$tempFileName;
        $request->file->storeAs("public/temp", $tempFileName);

        $checksum = md5_file(storage_path('app')."/".$tempStorageName);
        $size = Storage::size($tempStorageName);
        $mimeType = Storage::mimeType($tempStorageName);

        $data = [
            "tempFileName" => $tempFileName,
            "realFileName" => $realFileName,
            "url" => "/storage/preview/?path=temp/".$tempFileName,
            "checksum" => $checksum,
            "size" => $size,
            "mimeType" => $mimeType,
        ];
        return ["status" => $status, "data" => $data, "error" =>  $error];
    }
}