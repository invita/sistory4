<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntityHelpers;
use App\Helpers\EntitySelect;
use App\Helpers\Enums;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Files extends Controller
{
    public function fileList(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $pageStart = Si4Util::getArg($postJson, "pageStart", 0);
        $pageCount = Si4Util::getArg($postJson, "pageCount", 20);

        $query = File::query();
        $rowCount = $query->select()->count();

        $files = $query->offset($pageStart)->limit($pageCount)->get();
        $files->map(function($f) { $f->url = $f->getUrl(); });
        return ["status" => true, "data" => $files, "rowCount" => $rowCount, "error" =>  null];

    }

    public function saveFile(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = Si4Util::getArg($postJson, "id", 0);
        $name = Si4Util::getArg($postJson, "name", "");
        $path = Si4Util::getArg($postJson, "path", "");
        $type = Si4Util::getArg($postJson, "type", "other");

        if (!$path) $path = "";
        if (!$type) $type = "other";

        $status = true;
        $error = null;
        $file = File::findOrNew($id);

        if ($file && $file->name && ($path != $file->path || $type != $file->type || $name !== $file->name)) {
            $oldFile = $file->getStorageName();
            $newFile = File::makeStorageName($type, $path, $name);
            if (Storage::exists($newFile)) Storage::delete($newFile);
            Storage::move($oldFile, $newFile);
        }

        $tempFileName = Si4Util::getArg($postJson, "tempFileName", "");
        $tempStorageName = "public/temp/".$tempFileName;
        if ($tempFileName && Storage::exists($tempStorageName)) {
            $targetStorageName = File::makeStorageName($type, $path, $name);
            if (Storage::exists($targetStorageName)) {
                Storage::delete($targetStorageName);
            }
            Storage::move($tempStorageName, $targetStorageName);
            $file->checksum = md5_file(storage_path('app')."/".$targetStorageName);
            $file->size = Storage::size($targetStorageName);
            $file->mimeType = Storage::mimeType($targetStorageName);
        }

        $file->name = $name;
        $file->path = $path;
        $file->type = $type;
        $file->save();

        return ["status" => $status, "error" => $error];
    }

    public function deleteFile(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $id = $postJson["data"]["id"];

        $file = File::find($id);
        if ($file) {
            $file->delete();
        }

        return $this->fileList($request);
    }

    public static function getFileUrl(File $file) {
        return $file->type."/".$file->path."/".$file->name;
    }
}