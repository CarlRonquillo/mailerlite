<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use MailerLiteApi\MailerLite;

class SubscribersTest extends TestCase
{

    // use my name as test domain to make sure that no real email address will be used
    private $testEmail = 'testemail@carlronquillo.com';

    //Test subscribers list page
    public function test_subscriber_list_page()
    {
        $response = $this->get('/subscribers');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('Subscribers List', $content);
    }

    //Test subscribers create page
    public function test_subscriber_create_page()
    {
        $response = $this->get('/subscribers/create');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('Add Subscriber', $content);
    }

    //Test an valid subscriber to access edit page
    public function test_valid_subscriber_edit_page()
    {
        $id = $this->createTestSubscriber()->id;

        $response = $this->get('/subscribers/'.(string)$id.'/edit');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('Edit Subscriber', $content);

        $this->deleteTestSubscriber();
    }

    //Test an invalid subscriber to access edit page
    public function test_invalid_subscriber_edit_page()
    {
        $response = $this->get('/subscribers/123/edit');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('Subscriber ID is not valid.', $content);
    }

    private function createTestSubscriber()
    {
        try {
            $mailerLiteApi = (new MailerLite(env('MAILERLITE_API_KEY')))->subscribers();

            $testData = [
                'email' => $this->testEmail,
                'name' => 'Carl Ronquillo Test Only',
                'fields' => [
                    'country'=>'Philippines'
                ]
            ];

            return $mailerLiteApi->create($testData);
        } catch (\Exception $e) {
            $this->fail('ERROR: '.$e->getMessage());
        }
    }

    private function deleteTestSubscriber()
    {
        try {
            $mailerLiteApi = (new MailerLite(env('MAILERLITE_API_KEY')))->subscribers();

            $subscriber = $mailerLiteApi->find($this->testEmail);

            if (isset($subscriber->id)){
                $mailerLiteApi->delete($subscriber->id);
            }
        } catch (\Exception $exception) {
            $this->fail('ERROR: '.$e->getMessage());
        }
    }
}
