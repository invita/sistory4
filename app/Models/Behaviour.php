<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Behaviour
 *
 * @property string $name
 * @property string $data
 * @mixin \Eloquent
 */
class Behaviour extends Model
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
        'data',
    ];
}