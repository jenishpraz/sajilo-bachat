<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\LoanService;
use App\Models\MembershipApplication;
use App\Models\Notice;
use App\Models\Person;
use App\Models\User;
use App\Services\MediaService;
use App\Support\AdminResources;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'super_admin', 'is_active' => true, 'must_change_password' => false]);
    }

    public function test_public_pages_render(): void
    {
        foreach (['home', 'about', 'people.board', 'people.employees', 'people.former-committee', 'people.former-employees',
            'saving.index', 'loans.index', 'calculators', 'gallery.photos', 'gallery.videos', 'events.index', 'calendar',
            'branches', 'downloads', 'notices.index', 'notices.auction', 'contact', 'membership.create', 'sitemap', 'robots'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_detail_pages_and_404(): void
    {
        $loan = LoanService::first();
        $this->get(route('loans.show', $loan->slug))->assertOk()->assertSee($loan->name_en);
        $this->get(route('notices.show', Notice::first()->slug))->assertOk();
        $this->get('/services/loan-services/does-not-exist')->assertNotFound();
    }

    public function test_committee_is_seeded_from_pdf(): void
    {
        $this->assertSame(42, Person::where('group', 'board')->count());
        $this->assertTrue(Person::where('name_ne', 'बालमुकुन्द प्रजापति')->where('designation_ne', 'संचालक समिति अध्यक्ष')->exists());
    }

    public function test_language_switch_persists(): void
    {
        $this->get(route('locale', 'ne'))->assertRedirect();
        $this->get(route('home'))->assertSee('हाम्रो बारेमा');
        $this->get(route('locale', 'en'));
        $this->get(route('home'))->assertSee('About Us');
    }

    public function test_membership_application_is_stored(): void
    {
        $response = $this->post(route('membership.store'), [
            'full_name' => 'Test Member', 'date_of_birth' => '1990-01-01', 'gender' => 'female', 'phone' => '9800000000',
            'permanent_address' => 'Somewhere', 'consent' => '1',
            'document' => UploadedFile::fake()->create('id.pdf', 100, 'application/pdf'),
        ]);
        $response->assertRedirect(route('membership.thanks'));
        $app = MembershipApplication::firstOrFail();
        $this->assertMatchesRegularExpression('/^SB-\d{4}-[A-Z0-9]{6}$/', $app->reference_no);
        $this->assertTrue(File::isFile(MediaService::absolutePath($app->document)));
        $this->followRedirects($response)->assertSee($app->reference_no);
    }

    public function test_membership_validation(): void
    {
        $this->post(route('membership.store'), [])->assertSessionHasErrors(['full_name', 'date_of_birth', 'gender', 'phone', 'permanent_address', 'consent']);
        $this->post(route('membership.store'), ['website' => 'spam'])->assertSessionHasErrors('website');
    }

    public function test_contact_form_is_stored(): void
    {
        $this->post(route('contact.store'), ['name' => 'A B', 'email' => 'a@b.com', 'subject' => 'Hello there', 'message' => 'This is a test message.'])
            ->assertRedirect(route('contact'));
        $this->assertDatabaseHas('contact_messages', ['email' => 'a@b.com']);
    }

    public function test_admin_requires_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.resource.index', 'banners'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_login_and_forced_password_change(): void
    {
        $user = User::factory()->create(['email' => 'boss@example.com', 'password' => 'Secret#12345', 'role' => 'super_admin', 'must_change_password' => true]);
        $this->post(route('admin.login.attempt'), ['email' => 'boss@example.com', 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->post(route('admin.login.attempt'), ['email' => 'boss@example.com', 'password' => 'Secret#12345'])->assertRedirect();
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.password.edit'));
        $this->put(route('admin.password.update'), ['current_password' => 'Secret#12345', 'password' => 'NewSecret#9876', 'password_confirmation' => 'NewSecret#9876'])
            ->assertRedirect(route('admin.dashboard'));
        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_every_admin_module_lists_and_opens_create_form(): void
    {
        $this->actingAs($this->admin());
        foreach (array_keys(AdminResources::all()) as $key) {
            $this->get(route('admin.resource.index', $key))->assertOk();
            if ($key !== 'about') {
                $this->get(route('admin.resource.create', $key))->assertOk();
            }
        }
        foreach (['general', 'logo', 'contact', 'footer', 'seo'] as $s) {
            $this->get(route('admin.settings.edit', $s))->assertOk();
        }
        foreach (['admin.applications.index', 'admin.messages.index', 'admin.users.index', 'admin.translations.index', 'admin.password.edit'] as $r) {
            $this->get(route($r))->assertOk();
        }
    }

    public function test_admin_crud_with_image_upload_and_cleanup(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.resource.store', 'banners'), [
            'title_en' => 'Hello', 'title_ne' => 'नमस्ते', 'is_active' => '1', 'sort_order' => 1,
            'image' => UploadedFile::fake()->image('b.jpg', 1200, 600),
        ])->assertRedirect(route('admin.resource.index', 'banners'));

        $banner = Banner::firstOrFail();
        $this->assertTrue(File::isFile(MediaService::absolutePath($banner->image)));
        $this->get(route('home'))->assertSee('Hello');

        $this->put(route('admin.resource.update', ['banners', $banner->id]), ['title_en' => 'Changed', 'is_active' => '1'])->assertRedirect();
        $this->assertSame('Changed', $banner->fresh()->title_en);
        $this->assertTrue(File::isFile(MediaService::absolutePath($banner->image))); // kept when no new upload

        $path = $banner->image;
        $this->post(route('admin.resource.toggle', ['banners', $banner->id]))->assertRedirect();
        $this->assertFalse($banner->fresh()->is_active);

        $this->delete(route('admin.resource.destroy', ['banners', $banner->id]))->assertRedirect();
        $this->assertFalse(File::exists(MediaService::absolutePath($path))); // no orphaned file
    }

    public function test_notice_with_pdf_and_publish_rules(): void
    {
        $this->actingAs($this->admin());
        $this->post(route('admin.resource.store', 'notices'), [
            'title_en' => 'Auction test', 'is_published' => '1', 'published_at' => now()->subDay()->format('Y-m-d\TH:i'),
            'attachment' => UploadedFile::fake()->create('n.pdf', 50, 'application/pdf'),
        ])->assertRedirect();
        $notice = Notice::where('title_en', 'Auction test')->firstOrFail();
        $this->assertSame('general', $notice->type);
        $this->get(route('notices.show', $notice->slug))->assertOk();

        $notice->update(['is_published' => false]);
        $this->get(route('notices.show', $notice->slug))->assertNotFound();
    }

    public function test_editor_cannot_manage_users(): void
    {
        $editor = User::factory()->create(['role' => 'editor', 'is_active' => true, 'must_change_password' => false]);
        $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_dangerous_uploads_are_rejected(): void
    {
        $this->actingAs($this->admin());
        $this->post(route('admin.resource.store', 'banners'), [
            'title_en' => 'x', 'image' => UploadedFile::fake()->create('evil.php', 10, 'application/x-php'),
        ])->assertSessionHasErrors('image');
    }
}
