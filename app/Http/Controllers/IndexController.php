<?php
namespace App\Http\Controllers;

use App\Helpers\Si4Helpers;
use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\Enums;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class IndexController extends FrontendController
{
    public function index(Request $request) {

        $data = [
            "layoutData" => $this->layoutData($request),
            "indexEntities" => $this->prepareIndexEntities(),
        ];

        return view('fe.index', $data);
    }

    private function prepareIndexEntities() {
        $topMenuHandle = ElasticHelpers::getTopMenuHandleId();
        $assocData = ElasticHelpers::searchChildren($topMenuHandle);
        $result = [];
        foreach ($assocData as $doc) {
            //$result[] = DcHelpers::mapElasticEntity($doc);
            $result[] = Si4Helpers::getEntityListPresentation($doc);
        }

        //print_r($result);

        return $result;
    }

    public function lang(Request $request, $lang = 'eng') {
        if (in_array($lang, Enums::$feLanguages)) {
            session(["lang" => $lang]);
        }

        if (isset($_SERVER["HTTP_REFERER"]) && isset($_SERVER["HTTP_REFERER"])) {
            return redirect($_SERVER["HTTP_REFERER"]);
        } else {
            return redirect("/");
        }
    }
}