<?php
namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function index(Request $request, $id = 0) {

        Artisan::call("test:test");

        return view('fe.test', [
            "data" => [
                "test" => "foobar",
                "id" => $id,
                "path" => $request->path(),
                "url" => $request->url(),
                "fullUrl" => $request->fullUrl(),
                "method" => $request->method(),
                "all" => $request->all(),
                "query" => $request->query(),
            ]
        ]);
    }
}