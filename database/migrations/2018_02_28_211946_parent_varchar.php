<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ParentVarchar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn("parent");
        });
        Schema::table('entities', function (Blueprint $table) {
            $table->string("parent", 16)->after("handle_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn("parent");
        });
        Schema::table('entities', function (Blueprint $table) {
            $table->integer("parent")->after("handle_id");
        });
    }
}
