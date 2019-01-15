<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaiFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oai_fields', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("oai_group_id");
            $table->string("name", 32)->index();
            $table->boolean("has_language", 32);
            $table->string("xml_path", 128);
            $table->string("xml_name", 128);
            $table->text("mapping");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oai_fields');
    }
}
