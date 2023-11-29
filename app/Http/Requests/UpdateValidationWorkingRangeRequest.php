<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateValidationWorkingRangeRequest extends FormRequest
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
            // ->where('is_active', true)
            ->get()->pluck('id')->toArray();


        return [
            'current_date' => ['required'],
            'worker_id' => ['required', Rule::in($usersIds)],
            'sale_point_id' => ['sometimes', 'nullable'],

            'validated_started_at' => ['required', 'array'],
            'validated_ended_at' => ['required', 'array'],

            'validated_started_at.date' => ['required', 'date_format:Y-m-d'],
            'validated_started_at.time' => ['required', 'date_format:H:i'],
            'validated_ended_at.date' => ['required', 'date_format:Y-m-d'],
            'validated_ended_at.time' => ['required', 'date_format:H:i'],
        ];
    }
}
