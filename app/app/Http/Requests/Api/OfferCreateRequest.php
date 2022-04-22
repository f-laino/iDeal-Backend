<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;

class OfferCreateRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "car" => "required|exists:cars,id",
            "monthly_rate" => "required|numeric|min:0|max:99999",
            "duration" => "required|numeric|min:12|max:60",
            "deposit" => "required|numeric|min:0|max:999999",
            "distance" => "required|numeric|min:1000|max:100000",
        ];
    }
}
