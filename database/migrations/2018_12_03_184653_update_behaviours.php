<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBehaviours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('behaviour_fields', function (Blueprint $table) {
            $table->increments("id");
            $table->string("behaviour_name", 32);
            $table->string("field_name", 32);
            $table->boolean("show_all_languages")->default(false);
            $table->boolean("inline")->default(false);
            $table->string("inline_separator", 10)->default("");
            $table->boolean("display_frontend")->default(false);
            $table->boolean("enable_adv_search")->default(false);

            $table->unique(["behaviour_name", "field_name"]);
        });

        Schema::table('si4_fields', function (Blueprint $table) {
            $table->dropColumn("show_all_languages", "inline", "inline_separator", "display_frontend", "enable_adv_search");
        });

        Schema::table('behaviours', function (Blueprint $table) {
            $table->text("description")->after("name");
            $table->text("template")->after("description");
            $table->text("advanced_search")->after("template");
            $table->dropColumn("data");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('behaviour_fields');
        Schema::table('si4_fields', function (Blueprint $table) {
            $table->boolean("show_all_languages")->default(false);
            $table->boolean("inline")->default(false);
            $table->string("inline_separator", 10)->default("");
            $table->boolean("display_frontend")->default(false);
            $table->boolean("enable_adv_search")->default(false);
        });
        Schema::table('behaviours', function (Blueprint $table) {
            $table->dropColumn("description", "template", "advanced_search");
            $table->text("data");
        });
    }
}
