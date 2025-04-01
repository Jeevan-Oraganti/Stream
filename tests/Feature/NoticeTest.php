<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Models\NoticeType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\post;

class NoticeTest extends TestCase
{
    use RefreshDatabase;

    public function test_assertAdminCanCreateNotice(): void
    {
        // Create an admin user
        $admin = User::factory()->create(['id' => 1]);
        $this->actingAs($admin);

        // Create a notice type
        $noticeType = NoticeType::factory()->create();

        // Send POST request
        $response = $this->post('/admin/notice/createOrUpdate', [
            'name' => 'Test Notice',
            'description' => 'This is a test notice.',
            'notice_type_id' => $noticeType->id,
            'expiry_date' => now()->addDays(7),
            'is_sticky' => true,
            'scheduled_at' => now(),
            'recurrence' => 'weekly',
            'recurrence_days' => ['Monday', 'Wednesday'],
        ]);

        // Assert response status
        $response->assertStatus(200);

        // Check database
        $this->assertDatabaseHas('notices', [
            'name' => 'Test Notice',
            'is_sticky' => true,
            'recurrence' => 'weekly',
        ]);
    }

    public function test_assertNormalUserCannotInsertNotice(): void
    {
        // Create a normal user
        $user = User::factory()->create(['id' => 3]);

        $this->actingAs($user->fresh());

        // Create a notice type
        $noticeType = NoticeType::factory()->create();

        // Send POST request
        $response = $this->post('/admin/notice/createOrUpdate', [
            'name' => 'Test Notice',
            'description' => 'This is a test notice.',
            'notice_type_id' => $noticeType->id,
            'expiry_date' => now()->addDays(7),
            'is_sticky' => true,
            'scheduled_at' => now(),
            'recurrence' => 'weekly',
            'recurrence_days' => ['Monday', 'Wednesday'],
        ]);

        // Assert response status
        $response->assertStatus(403);

        // Ensure the notice is not created in the database
        $this->assertDatabaseMissing('notices', [
            'name' => 'Test Notice',
            'description' => 'This is a test notice.',
        ]);
    }

    public function test_assertNormalUserCanFetchNotices(): void
    {
        // Create an admin user
        $admin = User::factory()->create(['id' => 1]);

        // Create a normal user
        $user = User::factory()->create(['id' => 2]);

        // Create a notice type
        $noticeType = NoticeType::factory()->create();

        // Acting as admin to create a notice
        $this->actingAs($admin)->post('/admin/notice/createOrUpdate', [
            'name' => 'User Notice',
            'description' => 'This is a notice for users.',
            'notice_type_id' => $noticeType->id,
            'expiry_date' => now()->addDays(7),
            'is_sticky' => true,
            'is_active' => true,
            'scheduled_at' => now(),
            'recurrence' => 'weekly',
            'recurrence_days' => ['Monday', 'Wednesday'],
        ]);

        // Log in as a normal user
        $this->actingAs($user);

        // Update the is_active to true for fetching
        Notice::where('id', 2)->update(['is_active' => true]);

        // Fetch notices for the normal user
        $response = $this->get('/notices/unread');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'User Notice',
                'description' => 'This is a notice for users.',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'is_sticky' => true
            ]);

        $this->assertDatabaseHas('notices', [
            'name' => 'User Notice',
            'description' => 'This is a notice for users.',
        ]);
    }

    public function test_assertStickyNoticeIsFetchedFirst(): void
    {
        // Create an admin user
        $admin = User::factory()->create(['id' => 1]);

        // Create a normal user
        $user = User::factory()->create(['id' => 2]);

        // Create a notice type
        $noticeType = NoticeType::factory()->create();

        // Acting as admin to create a notice
        $this->actingAs($admin)->post('/admin/notice/createOrUpdate', [
            'name' => 'User Notice 1',
            'description' => 'This is a notice for users.',
            'notice_type_id' => $noticeType->id,
            'expiry_date' => now()->addDays(7),
            'is_sticky' => true,
            'is_active' => true,
            'scheduled_at' => now(),
            'recurrence' => 'weekly',
            'recurrence_days' => ['Monday', 'Wednesday'],
        ]);

        // Acting as admin to create a notice
        $this->actingAs($admin)->post('/admin/notice/createOrUpdate', [
            'name' => 'User Notice 2',
            'description' => 'This is a notice for users.',
            'notice_type_id' => $noticeType->id,
            'expiry_date' => now()->addDays(7),
            'is_sticky' => false,
            'is_active' => true,
            'scheduled_at' => now(),
            'recurrence' => 'weekly',
            'recurrence_days' => ['Monday', 'Wednesday'],
        ]);

        // Log in as a normal user
        $this->actingAs($user);

        // Update the is_active to true for fetching
        Notice::where('id', 3)->update(['is_active' => true]);
        Notice::where('id', 4)->update(['is_active' => true]);

        // Fetch notices for the normal user
        $response = $this->get('/notices/unread');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'User Notice 1',
                'description' => 'This is a notice for users.',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'is_sticky' => true
            ]);

        $this->assertDatabaseHas('notices', [
            'name' => 'User Notice 1',
            'description' => 'This is a notice for users.',
        ]);
    }

    public function test_assertAdminCanDeleteNotice(): void
    {
        // Create an admin user
        $admin = User::factory()->create(['id' => 1]);
        $this->actingAs($admin);

        // Create a notice type
        $noticeType = NoticeType::factory()->create();

        // Send POST request
        $response = $this->post('/admin/notice/createOrUpdate', [
            'name' => 'Test Notice Deleting',
            'description' => 'This is a test notice for deletion.',
            'notice_type_id' => $noticeType->id,
            'expiry_date' => now()->addDays(7),
            'is_sticky' => true,
            'scheduled_at' => now(),
            'recurrence' => 'weekly',
            'recurrence_days' => ['Monday', 'Wednesday'],
        ]);

        // Retrieve the created notice
        $notice = Notice::where('name', 'Test Notice Deleting')->firstOrFail();

        // Send DELETE request
        $response = $this->post("/admin/notice/{$notice->id}/delete");

        // Assert response status
        $response->assertStatus(302);

        // Check database
        $this->assertDatabaseMissing('notices', [
            'id' => $notice->id,
            'name' => 'Test Notice Deleting',
            'description' => 'This is a test notice for deletion.',
        ]);
    }

    public function test_assertNormalUserCannotDeleteNotice(): void
    {
        // Create an admin user
        $admin = User::factory()->create(['id' => 1]);

        $user = User::factory()->create(['id' => 2]);

        $this->actingAs($admin);

        // Create a notice type
        $noticeType = NoticeType::factory()->create();

        // Send POST request
        $this->post('/admin/notice/createOrUpdate', [
            'name' => 'Test Notice Deleting',
            'description' => 'This is a test notice for deletion.',
            'notice_type_id' => $noticeType->id,
            'expiry_date' => now()->addDays(7),
            'is_sticky' => true,
            'scheduled_at' => now(),
            'recurrence' => 'weekly',
            'recurrence_days' => ['Monday', 'Wednesday'],
        ]);

        $this->actingAs($user);

        // Retrieve the created notice
        $notice = Notice::where('name', 'Test Notice Deleting')->firstOrFail();

        // Send DELETE request
        $response = $this->post("/admin/notice/{$notice->id}/delete");

        // Assert response status
        $response->assertStatus(403);

        // Check database
        $this->assertDatabaseHas('notices', [
            'id' => $notice->id,
            'name' => 'Test Notice Deleting',
            'description' => 'This is a test notice for deletion.',
        ]);
    }

    public function test_assertExpiredNoticeIsNotFetched(): void
    {
        // Create an admin user
        $admin = User::factory()->create(['id' => 1]);

        // Create a normal user
        $user = User::factory()->create(['id' => 2]);

        // Create a notice type
        $noticeType = NoticeType::factory()->create();

        // Acting as admin to create a notice
        $this->actingAs($admin)->post('/admin/notice/createOrUpdate', [
            'name' => 'User Notice 1',
            'description' => 'This is a notice for users.',
            'notice_type_id' => $noticeType->id,
            'expiry_date' => now()->subDays(7),
            'is_sticky' => true,
            'is_active' => true,
            'scheduled_at' => now(),
            'recurrence' => 'weekly',
            'recurrence_days' => ['Monday', 'Wednesday'],
        ]);

        // Log in as a normal user
        $this->actingAs($user);

        // Update the is_active to true for fetching
        Notice::where('id', 2)->update(['is_active' => true]);

        // Fetch notices for the normal user
        $response = $this->get('/notices/unread');

        $response->assertStatus(200)
            ->assertJsonMissing([
                'name' => 'User Notice 1',
                'description' => 'This is a notice for users.',
            ]);

        $this->assertDatabaseHas('notices', [
            'name' => 'User Notice 1',
            'description' => 'This is a notice for users.',
        ]);
    }

    public function test_assertInactiveNoticeIsNotFetched(): void
    {
        // Create an admin user
        $admin = User::factory()->create(['id' => 1]);

        // Create a normal user
        $user = User::factory()->create(['id' => 2]);

        // Create a notice type
        $noticeType = NoticeType::factory()->create();

        // Acting as admin to create a notice
        $this->actingAs($admin)->post('/admin/notice/createOrUpdate', [
            'name' => 'Inactive Notice',
            'description' => 'This is a notice for users.',
            'notice_type_id' => $noticeType->id,
            'expiry_date' => now()->addDays(7),
            'is_sticky' => true,
            'is_active' => false,
            'scheduled_at' => now(),
        ]);

        // Log in as a normal user
        $this->actingAs($user);

        // Update the is_active to false
        Notice::where('name', 'Inactive Notice')->update(['is_active' => false]);

        // Fetch notices for the normal user
        $response = $this->get('/notices/unread');

        $response->assertStatus(200)
            ->assertJsonMissing([
                'name' => 'User Notice 1',
                'description' => 'This is a notice for users.',
            ]);

        $this->assertDatabaseHas('notices', [
            'name' => 'Inactive Notice',
            'description' => 'This is a notice for users.',
        ]);
    }

    public function test_assertAdminCanUpdateNotice(): void
    {
        $admin = User::factory()->create(['id' => 1]);
        $this->actingAs($admin);

        $noticeType = NoticeType::factory()->create();
        $notice     = Notice::factory()->create([
            'name' => 'Old Notice',
            'description' => 'Old description',
            'notice_type_id' => $noticeType->id,
            'is_sticky' => false,
        ]);

        $response = $this->post("/admin/notice/createOrUpdate", [
            'id' => $notice->id,
            'name' => 'Updated Notice',
            'description' => 'Updated description',
            'notice_type_id' => $noticeType->id,
            'is_sticky' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('notices', [
            'id' => $notice->id,
            'name' => 'Updated Notice',
            'description' => 'Updated description',
        ]);
    }

    public function test_assertNoticesAreFetchedInDescendingOrder(): void
    {
        $user = User::factory()->create(['id' => 1]);
        $this->actingAs($user);

        $noticeType = NoticeType::factory()->create();

        $notice1 = Notice::factory()->create([
            'name' => 'First Notice',
            'created_at' => now()->subDays(2),
            'is_active' => true,
            'notice_type_id' => $noticeType->id,
        ]);
        $notice2 = Notice::factory()->create([
            'name' => 'Second Notice',
            'created_at' => now()->subDay(),
            'is_active' => true,
            'notice_type_id' => $noticeType->id,
        ]);
        $notice3 = Notice::factory()->create([
            'name' => 'Third Notice',
            'created_at' => now(),
            'is_active' => true,
            'notice_type_id' => $noticeType->id,
        ]);

        $response = $this->get('/notices/unread');

//        dd(Notice::all()->toArray());

        $response->assertStatus(200)
            ->assertSeeInOrder([$notice3->id, $notice2->id, $notice1->id]);
    }
}
