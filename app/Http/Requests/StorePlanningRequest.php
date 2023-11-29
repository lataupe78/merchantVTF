<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\SalePoint;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePlanningRequest extends FormRequest
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

        $salePointsIds = SalePoint::query()
            ->select('id')
            ->where('is_active', true)
            ->get()->pluck('id')->toArray();

        $workersIds = User::query()
            ->select('id')
            ->where('is_active', true)
            ->get()->pluck('id')->toArray();

            

        $rules =  [
            'title' => 'string|nullable|max:125',
            'current_date' => [
                'required', 'date_format:Y-m-d',
            ],
            'worker_id' => Rule::in($workersIds),
            'sale_point_id' => Rule::in($salePointsIds),

            'starts_at' => ['required', 'nullable'],
            'ends_at' => ['required', 'nullable'],

            'started_at' => ['sometimes', 'nullable'],
            'ended_at' => ['sometimes', 'nullable'],

            'comment' => ['string', 'nullable'],
        ];

        return $rules;
    }
}
