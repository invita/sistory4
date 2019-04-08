<?php
namespace App\Http\Controllers;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\FileHelpers;
use App\Helpers\ImageHelpers;
use App\Helpers\Si4Helpers;
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

            //$data["doc"] = DcHelpers::mapElasticEntity($docData);
            $data["doc"] = Si4Helpers::getEntityDetailsPresentation($docData);

            $externalCollection = Si4Util::pathArg($data, "doc/si4tech/externalCollection", null);
            if ($externalCollection) {
                return redirect($externalCollection);
            }

            $struct_type = Si4Util::pathArg($docData, "_source/struct_type", "entity");
            $struct_subtype = Si4Util::pathArg($docData, "_source/struct_subtype", "default");

            // Parents
            $parenData = ElasticHelpers::searchParentsRecursive($data["doc"]["system"]["parent"]);
            $parents = [];
            foreach ($parenData as $parent) {
                //$parents[] = DcHelpers::mapElasticEntity($parent);
                $parents[] = Si4Helpers::getEntityListPresentation($parent);
            }
            $data["parents"] = $parents;

            $this->prepareBreadcrumbs($request, $hdl, $docData, $data);

            switch ($struct_type) {
                case "collection":
                    $viewName = "fe.details.collection";
                    $this->prepareDataForCollection($request, $hdl, $docData, $data);
                    break;
                case "file":
                    $viewName = "fe.details.file";
                    $this->prepareDataForFile($request, $hdl, $docData, $data);
                    break;
                case "entity": default:
                    $viewName = "fe.details.entity";
                    $this->prepareDataForEntity($request, $hdl, $docData, $data);
                    break;
            }
        }

        $layoutData = $this->layoutData($request);

        // Modify layout data when looking at collection details, so that search will look inside this collection
        if ($struct_type == "collection") {
            $layoutData["allowInsideSearch"] = true;
            $layoutData["hdl"] = $hdl;
            $layoutData["hdlTitle"] = first($data["doc"]["si4"]["title"]);
        }

        return view($viewName, [
            "layoutData" => $layoutData,
            "hdl" => $hdl,
            "data" => $data,
        ]);
    }


    private function prepareDataForCollection(Request $request, $hdl, $docData, &$data) {

        $searchResultsSort = Si4Util::pathArg($docData, "_source/data/si4tech/searchResultsSort", null);
        $sort = ElasticHelpers::elasticSortFromString($searchResultsSort);
        //print_r($sort);

        // Find children
        $childData = ElasticHelpers::searchChildren($hdl, 0, SI4_DEFAULT_PAGINATION_SIZE, $sort);
        $children = [];
        foreach ($childData as $child) {
            //$children[] = DcHelpers::mapElasticEntity($child);
            $children[] = Si4Helpers::getEntityListPresentation($child);
        }
        $data["children"] = $children;
        //print_r(array_keys($childData));
    }

    private function prepareDataForEntity(Request $request, $hdl, $docData, &$data) {

        $searchResultsSort = Si4Util::pathArg($docData, "_source/data/si4tech/searchResultsSort", null);
        $sort = ElasticHelpers::elasticSortFromString($searchResultsSort);

        // Find children
        $childData = ElasticHelpers::searchChildren($hdl, 0, SI4_DEFAULT_PAGINATION_SIZE, $sort);
        $children = [];
        foreach ($childData as $child) {
            $children[] = Si4Helpers::getEntityListPresentation($child);
        }
        $data["children"] = $children;

        $data["files"] = [];
        $files = ElasticHelpers::searchMust([
            "parent" => $hdl,
            "struct_type" => "file"
        ]);

        foreach ($files as $file) {
            $fileHandleId = Si4Util::pathArg($file, "_source/handle_id");
            $fileName = Si4Util::pathArg($file, "_source/data/files/0/fileName");

            $fileSize = Si4Util::pathArg($file, "_source/data/files/0/size");
            $fileCreated = Si4Util::pathArg($file, "_source/data/files/0/createDate");

            $data["files"][] = [
                "handle_id" => $fileHandleId,
                "fileName" => $fileName,
                "url" => FileHelpers::getPreviewUrl($hdl, "file", $fileName),
                //"thumbUrl" => FileHelpers::getThumbUrl($hdl, "file", $fileName),
                "thumbUrl" => ImageHelpers::getSmallThumbUrl($hdl, $fileName),
                "mimeType" => Si4Util::pathArg($file, "_source/data/files/0/mimeType"),
                "size" => $fileSize,
                "displaySize" => DcHelpers::fileSizePresentation($fileSize),
                "createDate" => $fileCreated,
                "displayCreated" => DcHelpers::fileDatePresentation($fileCreated),
                "checksum" => Si4Util::pathArg($file, "_source/data/files/0/checksum"),
                "checksumType" => Si4Util::pathArg($file, "_source/data/files/0/checksumType"),
            ];
        }
    }

    private function prepareDataForFile(Request $request, $hdl, $docData, &$data) {
        //print_r($docData);
        $parent = Si4Util::pathArg($docData, "_source/parent", null);
        $file = Si4Util::pathArg($docData, "_source/data/files/0", null);
        $fileName = Si4Util::pathArg($file, "fileName", "");

        $size = Si4Util::pathArg($file, "size");
        $createDate = Si4Util::pathArg($file, "createDate");

        //print_r($file);
        if ($file) {
            $data["file"] = [
                "handle_id" => $hdl,
                "parent" => $parent,
                "fileName" => $fileName,
                "url" => FileHelpers::getPreviewUrl($parent, "file", $fileName),
                //"thumbUrl" => FileHelpers::getThumbUrl($parent, "file", $fileName),
                "thumbUrl" => ImageHelpers::getMainThumbUrl($parent, $fileName),
                "mimeType" => Si4Util::pathArg($file, "mimeType"),
                "size" => $size,
                "displaySize" => DcHelpers::fileSizePresentation($size),
                "createDate" => $createDate,
                "displayCreated" => DcHelpers::fileDatePresentation($createDate),
                "checksum" => Si4Util::pathArg($file, "checksum"),
                "checksumType" => Si4Util::pathArg($file, "checksumType"),
            ];
        }

        //print_r($data["file"]);
    }


    private function prepareBreadcrumbs(Request $request, $hdl, $docData, &$data) {
        //print_r($data["doc"]);

        $linkPrefix = "/details/";
        $skipHandles = [env("SI4_ELASTIC_ROOT_COLLECTION"), "menuTop", "menuBottom"];
        $breadcrumbs = [];

        // Add Parents to breadcrumbs

        $breadcrumbs[] = [
            "link" => "/",
            "text" => si4config("siteName")
        ];

        //print_r($data);

        $parentsReverse = array_reverse($data["parents"]);
        foreach ($parentsReverse as $parent) {
            if (in_array($parent["system"]["handle_id"], $skipHandles)) continue;
            $breadcrumbs[] = [
                "link" => $linkPrefix.$parent["system"]["handle_id"],
                "text" => first($parent["si4"]["title"])
            ];
        }

        // Add current doc to breadcrumbs
        $curDoc = [
            "link" => $linkPrefix.$data["doc"]["system"]["handle_id"],
            "text" => first($data["doc"]["si4"]["title"])
        ];
        if (!$curDoc["text"] && $data["doc"]["system"]["struct_type"] === "file")
            $curDoc["text"] = Si4Util::pathArg($data["doc"], "file/fileName");

        $breadcrumbs[] = $curDoc;

        $data["breadcrumbs"] = $breadcrumbs;
        $data["html_breadcrumbs"] = Si4Helpers::breadcrumbsPresentation($breadcrumbs);

        //print_r($breadcrumbs);

    }

}