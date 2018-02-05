<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEntitiesStructTypeFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE entities CHANGE COLUMN struct_type struct_type ENUM('entity', 'collection', 'file')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE entities CHANGE COLUMN struct_type struct_type ENUM('entity', 'collection')");
    }
}
