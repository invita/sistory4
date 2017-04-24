<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Relation
 *
 * @property int $id
 * @property int $first_entity_id
 * @property int $relation_type_id
 * @property int $second_entity_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Entity $firstEntity
 * @property-read \App\Models\RelationType $relationType
 * @property-read \App\Models\Entity $secondEntity
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Relation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Relation whereFirstEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Relation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Relation whereRelationTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Relation whereSecondEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Relation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Relation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "first_entity_id",
        "relation_type_id",
        "second_entity_id"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function firstEntity()
    {
        return $this->belongsTo(Entity::class, "first_entity_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relationType()
    {
        return $this->belongsTo(RelationType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function secondEntity()
    {
        return $this->belongsTo(Entity::class, "second_entity_id");
    }
}