<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;

class FeeUpdateRequest extends FormRequest
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
            'broker' => 'required|string|unique:fees,broker,'. $this->index_id .",id,deleted_at,NULL",
            'segment_a' => 'required|numeric',
            'segment_b' => 'required|numeric',
            'segment_c' => 'required|numeric',
            'segment_d' => 'required|numeric',
            'segment_e' => 'required|numeric',
        ];
    }
}
