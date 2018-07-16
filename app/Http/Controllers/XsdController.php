<?php
namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class XsdController extends Controller
{
    public function index(Request $request, $name = "") {

        $path = resource_path()."/assets/xsd/".$name.".xsd";
        if (!file_exists($path)) {
            $response = Response::make("Schema not found", 404);
            return $response;
        }

        $contents = file_get_contents($path);
        $response = Response::make($contents, 200);
        $response->header("Content-Type", "text/xml");
        return $response;
    }
}