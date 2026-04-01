<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactSendsEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_submission_sends_email_to_configured_recipient(): void
    {
        config()->set('mail.contact_to.address', 'owner@example.com');
        config()->set('mail.contact_to.name', 'Owner');

        Mail::fake();

        $response = $this->postJson('/api/contact', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Hello',
            'message' => 'Test message',
        ]);

        $response->assertCreated();

        Mail::assertSent(ContactMessageReceived::class, function (ContactMessageReceived $mail) {
            return $mail->hasTo('owner@example.com')
                && $mail->hasReplyTo('jane@example.com')
                && str_contains($mail->envelope()->subject, 'Hello');
        });
    }
}

