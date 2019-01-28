<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OaiGroup
 *
 * @property string $name
 * @property string $behaviours
 * @property string $attrs
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
        'attrs',
        'behaviours',
    ];

    private $_parsedAttrs = null;
    public function getParsedAttrs() {
        if (!$this->_parsedAttrs) {
            $attributesParsed = json_decode($this->attrs, true);
            $this->_parsedAttrs = [];
            if ($attributesParsed) {
                foreach ($attributesParsed as $attr) {
                    if (isset($attr["key"]) && $attr["key"] && isset($attr["value"]) && $attr["value"])
                        $this->_parsedAttrs[] = $attr;
                }
            }
        }
        return $this->_parsedAttrs;
    }

    private $_parsedBehaviours = null;
    public function getParsedBehaviours() {
        if (!$this->_parsedBehaviours) {
            $behavioursParsed = json_decode($this->behaviours, true);
            $this->_parsedBehaviours = [];
            if ($behavioursParsed) {
                foreach ($behavioursParsed as $beh) {
                    if ($beh) $this->_parsedBehaviours[] = $beh;
                }
            }
        }
        return $this->_parsedBehaviours;
    }

    public function toArray() {
        $result = parent::toArray();
        $result["attrs"] = $this->getParsedAttrs();
        $result["behaviours"] = $this->getParsedBehaviours();
        return $result;
    }

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

    public static function getOaiGroup($groupName) {
        if (!$groupName) return null;
        $oaiGroups = self::getOaiGroups();
        if (!isset($oaiGroups[$groupName])) return null;
        return $oaiGroups[$groupName];
    }
}