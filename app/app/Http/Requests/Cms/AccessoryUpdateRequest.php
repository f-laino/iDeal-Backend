<?php

namespace App\Http\Requests\Cms;

use App\Models\CarAccessory;
use Illuminate\Foundation\Http\FormRequest;

class AccessoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $allowedTypes = implode(',', CarAccessory::$ALLOWED_TYPES);
        return [
            'id' => 'required|exists:car_accessories',
            'price' => "required|numeric|min:0|max:100000",
            'type' => "required|string|in:$allowedTypes",
            'available' => "required|boolean",
            'description' => 'nullable|string|min:1|max:190',
            'standard_description' => 'nullable|string|min:1|max:190',
            'short_description' => 'nullable|string|min:1|max:190',

        ];
    }
}
