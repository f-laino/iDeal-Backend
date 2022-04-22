<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;

class RequestNewOfferRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "brand" => "required|string",
            "model" => "required|string",
            "monthly_rate" => "nullable|numeric|min:0|max:99999",
            "duration" => "nullable|numeric|min:0|max:90",
            "deposit" => "nullable|numeric|min:0|max:999999",
            "distance" => "nullable|numeric|min:0",
            "services" => "nullable|string",
            "note" => "nullable|string",
        ];
    }
}
