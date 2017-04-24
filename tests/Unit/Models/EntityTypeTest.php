<?php

namespace Tests\Unit\Models;

use App\Models\EntityType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EntityTypeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        /** @var EntityType $entityType */
        $entityType = factory(EntityType::class)->create();

        $this->assertDatabaseHas('entity_types', [
            'name' => $entityType->name
        ]);
    }
}
