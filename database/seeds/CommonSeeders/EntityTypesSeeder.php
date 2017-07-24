<?php
namespace CommonSeeders;

use Illuminate\Database\Seeder;
use App\Models\EntityType;

class EntityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EntityType::truncate();
        EntityType::create(["name" => "entity"]);
        EntityType::create(["name" => "collection"]);
    }
}