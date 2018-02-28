<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrimaryVarchar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn("primary");
        });
        Schema::table('entities', function (Blueprint $table) {
            $table->string("primary", 16)->after("parent");
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
            $table->dropColumn("primary");
        });
        Schema::table('entities', function (Blueprint $table) {
            $table->integer("primary")->after("parent");
        });
    }
}
