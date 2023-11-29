<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSalePointRequest extends FormRequest
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
        $usersIds = User::query()
            ->select('id')
            ->where('is_active', true)
            ->get()->pluck('id')->toArray();

        return [
            'name' => ['required', 'min:2'],
            'is_active' => 'boolean',
            'address',
            'city',
            'longitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'latitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],

            'workers' => ['sometimes', 'array', 'min:1'],

            'workers.*' => Rule::in($usersIds)
        ];
    }
}
