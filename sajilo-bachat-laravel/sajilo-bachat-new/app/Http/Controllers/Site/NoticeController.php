<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function general(Request $request)
    {
        return $this->listing($request, Notice::GENERAL);
    }

    public function auction(Request $request)
    {
        return $this->listing($request, Notice::AUCTION);
    }

    private function listing(Request $request, string $type)
    {
        $query = Notice::published()->where('type', $type)->with('category');

        if (($term = trim((string) $request->input('q'))) !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(fn ($w) => $w->where('title_en', 'like', $like)->orWhere('title_ne', 'like', $like)
                ->orWhere('body_en', 'like', $like)->orWhere('body_ne', 'like', $like));
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        $notices = $query->orderByDesc('is_important')->orderByDesc('published_at')->orderByDesc('id')->paginate(10)->withQueryString();

        return view('site.notices.index', [
            'notices' => $notices,
            'type' => $type,
            'categories' => Category::ofType('notice')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $notice = Notice::published()->where('slug', $slug)->with('category')->firstOrFail();
        $related = Notice::published()->where('type', $notice->type)->whereKeyNot($notice->id)
            ->orderByDesc('published_at')->take(4)->get();

        return view('site.notices.show', compact('notice', 'related'));
    }
}
