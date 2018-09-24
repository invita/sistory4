<?php
namespace App\Models\OAI;

use App\Helpers\Si4Util;
use App\Models\Entity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\OAI\OAIResumptionToken
 *
 * @property int $id
 * @property string $token
 * @property \Carbon\Carbon $valid_since
 * @property \Carbon\Carbon $valid_to
 * @property \Carbon\Carbon $updated_at
 * @property string $data
 * @mixin \Eloquent
 */
class OAIResumptionToken extends Model {

    protected $table = 'oai_resump_tokens';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'token',
        'valid_since',
        'valid_to',
        'data',
    ];

    private $cursor;
    public function setCursor($cursor) {
        $this->cursor = $cursor;
    }
    public function getCursor() {
        return $this->cursor;
    }
    public function incrementCursor() {
        return $this->cursor += $this->batchSize;
    }

    private $batchSize;
    public function setBatchSize($batchSize) {
        $this->batchSize = $batchSize;
    }
    public function getBatchSize() {
        return $this->batchSize;
    }

    private $completeListSize;
    public function setCompleteListSize($completeListSize) {
        $this->completeListSize = $completeListSize;
    }
    public function getCompleteListSize() {
        return $this->completeListSize;
    }


    public function __construct() {
        parent::__construct();
        $this->cursor = 0;
        $this->batchSize = OAIHelper::$defaultBatchSize;
        $this->completeListSize = 0;
    }

    public static function generate() {

        $salt = "si4OAI";
        $tokenBase = $salt.microtime();
        $length = 11;
        $token = substr(md5($tokenBase), 0, $length);

        $oaiResumpToken = new OAIResumptionToken();
        $oaiResumpToken->token = $token;
        $oaiResumpToken->valid_since = Carbon::now();
        $oaiResumpToken->valid_to = Carbon::now()->addHours(OAIHelper::$resumpTokenValidHours);
        $oaiResumpToken->packData();

        return $oaiResumpToken;
    }

    public static function findToken($token) {
        $oaiResumpToken = self::where(["token" => $token])->first();
        if ($oaiResumpToken) $oaiResumpToken->unpackData();
        return $oaiResumpToken;
    }

    public function save() {
        $this->packData();
        parent::save();
    }

    public function packData() {
        $resumpData = [
            "cursor" => $this->cursor,
            "batchSize" => $this->batchSize,
            "completeListSize" => $this->completeListSize,
        ];
        $this->data = json_encode($resumpData);
    }

    public function unpackData() {
        $dataArr = json_decode($this->data, true);
        $this->cursor = Si4Util::getArg($dataArr, "cursor", 0);
        $this->batchSize = Si4Util::getArg($dataArr, "batchSize", OAIHelper::$defaultBatchSize);
        $this->completeListSize = Si4Util::getArg($dataArr, "completeListSize", 0);
    }

    public function toXml(){
        return $this->toXmlElement()->toXml();
    }

    public function toXmlElement(){
        $rtElement = new OAIXmlElement("resumptionToken");
        $rtElement->setAttribute("expirationDate", $this->valid_to);
        $rtElement->setAttribute("completeListSize", $this->completeListSize);
        $rtElement->setAttribute("cursor", $this->cursor);

        // Set resumptionToken value only if the list is not complete yet:
        if (!$this->isListComplete()) $rtElement->setValue($this->token);

        return $rtElement;
    }

    public function isListComplete(){
        return $this->cursor +$this->batchSize >= $this->completeListSize;
    }

    /*

    public $cursor;
    public $completeListSize;
    public $batchSize;

    public $expirationDate;

    public $tokenId;
    public $token;

    public $requestData;

    public $loadError = false;

    public $isLoaded = false;

    public function __construct(){
        $this->cursor = 0;
        $this->completeListSize = 0;
        $this->expirationDate = new OAIDate();
        $this->expirationDate->setDateTime(time()+ 3600 * OAIHelper::$resumpTokenValidHours);
        $this->generateRandomToken();
        $this->batchSize = OAIHelper::$defaultBatchSize;
        $this->requestData = array();
    }

    public function save($request){
        self::cleanOverdueTokens();

        $db = Zend_Registry::get("mysql");

        if ($this->isListComplete()) {
            $query = "DELETE FROM OAI_RESUMP_TOKEN WHERE TOKEN = {$db->quote($this->token)}";
            $db->query($query);
            return;
        }

        if (!$this->isLoaded)
            $this->requestData["requestArgs"] = $request->arguments;

        $dataStr = $this->stringifyData();
        $validTo = $this->expirationDate->toSqlString();

        $query = "INSERT INTO OAI_RESUMP_TOKEN (ID, TOKEN, VALID_SINCE, VALID_TO, `DATA`)
                      VALUES ({$this->tokenId}, {$db->quote($this->token)}, NOW(), {$db->quote($validTo)}, {$db->quote($dataStr)})
                  ON DUPLICATE KEY UPDATE `DATA` = {$db->quote($dataStr)}";

        $db->query($query);
    }

    public function load($token, $request){
        $db = Zend_Registry::get("mysql");
        $query = "SELECT * FROM OAI_RESUMP_TOKEN WHERE TOKEN = {$db->quote($token)}";
        $tokenRow = $db->fetchRow($query);
        $this->loadError = !$tokenRow;
        if ($this->loadError) return;

        $this->tokenId = $tokenRow["ID"];
        $this->token = $tokenRow["TOKEN"];
        $this->expirationDate->setDateTime(strtotime($tokenRow["VALID_TO"]));

        $data = $tokenRow["DATA"];
        $this->unstringifyData($data);

        $this->cursor += $this->batchSize;

        if (isset($this->requestData["requestArgs"])) {
            $request->arguments = $this->requestData["requestArgs"];
            //$request->validateRequestArguments();
        }

        $this->isLoaded = true;
    }

    public function setBatchSize($newSize){
        $this->batchSize = $newSize;
        if ($this->batchSize > 1000) $this->batchSize = 1000;
    }

    public function isListComplete(){
        return $this->cursor +$this->batchSize >= $this->completeListSize;
    }

    public function toXml(){
        return $this->toXmlElement()->toXml();
    }

    public function toXmlElement(){
        $rtElement = new OAIXmlElement("resumptionToken");
        $rtElement->setAttribute("expirationDate", $this->expirationDate);
        $rtElement->setAttribute("completeListSize", $this->completeListSize);
        $rtElement->setAttribute("cursor", $this->cursor);

        // Set resumptionToken value only if the list is not complete yet:
        if (!$this->isListComplete()) $rtElement->setValue($this->token);

        return $rtElement;
    }

    private function stringifyData(){
        $this->requestData["cursor"] = $this->cursor;
        $this->requestData["batchSize"] = $this->batchSize;
        $this->requestData["completeListSize"] = $this->completeListSize;
        return json_encode($this->requestData);
    }

    private function unstringifyData($dataStr){
        $this->requestData = json_decode($dataStr, true);

        if (isset($this->requestData["cursor"]))
            $this->cursor = $this->requestData["cursor"];
        if (isset($this->requestData["batchSize"]))
            $this->batchSize = $this->requestData["batchSize"];
        if (isset($this->requestData["completeListSize"]))
            $this->completeListSize = $this->requestData["completeListSize"];

    }

    private function generateRandomToken(){
        $db = Zend_Registry::get("mysql");
        $query = "SELECT COALESCE(MAX(ID), 0) FROM OAI_RESUMP_TOKEN";
        $lastTokenId = $db->fetchOne($query);

        $salt = "SISTORY-OAI-RESUMPTION-TOKEN-";
        $length = 11;

        $this->tokenId = $lastTokenId +1;
        $this->token = substr(md5($salt.$this->tokenId), 0, $length);
        return $this->token;
    }


    // Static

    public static function cleanOverdueTokens(){
        $db = Zend_Registry::get("mysql");
        $query = "DELETE FROM OAI_RESUMP_TOKEN WHERE VALID_TO < NOW()";
        $db->query($query);
    }
    */


}