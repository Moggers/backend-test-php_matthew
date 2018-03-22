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
      $response = $this
        ->get('/api/v1/sections');

      $response
        ->assertJsonFragment(['name'])
        ->assertStatus(200);

      $sections = $response
        ->decodeResponseJson();

      $this
        ->get('/api/v1/sections/' . $sections[0]['id'])
        ->assertJsonFragment(['name'])
        ->assertStatus(200);
    }
}
