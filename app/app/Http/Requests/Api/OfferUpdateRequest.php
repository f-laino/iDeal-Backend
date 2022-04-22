<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use App\Models\Offer;

class OfferUpdateRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function authorize()
    {
        try {
            $offer = Offer::findByCode($this->code);
            $agent = auth('api')->user();
            return $offer->owner_id === $agent->id;
        } catch (\Exception $exception) {
            return false;
        }

    }

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
