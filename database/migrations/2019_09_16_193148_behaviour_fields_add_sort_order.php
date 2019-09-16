<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BehaviourFieldsAddSortOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('behaviour_fields', function (Blueprint $table) {
            $table->integer("sort_order")->default(0);
        });
        DB::statement("UPDATE behaviour_fields SET sort_order = id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('behaviour_fields', function (Blueprint $table) {
            $table->dropColumn("sort_order");
        });
    }
}
