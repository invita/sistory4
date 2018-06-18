<?php
namespace App\Http\Controllers;

use App\Models\Entity;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function preview(Request $request) {
        $path = $request->get("path");
        $attach = $request->get("attach");

        $targetStoragePath = "public/".$path;

        if (!Storage::exists($targetStoragePath)) abort(404);

        $file = Storage::get($targetStoragePath);
        $type = Storage::mimeType($targetStoragePath);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        if ($attach) {
            $filePathExplode = explode("/", $targetStoragePath);
            $fileName = array_pop($filePathExplode);
            header('Content-Disposition: attachment;filename="'.$fileName.'"');
        }

        return $response;
    }
    public function mets(Request $request) {
        $handleId = $request->get("handleId");
        $attach = $request->get("attach");

        $entity = Entity::where(["handle_id" => $handleId])->first();

        if (!$entity || !$entity->id) abort(404);

        $type = "text/xml";

        $response = Response::make($entity->data, 200);
        $response->header("Content-Type", $type);

        if ($attach) {
            $fileName = $handleId."_mets.xml";
            header('Content-Disposition: attachment;filename="'.$fileName.'"');
        }

        return $response;
    }



}