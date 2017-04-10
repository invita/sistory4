<?php

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
    }
}
