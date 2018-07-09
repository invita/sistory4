<?php
namespace App\Helpers;

class Timer {

    private static $timers = [];
    private static $results = [];

    public static function start($timerName = "default") {
        self::$timers[$timerName] = microtime(true);
    }
    public static function stop($timerName = "default") {
        $result = 0;
        $cur = microtime(true);
        if (isset(self::$timers[$timerName])) {
            $result = $cur - self::$timers[$timerName];
        }
        if (!isset(self::$results[$timerName])) self::$results[$timerName] = 0;
        self::$results[$timerName] += $result;
        return $result;
    }
    public static function getResult($timerName = "default") {
        if (isset(self::$results[$timerName])) {
            return self::$results[$timerName];
        }
        return 0;
    }
    public static function getResults() {
        return self::$results;
    }
}