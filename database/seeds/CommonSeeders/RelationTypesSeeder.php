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
        RelationType::truncate();
        RelationType::create(["name" => "isChildOf", "name_rev" => "isParentOf"]);
        RelationType::create(["name" => "cites", "name_rev" => "isCitedIn"]);
    }
}
