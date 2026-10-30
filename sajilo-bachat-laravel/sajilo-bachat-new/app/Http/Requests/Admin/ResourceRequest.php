<?php

namespace App\Http\Requests\Admin;

use App\Support\AdminResources;
use Illuminate\Foundation\Http\FormRequest;

/** Validates any admin module form using the rules declared in AdminResources. */
class ResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $def = AdminResources::get((string) $this->route('resource'));
        abort_unless($def, 404);

        return AdminResources::rules($def['fields'], $this->isMethod('PUT') || $this->isMethod('PATCH'));
    }

    public function attributes(): array
    {
        $def = AdminResources::get((string) $this->route('resource'));

        return $def ? collect($def['fields'])->pluck('label', 'name')->all() : [];
    }
}
