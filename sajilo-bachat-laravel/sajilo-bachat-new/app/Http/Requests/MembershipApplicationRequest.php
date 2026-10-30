<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MembershipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:3', 'max:150'],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'gender' => ['required', 'in:male,female,other'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'email' => ['nullable', 'email:rfc', 'max:190'],
            'permanent_address' => ['required', 'string', 'max:255'],
            'current_address' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:150'],
            'membership_type_id' => ['nullable', Rule::exists('categories', 'id')->where('type', 'membership')],
            'message' => ['nullable', 'string', 'max:2000'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'consent' => ['accepted'],
            'website' => ['nullable', 'max:0'],   // honeypot: humans leave this empty
        ];
    }

    public function messages(): array
    {
        return [
            'consent.accepted' => ui('membership.consent_required'),
            'phone.regex' => ui('membership.phone_invalid'),
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => ui('membership.full_name'),
            'date_of_birth' => ui('membership.dob'),
            'gender' => ui('membership.gender'),
            'phone' => ui('membership.phone'),
            'email' => ui('membership.email'),
            'permanent_address' => ui('membership.permanent_address'),
            'document' => ui('membership.document'),
        ];
    }
}
