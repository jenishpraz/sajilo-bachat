<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Branch;
use App\Models\Event;
use App\Models\GalleryPhoto;
use App\Models\ListItem;
use App\Models\LoanService;
use App\Models\Notice;
use App\Models\Person;
use App\Models\SavingPlan;
use App\Models\SiteContent;
use App\Models\Statistic;
use App\Services\CalendarService;
use App\Services\ContentCache;

class HomeController extends Controller
{
    public function __invoke()
    {
        $data = ContentCache::remember('home', fn () => [
            'banners' => Banner::active()->ordered()->get(),
            'notices' => Notice::current()->with('category')
                ->orderByDesc('is_important')->orderByDesc('published_at')->orderByDesc('id')->take(6)->get(),
            'stats' => Statistic::active()->ordered()->get(),
            'content' => SiteContent::keyed(),
            'objectives' => ListItem::active()->ofType(ListItem::OBJECTIVE)->ordered()->get(),
            'values' => ListItem::active()->ofType(ListItem::VALUE)->ordered()->get(),
            'plans' => SavingPlan::active()->ordered()->take(6)->get(),
            'loans' => LoanService::active()->ordered()->take(6)->get(),
            'board' => Person::active()->inGroup(Person::BOARD)->where('section', 'board')->ordered()->take(8)->get(),
            'employees' => Person::active()->inGroup(Person::EMPLOYEE)->ordered()->take(8)->get(),
            'events' => Event::published()->with('category')->orderByDesc('event_date')->take(3)->get(),
            'photos' => GalleryPhoto::active()->ordered()->take(6)->get(),
            'branches' => Branch::active()->ordered()->take(3)->get(),
        ], 300);

        return view('site.home', $data + [
            'calendarItems' => CalendarService::items(),
            'upcoming' => CalendarService::upcoming(5),
        ]);
    }
}
