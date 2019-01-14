<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MappingFieldsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mapping_fields', function (Blueprint $table) {
            $table->renameColumn("data", "variables");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mapping_fields', function (Blueprint $table) {
            $table->renameColumn("variables", "data");
        });
    }
}
