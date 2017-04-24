<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\RelationType;

class RelationTypeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        /** @var RelationType $relationType */
        $relationType = factory(RelationType::class)->create();

        $this->assertDatabaseHas('relation_types', [
            'name' => $relationType->name
        ]);
    }
}
