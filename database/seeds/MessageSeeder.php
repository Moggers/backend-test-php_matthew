<?php

use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // We need to repeat this to give it a chance to use each step as a parent
        factory(\App\Models\Message::class, 20)->create();
        factory(\App\Models\Message::class, 20)->create();
        factory(\App\Models\Message::class, 20)->create();
        factory(\App\Models\Message::class, 20)->create();
        factory(\App\Models\Message::class, 20)->create();
    }
}
