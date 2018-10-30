<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMappingFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mapping_fields', function (Blueprint $table) {
            $table->text("value_xpath")->after("source_xpath");
            $table->text("lang_xpath")->after("value_xpath");
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
            $table->dropColumn("value_xpath");
            $table->dropColumn("lang_xpath");
        });
    }
}
