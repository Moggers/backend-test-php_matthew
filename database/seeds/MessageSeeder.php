<?php

use Illuminate\Database\Seeder;
use App\Models\Message;

class MessageSeeder extends Seeder
{
  function generateAnother($topic) {
    $faker = Faker\Factory::create();
    $children = [];
    if($faker->boolean(50)) {
      $children[] = $this->generateAnother($topic);
    }
    return [
      'body' => $faker->text,
      'is_highlight' => $faker->boolean(10),
      'user_id' => App\Models\User::inRandomOrder()->first()->id,
      'topic_id' => $topic->id,
      'children' => $children
    ];
  }

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // We need to repeat this to give it a chance to use each step as a parent

    $faker = Faker\Factory::create();

    for($x = 0; $x < 100; $x++) {
      $topic = App\Models\Topic::inRandomOrder()->first();
      $message =  Message::create([
        'body' => $faker->text,
        'is_highlight' => $faker->boolean,
        'user_id' => App\Models\User::inRandomOrder()->first()->id,
        'topic_id' => $topic->id,
        'children' => [$this->generateAnother($topic)],
      ]);
    }
  }
}
