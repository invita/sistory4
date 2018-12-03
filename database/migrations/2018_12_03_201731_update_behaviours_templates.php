<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBehavioursTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('behaviours', function (Blueprint $table) {
            $table->renameColumn("template", "template_entity");
        });
        Schema::table('behaviours', function (Blueprint $table) {
            $table->text("template_collection")->after("template_entity");
            $table->text("template_file")->after("template_collection");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('behaviours', function (Blueprint $table) {
            $table->dropColumn("template_collection", "template_file");
            $table->renameColumn("template_entity", "template");
        });
    }
}
