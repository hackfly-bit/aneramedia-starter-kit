<?php

namespace App\Http\Requests\permissions;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $permission = $this->route('permission');
        
        return [
            'name' => 'sometimes|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ];
    }
}
