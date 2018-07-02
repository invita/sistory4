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

        $q = $request->query("q", ""); // Query string
        $st = $request->query("st", "all"); // Search type
        $hdl = $request->query("hdl", ""); // Handle id filter
        $offset = $request->query("offset", 0);
        $size = $request->query("size", SI4_DEFAULT_PAGINATION_SIZE);

        $data = [
            "took" => 0,
            "totalHits" => 0,
            "maxScore" => 0,
            "results" => [],
        ];

        if ($q) {
            $elasticData = ElasticHelpers::searchString($q, $st, $hdl, $offset, $size);

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

        $layoutData = $this->layoutData($request);
        if ($hdl) {
            $layoutData["allowInsideSearch"] = true;
            $layoutData["hdl"] = $hdl;

            $hdlElasticData = ElasticHelpers::searchByHandleArray([$hdl]);
            $hdlDocData = $hdlElasticData[array_keys($hdlElasticData)[0]];
            $hdlDoc = DcHelpers::mapElasticEntity($hdlDocData);

            $layoutData["hdlTitle"] = $hdlDoc["first_dc_title"];
        }

        return view("fe.search", [
            "layoutData" => $layoutData,
            "searchType" => "search",
            "data" => $data,
            "paginatorTop" => $this->preparePaginator($data, "top"),
            "paginatorBot" => $this->preparePaginator($data, "bottom"),
        ]);
    }

    public function advanced(Request $request) {

        $offset = $request->query("offset", 0);
        $size = $request->query("size", SI4_DEFAULT_PAGINATION_SIZE);

        $data = [
            "took" => 0,
            "totalHits" => 0,
            "maxScore" => 0,
            "results" => [],
        ];

        $queryStr = isset($_SERVER['QUERY_STRING']) ? urldecode($_SERVER['QUERY_STRING']) : "";
        $queryStrExplode = explode("&", $queryStr);
        $queryParams = [];

        foreach ($queryStrExplode as $param) {
            $kv = explode("=", $param);
            if (count($kv) != 2) continue;
            $operAndName = explode("-", $kv[0]);
            if (count($operAndName) != 2) continue;
            if (!in_array($operAndName[0], ElasticHelpers::$advancedSearchOperators)) continue;

            $operator = $operAndName[0];
            $fieldName = $operAndName[1];
            $fieldValue = $kv[1];

            if (!$fieldValue) continue;

            $queryParams[] = [
                "operator" => $operator,
                "fieldName" => $fieldName,
                "fieldValue" => $fieldValue,
            ];
        }

        if ($queryParams) {

            $elasticData = ElasticHelpers::searchAdvanced($queryParams, $offset, $size);

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
            "searchType" => "advanced-search",
            "q" => "",
            "data" => $data,
            "paginatorTop" => $this->preparePaginator($data, "top"),
            "paginatorBot" => $this->preparePaginator($data, "bottom"),
        ]);
    }
}