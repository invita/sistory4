<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EntityType
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EntityType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EntityType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EntityType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EntityType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EntityType extends Model
{
    CONST ENTITY_TYPE_MENU_ITEM = 1;
    CONST ENTITY_TYPE_PUBLICATION = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}