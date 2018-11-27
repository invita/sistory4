<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSi4fieldAdvancedSearchCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('si4_fields', function (Blueprint $table) {
            $table->boolean("enable_adv_search")->after("display_frontend");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('si4_fields', function (Blueprint $table) {
            $table->dropColumn("enable_adv_search");
        });
    }
}
