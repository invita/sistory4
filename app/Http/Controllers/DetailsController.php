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

        if (!$hdl) die();

        $data = [
        ];

        if ($hdl) {
            $elasticData = ElasticHelpers::searchByHandleArray([$hdl]);
            if (!count($elasticData)) {
                die("Handle id not found");
            }

            $docData = $elasticData[array_keys($elasticData)[0]];
            //echo "<pre>"; print_r($docData); echo "</pre>";

            $data["doc"] = DcHelpers::mapElasticEntity($docData);
        }

        return view("fe.details", [
            "hdl" => $hdl,
            "data" => $data,
        ]);
    }
}