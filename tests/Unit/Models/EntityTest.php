<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Entity;

class EntityTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        /** @var Entity $entity */
        $entity = factory(Entity::class)->create();

        $this->assertDatabaseHas('entities', [
            "entity_type_id" => $entity->entityType->id,
            "data" => $entity->data
        ]);
    }
}
