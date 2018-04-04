<?php
namespace App\Http\Controllers;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\Si4Util;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class SearchController extends Controller
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

            $docPathMap = [

                // System
                "id" => ["path" => "id"],
                "handle_id" => ["path" => "_source/handle_id"],
                "parent" => ["path" => "_source/parent"],
                "primary" => ["path" => "_source/primary"],
                "collection" => ["path" => "_source/collection"],
                "struct_type" => ["path" => "_source/struct_type"],
                "struct_subtype" => ["path" => "_source/struct_subtype"],
                "entity_type" => ["path" => "_source/entity_type"],
                "active" => ["path" => "_source/active"],

                // Xml Basic
                "handle_url" => ["path" => "_source/data/objId"],
                "created_at" => ["path" => "_source/data/created_at"],
                "modified_at" => ["path" => "_source/data/modified_at"],

                // DC
                "dc_title" => [
                    "path" => "_source/data/dmd/dc/title",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_creator" => [
                    "path" => "_source/data/dmd/dc/creator",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_subject" => [
                    "path" => "_source/data/dmd/dc/subject",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_description" => [
                    "path" => "_source/data/dmd/dc/description",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_publisher" => [
                    "path" => "_source/data/dmd/dc/publisher",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_contributor" => [
                    "path" => "_source/data/dmd/dc/contributor",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_date" => [
                    "path" => "_source/data/dmd/dc/date",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_type" => [
                    "path" => "_source/data/dmd/dc/type",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "dc_format" => [
                    "path" => "_source/data/dmd/dc/format",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "identifier" => [
                    "path" => "_source/data/dmd/dc/identifier",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "source" => [
                    "path" => "_source/data/dmd/dc/source",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "language" => [
                    "path" => "_source/data/dmd/dc/language",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "relation" => [
                    "path" => "_source/data/dmd/dc/relation",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "coverage" => [
                    "path" => "_source/data/dmd/dc/coverage",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],
                "rights" => [
                    "path" => "_source/data/dmd/dc/rights",
                    "parser" => DcHelpers::getDcPresentationParser(),
                ],

                // Files
                "fileName" => [
                    "path" => "_source/data/files",
                    "parser" => DcHelpers::getDcFirstFileNameParser(),
                ],
            ];

            foreach ($assocData as $doc) {
                $docResult = [];
                foreach ($docPathMap as $key => $bluePrint) {
                    $path = $bluePrint["path"];
                    $docResult[$key] = Si4Util::pathArg($doc, $path, "");
                    if (isset($bluePrint["parser"]))
                        $docResult[$key] = $bluePrint["parser"]($key, $docResult[$key]);
                }
                $data["results"][] = $docResult;
            }
        }

        //echo "<pre>"; print_r($data); echo "</pre>";

        return view("fe.search", [
            "q" => $q,
            "data" => $data,
        ]);
    }
}