<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MappingGroup
 *
 * @property string $name
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
        'description',
        'data',
    ];
}