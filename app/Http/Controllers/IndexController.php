<?php
namespace App\Http\Controllers;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
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
            $result[] = DcHelpers::mapElasticEntity($doc);
        }

        return $result;
    }
}