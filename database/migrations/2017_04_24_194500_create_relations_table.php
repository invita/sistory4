<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("first_entity_id")->references("id")->on("entities")->index();
            $table->integer("relation_type_id")->references("id")->on("relation_types")->index();
            $table->integer("second_entity_id")->references("id")->on("entities")->index();
            $table->timestamps();

            $table->index(["first_entity_id", "second_entity_id"]);
            $table->index(["first_entity_id", "relation_type_id"]);
            $table->index(["second_entity_id", "relation_type_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relations');
    }
}
