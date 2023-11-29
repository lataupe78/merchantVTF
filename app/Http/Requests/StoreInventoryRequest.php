<?php

namespace App\Http\Requests;

use App\Models\Stock;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
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

        $stocksIds = Stock::query()
            ->whereHas('sale_point', function ($query) {
                $query->whereHas('workers', function ($query) {
                    $query->where('users.id', auth()->id());
                });
            })
            ->get()
            ->pluck('id')
            ->toArray();

        // Log::info(['stockIds' => $stocksIds]);


        return [
            'stocks' => ['array', 'required'],

            'stocks.*.assets.*.id' => Rule::in($stocksIds),
            'stocks.*.assets.*.stock_new' => ['nullable'],
        ];
    }
}
