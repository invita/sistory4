<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BehaviourField
 *
 * @property string $behaviour_name
 * @property string $field_name
 * @property boolean $show_all_languages
 * @property boolean $inline
 * @property string $inline_separator
 * @property boolean $display_frontend
 * @mixin \Eloquent
 */
class BehaviourField extends Model
{
    protected $table = 'behaviour_fields';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'behaviour_name',
        'field_name',
        'show_all_languages',
        'inline',
        'inline_separator',
        'display_frontend',
    ];

    private static $behaviourFieldsArray = [];
    public static function getBehaviourFieldsArray($behaviour_name) {
        if (!isset(self::$behaviourFieldsArray[$behaviour_name])) {
            self::$behaviourFieldsArray[$behaviour_name] = [];
            $behaviourFields = self::query()->where(['behaviour_name' => $behaviour_name])->get();
            foreach ($behaviourFields as $behaviourField) {
                self::$behaviourFieldsArray[$behaviour_name][$behaviourField->field_name] = $behaviourField->toArray();
            }
        }
        return self::$behaviourFieldsArray[$behaviour_name];
    }

}