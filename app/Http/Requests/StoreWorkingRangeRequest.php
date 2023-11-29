<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\SalePoint;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkingRangeRequest extends FormRequest
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

        // dd([
        //     'validationData' => $this->validationData(),
        //     // 'all' => $this->all(),

        // ]);


        $rules =  [
            'title' => 'string|nullable|max:125',

            'current_date' => [
                'required', 'date_format:Y-m-d',
            ],

            'worker_id' => Rule::in($workersIds),
            'sale_point_id' => Rule::in($salePointsIds),

            'starts_at' => ['array'],
            'ends_at' => ['array'],

            'starts_at.date' => ['date_format:Y-m-d'],
            'starts_at.time' => ['date_format:H:i'],
            'ends_at.date' => ['date_format:Y-m-d'],
            'ends_at.time' => ['date_format:H:i'],

            'started_at' => ['sometimes', 'nullable'],
            'ended_at' => ['sometimes', 'nullable'],

            'started_at.date' => ['required_with:started_at', 'date_format:Y-m-d'],
            'started_at.time' => ['required_with:started_at', 'date_format:H:i'],
            'ended_at.date' => ['required_with:ended_at', 'date_format:Y-m-d'],
            'ended_at.time' => ['required_with:ended_at', 'date_format:H:i'],

            'validated_started_at' => ['sometimes', 'nullable'],
            'validated_ended_at' => ['sometimes', 'nullable'],

            'validated_started_at.date' => ['nullable', 'date_format:Y-m-d'],
            'validated_started_at.time' => ['nullable', 'date_format:H:i'],
            'validated_ended_at.date' => ['nullable', 'date_format:Y-m-d'],
            'validated_ended_at.time' => ['nullable', 'date_format:H:i'],

            'comment' => ['string', 'nullable'],
        ];

        return $rules;
    }
}
