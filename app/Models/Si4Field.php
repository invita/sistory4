<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Si4Field
 *
 * @property string $field_name
 * @property string $translate_key
 * @property boolean $has_language
 * @property boolean $show_all_languages
 * @property boolean $inline
 * @property string $inline_separator
 * @property boolean $display_frontend
 * @mixin \Eloquent
 */
class Si4Field extends Model
{
    protected $table = 'si4_fields';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'field_name',
        'translate_key',
        'has_language',
        'show_all_languages',
        'inline',
        'inline_separator',
        'display_frontend',
    ];

    // Assoc array of Si4Field
    private static $si4Fields = null;
    public static function getSi4Fields() {
        if (!self::$si4Fields) {
            self::$si4Fields = [];
            $si4fields = self::all();
            foreach ($si4fields as $si4field) {
                self::$si4Fields[$si4field->field_name] = $si4field;
            }
        }
        return self::$si4Fields;
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