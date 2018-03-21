<?php

namespace Tests\Feature;

use Tests\PassportTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SectionControllerTest extends PassportTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateSection()
    {
      $this
        ->post('/api/v1/sections', ['name' => 'New Section'])
        ->assertJson(['name' => 'New Section'])
        ->assertStatus(201);
    }

    public function testShowSessions()
    {
      $this
        ->post('/api/v1/sections', ['name' => 'New Section'])
        ->assertStatus(201);
      $this
        ->post('/api/v1/sections', ['name' => 'Another New Section'])
        ->assertStatus(201);
      $this
        ->get('/api/v1/sections')
        ->assertJsonFragment(['name' => 'New Section'])
        ->assertJsonFragment(['name' => 'Another New Section'])
        ->assertStatus(200);
    }
}
