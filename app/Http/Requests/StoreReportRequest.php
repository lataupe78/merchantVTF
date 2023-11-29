<?php

namespace App\Http\Requests;

use App\Models\SalePoint;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
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

        $rules =  [
            'date' => [
                'required', 'date', // 'before:tomorrow',

                // TODO : implementer  unique Rule pertinente :
                // comme il peut y avoir plusieurs reports pour un même sale_point / date
                // si plusieurs vendeurs reportent leur chiffre d'affaire
                // la rule s'appliqe avec un seller_id unique
                // cf UpdateReportRequest
               
                // comme seller_id n'est pas défini actuellement ( seul l'admin crée les reports sans tenir compte des seller )

                // check http://merchant3.test/manager/reports/create
                
                // je laisse en commentaire pour li'instant

                // Rule::unique('reports', 'date')
                //     ->where('sale_point_id', $this->input('sale_point_id')),
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
