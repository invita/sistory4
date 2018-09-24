<?php
namespace App\Http\Controllers;

use App\Models\OAI\OAIProcessor;
use App\Models\OAI\OAIRequest;
use App\Models\OAI\OAIError;
use \Illuminate\Http\Request;

class OaiController extends FrontendController
{
    public function index(Request $request) {

        try {
            // read OAI PMH request
            $request = OAIRequest::fromIlluminateRequest($request);

            // process
            $processor = new OAIProcessor($request);
            $response = $processor->process();
            $responseXml = $response->output();

        } catch (OAIError $oaiError) {
            $responseXml = $oaiError->getOAIResponse();
        }

        return response($responseXml, 200)->header('Content-Type', 'text/xml');
    }
}