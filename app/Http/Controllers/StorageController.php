<?php
namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function preview(Request $request) {
        $path = $request->get("path");
        $targetStoragePath = "public/".$path;

        if (!Storage::exists($targetStoragePath)) abort(404);

        $file = Storage::get($targetStoragePath);
        $type = Storage::mimeType($targetStoragePath);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}