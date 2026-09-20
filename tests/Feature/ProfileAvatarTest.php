<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class ProfileAvatarTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    private function customerAvatarUpload(array $overrides = [])
    {
        return $this->actingAs($this->makeCustomer())->put('/profile', array_merge([
            'name' => 'Avatar User',
            'email' => 'avatar@example.com',
            'avatar' => File::image('avatar.png', 64, 64),
        ], $overrides));
    }

    public function test_customer_can_upload_avatar(): void
    {
        Storage::fake('public');

        $this->customerAvatarUpload()->assertRedirect();

        $user = User::where('email', 'avatar@example.com')->first();
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_customer_can_remove_avatar(): void
    {
        Storage::fake('public');

        $user = $this->makeCustomer();
        $path = File::fake()->image('avatar.png')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'remove_avatar' => '1',
        ])->assertRedirect();

        $user->refresh();
        $this->assertNull($user->avatar);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_customer_can_replace_avatar(): void
    {
        Storage::fake('public');

        $user = $this->makeCustomer();
        $old = File::fake()->image('avatar.png')->store('avatars', 'public');
        $user->update(['avatar' => $old]);

        $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => File::image('new-avatar.png', 64, 64),
        ])->assertRedirect();

        $user->refresh();
        $this->assertNotEquals($old, $user->avatar);
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_admin_can_upload_avatar_on_own_profile(): void
    {
        Storage::fake('public');

        $admin = $this->makeAdmin();

        $this->actingAs($admin)->put('/admin/profile', [
            'name' => $admin->name,
            'email' => $admin->email,
            'avatar' => File::image('admin-avatar.png', 64, 64),
        ])->assertRedirect();

        $admin->refresh();
        $this->assertNotNull($admin->avatar);
        Storage::disk('public')->assertExists($admin->avatar);
    }

    public function test_admin_can_remove_avatar_on_own_profile(): void
    {
        Storage::fake('public');

        $admin = $this->makeAdmin();
        $path = File::fake()->image('avatar.png')->store('avatars', 'public');
        $admin->update(['avatar' => $path]);

        $this->actingAs($admin)->put('/admin/profile', [
            'name' => $admin->name,
            'email' => $admin->email,
            'remove_avatar' => '1',
        ])->assertRedirect();

        $admin->refresh();
        $this->assertNull($admin->avatar);
    }

    public function test_avatar_must_be_an_image(): void
    {
        Storage::fake('public');

        $this->customerAvatarUpload([
            'avatar' => File::fake()->create('notes.txt', 100, 'text/plain'),
        ])->assertSessionHasErrors('avatar');
    }
}