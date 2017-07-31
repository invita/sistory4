<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RelationType
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RelationType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RelationType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RelationType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RelationType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RelationType extends Model
{
    CONST RELATION_TYPE_IS_CHILD_OF = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_rev'
    ];

    public static function loadAll() {

    }
}