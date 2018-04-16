<?php
namespace App\Http\Controllers;

use App\Helpers\DcHelpers;
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
            case "searchSuggest_new": return $this->searchSuggest2($request);
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
        $this->unfinishedWord = mb_strtolower(array_pop($termWords));
        $this->finishedWord = mb_strtolower(join(" ", $termWords));

        $this->bagOfWords = [];

        foreach ($assocData as $doc) {
            $title = DcHelpers::dcTextArray(Si4Util::pathArg($doc, "_source/data/dmd/dc/title", []));
            foreach ($title as $s) $this->addWordsIntoBag($s);

            $creator = DcHelpers::dcTextArray(Si4Util::pathArg($doc, "_source/data/dmd/dc/creator", []));
            foreach ($creator as $s) $this->addWordsIntoBag($s);

            $date = DcHelpers::dcTextArray(Si4Util::pathArg($doc, "_source/data/dmd/dc/date", []));
            foreach ($date as $s) $this->addWordsIntoBag($s);
        }

        $data = $this->bagOfWords;

        // Append finishedWord to given suggestions
        if ($this->finishedWord) {
            $data = array_map(function($w) {
                return $this->finishedWord." ".$w;
            }, $data);
        }

        return json_encode($data);
    }

    private function addWordsIntoBag($s) {
        $s = mb_strtolower($s);
        $words = explode(" ", $s);
        foreach ($words as $word) {
            if (substr($word, 0, strlen($this->unfinishedWord)) == $this->unfinishedWord) {
                if (!in_array($word, $this->bagOfWords))
                    $this->bagOfWords[] = $word;
            }
        }
    }




    private function searchSuggest2(Request $request) {

        $term = $request->query("term", "");
        $elasticData = ElasticHelpers::suggestEntites($term, 50);
        $assocData = ElasticHelpers::elasticResultToAssocArray($elasticData);

        $titleSuggestions = [];
        $creatorSuggestions = [];
        $dateSuggestions = [];

        $termLower = mb_strtolower($term);
        $termWords = explode(" ", $termLower);

        /*
        $this->unfinishedWord = mb_strtolower(array_pop($termWords));
        $this->finishedWord = mb_strtolower(join(" ", $termWords));
        //$this->bagOfWords = [];
        */

        $creators = [];
        $titles = [];
        $dates = [];

        $termMatches = function($termWords, $dcWords) {
            foreach ($dcWords as $dcWord) {
                foreach ($termWords as $termWord) {
                    if (substr($dcWord, 0, strlen($termWord)) == $termWord) {
                        return true;
                    }
                }
            }
            return false;
        };

        $result = [];

        foreach ($assocData as $doc) {
            $creatorData = DcHelpers::dcTextArray(Si4Util::pathArg($doc, "_source/data/dmd/dc/creator", []));
            foreach ($creatorData as $creator) {
                $creator = mb_strtolower($creator);
                $creatorWords = explode(" ", $creator);
                $creatorMatches = $termMatches($termWords, $creatorWords);

                if ($creatorMatches) {
                    $result[] = $creator;
                }

                /*
                foreach ($creatorWords as $creatorWord) {
                    //foreach ($termWords as )
                }
                */
            }

            /*
            $title = DcHelpers::dcTextArray(Si4Util::pathArg($doc, "_source/data/dmd/dc/title", []));
            foreach ($title as $s) $titles[] = explode(" ", $s);

            $date = DcHelpers::dcTextArray(Si4Util::pathArg($doc, "_source/data/dmd/dc/date", []));
            foreach ($date as $s) $dates[$s];
            */
        }


        /*
        $data = $this->bagOfWords;

        // Append finishedWord to given suggestions
        if ($this->finishedWord) {
            $data = array_map(function($w) {
                return $this->finishedWord." ".$w;
            }, $data);
        }
        */

        return json_encode($result);
    }



}