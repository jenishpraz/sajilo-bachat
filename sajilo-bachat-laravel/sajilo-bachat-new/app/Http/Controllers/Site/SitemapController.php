<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\LoanService;
use App\Models\Notice;
use App\Models\SavingPlan;
use App\Services\ContentCache;

class SitemapController extends Controller
{
    public function sitemap()
    {
        $urls = ContentCache::remember('sitemap', function () {
            $urls = collect([
                route('home'), route('about'), route('people.board'), route('people.employees'),
                route('people.former-committee'), route('people.former-employees'),
                route('saving.index'), route('loans.index'), route('calculators'),
                route('gallery.photos'), route('gallery.videos'), route('events.index'), route('calendar'),
                route('branches'), route('downloads'), route('notices.index'), route('notices.auction'),
                route('contact'), route('membership.create'),
            ])->map(fn ($u) => ['loc' => $u, 'lastmod' => null]);

            return $urls
                ->merge(SavingPlan::active()->get()->map(fn ($m) => ['loc' => route('saving.show', $m->slug), 'lastmod' => $m->updated_at]))
                ->merge(LoanService::active()->get()->map(fn ($m) => ['loc' => route('loans.show', $m->slug), 'lastmod' => $m->updated_at]))
                ->merge(Notice::published()->get()->map(fn ($m) => ['loc' => route('notices.show', $m->slug), 'lastmod' => $m->updated_at]))
                ->merge(Event::published()->get()->map(fn ($m) => ['loc' => route('events.show', $m->slug), 'lastmod' => $m->updated_at]))
                ->all();
        });

        return response()->view('site.sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $body = "User-agent: *\nDisallow: /admin\nDisallow: /admin/\nAllow: /\n\nSitemap: ".route('sitemap')."\n";

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }
}
