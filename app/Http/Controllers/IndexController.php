<?php
namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class IndexController extends FrontendController
{
    public function index(Request $request) {

        $data = [
            "layoutData" => $this->layoutData($request),
        ];

        return view('fe.index', $data);
    }
}