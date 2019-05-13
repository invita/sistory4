<?php
namespace App\Http\Controllers;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\EntitySelect;
use App\Helpers\Enums;
use App\Helpers\Si4Util;
use App\Http\Middleware\SessionLanguage;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FrontendController extends Controller
{
    public function layoutData(Request $request) {

        $layoutData = [];

        // Search parameters remain
        $layoutData["lang"] = SessionLanguage::current(); // Selected language
        $layoutData["q"] = $request->query("q", ""); // Query string
        $layoutData["st"] = $request->query("st", "all"); // Search type
        $layoutData["hdl"] = $request->query("hdl", ""); // Handle filter
        $layoutData["allowInsideSearch"] = false;
        $layoutData["hdlTitle"] = "";

        // Available search types
        $layoutData["searchTypes"] = $this->prepareSearchTypes();

        // Available search tabs
        $layoutData["searchTabs"] = $this->prepareSearchTabs();

        // Available langauges
        $layoutData["langauges"] = $this->prepareLanguages();

        // Top menu HTML
        $layoutData["topMenuHtml"] = $this->prepareTopMenuHtml();

        // Bottom menu HTML
        $layoutData["bottomMenuHtml"] = $this->prepareBottomMenuHtml();

        // Javascript data
        $layoutData["jsData"] = $this->prepareJavascriptData();

        return $layoutData;
    }


    // Search types
    private function prepareSearchTypes() {
        return ElasticHelpers::$searchTypes;
    }

    // Search tabs
    private function prepareSearchTabs() {
        return [
            [
                "title" => __("fe.searchTab_pubs"),
                "link" => "http://www.sistory.si/",
                "active" => true
            ],
            [
                "title" => __("fe.searchTab_zv1"),
                "link" => "http://zv1.sistory.si/?lang=sl",
                "active" => false
            ],
            [
                "title" => __("fe.searchTab_zv2"),
                "link" => "http://www.sistory.si/zrtve",
                "active" => false
            ],
            [
                "title" => __("fe.searchTab_pops"),
                "link" => "http://www.sistory.si/popis",
                "active" => false
            ],
            [
                "title" => __("fe.searchTab_zic"),
                "link" => "http://www.sistory.si/zic",
                "active" => false
            ],
        ];
    }

    // Languages
    private function prepareLanguages() {
        $result = [];
        foreach (Enums::$feLanguages as $lang) {
            $result[$lang] = __("fe.lang_".$lang);
        }
        return $result;
    }

    // *** TopMenu ***

    // Find topMenu collections from elastic and concatenate them in a neat web manner
    private function prepareTopMenuHtml() {
        $topMenu = EntitySelect::selectTopMenu();
        //print_r($topMenu);

        $topMenuHtml = "<ul id=\"topMenu\">\n";
        foreach ($topMenu as $menuItem) {
            $topMenuHtml .= $this->topMenuRecurseChildren($menuItem);
        }
        $topMenuHtml .= "</ul>\n";
        return $topMenuHtml;
    }

    // Recurse through topMenu items and children to concatenate a proper HTML for web presentation
    private function topMenuRecurseChildren($menuItem, $level = 0) {
        $tabs = str_repeat("\t", $level *2);
        $itemResult = $tabs. "\t<li>\n";
        $itemResult .= $tabs. "\t\t<div data-handle=\"".$menuItem["handle_id"]."\" data-url=\"".$menuItem["url"]."\">". $menuItem["title"] ."</div>\n";
        if (count($menuItem["children"])) {
            $itemResult .= $tabs. "\t\t<ul>\n";
            foreach($menuItem["children"] as $childItem) {
                $itemResult .= $this->topMenuRecurseChildren($childItem, $level +1);
            }
            $itemResult .= $tabs. "\t\t</ul>\n";
        }
        $itemResult .= $tabs. "\t</li>\n";
        return $itemResult;
    }


    // *** BottomMenu ***

    // Find bottomMenu collections from elastic and concatenate them in a neat web manner
    private function prepareBottomMenuHtml() {
        $bottomMenu = EntitySelect::selectBottomMenu();
        //print_r($bottomMenu);

        $bottomMenuHtml = "<ul id=\"bottomMenu\">\n";
        foreach ($bottomMenu as $menuItem) {
            $bottomMenuHtml .= $this->bottomMenuRecurseChildren($menuItem);
        }
        $bottomMenuHtml .= "</ul>\n";
        return $bottomMenuHtml;
    }

    // Recurse through bottomMenu items and children to concatenate a proper HTML for web presentation
    private function bottomMenuRecurseChildren($menuItem, $level = 0) {
        $tabs = str_repeat("\t", $level *2);
        $itemResult = $tabs. "\t<li>\n";
        $itemResult .= $tabs. "\t\t<a data-handle=\"".$menuItem["handle_id"]."\" href=\"".$menuItem["url"]."\">". $menuItem["title"] ."</a>\n";
        if (count($menuItem["children"])) {
            $itemResult .= $tabs. "\t\t<ul>\n";
            foreach($menuItem["children"] as $childItem) {
                $itemResult .= $this->bottomMenuRecurseChildren($childItem, $level +1);
            }
            $itemResult .= $tabs. "\t\t</ul>\n";
        }
        $itemResult .= $tabs. "\t</li>\n";
        return $itemResult;
    }


    // Javascript data
    private function prepareJavascriptData() {

        $data = [];

        // Basic

        $data["siteName"] = si4config("siteName");

        // Advanced search - Operators enum
        $advSearch_operators = array_map(function($oper) {
            return [
                "value" => $oper,
                "text" => __("fe.advSearch_oper_".$oper)
            ];
        }, ElasticHelpers::$advancedSearchOperators);

        // Advanced search - Field names enum
        $advSearch_fieldNames = array_map(function($fieldName) {
            return [
                "value" => $fieldName,
                "text" => __("fe.advSearch_field_".$fieldName)
            ];
        }, array_keys(ElasticHelpers::getAdvancedSearchFieldsMap()));

        $data["advancedSearch"] = [
            "operators" => $advSearch_operators,
            "fieldNames" => $advSearch_fieldNames,
        ];

        return json_encode($data);
    }


    protected function preparePaginator($resultData, $context = null) {

        // Get paginator URL params
        $offset = request()->query("offset", 0);
        $size = request()->query("size", SI4_DEFAULT_PAGINATION_SIZE);
        if (!$size) $size = SI4_DEFAULT_PAGINATION_SIZE;

        // Calculate curPage and totalPages
        $totalHits = isset($resultData["totalHits"]) ? $resultData["totalHits"] : 0;
        $curPage = floor($offset / $size) +1;
        $totalPages = floor(($totalHits -1) / $size) +1;

        // Elastic took time
        $displayTook = $context != "bottom" && isset($resultData["took"]);
        $tookStr = $displayTook ? "<span class=\"took\">Query took {$resultData["took"]}ms.</span>" : "";

        // Elastic total hits
        $displayTotalHits = $context != "bottom" && isset($resultData["totalHits"]);
        $totalHitsStr = $displayTotalHits ? "<span class=\"totalHits\">Found {$totalHits} matching entities.</span>" : "";

        // Parse URL
        $requestUri = request()->getUri();
        $hostParamsExplode = explode("?", $requestUri);
        $baseLink = $hostParamsExplode[0];
        $urlParamsStr = isset($hostParamsExplode[1]) ? $hostParamsExplode[1] : "";
        $kvParams = explode("&", $urlParamsStr);
        $paramsCopy = [];
        foreach ($kvParams as $kvParam) {
            // $kvParam is of shape "key=value"
            // Copy all params except offset and size
            $startsWith = "offset="; if (substr($kvParam, 0, strlen($startsWith)) === $startsWith) continue;
            $startsWith = "size="; if (substr($kvParam, 0, strlen($startsWith)) === $startsWith) continue;
            $paramsCopy[] = $kvParam;
        }


        // Prepare links
        $firstLink = $this->paginatorMakeLink(0, $size, $baseLink, $paramsCopy);
        $backLink = $this->paginatorMakeLink(max(0, ($curPage -2) *$size), $size, $baseLink, $paramsCopy);
        $nextLink = $this->paginatorMakeLink(min(($curPage) *$size, ($totalPages -1)*$size), $size, $baseLink, $paramsCopy);;
        $lastLink = $this->paginatorMakeLink(($totalPages -1)*$size, $size, $baseLink, $paramsCopy);;

        $result = <<<HERE
            <div class="paginator">
                <a class="default first" href="{$firstLink}">&lt;&lt;</a>
                <a class="default back" href="{$backLink}">&lt;</a>
                <span>
                    <span class="default curPage">{$curPage}</span>
                    <span class="default sep">/</span>
                    <span class="maxPage">{$totalPages}</span>
                </span>
                <a class="default next" href="{$nextLink}">&gt;</a>
                <a class="default last" href="{$lastLink}">&gt;&gt;</a>
                {$totalHitsStr}
                {$tookStr}
            </div>
HERE;
        return $result;
    }

    private function paginatorMakeLink($offset, $size, $baseLink, $paramsCopy) {
        $params = array_merge($paramsCopy, ["offset=".$offset, "size=".$size]);
        return $baseLink ."?". join("&", $params);
    }

}