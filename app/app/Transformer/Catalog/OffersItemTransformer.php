<?php namespace App\Transformer\Catalog;

use App\Models\Image;
use App\Models\Offer;
use App\Transformer\BaseTransformer;

class OffersItemTransformer extends BaseTransformer
{

    /**
     * @param Offer $offer
     * @return array
     */
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
        $response = [
            'image' => $image->path,
            'brand' => $car->brand->name,
            'model' => $car->descrizione_serie_gamma,
            'version' => $car->version,
            'code' => $offer->code,
            'monthly_rate' => intval($offer->monthly_rate),
            'deposit' => intval($offer->deposit),
            'duration' => intval($offer->duration),
            'distance' => intval($offer->distance),
            'isMain' => boolval($offer->parent_id),
            'isHighlighted' => boolval($offer->highlighted),
            'status' => boolval($offer->agent_offer_status),
            'canEdit' => (boolean) $offer->owner_id == $offer->agent_id,
        ];

        return $response;
    }
}
