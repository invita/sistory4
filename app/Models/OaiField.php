<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MappingField
 *
 * @property int $oai_group_id
 * @property string $name
 * @property string $data
 * @mixin \Eloquent
 */
class OaiField extends Model
{

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'oai_group_id',
        'name',
        'has_language',
        'xml_path',
        'xml_name',
        'mapping',
    ];

    private $_parsedMapping = null;
    public function getParsedMapping() {
        if (!$this->_parsedMapping) {
            $mappingParsed = json_decode($this->mapping, true);
            $this->_parsedMapping = [];
            if ($mappingParsed) {
                foreach ($mappingParsed as $mapping) {
                    if (isset($mapping["si4field"]) && $mapping["si4field"])
                        $this->_parsedMapping[] = $mapping;
                }
            }
        }
        return $this->_parsedMapping;
    }

    public function toArray() {
        $result = parent::toArray();
        $result["mapping"] = $this->getParsedMapping();
        return $result;
    }

    private static $oaiFieldsArray = [];
    public static function getOaiFieldsForGroup($oaiGroupId) {
        if (!isset(self::$oaiFieldsArray[$oaiGroupId])) {
            self::$oaiFieldsArray[$oaiGroupId] = [];
            $oaiFields = self::query()->where(['oai_group_id' => $oaiGroupId])->get();
            foreach ($oaiFields as $oaiField) {
                self::$oaiFieldsArray[$oaiGroupId][$oaiField->id] = $oaiField->toArray();
            }
        }
        return self::$oaiFieldsArray[$oaiGroupId];
    }

    public static function getOaiFieldsForGroupName($oaiGroupName) {
        $group = OaiGroup::query()->where(['name' => $oaiGroupName])->get()->first();
        return self::getOaiFieldsForGroup($group->id);
    }

}