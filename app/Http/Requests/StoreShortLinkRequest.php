<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShortLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'original_url' => [
                'required',
                'string',
                'url:http,https',
                'max:2048',
            ],
            'custom_alias' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[A-Za-z0-9-]+$/',
                'unique:short_links,code',
            ],
            'expires_at' => [
                'nullable',
                'date',
                'after:now',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'original_url.required' => 'URL wajib diisi.',
            'original_url.url' => 'URL harus menggunakan http:// atau https://.',
            'original_url.max' => 'Panjang URL maksimal 2048 karakter.',
            'custom_alias.regex' => 'Custom alias hanya boleh berisi huruf, angka, dan tanda -.',
            'custom_alias.unique' => 'Custom alias sudah digunakan. Silakan pilih alias lain.',
            'custom_alias.max' => 'Custom alias maksimal 100 karakter.',
            'expires_at.date' => 'Format waktu kedaluwarsa tidak valid.',
            'expires_at.after' => 'Waktu kedaluwarsa harus setelah waktu sekarang.',
        ];
    }
}
