<?php
namespace App\Models\OAI;

class OAIError extends \Exception {

    private $oaiRequest;
    private $oaiErrorCode;

    public function __construct($oaiRequest, $oaiErrorCode, $message) {
        parent::__construct($message, 1);
        $this->oaiErrorCode = $oaiErrorCode;
    }

    public function getOAIErrorCode() {
        return $this->oaiErrorCode;
    }
    public function getOAIRequest() {
        return $this->oaiRequest;
    }

    public function getOAIResponse() {
        $response = new OAIResponse($this->oaiRequest);
        $response->setError($this->oaiErrorCode, $this->message);
        return $response->output();
        //return $this->oaiRequest;
    }
}