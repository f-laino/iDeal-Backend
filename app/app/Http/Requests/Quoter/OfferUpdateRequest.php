<?php

namespace App\Http\Requests\Quoter;

class OfferUpdateRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //offer params
            "offer.code" => "required|string",
            "offer.crm_id" => "nullable|numeric",
            "offer.parent_id" => "nullable|numeric",
            "offer.monthly_rate" => "required|numeric",
            "offer.web_monthly_rate" => "nullable|numeric",
            "offer.duration" => "required|numeric",
            "offer.deposit" => "required|numeric",
            "offer.distance" => "required|numeric",
            "offer.broker" => "required|string",
            "offer.status" => "nullable|boolean",
            "offer.highlighted" => "nullable|boolean",
            "offer.deleted" => "nullable|string",
            //tag params
            "tag.value" => "nullable|string|min:2|max:250",
            "tag.description" => "nullable|string|min:2|max:250",
            //childs offers
            "childs.*.monthly_rate" => "required|numeric",
            "childs.*.web_monthly_rate" => "nullable|numeric",
            "childs.*.deposit" => "required|numeric",
            "childs.*.distance" => "required|numeric",
            "childs.*.duration" => "required|numeric",
            "childs.*.deleted" => "nullable|string",
            //car params
            "car.segment" => "required|string|in:A,B,C,D,E",
            "car.codice_motornet" => "required|string",
            "car.codice_eurotax" => "required|string",
            "car.descrizione_serie_gamma" => "required|string",
            "car.modello" => "required|string",
            "car.allestimento" => "required|string",
            //image params
            "images.*.code" => "required|string",
            "images.*.path" => "required|string",
            "images.*.image_alt" => "nullable|string",
            "images.*.type" => "required|in:MAIN,COVER,SLIDER,OTHER",
            "images.*.deleted_at" => "nullable|string",
            //car accessories params
            "accessories.*.type" => "required|string",
            "accessories.*.price" => "required|numeric",
            "accessories.*.description" => "nullable|string",
            "accessories.*.short_description" => "nullable|string",
            "accessories.*.standard_description" => "nullable|string",
            "accessories.*.available" => "required|boolean",
            //car accessories group params
            "accessories.*.group_id" => "required|numeric",
            "accessories.*.group_code" => "required|string",
            "accessories.*.group_description" => "nullable|string",
        ];
    }
}
