<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt(str_random(10)),
        "firstname" => $faker->firstName,
        "lastname" => $faker->lastName,
        'remember_token' => str_random(100),
    ];
});
$factory->define(App\Models\EntityType::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->title
    ];
});
$factory->define(App\Models\RelationType::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->title
    ];
});
$factory->define(App\Models\Entity::class, function (Faker\Generator $faker) {
    return [
        'entity_type_id' => function(){ return factory(\App\Models\EntityType::class)->create()->id; },
        "data" => $faker->text()
    ];
});
$factory->define(App\Models\Relation::class, function (Faker\Generator $faker) {
    return [
        "first_entity_id" => function(){ return factory(\App\Models\Entity::class)->create()->id; },
        "relation_type_id" => function(){ return factory(\App\Models\RelationType::class)->create()->id; },
        "second_entity_id" => function(){ return factory(\App\Models\Entity::class)->create()->id; },
    ];
});