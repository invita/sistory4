<?php
namespace App\Models\OAI;

class OAIResponse {

    public $xmlOut;

    private $request = null;

    private $errorCode = "";
    private $errorMessage = "";

    private $responseDate;

    private $resultElement;

    private $resumptionToken = null;

    public function __construct($request){
        $this->request = $request;

        $this->xmlOut = new OAIXmlOutput();
        $this->responseDate = new OAIDate("responseDate");
        $this->responseDate->setDateTime();
    }

    public function output() {
        $this->xmlOut->startOutput();

        // responseDate
        $this->xmlOut->outputLine($this->responseDate->toXml());

        // request
        if ($this->request) {
            $this->xmlOut->outputLine($this->request->toXml());
        }

        if ($this->errorCode) {

            // Error
            $errorXml = '<error code="'.$this->errorCode.'">'.$this->errorMessage.'</error>';
            $this->xmlOut->outputLine($errorXml);
        } else if ($this->resultElement) {

            // Success
            if ($this->resumptionToken) {
                $rVal = trim($this->resultElement->value.$this->resumptionToken->toXml());
                $this->resultElement->setContentXml($rVal);
            }

            $this->xmlOut->outputXml($this->resultElement->toXml());
        }

        $this->xmlOut->endOutput();

        //$this->xmlOut->render();
        return $this->xmlOut->getXml();
    }

    public function setResultElement($resultElement) {
        $this->resultElement = $resultElement;
    }

    public function setResumptionToken($resumptionToken) {
        $this->resumptionToken = $resumptionToken;
    }

    public function setError($errorCode, $message) {
        $this->errorCode = $errorCode;
        $this->errorMessage = $message;
    }
}