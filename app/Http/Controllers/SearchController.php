<?php
namespace App\Http\Controllers;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\Si4Util;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class SearchController extends FrontendController
{
    public function index(Request $request) {

        $size = 100;

        $q = $request->query("q", "");
        $data = [
            "took" => 0,
            "totalHits" => 0,
            "maxScore" => 0,
            "results" => [],
        ];

        if ($q) {
            $elasticData = ElasticHelpers::searchString($q, 0, $size);

            $data["took"] = Si4Util::getArg($elasticData, "took", 0);
            $data["totalHits"] = Si4Util::pathArg($elasticData, "hits/total", 0);
            $data["maxScore"] = Si4Util::pathArg($elasticData, "hits/max_score", 0);

            $assocData = ElasticHelpers::elasticResultToAssocArray($elasticData);
            //echo "<pre>"; print_r($assocData); echo "</pre>";

            foreach ($assocData as $doc) {
                $data["results"][] = DcHelpers::mapElasticEntity($doc);
            }
        }

        //echo "<pre>"; print_r($data); echo "</pre>";

        return view("fe.search", [
            "layoutData" => $this->layoutData($request),
            "q" => $q,
            "data" => $data,
        ]);
    }
}