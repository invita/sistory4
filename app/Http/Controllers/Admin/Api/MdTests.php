<?php
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Si4\MetsToSi4;

class MdTests extends Controller
{

    public function xmlToSi4Test(Request $request)
    {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $xml = $postJson["xml"];

        $metsToSi4 = new MetsToSi4($xml);
        $result = $metsToSi4->run();



        return ["status" => true, "error" =>  null, "data" => $result];
    }



}