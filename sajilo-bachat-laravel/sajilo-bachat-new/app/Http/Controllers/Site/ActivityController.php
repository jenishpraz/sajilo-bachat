<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Services\CalendarService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function photos(Request $request)
    {
        $query = GalleryPhoto::active()->with('category')->ordered();
        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        return view('site.gallery.photos', [
            'photos' => $query->paginate(24)->withQueryString(),
            'categories' => Category::ofType('gallery')->get(),
        ]);
    }

    public function videos(Request $request)
    {
        $query = GalleryVideo::active()->with('category')->ordered();
        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        return view('site.gallery.videos', [
            'videos' => $query->paginate(12)->withQueryString(),
            'categories' => Category::ofType('video')->get(),
        ]);
    }

    public function events(Request $request)
    {
        $query = Event::published()->with('category');
        $when = $request->input('when');

        if ($when === 'upcoming') {
            $query->upcoming()->orderBy('event_date');
        } elseif ($when === 'past') {
            $query->whereDate('event_date', '<', today())->orderByDesc('event_date');
        } else {
            $query->orderByDesc('event_date');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        return view('site.events.index', [
            'events' => $query->paginate(9)->withQueryString(),
            'categories' => Category::ofType('event')->get(),
            'when' => $when,
        ]);
    }

    public function eventShow(string $slug)
    {
        $event = Event::published()->where('slug', $slug)->with('category')->firstOrFail();
        $others = Event::published()->whereKeyNot($event->id)->orderByDesc('event_date')->take(3)->get();

        return view('site.events.show', compact('event', 'others'));
    }

    public function calendar()
    {
        return view('site.calendar', [
            'calendarItems' => CalendarService::items(),
            'upcoming' => CalendarService::upcoming(10),
        ]);
    }
}
