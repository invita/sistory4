<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\EntityHandleSeq;

class UpdateEntityHandleIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        EntityHandleSeq::truncate();
        EntityHandleSeq::create([
            "entity_struct_type" => "entity",
            "format" => "entity#",
            "last_num" => 0
        ]);
        EntityHandleSeq::create([
            "entity_struct_type" => "collection",
            "format" => "collection#",
            "last_num" => 0
        ]);
        EntityHandleSeq::create([
            "entity_struct_type" => "file",
            "format" => "file#",
            "last_num" => 0
        ]);

        $entities = \App\Models\Entity::all();
        $entities->each(function($entity) {
            if (!$entity->struct_type) return;
            $entity->handle_id = \App\Models\EntityHandleSeq::nextNumSeq($entity->struct_type);
            $entity->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Nothing to do
    }
}
