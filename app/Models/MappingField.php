<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MappingField
 *
 * @property int $mapping_group_id
 * @property string $source_xpath
 * @property string $target_field
 * @property string $data
 * @mixin \Eloquent
 */
class MappingField extends Model
{

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'mapping_group_id',
        'source_xpath',
        'value_xpath',
        'lang_xpath',
        'target_field',
        'variables',
    ];

    private $_parsedVariables = null;
    public function getParsedVariables() {
        if (!$this->_parsedVariables) {
            $variablesParsed = json_decode($this->variables, true);
            $this->_parsedVariables = [];
            if ($variablesParsed) {
                foreach ($variablesParsed as $var) {
                    if (isset($var["name"]) && $var["name"] && isset($var["value"]) && $var["value"])
                        $this->_parsedVariables[] = $var;
                }
            }
        }
        return $this->_parsedVariables;
    }

    public function toArray() {
        $result = parent::toArray();
        $result["variables"] = $this->getParsedVariables();
        return $result;
    }

    private static $mappingFieldsArray = [];
    public static function getMappingFieldsForGroup($mappingGroupId) {
        if (!isset(self::$mappingFieldsArray[$mappingGroupId])) {
            self::$mappingFieldsArray[$mappingGroupId] = [];
            $mappingFields = self::query()->where(['mapping_group_id' => $mappingGroupId])->get();
            foreach ($mappingFields as $mappingField) {
                self::$mappingFieldsArray[$mappingGroupId][$mappingField->id] = $mappingField->toArray();
            }
        }
        return self::$mappingFieldsArray[$mappingGroupId];
    }
}