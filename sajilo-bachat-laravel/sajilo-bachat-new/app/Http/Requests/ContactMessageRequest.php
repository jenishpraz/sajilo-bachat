<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'subject' => ['required', 'string', 'min:3', 'max:190'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'website' => ['nullable', 'max:0'],   // honeypot
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => ui('contact.name'), 'email' => ui('contact.email'), 'phone' => ui('contact.phone'),
            'subject' => ui('contact.subject'), 'message' => ui('contact.message'),
        ];
    }
}
