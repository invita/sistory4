<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEntitiesTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entities', function (Blueprint $table) {
            $platform = Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform();
            $platform->registerDoctrineTypeMapping('enum', 'string');
            $table->renameColumn("data", "xml");
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
            $platform = Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform();
            $platform->registerDoctrineTypeMapping('enum', 'string');
            $table->renameColumn("xml", "data");
        });
    }
}
