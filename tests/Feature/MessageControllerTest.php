<?php

namespace Tests\Feature;

use Tests\PassportTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Message;

class MessageControllerTest extends PassportTestCase
{
    public function testReply() 
    {
        $sections = $this
            ->get('/api/v1/sections/')
            ->decodeResponseJson();
        $topics = $this
            ->get('/api/v1/sections/' . $sections['data'][0]['id'] . '/topics')
            ->decodeResponseJson();
        $messages = $this
            ->get('/api/v1/topics/' . $topics['data'][0]['id'] . '/messages')
            ->decodeResponseJson();

        $reply = $this
            ->put('/api/v1/messages/' . $messages['data'][0]['id'] . '/reply', ['body' => 'This is a good post!'])
            ->assertJsonFragment(['parent_id' => intval($messages['data'][0]['id'])])
            ->assertStatus(200);
    }

    public function testUpdateAuthFails() 
    {
        $sections = $this
            ->get('/api/v1/sections/')
            ->decodeResponseJson();
        $topics = $this
            ->get('/api/v1/sections/' . $sections['data'][0]['id'] . '/topics')
            ->decodeResponseJson();
        $messages = $this
            ->get('/api/v1/topics/' . $topics['data'][0]['id'] . '/messages')
            ->decodeResponseJson();

        $this
            ->post('/api/v1/messages/' . $messages['data'][0]['id'], ['body' => 'This is an illegal update'])
            ->assertStatus(403);
    }
    public function testUpdate() 
    {
        $sections = $this
            ->get('/api/v1/sections/')
            ->decodeResponseJson();
        $topics = $this
            ->get('/api/v1/sections/' . $sections['data'][0]['id'] . '/topics')
            ->decodeResponseJson();
        $message = $this
            ->put('/api/v1/topics/' . $topics['data'][0]['id'] . '/messages', ['body' => 'This is a post'])
            ->assertJsonFragment(['body' => 'This is a post'])
            ->assertStatus(200)
            ->decodeResponseJson();

        $this
            ->post('/api/v1/messages/' . $message['data']['id'], ['body' => 'This is a legal update'])
            ->assertJsonFragment(['body' => 'This is a legal update'])
            ->assertStatus(200);
    }
}
