<?php

namespace App\Transformer;

use App\Models\Image;
use App\Models\Offer;

class OffersItemTransformer extends BaseTransformer
{
    protected array $defaultIncludes = [];


    public function transform(Offer $offer)
    {
        $car = $offer->car;
        $image = $car->mainImage();
        if (empty($image)) {
            $image = $car->firstImage();
        }
        if (empty($image) || !$image instanceof Image) {
            $image = new Image;
        }

        $delivery_time_label = $this->includeDeliveryTimeLabel($offer->deliveryTime, $offer->fastDelivery);

        $response = [
            'image' => $image->path,
            'brand' => $car->brand->name,
            'model' => $car->descrizione_serie_gamma,
            'version' => $car->version,
            'code' => $offer->code,
            'color' => $offer->getColorName(),
            'monthly_rate' => intval($offer->monthly_rate),
            'deposit' => intval($offer->deposit),
            'duration' => intval($offer->duration),
            'distance' => intval($offer->distance),
            'delivery_time_label' => $delivery_time_label,
            'fast_delivery' => !empty($offer->fastDelivery->value),
            'tag' => !empty($offer->leftLabel) ? $offer->leftLabel->description : '',
            'secondaryTag' => !empty($offer->rightLabel) ? $offer->rightLabel->description : '',
            'isMain' => boolval($offer->parent_id),
            'isHighlighted' => boolval($offer->highlighted),
            'isSuggested' => boolval($offer->suggested)
        ];

        return $response;
    }

    public function includeDeliveryTimeLabel($deliveryTime, $fastDelivery)
    {
        if (empty($deliveryTime) || empty($deliveryTime->value)) {
            return null;
        }

        if (!empty($fastDelivery) && !empty($fastDelivery->value)) {
            return "Consegna Veloce";
        }

        $todayYear = date("Y");
        list(, $deliveryYear) = explode('-', $deliveryTime->value);

        if ($deliveryYear != $todayYear) {
            return "Consegna " . preg_replace('/(20)([0-9]{2})/', '\'$2', $deliveryTime->description);
        }

        list($deliveryMonth, ) = explode(' ', $deliveryTime->description);

        return "Consegna " . $deliveryMonth;
    }
}
