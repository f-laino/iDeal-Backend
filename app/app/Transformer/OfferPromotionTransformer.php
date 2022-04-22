<?php

namespace App\Transformer;

use App\Models\Car;
use App\Models\Image;
use App\Models\Offer;

/**
 * @OA\Schema(
 *  schema="OfferPromotion",
 *  type="object",
 *  @OA\Property(property="code", type="string", example="opel-corsa-elettrica"),
 *  @OA\Property(property="brand", type="string", example="Opel"),
 *  @OA\Property(property="model", type="string", example="Corsa"),
 *  @OA\Property(property="image", type="string", example="https://cdn1.carplanner.com/cars/Opel/Opel/OPE9343_CUSTOM_1621237811.png"),
 *  @OA\Property(property="image_binary", type="string", format="binary"),
 *  @OA\Property(property="image_name", type="string", example="Opel-Corsa.png"),
 *  @OA\Property(property="monthly_rate", type="integer"),
 *  @OA\Property(property="duration", type="integer"),
 *  @OA\Property(property="distance", type="integer"),
 * )
 */
class OfferPromotionTransformer extends BaseTransformer
{
    public function transform(Offer $offer)
    {
        /** @var Car $car */
        $car = $offer->car;
        $brand = $car->brand->name;
        $model = $car->descrizione_serie_gamma;

        /** @var Image $image */
        $image = $offer->getPromotionalImage();

        //crea imagine in base64
        $url = str_replace(' ', "%20", $image->path);
        $file = file_get_contents($url);
        $file = base64_encode($file);

        //crea nome del file
        $extension = explode('.', $image->path);
        $image_name = "{$brand}-{$model}." . end($extension);

        $monthly_rate = (float)$offer->monthly_rate ;

        $response = [
            'code' => $offer->code,
            'brand' => $brand,
            'model' => $model,
            'image' => $image->path,
            'image_binary' => $file,
            'image_name' => $image_name,
            'monthly_rate' => $monthly_rate,
            'duration' => $offer->duration,
            'distance' => $offer->distance,
        ];

        return $response;
    }
}
