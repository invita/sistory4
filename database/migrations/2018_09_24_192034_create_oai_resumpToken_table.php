<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaiResumpTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oai_resump_tokens', function (Blueprint $table) {
            $table->increments("id");
            $table->string("token", 64);
            $table->timestamp("valid_since")->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp("valid_to")->nullable();
            $table->longText("data");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oai_resump_tokens');
    }
}
