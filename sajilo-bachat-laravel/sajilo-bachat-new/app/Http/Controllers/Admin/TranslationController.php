<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UiText;
use App\Services\UiTextService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * "Language & Website Content": edit any interface string in English and Nepali.
 * Only values that differ from the shipped defaults are stored.
 */
class TranslationController extends Controller
{
    public function index(Request $request, UiTextService $ui)
    {
        $en = $ui->defaults('en');
        $ne = $ui->defaults('ne');
        $overrides = UiText::all()->keyBy('key');

        $groups = collect(array_keys($en))->map(fn ($k) => Str::before($k, '.'))->unique()->values();
        $group = $request->input('group', $groups->first());
        $term = trim((string) $request->input('q'));

        $rows = collect($en)->filter(function ($text, $key) use ($group, $term, $ne, $overrides) {
            if ($term !== '') {
                $hay = mb_strtolower($key.' '.$text.' '.($ne[$key] ?? '').' '.($overrides[$key]->en ?? '').' '.($overrides[$key]->ne ?? ''));

                return str_contains($hay, mb_strtolower($term));
            }

            return Str::before($key, '.') === $group;
        })->map(fn ($text, $key) => [
            'key' => $key,
            'default_en' => $text,
            'default_ne' => $ne[$key] ?? '',
            'en' => $overrides[$key]->en ?? null,
            'ne' => $overrides[$key]->ne ?? null,
        ])->values();

        return view('admin.translations', compact('rows', 'groups', 'group', 'term'));
    }

    public function update(Request $request, UiTextService $ui)
    {
        $request->validate([
            'text' => ['required', 'array'],
            'text.*.en' => ['nullable', 'string', 'max:2000'],
            'text.*.ne' => ['nullable', 'string', 'max:2000'],
        ]);

        $defaultsEn = $ui->defaults('en');
        $defaultsNe = $ui->defaults('ne');

        foreach ($request->input('text') as $encodedKey => $values) {
            $key = str_replace('__', '.', $encodedKey);           // dots are not safe in input names
            if (! array_key_exists($key, $defaultsEn)) {
                continue;
            }
            $en = trim((string) ($values['en'] ?? ''));
            $ne = trim((string) ($values['ne'] ?? ''));
            // Blank or identical to the default = no override.
            $en = ($en === '' || $en === $defaultsEn[$key]) ? null : $en;
            $ne = ($ne === '' || $ne === ($defaultsNe[$key] ?? '')) ? null : $ne;

            if ($en === null && $ne === null) {
                UiText::where('key', $key)->delete();
            } else {
                UiText::updateOrCreate(['key' => $key], ['en' => $en, 'ne' => $ne]);
            }
        }

        return back()->with('success', 'Website text saved.');
    }
}
