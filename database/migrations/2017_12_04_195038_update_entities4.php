<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEntities4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add struct_type enum
        Schema::table('entities', function (Blueprint $table) {
            $table->enum("struct_type", ["entity", "collection"])->after('id');
        });

        // Update data from old struct_type_id
        DB::raw("UPDATE entities SET struct_type = 'entity' WHERE struct_type_id = 1");
        DB::raw("UPDATE entities SET struct_type = 'collection' WHERE struct_type_id = 2");

        // Drop struct_type_id
        Schema::table('entities', function (Blueprint $table) {
            //$table->dropColumn("struct_type_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add back struct_type_id
        Schema::table('entities', function (Blueprint $table) {
            $table->integer("entity_type_id")->after('id')->references("id")->on("struct_types")->index();
        });

        // Update data from old struct_type_id
        DB::raw("UPDATE entities SET struct_type_id = 1 WHERE struct_type = 'entity'");
        DB::raw("UPDATE entities SET struct_type_id = 2 WHERE struct_type = 'collection'");

        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn("struct_type");
        });
    }
}
