<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEnttitesEmptyableEnums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE entities CHANGE COLUMN struct_type struct_type ENUM('', 'entity', 'collection', 'file')");
        DB::statement("ALTER TABLE entities CHANGE COLUMN entity_type entity_type ENUM('', 'primary', 'dependant')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE entities CHANGE COLUMN struct_type struct_type ENUM('entity', 'collection', 'file')");
        DB::statement("ALTER TABLE entities CHANGE COLUMN entity_type entity_type ENUM('primary', 'dependant')");
    }
}
