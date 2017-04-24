<?php
namespace TestSeeders;

use Illuminate\Database\Seeder;
use App\Models\Entity;

class EntitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<100; $i++){
            factory(Entity::class)->create();
        }
    }
}