<?php

namespace Tests\Feature;

use App\Mail\ContactAcknowledgementMail;
use App\Mail\ContactInquiryMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_sends_inquiry_and_acknowledgement_emails(): void
    {
        Mail::fake();

        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello, this is a test message.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Mail::assertSent(ContactInquiryMail::class, function (ContactInquiryMail $mail) {
            return $mail->hasTo(config('mail.from.address'));
        });

        Mail::assertSent(ContactAcknowledgementMail::class, function (ContactAcknowledgementMail $mail) {
            return $mail->hasTo('john@example.com');
        });
    }

    public function test_contact_form_requires_all_fields(): void
    {
        $response = $this->post('/contact', []);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }
}
