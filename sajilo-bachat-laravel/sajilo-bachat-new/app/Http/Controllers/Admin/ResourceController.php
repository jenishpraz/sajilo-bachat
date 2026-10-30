<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResourceRequest;
use App\Services\MediaService;
use App\Support\AdminResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * One controller drives every simple CRUD module (banners, plans, notices,
 * people, gallery, ...). Behaviour is declared in App\Support\AdminResources.
 */
class ResourceController extends Controller
{
    /** Files replaced/removed during an update, deleted only after the save succeeded. */
    private array $staleFiles = [];

    public function index(Request $request, string $resource)
    {
        $def = $this->definition($resource);
        $query = $this->query($def);

        if (($term = trim((string) $request->input('q'))) !== '' && ! empty($def['search'])) {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(function (Builder $w) use ($def, $like) {
                foreach ($def['search'] as $column) {
                    $w->orWhere($column, 'like', $like);
                }
            });
        }

        foreach ($def['filters'] ?? [] as $column => $filter) {
            $value = $request->input($column);
            if ($value === null || $value === '') {
                continue;
            }
            $filter['type'] === 'bool' ? $query->where($column, $value === '1') : $query->where($column, $value);
        }

        foreach ($def['order'] ?? [['id', 'desc']] as [$column, $dir]) {
            $query->orderBy($column, $dir);
        }

        $items = $query->paginate(15)->withQueryString();

        return view('admin.resource.index', ['def' => $def, 'items' => $items, 'resource' => $resource]);
    }

    public function create(string $resource)
    {
        $def = $this->definition($resource);
        abort_unless($def['can']['create'] ?? true, 403);

        return view('admin.resource.form', ['def' => $def, 'resource' => $resource, 'model' => new $def['model']]);
    }

    public function store(ResourceRequest $request, string $resource): RedirectResponse
    {
        $def = $this->definition($resource);
        abort_unless($def['can']['create'] ?? true, 403);

        $data = $this->payload($request, $def, null) + ($def['scope'] ?? []);

        DB::transaction(fn () => $def['model']::create($data));

        return redirect()->route('admin.resource.index', $resource)
            ->with('success', $def['singular'].' created successfully.');
    }

    public function edit(string $resource, int $id)
    {
        $def = $this->definition($resource);

        return view('admin.resource.form', [
            'def' => $def, 'resource' => $resource, 'model' => $this->query($def)->findOrFail($id),
        ]);
    }

    public function update(ResourceRequest $request, string $resource, int $id): RedirectResponse
    {
        $def = $this->definition($resource);
        $model = $this->query($def)->findOrFail($id);

        $data = $this->payload($request, $def, $model);
        DB::transaction(fn () => $model->update($data));

        foreach ($this->staleFiles as $path) {
            MediaService::delete($path);
        }

        return redirect()->route('admin.resource.index', $resource)
            ->with('success', $def['singular'].' updated successfully.');
    }

    public function destroy(string $resource, int $id): RedirectResponse
    {
        $def = $this->definition($resource);
        abort_unless($def['can']['delete'] ?? true, 403);

        // The HasFiles trait removes uploaded files with the record.
        $this->query($def)->findOrFail($id)->delete();

        return redirect()->back()->with('success', $def['singular'].' deleted.');
    }

    /** Publish / unpublish (or activate / deactivate) with one click. */
    public function toggle(string $resource, int $id): RedirectResponse
    {
        $def = $this->definition($resource);
        abort_unless(! empty($def['toggle']), 404);

        $model = $this->query($def)->findOrFail($id);
        $column = $def['toggle'];
        $model->update([$column => ! $model->{$column}]);

        return redirect()->back()->with('success', $def['singular'].($model->{$column} ? ' published.' : ' unpublished.'));
    }

    // ------------------------------------------------------------------ internals

    private function definition(string $resource): array
    {
        $def = AdminResources::get($resource);
        abort_unless($def, 404);

        return $def;
    }

    private function query(array $def): Builder
    {
        $query = (new $def['model'])->newQuery();

        foreach ($def['scope'] ?? [] as $column => $value) {
            $query->where($column, $value);
        }
        if (! empty($def['with'])) {
            $query->with($def['with']);
        }

        return $query;
    }

    /** Turn the validated request into column values, handling uploads. */
    private function payload(ResourceRequest $request, array $def, ?Model $model): array
    {
        $this->staleFiles = [];
        $data = [];

        foreach ($def['fields'] as $f) {
            $name = $f['name'];

            switch ($f['type']) {
                case 'image':
                case 'file':
                    $old = $model?->getAttribute($name);
                    $new = $old;

                    if ($request->hasFile($name)) {
                        $file = $request->file($name);
                        $new = $f['type'] === 'image'
                            ? MediaService::storeImage($file, $f['dir'], $f['max_width'] ?? 1600)
                            : MediaService::storeFile($file, $f['dir']);
                    } elseif (($existing = $request->input($name.'__existing')) && ! empty($f['library'])
                        && in_array($existing, MediaService::library($f['library']), true)) {
                        $new = $existing;
                    } elseif ($request->boolean('remove_'.$name) && empty($f['required'])) {
                        $new = null;
                    }

                    if ($old && $new !== $old) {
                        $this->staleFiles[] = $old;
                    }
                    $data[$name] = $new;
                    break;

                case 'checkbox':
                    $data[$name] = $request->boolean($name);
                    break;

                case 'number':
                    $data[$name] = $request->filled($name) ? (int) $request->input($name) : ($name === 'sort_order' ? 0 : null);
                    break;

                default:
                    $data[$name] = $request->input($name);
            }
        }

        return $data;
    }
}
