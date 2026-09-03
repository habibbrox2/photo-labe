<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderFile;
use App\Models\OrderMessage;
use App\Models\OrderRevision;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class OrderAndFileSecurityTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    // ─── Order ownership ──────────────────────────────────────

    public function test_customer_can_view_their_own_order(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);

        $this->actingAs($customer)
            ->get(route('account.orders.show', $order))
            ->assertOk();
    }

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $owner = $this->makeCustomer();
        $intruder = $this->makeCustomer();
        $order = $this->makeOrder($owner);

        $this->actingAs($intruder)
            ->get(route('account.orders.show', $order))
            ->assertForbidden();
    }

    public function test_admin_can_view_any_customers_order(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order))
            ->assertOk();
    }

    // ─── Messages ─────────────────────────────────────────────

    public function test_customer_can_send_a_message_on_their_order(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);

        $this->actingAs($customer)
            ->post(route('account.orders.message', $order), [
                'message' => 'Please start the edit soon.',
            ])
            ->assertRedirect(route('account.orders.show', $order));

        $this->assertDatabaseHas('order_messages', [
            'order_id' => $order->id,
            'user_id' => $customer->id,
            'message' => 'Please start the edit soon.',
            'is_read' => false,
        ]);
    }

    public function test_customer_cannot_send_a_message_on_someone_elses_order(): void
    {
        $owner = $this->makeCustomer();
        $intruder = $this->makeCustomer();
        $order = $this->makeOrder($owner);

        $this->actingAs($intruder)
            ->post(route('account.orders.message', $order), ['message' => 'hi'])
            ->assertForbidden();

        $this->assertDatabaseCount('order_messages', 0);
    }

    public function test_viewing_an_order_marks_staff_messages_as_read(): void
    {
        $customer = $this->makeCustomer();
        $staff = $this->makeAdmin();
        $order = $this->makeOrder($customer);

        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $staff->id,
            'message' => 'Update from the studio.',
            'is_read' => false,
        ]);

        $this->actingAs($customer)
            ->get(route('account.orders.show', $order))
            ->assertOk();

        $this->assertSame(1, OrderMessage::where('order_id', $order->id)->where('is_read', true)->count());
    }

    // ─── Revisions ────────────────────────────────────────────

    public function test_customer_can_request_a_revision_on_an_in_progress_order(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer, ['status' => 'in_progress']);

        $this->actingAs($customer)
            ->post(route('account.orders.revision', $order), [
                'message' => 'Please make the sky bluer.',
            ])
            ->assertRedirect(route('account.orders.show', $order));

        $this->assertDatabaseHas('order_revisions', [
            'order_id' => $order->id,
            'message' => 'Please make the sky bluer.',
            'status' => 'pending',
        ]);

        $this->assertSame('revision', $order->fresh()->status);
    }

    public function test_customer_cannot_request_a_revision_on_a_pending_order(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer, ['status' => 'pending']);

        $this->actingAs($customer)
            ->post(route('account.orders.revision', $order), [
                'message' => 'Please make the sky bluer.',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('order_revisions', 0);
        $this->assertSame('pending', $order->fresh()->status);
    }

    // ─── Admin file upload (private storage) ─────────────────

    public function test_admin_can_upload_an_output_file_to_a_private_disk(): void
    {
        Storage::fake('local');

        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);
        $admin = $this->makeAdmin();

        $file = UploadedFile::fake()->create('final-delivery.zip', 20, 'application/zip');

        $this->actingAs($admin)
            ->post(route('admin.orders.files.upload', $order), [
                'file' => $file,
                'type' => 'output',
            ])
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertDatabaseHas('order_files', [
            'order_id' => $order->id,
            'original_name' => 'final-delivery.zip',
            'type' => 'output',
        ]);

        $saved = OrderFile::where('order_id', $order->id)->first();
        $this->assertNotNull($saved);
        $this->assertStringStartsWith("private/orders/{$order->id}/output/", $saved->file_path);

        // The file lives on the private local disk, never the public one.
        Storage::disk('local')->assertExists($saved->file_path);
    }

    public function test_admin_file_upload_rejects_disallowed_extensions(): void
    {
        Storage::fake('local');

        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post(route('admin.orders.files.upload', $order), [
                'file' => UploadedFile::fake()->create('malware.exe', 10, 'application/x-msdownload'),
                'type' => 'output',
            ])
            ->assertSessionHasErrors('file');

        $this->assertDatabaseCount('order_files', 0);
    }

    // ─── Customer download authorization ─────────────────────

    public function test_customer_can_download_an_input_file_on_their_order(): void
    {
        $this->fakeOrderFileDisk();

        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);
        $file = $this->makeOrderFile($order, 'input');

        $this->actingAs($customer)
            ->get(route('account.orders.files.download', [$order, $file]))
            ->assertOk()
            ->assertDownload('delivery.zip');
    }

    public function test_customer_cannot_download_a_file_from_another_customers_order(): void
    {
        $this->fakeOrderFileDisk();

        $owner = $this->makeCustomer();
        $intruder = $this->makeCustomer();
        $order = $this->makeOrder($owner);
        $file = $this->makeOrderFile($order, 'input');

        $this->actingAs($intruder)
            ->get(route('account.orders.files.download', [$order, $file]))
            ->assertForbidden();
    }

    public function test_output_files_are_locked_until_the_order_is_completed(): void
    {
        $this->fakeOrderFileDisk();

        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer, ['status' => 'processing']);
        $file = $this->makeOrderFile($order, 'output');

        // Blocked while the order is in progress...
        $this->actingAs($customer)
            ->get(route('account.orders.files.download', [$order, $file]))
            ->assertForbidden();

        // ...and released once the order is completed.
        $order->update(['status' => 'completed', 'completed_at' => now()]);

        $this->actingAs($customer)
            ->get(route('account.orders.files.download', [$order, $file]))
            ->assertOk()
            ->assertDownload('delivery.zip');
    }

    public function test_customer_downloading_a_mismatched_file_gets_404(): void
    {
        $this->fakeOrderFileDisk();

        $customer = $this->makeCustomer();
        $orderA = $this->makeOrder($customer);
        $orderB = $this->makeOrder($customer);
        $file = $this->makeOrderFile($orderB, 'input');

        $this->actingAs($customer)
            ->get(route('account.orders.files.download', [$orderA, $file]))
            ->assertNotFound();
    }

    public function test_admin_can_download_any_order_file(): void
    {
        $this->fakeOrderFileDisk();

        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);
        $file = $this->makeOrderFile($order, 'output');
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get(route('admin.orders.files.download', [$order, $file]))
            ->assertOk()
            ->assertDownload('delivery.zip');
    }

    public function test_admin_can_delete_an_order_file(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);
        $file = $this->makeOrderFile($order, 'input');
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->delete(route('admin.orders.files.destroy', [$order, $file]))
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertDatabaseMissing('order_files', ['id' => $file->id]);
        Storage::disk('local')->assertMissing($file->file_path);
    }

    // ─── Missing files ────────────────────────────────────────

    public function test_download_of_a_missing_file_returns_404(): void
    {
        $this->fakeOrderFileDisk();

        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);
        $file = OrderFile::create([
            'order_id' => $order->id,
            'original_name' => 'ghost.zip',
            'stored_name' => 'ghost.zip',
            'file_path' => 'private/orders/' . $order->id . '/input/ghost.zip',
            'type' => 'input',
        ]);

        $this->actingAs($customer)
            ->get(route('account.orders.files.download', [$order, $file]))
            ->assertNotFound();
    }
}
