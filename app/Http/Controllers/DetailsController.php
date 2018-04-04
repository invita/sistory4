<?php
namespace App\Http\Controllers;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\Si4Util;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DetailsController extends Controller
{
    public function index(Request $request, $hdl = null) {

        $data = [
        ];

        return view("fe.details", [
            "hdl" => $hdl,
            "data" => $data,
        ]);
    }
}