<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\ApiRequest;

class ChildOfferDestroy extends ApiRequest
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
            'child' => 'required|exists:offers,id',
        ];
    }
}
