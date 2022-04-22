<?php

namespace App\Http\Requests\Cms;

use App\Models\Offer;
use App\Models\OfferAttributes;
use Illuminate\Foundation\Http\FormRequest;

class OfferUpdateRequest extends FormRequest
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
        $labels = OfferAttributes::asRule(OfferAttributes::$rentLabels);
        $brokers = implode(',', array_keys(Offer::$BROKERS));
        return [
            "broker" => "required|string|in:$brokers",
            "carversion" => "required_if:custom-car-enabled,0",
            "custom_car" => "required_if:custom-car-enabled,1",
            "monthly_rate" => "required|numeric",
            "duration" => "required|numeric",
            "deposit" => "required|numeric",
            "distance" => "required|numeric",
            "left_label" => "nullable|string|in:$labels",
            "right_label" => "nullable|string|in:$labels",
        ];
    }
}
