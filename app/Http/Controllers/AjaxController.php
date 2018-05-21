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
        }

        $result = ["status" => false, "error" => "Bad call ".$name];
        return json_encode($result);
    }


    private static $skipChars = [",", "\\."];
    private function removeSkipCharacters($str) {
        foreach (self::$skipChars as $skipChar) {
            $str = mb_ereg_replace($skipChar, "", $str);
        }
        return $str;
    }

    private function strStartsWith($str, $startPart) {
        return mb_substr($str, 0, mb_strlen($startPart)) === $startPart;
    }
    private function strSameRoot($str1, $str2) {
        $len1 = mb_strlen($str1);
        $len2 = mb_strlen($str2);
        if (!$len1 || !$len2) return false;

        $shorterLen = min($len1, $len2);
        return mb_substr($str1, 0, $shorterLen) === mb_substr($str2, 0, $shorterLen);
    }


    private function searchSuggest(Request $request) {
        $term = $request->query("term", "");
        $termLower = mb_strtolower($term);

        //$termWords = explode(" ", $term);
        //$potTermsCreator = [];


        // Find potential creators

        $creatorElasticData = ElasticHelpers::suggestCreators($termLower, 10);
        $creatorAssocData = ElasticHelpers::elasticResultToAssocArray($creatorElasticData);

        $creatorResults = [];
        foreach ($creatorAssocData as $doc) {
            $creatorDc = DcHelpers::dcTextArray(Si4Util::pathArg($doc, "_source/data/dmd/dc/creator", []));
            foreach ($creatorDc as $s) {
                $creatorClean = mb_strtolower($this->removeSkipCharacters($s));
                //echo "creatorClean ".$creatorClean."\n";
                $creatorSplit = explode(" ", $creatorClean);
                $splitCount = count($creatorSplit);

                // Create creator firstName/lastName/(middleName) combinations
                $creatorCombs = [];
                if ($splitCount == 2) {
                    $creatorCombs[] = $creatorSplit[0]." ".$creatorSplit[1];
                    $creatorCombs[] = $creatorSplit[1]." ".$creatorSplit[0];
                } else if ($splitCount == 3) {
                    $creatorCombs[] = $creatorSplit[0]." ".$creatorSplit[1]." ".$creatorSplit[2];
                    $creatorCombs[] = $creatorSplit[0]." ".$creatorSplit[2]." ".$creatorSplit[1];
                    $creatorCombs[] = $creatorSplit[1]." ".$creatorSplit[0]." ".$creatorSplit[2];
                    $creatorCombs[] = $creatorSplit[1]." ".$creatorSplit[2]." ".$creatorSplit[0];
                    $creatorCombs[] = $creatorSplit[2]." ".$creatorSplit[0]." ".$creatorSplit[1];
                    $creatorCombs[] = $creatorSplit[2]." ".$creatorSplit[1]." ".$creatorSplit[0];

                } else {
                    $creatorCombs[] = $creatorClean;
                }

                foreach ($creatorCombs as $creatorComb) {
                    if (self::strSameRoot($creatorComb, $termLower)) {
                        if (!isset($creatorResults[$creatorComb]))
                            $creatorResults[$creatorComb] = 1;
                        else
                            $creatorResults[$creatorComb] += 1;

                    }
                }

            }
        }

        $onlyOneCreatorAndFullyMatched =
            count($creatorResults) == 1 &&
            self::strStartsWith($termLower, array_keys($creatorResults)[0]);


        if (!$onlyOneCreatorAndFullyMatched && count($creatorResults)) {

            return json_encode(array_keys($creatorResults));

        } else {

            // Find potential titles

            $oneCreator = count($creatorResults) ? array_keys($creatorResults)[0] : "";
            $termRest = trim(mb_substr($termLower, mb_strlen($oneCreator)));

            $titleElasticData = ElasticHelpers::suggestTitlesForCreator($oneCreator, $termRest, 10);
            $titleAssocData = ElasticHelpers::elasticResultToAssocArray($titleElasticData);

            $titleResults = [];
            foreach ($titleAssocData as $doc) {
                $titleDc = DcHelpers::dcTextArray(Si4Util::pathArg($doc, "_source/data/dmd/dc/title", []));
                foreach ($titleDc as $s) {
                    $titleClean = mb_strtolower($this->removeSkipCharacters($s));
                    $oneCreatorWithTitle = $oneCreator ? $oneCreator." ".$titleClean : $titleClean;
                    if (!$termRest || self::strSameRoot($titleClean, $termRest)) {
                        if (!isset($titleResults[$oneCreatorWithTitle]))
                            $titleResults[$oneCreatorWithTitle] = 1;
                        else
                            $titleResults[$oneCreatorWithTitle] += 1;

                    }
                }
            }

            return json_encode(array_keys($titleResults));

        }

    }


}