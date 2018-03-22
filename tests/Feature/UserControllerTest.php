<?php

namespace Tests\Feature;

use Tests\PassportTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends PassportTestCase
{
  public function testRegistration()
  {
      $this
        ->post('/register', ['name' => 'Test User', 'email' => 'test@test.test', 'password' => 'testpass', 'password_confirmation' => 'testpass', 'nickname' => 'New Section'])
        ->assertStatus(302);
  }

  public function testShowProfile()
  {
    $user = $this
      ->get('/api/v1/users/self')
      ->assertStatus(200)
      ->assertJsonFragment(['nickname'])
      ->decodeResponseJson();
    $this
        ->get('/api/v1/users/' . $user['id'])
        ->assertStatus(200)
        ->assertJsonFragment(['nickname']);
  }

  public function testUpdateProfile()
  {
    $this
      ->patch('/api/v1/users/self', ['nickname' => 'New Name'])
      ->assertJsonFragment(['nickname' => 'New Name'])
      ->assertStatus(200);
  }
}
