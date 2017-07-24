<?php
namespace InitialSeeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "admin",
            "password" => bcrypt("Grega."),
            "email" => "test@test.com",
            "firstname" => "Firstname",
            "lastname" => "Lastname"
        ]);
        User::create([
            "name" => "Duhec",
            "password" => "$2y$10$8Tc6Nq/MZfrK7ODRG3/grOA3PMMQlDago00/QDV0SMqcrdoFYOXUi",
            "email" => "matic.vrscaj@gmail.com",
            "firstname" => "Matic",
            "lastname" => "Vrscaj"
        ]);
    }
}
