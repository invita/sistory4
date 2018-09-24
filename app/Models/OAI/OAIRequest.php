<?php
namespace App\Models\OAI;
use \Illuminate\Http\Request;

class OAIRequest {

    public $arguments;

    public $requestUrl = "";
    public $verb = "";
    public $resumptionToken;

    public $from = null;
    public $until = null;

    private $outAsJson = false;
    public function setOutAsJson($bool) {
        $this->outAsJson = $bool;
    }
    public function getOutAsJson() {
        return $this->outAsJson;
    }

    private $lRequest;
    public function getLRequest() {
        return $this->lRequest;
    }

    public static function fromIlluminateRequest(Request $lRequest) {
        $oaiRequest = new OAIRequest();
        $oaiRequest->parseIlluminateRequest($lRequest);
        $oaiRequest->validateRequestArguments();
        return $oaiRequest;
    }

    private function __construct(){
    }

    private function parseIlluminateRequest(Request $lRequest) {

        $this->lRequest = $lRequest;
        $this->requestUrl = $lRequest->getScheme()."://".$lRequest->getHttpHost().$lRequest->getPathInfo();

        switch ($lRequest->getMethod()) {
            case "GET": $argumentsSource = $_GET; break;
            case "POST": $argumentsSource = $_POST; break;
            default: die("Invalid HTTP method"); break;
        }

        $result = array();
        foreach ($argumentsSource as $argName => $argValue) {
            $result[$argName] = $argValue;
        }

        $this->arguments = $result;
        return $result;
    }

    public function validateRequestArguments() {

        // check verb
        if (!isset($this->arguments["verb"]))
            $this->error("badVerb", "Required argument verb not found");

        $this->verb = $this->arguments["verb"];
        $allowedVerbList = OAIHelper::getAllowedVerbList();
        if (!in_array($this->verb, $allowedVerbList)) $this->error("badVerb", "Ilegal verb: ".$this->verb);

        // check arguments validity
        $possibleArguments = OAIHelper::getAllowedArgumentList();
        foreach ($this->arguments as $argName => $argValue) {
            if (!in_array($argName, $possibleArguments)) $this->error("badArgument", $argName);
            $result[$argName] = $argValue;
        }

        // check metadataPrefix validity
        if ($this->verb == "GetRecord" && !isset($this->arguments["metadataPrefix"])) {
            $this->error("badArgument", "metadataPrefix not specified");
        }
        if ($this->verb == "ListRecords" &&
                !isset($this->arguments["metadataPrefix"]) &&
                !isset($this->arguments["resumptionToken"])) {
            $this->error("badArgument", "metadataPrefix not specified");
        }

        if (isset($this->arguments["metadataPrefix"])){
            $mdPrefixData = OAIHelper::getMetadataPrefix($this->arguments["metadataPrefix"]);
            if (!$mdPrefixData) {
                $this->arguments["metadataPrefix"] = null;
                $this->error("cannotDisseminateFormat", "Bad metadataPrefix");
            }
        }

        // check identifier syntax
        if (in_array($this->verb, array("GetRecord")) && !isset($this->arguments["identifier"])) {
            $this->error("badArgument", "identifier not specified");
        }

        if (isset($this->arguments["identifier"])){
            if (substr_count($this->arguments["identifier"], ":") != 2) $idExpl = false;
            else $idExpl = explode(":", $this->arguments["identifier"]);
            if (!$idExpl || $idExpl[0] != "SISTORY" || $idExpl[1] != "ID" || !is_numeric($idExpl[2])) {
                $this->arguments["identifier"] = null;
                $this->error("badArgument", "Identifier syntax is invalid");
            }
        }

        if ($this->isListRequest()) {

            // resumptionToken
            if (isset($this->arguments["resumptionToken"])){
                if (isset($this->arguments["from"]) || isset($this->arguments["until"])) {
                    $this->error("badArgument", "resumptionToken is an exclusive argument");
                }

                $this->resumptionToken = OAIResumptionToken::findToken($this->arguments["resumptionToken"]);
                if (!$this->resumptionToken){
                    $this->error("badArgument", "resumptionToken is invalid");
                }
            } else {
                $this->resumptionToken = OAIResumptionToken::generate($this);
            }

            // batchSize
            if (isset($this->arguments["batchSize"]) &&
                is_integer($this->arguments["batchSize"]) &&
                $this->arguments["batchSize"] > 0)
                $this->resumptionToken->setBatchSize($this->arguments["batchSize"]);

            // from, until
            if (isset($this->arguments["from"])){
                $this->from = OAIDate::fromUTCString($this->arguments["from"]);
                if ($this->from->isSetToCurrentTime())
                    $this->error("badArgument", "from date syntax is invalid");
            }

            if (isset($this->arguments["until"])) {
                $this->until = OAIDate::fromUTCString($this->arguments["until"]);
                if ($this->until->isSetToCurrentTime())
                    $this->error("badArgument", "until date syntax is invalid");
            }

            if (isset($this->arguments["from"]) && isset($this->arguments["until"])){
                // check granularity consistency
                if (strlen($this->arguments["from"]) != strlen($this->arguments["until"]))
                    $this->error("badArgument", "from and until date granularities are not consistent");
            }
        }
    }

    public function isListRequest(){
        return in_array($this->verb, OAIHelper::getListRequestVerbs());
    }

    public function error($oaiErrorCode, $message) {
        OAIHelper::error($this, $oaiErrorCode, $message);
    }

    public function toXml() {
        $xmlEl = new OAIXmlElement("request");

        if ($this->verb) $xmlEl->setAttribute("verb", $this->verb);

        if (isset($this->arguments["metadataPrefix"])) $xmlEl->setAttribute("metadataPrefix", $this->arguments["metadataPrefix"]);
        if (isset($this->arguments["identifier"])) $xmlEl->setAttribute("identifier", $this->arguments["identifier"]);
        if (isset($this->arguments["resumptionToken"])) $xmlEl->setAttribute("resumptionToken", $this->arguments["resumptionToken"]);

        return $xmlEl->toXml();
    }

}