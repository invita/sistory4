<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MappingGroup
 *
 * @property string $name
 * @property string $base_xpath
 * @property string $description
 * @property string $data
 * @mixin \Eloquent
 */
class MappingGroup extends Model
{

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'base_xpath',
        'description',
        'data',
    ];

    private static $mappingGroupsArray = null;
    public static function getMappingGroups() {
        if (!self::$mappingGroupsArray) {
            self::$mappingGroupsArray = [];
            $mappingGroups = self::all();
            foreach ($mappingGroups as $mappingGroup) {
                self::$mappingGroupsArray[$mappingGroup->name] = $mappingGroup->toArray();
                $mappingFields = MappingField::getMappingFieldsForGroup($mappingGroup->id);
                self::$mappingGroupsArray[$mappingGroup->name]["fields"] = $mappingFields;
            }
        }
        return self::$mappingGroupsArray;

    }
}