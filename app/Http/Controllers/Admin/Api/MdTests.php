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

    public function xmlXPathTest(Request $request) {
        $postJson = json_decode(file_get_contents("php://input"), true);
        $xml = $postJson["xml"];

        $xpath = $postJson["xpath"];

        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $domXPath = new \DOMXPath($doc);

        // Find XML namespaces with a sexy regex
        $nsMatches = [];
        preg_match_all("/xmlns:(.*?)=\\\"(.*?)\\\"/", $xml, $nsMatches);
        //print_r($nsMatches);

        if (isset($nsMatches[1]) && $nsMatches[1]) {
            foreach ($nsMatches[1] as $idx => $nsName) {
                $domXPath->registerNamespace($nsMatches[1][$idx], $nsMatches[2][$idx]);
            }
        }

        $xpathResult = $domXPath->evaluate($xpath);
        $result = is_string($xpathResult) ? $xpathResult : preg_replace("/[\\s]/", "", print_r($xpathResult, true));

        return ["status" => true, "error" =>  null, "data" => $result];
    }

}