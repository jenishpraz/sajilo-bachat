<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\MediaService;
use App\Support\AdminResources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function edit(string $section)
    {
        $config = $this->config($section);
        $values = Setting::query()->pluck('value', 'key')->all();

        return view('admin.settings.edit', compact('section', 'config', 'values'));
    }

    public function update(Request $request, string $section)
    {
        $config = $this->config($section);
        $fields = $config['fields'];

        $rules = [];
        foreach ($fields as $f) {
            $rules[$f['name']] = $f['rules'];
            if ($f['type'] === 'image') {
                $rules['remove_'.$f['name']] = ['nullable', 'boolean'];
            }
        }
        $data = Validator::make($request->all(), $rules, [], collect($fields)->pluck('label', 'name')->all())->validate();

        $current = Setting::query()->pluck('value', 'key')->all();

        foreach ($fields as $f) {
            $name = $f['name'];

            if ($f['type'] === 'image') {
                $old = $current[$name] ?? null;
                $new = $old;
                if ($request->hasFile($name)) {
                    $new = MediaService::storeImage($request->file($name), $f['dir'], $f['max_width'] ?? 1600);
                } elseif ($request->boolean('remove_'.$name)) {
                    $new = null;
                }
                if ($old && $new !== $old) {
                    MediaService::delete($old);
                }
                Setting::put($name, $new, $section);
            } elseif ($f['type'] === 'checkbox') {
                Setting::put($name, $request->boolean($name) ? '1' : '0', $section);
            } else {
                Setting::put($name, $data[$name] ?? null, $section);
            }
        }

        return redirect()->route('admin.settings.edit', $section)->with('success', $config['title'].' saved.');
    }

    private function config(string $section): array
    {
        $all = AdminResources::settingsSections();
        abort_unless(isset($all[$section]), 404);

        return $all[$section];
    }
}
