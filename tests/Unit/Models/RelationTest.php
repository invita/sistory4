<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Relation;

class RelationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        /** @var Relation $relation */
        $relation = factory(Relation::class)->create();

        $this->assertDatabaseHas('relations', [
            "first_entity_id" => $relation->firstEntity->id,
            "relation_type_id" => $relation->relationType->id,
            "second_entity_id" => $relation->secondEntity->id
        ]);
    }
}