<?php
namespace App\Http\Controllers;

use App\Helpers\FileHelpers;
use App\Models\Entity;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class CdnController extends Controller
{
    public function index(Request $request, $hdl, $file) {

        $prefix = substr($hdl, 0, 4);
        if ($prefix == "file") {
            $type = "file";
            $num = intval(str_replace("file", "", $hdl));
        } else if ($prefix == "menu") {
            $type = "menu";
            $num = intval(str_replace("menu", "", $hdl));
        } else if (is_numeric($hdl)) {
            $type = "entity";
            $num = intval($hdl);
        } else {
            // Error
            abort(404);
        }

        $idNS = FileHelpers::getIdNamespace($num);
        $path = $type."/".$idNS."/".$hdl."/".$file;


        $targetStoragePath = "public/".$path;
        if (!Storage::exists($targetStoragePath)) abort(404);

        $file = Storage::get($targetStoragePath);
        $type = Storage::mimeType($targetStoragePath);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;

    }
}