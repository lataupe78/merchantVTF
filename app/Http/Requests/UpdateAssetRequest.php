<?php

namespace App\Http\Requests;

use App\Models\AssetUnit;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
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
        $assetUnitsIds = AssetUnit::select('id')->pluck('id')->toArray();

 

        return [
            'name' => [
                'required', 'min:2',
                Rule::unique('assets', 'name')->ignore($this->asset->id ?? 0)
            ],
            'description' => 'nullable',

            'asset_unit_id' => ['required', Rule::in($assetUnitsIds)]
        ];
    }
}
