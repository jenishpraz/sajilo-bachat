<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Download;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $query = Download::active()->with('category')->ordered();

        if (($term = trim((string) $request->input('q'))) !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(fn ($w) => $w->where('title_en', 'like', $like)->orWhere('title_ne', 'like', $like)
                ->orWhere('description_en', 'like', $like));
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        return view('site.downloads', [
            'downloads' => $query->paginate(12)->withQueryString(),
            'categories' => Category::ofType('download')->get(),
        ]);
    }

    public function file(Download $download)
    {
        $path = MediaService::absolutePath($download->file);
        abort_unless($download->is_active && $path && File::isFile($path), 404);

        $download->increment('download_count');

        $name = (Str::slug($download->title_en) ?: 'document').'.'.pathinfo($download->file, PATHINFO_EXTENSION);

        return response()->download($path, $name);
    }
}
