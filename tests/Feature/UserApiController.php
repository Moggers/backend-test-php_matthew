<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiController extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPostUsers()
    {
      $this->json('POST', '/api/v1/users', [
        'name' => 'moggers',
        'email' => 'test@test.test',
        'password' => 'testpass',
      ])->assertStatus(201);
    }
}
