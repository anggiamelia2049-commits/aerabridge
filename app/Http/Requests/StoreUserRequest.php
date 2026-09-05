<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya super_admin yang boleh submit form ini
        return $this->user()->role === 'super_admin';
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(['instansi', 'petugas'])], 
            // sengaja TIDAK termasuk 'super_admin' & 'warga' di sini —
            // super_admin cuma dibuat lewat Tinker/seeder,
            // warga daftar sendiri lewat register publik
        ];
    }
}