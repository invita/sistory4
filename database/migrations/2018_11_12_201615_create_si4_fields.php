<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSi4Fields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('si4_fields', function (Blueprint $table) {
            $table->increments("id");
            $table->string("field_name", 32)->index()->unique();
            $table->string("translate_key", 64);
            $table->boolean("has_language")->default(false);
            $table->boolean("show_all_languages")->default(false);
            $table->boolean("inline")->default(false);
            $table->string("inline_separator", 10)->default("");
            $table->boolean("display_frontend")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('si4_fields');
    }
}
