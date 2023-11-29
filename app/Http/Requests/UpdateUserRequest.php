<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // dd($this->id);

        $rolesIds = Role::query()
            ->where('name', '<>', 'Super Admin')
            ->get()->pluck('id')->toArray();

        return [
            'name' => 'required|string|max:125',
            'email' => [
                'required', 'string', 'email', 'max:125',
                \Illuminate\Validation\Rule::unique(User::class)->ignore($this->id)
            ],
            'is_active' => 'boolean',
            'roles' => ['sometimes', 'array', 'min:0'],
            'roles.*' => Rule::in($rolesIds)
        ];
    }
}
