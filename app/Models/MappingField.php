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
        'data',
    ];
}