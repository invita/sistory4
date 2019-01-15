<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OaiGroup
 *
 * @property string $name
 * @property string $behaviour
 * @mixin \Eloquent
 */
class OaiGroup extends Model
{

    protected $table = 'oai_groups';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
    ];

    private static $oaiGroupsArray = null;
    public static function getOaiGroups() {
        if (!self::$oaiGroupsArray) {
            self::$oaiGroupsArray = [];
            $oaiGroups = self::all();
            foreach ($oaiGroups as $oaiGroup) {
                self::$oaiGroupsArray[$oaiGroup->name] = $oaiGroup->toArray();

                $oaiFields = OaiField::getOaiFieldsForGroup($oaiGroup->id);
                self::$oaiGroupsArray[$oaiGroup->name]["fields"] = $oaiFields;
            }
        }
        return self::$oaiGroupsArray;

    }
}