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

    private static function strStartsWith($str, $startPart) {
        return mb_substr($str, 0, mb_strlen($startPart)) === $startPart;
    }

    private static function countMatchingChars($str1, $str2) {
        $len1 = mb_strlen($str1);
        $len2 = mb_strlen($str2);
        if (!$len1 || !$len2) return 0;

        $shorterLen = min($len1, $len2);
        for ($i = 0; $i < $shorterLen; $i++) {
            if ($str1[$i] != $str2[$i]) return $i;
        }
        return $shorterLen;
    }

    // Find shortest best matching string in array
    // If more strings match with the same number of starting characters, shorter is chosen.
    private static function findShortestMatching($str, $array) {

        $bestScore = 0;
        $bestPotentials = [];

        foreach ($array as $potential) {
            $curScore = self::countMatchingChars($str, $potential);
            if ($curScore == $bestScore) {
                $bestPotentials[] = $potential;
            } else if ($curScore > $bestScore) {
                $bestScore = $curScore;
                $bestPotentials = [$potential];
            }
        }

        if (!count($bestPotentials)) return "";
        if (count($bestPotentials) == 1) return $bestPotentials[0];

        $shortest = $bestPotentials[0];
        foreach ($bestPotentials as $potential) {
            if (mb_strlen($potential) < mb_strlen($shortest))
                $shortest = $potential;
        }
        return $shortest;
    }

    private function strSameRoot($str1, $str2) {
        $len1 = mb_strlen($str1);
        $len2 = mb_strlen($str2);
        if (!$len1 || !$len2) return false;

        $shorterLen = min($len1, $len2);
        return mb_substr($str1, 0, $shorterLen) === mb_substr($str2, 0, $shorterLen);
    }

    private function searchSuggest(Request $request) {
        $searchType = $request->query("st", "all");
        if ($searchType == "fullText") {
            return $this->searchSuggestFullText($request);
        } else {
            return $this->searchSuggestMetadata($request);
        }
    }

    private function searchSuggestMetadata(Request $request) {
        $term = $request->query("term", "");
        $termLower = mb_strtolower($term);
        $st = $request->query("st", "all");
        $parent = $request->query("parent", null);
        //echo "termLower: '".$termLower."'\n";

        //$termWords = explode(" ", $term);
        //$potTermsCreator = [];


        // Find potential creators

        $creatorElasticData = ElasticHelpers::suggestCreators($termLower, $st, $parent);
        $creatorAssocData = ElasticHelpers::elasticResultToAssocArray($creatorElasticData);

        $creatorResults = [];
        foreach ($creatorAssocData as $doc) {
            //$creators = DcHelpers::dcTextArray(Si4Util::pathArg($doc, "_source/data/si4/creator", []));
            $creators = Si4Util::pathArg($doc, "_source/data/si4/creator", []);
            foreach ($creators as $creator) {
                $c = Si4Util::getArg($creator, "value", "");
                $creatorClean = mb_strtolower($this->removeSkipCharacters($c));
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

        $oneCreator = self::findShortestMatching($termLower, array_keys($creatorResults));

        //echo "creatorResults: ".print_r(array_keys($creatorResults), true)."\n";
        //echo "oneCreator: ".$oneCreator."\n";

        $onlyFewCreatorsAndFullyMatched = count($creatorResults) <= 3 && $oneCreator;

        if (!$onlyFewCreatorsAndFullyMatched && count($creatorResults)) {

            return json_encode(array_keys($creatorResults));

        } else {

            // Find potential titles

            // If more than one (a few) creators possible, list those with higher length
            $titleResults = [];
            if (count($creatorResults) > 1) {
                foreach (array_keys($creatorResults) as $c) {
                    if (mb_strlen($c) >= mb_strlen($termLower))
                        $titleResults[$c] = 1;
                }
            }

            $termRest = trim(mb_substr($termLower, mb_strlen($oneCreator)));
            //echo "termRest {$termRest}\n";

            $titleElasticData = ElasticHelpers::suggestTitlesForCreator($oneCreator, $termRest, $st, $parent, 10);
            $titleAssocData = ElasticHelpers::elasticResultToAssocArray($titleElasticData);

            foreach ($titleAssocData as $doc) {
                $titles = Si4Util::pathArg($doc, "_source/data/si4/title", []);
                foreach ($titles as $title) {
                    $t = Si4Util::getArg($title, "value", "");
                    $titleClean = mb_strtolower($this->removeSkipCharacters($t));
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


    private function searchSuggestFullText(Request $request) {
        $term = $request->query("term", "");
        $termLower = mb_strtolower($term);
        $maxResults = 10;
        $maxResultLength = 50;
        $results = [];

        if (strlen($term) > 2) {

            // Find matching files
            $elasticData = ElasticHelpers::searchString($termLower."*", "fullText", "", 0, 10);
            $assocData = ElasticHelpers::elasticResultToAssocArray($elasticData);
            //echo "assocData count ".count($assocData)."\n";

            foreach ($assocData as $elasticEntity) {
                $fullText = Si4Util::pathArg($elasticEntity, "_source/data/files/0/fullText", "");
                $startPos = stripos($fullText, $termLower);

                for ($i = 0; $i < 3; $i++) {
                    if ($startPos === false) break;

                    //echo substr($fullText, $startPos, 30)."... ";
                    $spacePos = strpos($fullText, " ", $startPos+strlen($term));
                    $spacePos2 = strpos($fullText, " ", $spacePos +1);
                    if (!$spacePos2) $spacePos2 = $spacePos;
                    //echo $startPos.", ".$spacePos.", ".$spacePos2."\n";
                    if (!$spacePos2) continue;

                    $len = $spacePos2 - $startPos;
                    if ($len > $maxResultLength) $len = $maxResultLength;
                    $result = substr($fullText, $startPos, $len);
                    $results[mb_strtolower($result)] = 1;
                    //echo "add result ".mb_strtolower($result)."\n";
                    //print_r($results);

                    if (count(array_keys($results)) >= $maxResults) break; // enough results

                    $startPos = stripos($fullText, $termLower, $startPos + strlen($term));
                }

                if (count(array_keys($results)) >= $maxResults) break; // enough results
            }
        }

        //print_r($assocData);
        $response = json_encode(array_keys($results));
        return $response ? $response : "[]";
    }


}