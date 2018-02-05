<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEntityHandleIdSequenceAddFormat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entity_handle_seq', function (Blueprint $table) {
            $table->string("format", 16)->after("entity_struct_type");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entity_handle_seq', function (Blueprint $table) {
            $table->drop("format");
        });
    }
}
