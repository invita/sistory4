<?php

namespace App\Models\Migration;

class MigUtil
{
    public static $migDbName = "sistory3_mig";

    public static $migImportUserId = "user.11";
    public static $migImportUserName = "Pančur, Andrej";

    public static function wrapCDATA($val) {
        if (is_string($val)) {
            if (strpos($val, "\n") !== false ||
                strpos($val, "\"") !== false ||
                strpos($val, "<") !== false ||
                strpos($val, ">") !== false)
            {
                return "<![CDATA[".$val."]]>";
            }
        }
        return $val;
    }

}