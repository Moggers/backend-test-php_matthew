<?php

namespace Tests\Feature;

use Tests\PassportTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicControllerTest extends PassportTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateTopic()
    {
      $sections = $this
        ->get('/api/v1/sections')
        ->decodeResponseJson();
      $response = $this
        ->post('/api/v1/sections/' . $sections[0]['id'] . '/topics', ['title' => 'Test', 'body' => 'This is a body'])
        ->assertJson(['title' => 'Test', 'user_id' => $this->user->id])
        ->assertStatus(201);
    }

    public function testShowTopics()
    {
      $sections = $this
        ->get('/api/v1/sections')
        ->decodeResponseJson();
      $this
        ->get('/api/v1/sections/' . $sections[0]['id'] . '/topics')
        ->assertJsonFragment(['title'])
        ->assertStatus(200);
    }

    public function testShowMessages() 
    {
      $sections = $this
        ->get('/api/v1/sections')
        ->decodeResponseJson();
      $topics = $this
        ->get('/api/v1/sections/' . $sections[0]['id'] . '/topics/')
        ->decodeResponseJson();
      $this
        ->get('/api/v1/topics/' . $topics[0]['id'] . '/messages')
        ->assertJsonFragment(['body'])
        ->assertStatus(200)
        ->decodeResponseJson();
    }

    public function testCreateMessage()
    {
      $sections = $this
        ->get('/api/v1/sections')
        ->decodeResponseJson();
      $topics = $this
        ->get('/api/v1/sections/' . $sections[0]['id'] . '/topics')
        ->decodeResponseJson();
      $this
        ->post(
          '/api/v1/topics/' . $topics[0]['id'] . '/messages', 
          ['body' => 'Man! Thats really hard!'])
        ->assertJsonFragment(['body'])
        ->assertStatus(201);
    }
}
