<?php

namespace App\Http\Requests;

use App\Models\SalePoint;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
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

        $ReportId = request()->route('report')->id;

        $salePointsIds = SalePoint::query()
            ->select('id')
            ->where('is_active', true)
            ->get()->pluck('id')->toArray();

        $rules =  [
            'date' => [
                'required', 'date', 'before:tomorrow',

                // comme il peut y avoir plusieurs reports pour un même sale_point / date
                // si plusieurs vendeurs reportent leur chiffre d'affaire
                // la rule s'appliqe avec un seller_id unique

                Rule::unique('reports', 'date', 'seller_id')
                    ->where('sale_point_id', $this->input('sale_point_id'))
                    ->where('seller_id', $this->input('seller_id'))
                    ->ignore($ReportId, 'id'),
            ],

            'cash' => ['sometimes', 'numeric', 'nullable'],
            'cash_reel' => ['sometimes', 'numeric', 'nullable'],
            'card' => ['sometimes', 'numeric', 'nullable'],
            'card_reel' => ['sometimes', 'numeric', 'nullable'],

            'comment' => ['string', 'nullable'],
            'sale_point_id' => Rule::in($salePointsIds)
        ];

        return $rules;
    }


    public function messages()
    {
        return [
            'date.unique' => 'Report for sale_point on selected date already exists.',
        ];
    }
}
