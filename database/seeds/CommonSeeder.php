<?php

use Illuminate\Database\Seeder;

class CommonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CommonSeeders\EntityTypesSeeder::class);
        $this->call(CommonSeeders\RelationTypesSeeder::class);
    }
}