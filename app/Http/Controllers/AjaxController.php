<?php
namespace App\Http\Controllers;

use App\Helpers\ElasticHelpers;
use App\Helpers\Si4Util;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AjaxController extends Controller
{
    public function index(Request $request, $name = null) {

        switch($name) {
            case "searchSuggest": return $this->searchSuggest($request);
        }

        $result = ["status" => false, "error" => "Bad call ".$name];
        return json_encode($result);
    }

    private $bagOfWords;
    private $unfinishedWord;
    private $finishedWord;
    private function searchSuggest(Request $request) {

        $term = $request->query("term", "");
        $elasticData = ElasticHelpers::suggestEntites($term, 50);
        $assocData = ElasticHelpers::elasticResultToAssocArray($elasticData);

        $termWords = explode(" ", $term);
        $this->unfinishedWord = array_pop($termWords);
        $this->finishedWord = join(" ", $termWords);

        $this->bagOfWords = [];

        foreach ($assocData as $doc) {
            $title = Si4Util::pathArg($doc, "_source/data/dmd/dc/title", []);
            foreach ($title as $s) $this->addWordsIntoBag($s);

            $creator = Si4Util::pathArg($doc, "_source/data/dmd/dc/creator", []);
            foreach ($creator as $s) $this->addWordsIntoBag($s);

            $date = Si4Util::pathArg($doc, "_source/data/dmd/dc/date", []);
            foreach ($date as $s) $this->addWordsIntoBag($s);
        }

        $data = $this->bagOfWords;
        if ($this->finishedWord)
            $data = array_map(function($w) { return $this->finishedWord." ".$w; }, $data);

        return json_encode($data);

        /*
        $result = [
            "status" => true,
            "data" => $data,
        ];
        return json_encode($result);
        */
    }

    private function addWordsIntoBag($s) {
        $words = explode(" ", $s);
        foreach ($words as $word) {
            if (substr($word, 0, strlen($this->unfinishedWord)) == $this->unfinishedWord) {
                if (!in_array($word, $this->bagOfWords))
                    $this->bagOfWords[] = $word;
            }
        }
    }
}