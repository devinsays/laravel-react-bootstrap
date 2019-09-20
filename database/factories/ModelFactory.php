<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory contains each of the model factory definitions for
| our application. Factories provide a convenient way to generate new
| model instances for testing / seeding the application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Todo::class, function (Faker $faker) {
    return [
        'value' => $faker->sentence(6),
        'status' => $faker->randomElement(['open', 'closed'])
    ];
});
