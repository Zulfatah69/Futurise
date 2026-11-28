<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccessRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $role = $this->route('role');
        $roleId = is_object($role) ? $role->id : $role;

        return [
            'kode'      => ['sometimes', 'required', 'string', 'max:50', Rule::unique('peran_akses', 'kode')->ignore($roleId)],
            'nama'      => ['sometimes', 'required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'izin'      => ['nullable', 'array'],
        ];
    }
}
