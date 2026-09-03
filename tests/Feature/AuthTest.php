<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    // ─── Guests ───────────────────────────────────────────────

    public function test_guests_can_view_public_pages(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/get-a-quote')->assertOk();
        $this->get('/products')->assertOk();
    }

    public function test_guests_are_redirected_from_protected_pages(): void
    {
        $this->get('/account')->assertRedirect(route('login'));
        $this->get('/account/orders')->assertRedirect(route('login'));
        $this->get('/admin')->assertRedirect(route('login'));
    }

    // ─── Registration ─────────────────────────────────────────

    public function test_registration_creates_customer_and_logs_in(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Customer',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'role' => 'customer',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        $this->makeCustomer(['email' => 'taken@example.com']);

        $this->post('/register', [
            'name' => 'Duplicate',
            'email' => 'taken@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $this->post('/register', [
            'name' => 'New Customer',
            'email' => 'mismatch@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    // ─── Login ────────────────────────────────────────────────

    public function test_customer_login_redirects_home(): void
    {
        $user = $this->makeCustomer(['password' => 'secret123']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = $this->makeAdmin(['password' => 'secret123']);

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'secret123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_rejects_bad_credentials(): void
    {
        $user = $this->makeCustomer();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_ends_session(): void
    {
        $user = $this->makeCustomer();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    // ─── Email verification gates customer area ───────────────

    public function test_unverified_customer_is_redirected_from_account_area(): void
    {
        $user = $this->makeCustomer(['email_verified_at' => null]);

        $this->actingAs($user)
            ->get('/account')
            ->assertRedirect(route('verification.notice'));
    }

    public function test_verified_customer_can_access_account_dashboard(): void
    {
        $user = $this->makeCustomer();

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertSee('Dashboard');
    }

    // ─── Profile & password ───────────────────────────────────

    public function test_customer_can_update_profile(): void
    {
        $user = $this->makeCustomer();

        $this->actingAs($user)
            ->put('/profile', [
                'name' => 'Updated Name',
                'email' => $user->email,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_customer_can_change_password_with_correct_current_password(): void
    {
        $user = $this->makeCustomer(['password' => 'old-password']);

        $this->actingAs($user)
            ->put('/change-password', [
                'current_password' => 'old-password',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertRedirect();

        $this->assertTrue(auth()->validate([
            'email' => $user->email,
            'password' => 'new-password-123',
        ]));
    }

    public function test_customer_cannot_change_password_with_wrong_current_password(): void
    {
        $user = $this->makeCustomer(['password' => 'old-password']);

        $this->actingAs($user)
            ->put('/change-password', [
                'current_password' => 'not-the-password',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertSessionHasErrors('current_password');
    }
}
