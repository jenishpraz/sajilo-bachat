<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Site;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/
Route::get('/lang/{locale}', Site\LocaleController::class)->whereIn('locale', ['en', 'ne'])->name('locale');

Route::get('/', Site\HomeController::class)->name('home');
Route::get('/about-us', Site\PageController::class)->name('about');

Route::prefix('human-resources')->group(function () {
    Route::get('/board-members', [Site\PeopleController::class, 'board'])->name('people.board');
    Route::get('/current-employees', [Site\PeopleController::class, 'employees'])->name('people.employees');
    Route::get('/former-committee', [Site\PeopleController::class, 'formerCommittee'])->name('people.former-committee');
    Route::get('/former-employees', [Site\PeopleController::class, 'formerEmployees'])->name('people.former-employees');
});

Route::prefix('services')->group(function () {
    Route::get('/saving-plans', [Site\ServiceController::class, 'savingIndex'])->name('saving.index');
    Route::get('/saving-plans/{slug}', [Site\ServiceController::class, 'savingShow'])->name('saving.show');
    Route::get('/loan-services', [Site\ServiceController::class, 'loanIndex'])->name('loans.index');
    Route::get('/loan-services/{slug}', [Site\ServiceController::class, 'loanShow'])->name('loans.show');
});

Route::get('/calculators', Site\CalculatorController::class)->name('calculators');

Route::prefix('activities')->group(function () {
    Route::get('/photo-gallery', [Site\ActivityController::class, 'photos'])->name('gallery.photos');
    Route::get('/video-gallery', [Site\ActivityController::class, 'videos'])->name('gallery.videos');
    Route::get('/events', [Site\ActivityController::class, 'events'])->name('events.index');
    Route::get('/events/{slug}', [Site\ActivityController::class, 'eventShow'])->name('events.show');
});
Route::get('/calendar', [Site\ActivityController::class, 'calendar'])->name('calendar');

Route::get('/branches', Site\BranchController::class)->name('branches');

Route::get('/downloads', [Site\DownloadController::class, 'index'])->name('downloads');
Route::get('/downloads/{download}/file', [Site\DownloadController::class, 'file'])->whereNumber('download')->name('downloads.file');

Route::get('/notices', [Site\NoticeController::class, 'general'])->name('notices.index');
Route::get('/auction-notices', [Site\NoticeController::class, 'auction'])->name('notices.auction');
Route::get('/notices/{slug}', [Site\NoticeController::class, 'show'])->name('notices.show');

Route::get('/contact-us', [Site\ContactController::class, 'show'])->name('contact');
Route::post('/contact-us', [Site\ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');

Route::get('/become-a-member', [Site\MembershipController::class, 'create'])->name('membership.create');
Route::post('/become-a-member', [Site\MembershipController::class, 'store'])->middleware('throttle:6,1')->name('membership.store');
Route::get('/become-a-member/thank-you', [Site\MembershipController::class, 'thanks'])->name('membership.thanks');

Route::get('/sitemap.xml', [Site\SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [Site\SitemapController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Admin dashboard
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [Admin\AuthController::class, 'login'])->middleware('throttle:20,1')->name('login.attempt');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::get('/password', [Admin\AuthController::class, 'editPassword'])->name('password.edit');
        Route::put('/password', [Admin\AuthController::class, 'updatePassword'])->name('password.update');

        // Generic CRUD modules (see App\Support\AdminResources)
        Route::prefix('r/{resource}')->group(function () {
            Route::get('/', [Admin\ResourceController::class, 'index'])->name('resource.index');
            Route::get('/create', [Admin\ResourceController::class, 'create'])->name('resource.create');
            Route::post('/', [Admin\ResourceController::class, 'store'])->name('resource.store');
            Route::get('/{id}/edit', [Admin\ResourceController::class, 'edit'])->whereNumber('id')->name('resource.edit');
            Route::put('/{id}', [Admin\ResourceController::class, 'update'])->whereNumber('id')->name('resource.update');
            Route::delete('/{id}', [Admin\ResourceController::class, 'destroy'])->whereNumber('id')->name('resource.destroy');
            Route::post('/{id}/toggle', [Admin\ResourceController::class, 'toggle'])->whereNumber('id')->name('resource.toggle');
        });

        Route::get('/settings/{section}', [Admin\SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/{section}', [Admin\SettingsController::class, 'update'])->name('settings.update');

        Route::get('/applications', [Admin\ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [Admin\ApplicationController::class, 'show'])->name('applications.show');
        Route::put('/applications/{application}', [Admin\ApplicationController::class, 'update'])->name('applications.update');
        Route::get('/applications/{application}/document', [Admin\ApplicationController::class, 'document'])->name('applications.document');
        Route::delete('/applications/{application}', [Admin\ApplicationController::class, 'destroy'])->name('applications.destroy');

        Route::get('/messages', [Admin\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}', [Admin\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{message}/toggle-read', [Admin\MessageController::class, 'toggleRead'])->name('messages.toggle');
        Route::delete('/messages/{message}', [Admin\MessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('/translations', [Admin\TranslationController::class, 'index'])->name('translations.index');
        Route::put('/translations', [Admin\TranslationController::class, 'update'])->name('translations.update');

        Route::middleware('role:super_admin')->group(function () {
            Route::resource('users', Admin\UserController::class)->except('show');
        });
    });
});
