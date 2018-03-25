<?php

namespace Tests\Feature;

use Tests\PassportTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SectionControllerTest extends PassportTestCase
{
    public function testCreateSection()
    {
        $this
            ->post('/api/v1/sections', ['name' => 'New Section'])
            ->assertJsonFragment(['name' => 'New Section'])
            ->assertStatus(200);
    }

    public function testShowSessions()
    {
        $sections = $this
            ->get('/api/v1/sections')
            ->assertJsonFragment(['name'])
            ->assertStatus(200)
            ->decodeResponseJson();

        $this
            ->get('/api/v1/sections/' . $sections['data'][0]['id'])
            ->assertJsonFragment(['name'])
            ->assertJsonFragment(['message_count'])
            ->assertStatus(200);
    }
}
