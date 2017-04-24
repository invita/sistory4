<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            "email" => $user->email,
            "password" => $user->password,
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "remember_token" => $user->remember_token,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at
        ]);
    }
}