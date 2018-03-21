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

$factory->define(App\Models\Message::class, function (Faker $faker) {
    return [
      'body' => $faker->text,
      'is_highlight' => $faker->boolean,
      'user_id' => App\Models\User::inRandomOrder()->first(),
      'topic_id' => App\Models\Topic::inRandomOrder()->first(),
      'parent_id' => function(array $post) {
        return App\Models\Message::where(['topic_id' => $post['topic_id']])->inRandomOrder()->first();
      }
    ];
});
