<?php
namespace App\Models\OAI;

class OAIDate {

    const GRANULARITY_SHORT = "YYYY-MM-DD";
    const GRANULARITY_LONG = "YYYY-MM-DDThh:mm:ssZ";

    private $timezoneOffsetHours = 1;

    private $time = null;
    private $name;

    private $isSetToCurrentTime = false;
    private $granularity = self::GRANULARITY_LONG;

    public function __construct($name = "date") {
        $this->name = $name;
        $this->setDateTime();
    }

    public static function fromSqlString($utcDate){
        $result = new self();
        $time = strtotime($utcDate);

        // Check if LONG granularity
        $hourMinSec = date("H:i:s", $time);
        if ($hourMinSec == "00:00:00") $result->granularity = self::GRANULARITY_SHORT;

        $result->setDateTime($time);
        return $result;
    }

    public static function fromUTCString($utcDate){
        $result = new self();
        $time = strtotime($utcDate);

        // Check if LONG granularity
        $hourMinSec = date("H:i:s", $time);
        if ($hourMinSec == "00:00:00") {
            $result->granularity = self::GRANULARITY_SHORT;
        } else {
            $result->granularity = self::GRANULARITY_LONG;
            $time -= $result->timezoneOffsetHours *3600;
        }

        $result->setDateTime($time);
        return $result;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDateTime($time = null) {
        if (!$time) $time = time();
        $this->isSetToCurrentTime = $time == time();
        $this->time = $time;
    }

    public function __toString(){
        return date("Y-m-d\\TH:i:s\\Z", $this->time);
    }

    public function toXml() {
        $xml = '<'.$this->name.'>'.$this.'</'.$this->name.'>';
        return $xml;
    }

    public function toSqlString(){
        return date("Y-m-d H:i:s", $this->time);
    }

    public function toSqlDateString(){
        return date("Y-m-d", $this->time);
    }

    public function isSetToCurrentTime() {
        return $this->isSetToCurrentTime;
    }

    public function getGranularity() {
        return $this->granularity;
    }
}