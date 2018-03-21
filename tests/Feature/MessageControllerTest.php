<?php

namespace Tests\Feature;

use Tests\PassportTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Message;

class MessageControllerTest extends PassportTestCase
{
    public function testReply() {
      $sections = $this
        ->get('/api/v1/sections/')
        ->decodeResponseJson();
      $topics = $this
        ->get('/api/v1/sections/' . $sections[0]['id'] . '/topics')
        ->decodeResponseJson();
      $messages = $this
        ->get('/api/v1/topics/' . $topics[0]['id'] . '/messages')
        ->decodeResponseJson();

      $reply = $this
        ->post('/api/v1/messages/' . $messages[0]['id'] . '/reply', ['body' => 'This is a good post!'])
        ->assertJsonFragment(['parent_id' => $messages[0]['id']])
        ->assertStatus(201);
    }

    public function testUpdateAuthFails() {
      $sections = $this
        ->get('/api/v1/sections/')
        ->decodeResponseJson();
      $topics = $this
        ->get('/api/v1/sections/' . $sections[0]['id'] . '/topics')
        ->decodeResponseJson();
      $messages = $this
        ->get('/api/v1/topics/' . $topics[0]['id'] . '/messages')
        ->decodeResponseJson();

      $this
        ->post('/api/v1/messages/' . $messages[0]['id'], ['body' => 'This is an illegal update'])
        ->assertStatus(403);
    }
    public function testUpdate() {
      $sections = $this
        ->get('/api/v1/sections/')
        ->decodeResponseJson();
      $topics = $this
        ->get('/api/v1/sections/' . $sections[0]['id'] . '/topics')
        ->decodeResponseJson();
      $message = $this
        ->post('/api/v1/topics/' . $topics[0]['id'] . '/messages', ['body' => 'This is a post'])
        ->decodeResponseJson();

      $this
        ->post('/api/v1/messages/' . $message['id'], ['body' => 'This is an illegal update'])
        ->assertStatus(200);
    }
}
