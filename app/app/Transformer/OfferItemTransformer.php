<?php

namespace App\Transformer;

use App\Models\Image;
use App\Models\Offer;
use App\Transformer\OfferTransformer;

class OfferItemTransformer extends BaseTransformer
{
    protected array $defaultIncludes = ['services'];


    public function transform(Offer $offer)
    {
        $car = $offer->car;
        $image = $car->mainImage();
        $category = $car->category;
        $fuel = $car->fuel;
        if (empty($image)) {
            $image = $car->firstImage();
        }
        if (empty($image) || !$image instanceof Image) {
            $image = new Image;
        }

        $monthly_rate = (float)$offer->monthly_rate;

        $offerTransformer = new OfferTransformer();
        $delivery_time_label = $offerTransformer->includeDeliveryTimeLabel($offer->deliveryTime, $offer->fastDelivery);

        $agent = auth('api')->user();

        $response = [
            'code' => $offer->code,
            'ref' => $offer->id,
            'deposit' => $offer->deposit,
            'image' => $image->path,
            'image_alt' => $image->image_alt,
            'brand' => $car->brand->name,
            'brand_slug' => $car->brand->slug,
            'fuel_slug' => $fuel->slug,
            'categoria' => $category->slug,
            'model' => $car->descrizione_serie_gamma,
            'monthly_rate' => $monthly_rate,
            'leftLabel' => !empty($offer->leftLabel) ? $offer->leftLabel->description : '',
            'rightLabel' => !empty($offer->rightLabel) ? $offer->rightLabel->description : '',
            'type' => "RENT",
            'duration' => $offer->duration,
            'distance' => $offer->distance,
            'delivery_time_label' => $delivery_time_label,
            'fast_delivery' => !empty($offer->fastDelivery->value),
            'isMain' => boolval($offer->parent_id),
            'fee' => (float)$offer->fee($agent),
            'stillValid' => !$offer->trashed(),
        ];


        return $response;
    }

    public function includeServices(Offer $offer)
    {
        return $this->collection($offer->services, new ServiceTransformer);
    }
}
