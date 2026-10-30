<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ListItem;
use App\Models\SiteContent;
use App\Models\Statistic;
use App\Services\ContentCache;

class PageController extends Controller
{
    public function __invoke()
    {
        return view('site.about', ContentCache::remember('about', fn () => [
            'content' => SiteContent::keyed(),
            'objectives' => ListItem::active()->ofType(ListItem::OBJECTIVE)->ordered()->get(),
            'values' => ListItem::active()->ofType(ListItem::VALUE)->ordered()->get(),
            'achievements' => ListItem::active()->ofType(ListItem::ACHIEVEMENT)->ordered()->get(),
            'stats' => Statistic::active()->ordered()->get(),
        ]));
    }
}
