<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Support\FormTimeTrap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class NewsletterCsvExportTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    public function test_admin_can_export_subscribed_csv(): void
    {
        NewsletterSubscriber::create(['email' => 'active@example.com', 'ip_address' => '127.0.0.1']);
        NewsletterSubscriber::create(['email' => 'gone@example.com', 'status' => 'unsubscribed', 'unsubscribed_at' => now()]);

        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('admin.newsletter-subscribers.export'));

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $csv = $response->streamedContent();

        $this->assertStringContainsString('email,status,subscribed_at,unsubscribed_at,ip_address', $csv);
        $this->assertStringContainsString('active@example.com,subscribed', $csv);
        $this->assertStringNotContainsString('gone@example.com', $csv);
    }

    public function test_export_honours_status_filter(): void
    {
        NewsletterSubscriber::create(['email' => 'active@example.com']);
        NewsletterSubscriber::create(['email' => 'gone@example.com', 'status' => 'unsubscribed', 'unsubscribed_at' => now()]);

        $admin = $this->makeAdmin();

        $csv = $this->actingAs($admin)
            ->get(route('admin.newsletter-subscribers.export', ['status' => 'unsubscribed']))
            ->streamedContent();

        $this->assertStringContainsString('gone@example.com', $csv);
        $this->assertStringNotContainsString('active@example.com', $csv);
    }

    public function test_customer_cannot_export_subscribers(): void
    {
        $customer = $this->makeCustomer();

        $this->actingAs($customer)
            ->get(route('admin.newsletter-subscribers.export'))
            ->assertForbidden();
    }
}
