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
        //$layoutData["topMenu"] = EntitySelect::selectTopMenu();


        // Top menu HTML
        $topMenu = EntitySelect::selectTopMenu();
        //print_r($topMenu);

        $menuHtml = "<ul id=\"topMenu\">\n";
        foreach ($topMenu as $menuItem) {
            $menuHtml .= $this->topMenuRecurseChildren($menuItem);
        }
        $menuHtml .= "</ul>\n";
        //print_r($menuHtml);

        $layoutData["topMenuHtml"] = $menuHtml;


        return $layoutData;
    }

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

}