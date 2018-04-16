<?php
namespace App\Http\Controllers;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\EntitySelect;
use App\Helpers\Si4Util;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FrontendController extends Controller
{
    public function layoutData(Request $request) {
        $layoutData = [];

        // Top menu HTML
        $layoutData["topMenuHtml"] = $this->prepareTopMenuHtml();

        // Bottom menu HTML
        $layoutData["bottomMenuHtml"] = $this->prepareBottomMenuHtml();

        return $layoutData;
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
        $itemResult .= $tabs. "\t\t<div data-handle=\"".$menuItem["handle_id"]."\">". $menuItem["title"] ."</div>\n";
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
        $itemResult .= $tabs. "\t\t<a href=\"/details/".$menuItem["handle_id"]."\">". $menuItem["title"] ."</a>\n";
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

}