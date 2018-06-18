<?php
namespace App\Http\Controllers;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\FileHelpers;
use App\Helpers\Si4Util;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DetailsController extends FrontendController
{
    public function index(Request $request, $hdl = null) {

        if (!$hdl) die();

        $data = [];

        if (!$hdl) {
            $viewName = "fe.details.nodata";
        } else {
            $elasticData = ElasticHelpers::searchByHandleArray([$hdl]);
            if (!count($elasticData)) {
                die("Handle id not found");
            }

            $docData = $elasticData[array_keys($elasticData)[0]];
            //echo "<pre>"; print_r($docData); echo "</pre>";

            $data["xml"] = Si4Util::pathArg($docData, "_source/xml", "");
            //$data["elasticData"] = $docData;
            $data["doc"] = DcHelpers::mapElasticEntity($docData);

            $struct_type = Si4Util::pathArg($docData, "_source/struct_type", "entity");
            $struct_subtype = Si4Util::pathArg($docData, "_source/struct_subtype", "default");

            // Parents
            $parenData = ElasticHelpers::searchParentsRecursive($data["doc"]["parent"]);
            $parents = [];
            foreach ($parenData as $parent) {
                $parents[] = DcHelpers::mapElasticEntity($parent);
            }
            $data["parents"] = $parents;

            $this->prepareBreadcrumbs($request, $hdl, $data);

            switch ($struct_type) {
                case "collection":
                    $viewName = "fe.details.collection";
                    $this->prepareDataForCollection($request, $hdl, $data);
                    break;
                case "file":
                    $viewName = "fe.details.file";
                    $this->prepareDataForFile($request, $hdl, $data);
                    break;
                case "entity": default:
                    $viewName = "fe.details.entity";
                    $this->prepareDataForEntity($request, $hdl, $data);
                    break;
            }
        }

        return view($viewName, [
            "layoutData" => $this->layoutData($request),
            "hdl" => $hdl,
            "data" => $data,
        ]);
    }


    private function prepareDataForCollection(Request $request, $hdl, &$data) {

        // Find children
        $childData = ElasticHelpers::searchChildren($hdl);
        $children = [];
        foreach ($childData as $child) {
            $children[] = DcHelpers::mapElasticEntity($child);
        }
        $data["children"] = $children;


        //print_r($data);
    }

    private function prepareDataForEntity(Request $request, $hdl, &$data) {
        $data["files"] = [];
        $files = ElasticHelpers::searchMust([
            "parent" => $hdl,
            "struct_type" => "file"
        ]);
        //print_r($files);

        foreach ($files as $file) {
            $fileHandleId = Si4Util::pathArg($file, "_source/handle_id");
            $fileName = Si4Util::pathArg($file, "_source/data/files/0/ownerId");
            $data["files"][] = [
                "handle_id" => $fileHandleId,
                "url" => Si4Util::pathArg($file, "_source/data/objId"),
                "fileName" => $fileName,
                "thumbUrl" => FileHelpers::getThumbUrl($hdl, $fileName),
                "mimeType" => Si4Util::pathArg($file, "_source/data/files/0/mimeType"),
                "size" => Si4Util::pathArg($file, "_source/data/files/0/size"),
                "created" => Si4Util::pathArg($file, "_source/data/files/0/created"),
                "checksum" => Si4Util::pathArg($file, "_source/data/files/0/checksum"),
                "checksumType" => Si4Util::pathArg($file, "_source/data/files/0/checksumType"),
            ];
        }
    }

    private function prepareDataForFile(Request $request, $hdl, &$data) {

    }


    private function prepareBreadcrumbs(Request $request, $hdl, &$data) {
        //print_r($data["doc"]);

        $linkPrefix = "/details/";
        $skipHandles = ["si4", "menuTop"];
        $breadcrumbs = [];

        // Add Parents to breadcrumbs

        $breadcrumbs[] = [
            "link" => "/",
            "text" => "Si4"
        ];

        $parentsReverse = array_reverse($data["parents"]);
        foreach ($parentsReverse as $parent) {
            if (in_array($parent["handle_id"], $skipHandles)) continue;
            $breadcrumbs[] = [
                "link" => $linkPrefix.$parent["handle_id"],
                "text" => $parent["first_dc_title"]
            ];
        }

        // Add current doc to breadcrumbs
        $breadcrumbs[] = [
            "link" => $linkPrefix.$data["doc"]["handle_id"],
            "text" => $data["doc"]["first_dc_title"]
        ];
        $data["breadcrumbs"] = $breadcrumbs;
        $data["html_breadcrumbs"] = DcHelpers::breadcrumbsPresentation($breadcrumbs);

        //print_r($breadcrumbs);

    }

}