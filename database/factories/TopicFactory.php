<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    return [
      'title' => $faker->text,
      'body' => $faker->text,
      'user_id' => App\Models\User::inRandomOrder()->first(),
      'section_id' => App\Models\Section::inRandomOrder()->first(),
    ];
});
