<?php

namespace App\Models;

use App\Helpers\Si4Util;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Behaviour
 *
 * @property string $name
 * @property string $description
 * @property string $template_entity
 * @property string $template_collection
 * @property string $template_file
 * @property string $advanced_search
 * @mixin \Eloquent
 */
class Behaviour extends Model
{

    protected $table = 'behaviours';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'description',
        'template_entity',
        'template_collection',
        'template_file',
        'advanced_search',
    ];

    private static $behavioursArray = null;
    public static function getBehavioursArray() {
        if (!self::$behavioursArray) {
            self::$behavioursArray = [];
            $behaviours = self::all();
            foreach ($behaviours as $behaviour) {
                self::$behavioursArray[$behaviour->name] = $behaviour->toArray();
                $behaviourFields = BehaviourField::getBehaviourFieldsArray($behaviour->name);
                self::$behavioursArray[$behaviour->name]["fields"] = $behaviourFields;
            }
        }
        return self::$behavioursArray;
    }

    public static function getBehaviour($name) {
        $behavioursArray = self::getBehavioursArray();
        if (!isset($behavioursArray[$name])) return [];
        return $behavioursArray[$name];
    }

    public static function getBehaviourForElasticEntity($elasticEntity) {
        $behaviourName = Si4Util::pathArg($elasticEntity, "_source/struct_subtype", "default");
        return self::getBehaviour($behaviourName);
    }


    private static $rootBehaviour = null;
    public static function getRootBehaviour() {
        if (!self::$rootBehaviour) {
            $rootEntity = Entity::getRootEntity();
            $rootBehaviourName = $rootEntity ? $rootEntity->struct_subtype : "default";
            $behaviour = self::query()->where(['name' => $rootBehaviourName])->get()->first();
            self::$rootBehaviour = $behaviour->toArray();
            self::$rootBehaviour["fields"] = BehaviourField::getBehaviourFieldsArray($behaviour->name);;
        }
        return self::$rootBehaviour;
    }


}