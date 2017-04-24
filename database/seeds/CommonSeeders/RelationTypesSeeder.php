<?php
namespace CommonSeeders;

use Illuminate\Database\Seeder;
use App\Models\RelationType;

class RelationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RelationType::create(["name" => "Is Child Of"]);
    }
}
