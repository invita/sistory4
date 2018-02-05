<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityHandleIdSequence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_handle_seq', function (Blueprint $table) {
            $table->enum("entity_struct_type", ["entity", "collection", "file"])->unique()->index();
            $table->integer("last_num");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('entity_handle_seq');
    }
}
