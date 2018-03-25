<?php

namespace Tests\Feature;

use Tests\PassportTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Tests userprofile endpoints
 */
class UserControllerTest extends PassportTestCase
{
    /***
     * Tests that calling register with name, nick, 
     * email, password, will return a user
     *
     * @return null
     */
    public function testRegistration()
    {
        $this
            ->post('/register', ['name' => 'Test User', 'email' => 'test@test.test', 'password' => 'testpass', 'password_confirmation' => 'testpass', 'nickname' => 'New Section'])
            ->assertStatus(302);
    }

    /**
     * Tests that calling get endpoint either against self or against 
     * a user id will return user details
     *
     * @return null
     */
    public function testShowProfile()
    {
        $user = $this
            ->get('/api/v1/users/self')
            ->assertJsonFragment(['nickname'])
            ->assertStatus(200)
            ->decodeResponseJson();
        $this
            ->get('/api/v1/users/' . $user['data']['id'])
            ->assertJsonFragment(['nickname'])
            ->assertStatus(200);
    }

    /**
     * Test that the user can update its own nickname
     *
     * @return null
     */
    public function testUpdateProfile()
    {
        $this
            ->patch('/api/v1/users/self', ['nickname' => 'New Name'])
            ->assertJsonFragment(['nickname' => 'New Name'])
            ->assertStatus(200);
    }
}
