<?php
namespace App\Http\Controllers;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\Si4Util;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DetailsController extends FrontendController
{
    public function index(Request $request, $hdl = null) {

        if (!$hdl) die();

        $data = [];

        if ($hdl) {
            $elasticData = ElasticHelpers::searchByHandleArray([$hdl]);
            if (!count($elasticData)) {
                die("Handle id not found");
            }

            $docData = $elasticData[array_keys($elasticData)[0]];
            //echo "<pre>"; print_r($docData); echo "</pre>";

            $data["xml"] = Si4Util::pathArg($docData, "_source/xml", "");
            $data["doc"] = DcHelpers::mapElasticEntity($docData);

            $struct_type = Si4Util::pathArg($docData, "_source/struct_type", "entity");
            $struct_subtype = Si4Util::pathArg($docData, "_source/struct_subtype", "default");
        }

        switch ($struct_type) {
            case "collection":
                $viewName = "fe.details.collection";
                $childData = ElasticHelpers::searchByParent($hdl);
                $children = [];
                foreach ($childData as $child) {
                    $children[] = DcHelpers::mapElasticEntity($child);
                }
                $data["children"] = $children;
                //print_r($children);
                break;
            case "file": $viewName = "fe.details.file"; break;
            case "entity": default: $viewName = "fe.details.entity"; break;
        }

        return view($viewName, [
            "layoutData" => $this->layoutData($request),
            "hdl" => $hdl,
            "data" => $data,
        ]);
    }
}