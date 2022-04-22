<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;

class BrandUpdateRequest extends FormRequest
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
        return [
            'name' => 'required|string|min:3|max:100',
            'title'=> 'nullable|string|min:3|max:190',
            'description' => 'nullable|string',
            'logo' => 'required|active_url|max:190|unique:brands,logo,'.$this->brand->id,
            'logo_alt' => 'nullable|string|min:3|max:190',
        ];
    }
}
