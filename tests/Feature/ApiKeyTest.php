<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Key;

class ApiKeyTest extends TestCase
{

    // Test index page if no error when no api key
    public function test_index_page_no_key()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    // Test subscriber list redirect to index page when no api key
    public function test_subscriber_list_no_key()
    {
        $response = $this->get('/subscribers');
        $response->assertRedirect('/');
    }

    // Test subscriber create redirect to index page when no api key
    public function test_subscriber_create_no_key()
    {
        $response = $this->get('/subscribers/create');
        $response->assertRedirect('/');
    }

    // Test subscriber edit redirect to index page when no api key
    public function test_subscriber_edit_no_key()
    {
        $response = $this->get('/subscribers/123/edit');
        $response->assertRedirect('/');
    }

    // Test subscriber delete redirect to index page when no api key
    public function test_subscriber_delete_no_key()
    {
        $response = $this->get('/subscribers/destroy');
        $response->assertRedirect('/');
    }

    // Test key delete 404 apge when no api key
    public function test_key_delete_no_key()
    {
        $response = $this->get('/keys/destroy');
        $response->assertStatus(404);
    }

    // Test empty api key sumbit
    public function test_key_submit_empty()
    {
        $response = $this->post('/keys',[
            'api_key' => ''
        ]);

        $response->assertRedirect('/');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('The api key field is required', $content);
    }

    // Test invalid api key sumbit
    public function test_key_submit_invalid()
    {
        $response = $this->post('/keys',[
            'api_key' => 'test_api_key'
        ]);

        $response->assertRedirect('/keys/create');
        $response->assertStatus(302);
        
        $response = $this->get('/keys/create');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('API Key is invalid', $content);
    }

    // Test valid api key sumbit from env variable MAILERLITE_API_KEY
    public function test_key_submit_valid()
    {
        $testKey = env('MAILERLITE_API_KEY');
        $response = $this->post('/keys',[
            'api_key' => $testKey
        ]);

        $response->assertRedirect('/subscribers');
        $response->assertStatus(302);
        
        $response = $this->get('/subscribers');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('Subscribers List', $content);

        $keys = Key::select('key')->first();
        $this->assertEquals($keys->key,$testKey);
    }

}
