<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Translation
 *
 * @property string $language
 * @property string $module
 * @property string $key
 * @property string $value
 * @mixin \Eloquent
 */
class Translation extends Model
{
    protected $table = 'translations';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'language',
        'module',
        'key',
        'value',
    ];

    // Assoc array of Translation
    private static $dbTranslations = null;
    public static function getDbTranslations($module, $language) {
        if (!self::$dbTranslations) self::$dbTranslations = [];
        if (!isset(self::$dbTranslations[$module])) self::$dbTranslations[$module] = [];
        if (!isset(self::$dbTranslations[$module][$language])) {
            self::$dbTranslations[$module][$language] = self::query()
                ->where('module', '=', $module)
                ->where('language', '=', $language)
                ->orderBy("key")
                ->get();
        }
        return self::$dbTranslations[$module][$language];
    }

    // Assoc array of array (each Si4Field item converted to array)
    private static $si4FieldsArray = null;
    public static function getSi4FieldsArray() {
        if (!self::$si4FieldsArray) {
            self::$si4FieldsArray = [];
            $si4fields = self::all();
            foreach ($si4fields as $si4field) {
                self::$si4FieldsArray[$si4field->field_name] = $si4field->toArray();
            }
        }
        return self::$si4FieldsArray;
    }
}