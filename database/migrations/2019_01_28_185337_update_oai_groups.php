<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOaiGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oai_groups', function (Blueprint $table) {
            $table->string("schema", 256);
            $table->string("namespace", 256);
            $table->text("behaviours");
            $table->text("attrs");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oai_groups', function (Blueprint $table) {
            $table->dropColumn("schema");
            $table->dropColumn("namespace");
            $table->dropColumn("behaviours");
            $table->dropColumn("attrs");
        });
    }
}
