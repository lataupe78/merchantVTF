<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\SalePoint;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMultipleWorkingRangeRequest extends FormRequest
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
            "ranges" => "required|array|min:1",

            'ranges.*.worker_id' => Rule::in($workersIds),
            'ranges.*.sale_point_id' => Rule::in($salePointsIds),

            'ranges.*.current_date' => [
                'required', 'date_format:Y-m-d',
            ],


            'ranges.*.starts_at.date' => ['date_format:Y-m-d'],
            'ranges.*.starts_at.time' => ['date_format:H:i'],
            'ranges.*.ends_at.date' => ['date_format:Y-m-d'],
            'ranges.*.ends_at.time' => ['date_format:H:i'],
        ];


        return $rules;
    }
}
