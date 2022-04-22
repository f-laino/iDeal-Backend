<?php

namespace App\Transformer;

use App\Models\Offer;

/**
 * @OA\Schema(
 *  schema="ChildOffer",
 *  type="object",
 *  @OA\Property(property="deposit", type="number", format="float", example="0"),
 *  @OA\Property(property="duration", type="integer", example="24"),
 *  @OA\Property(property="distance", type="integer", example="15000"),
 *  @OA\Property(property="monthly_rate", type="number", format="float", example="250"),
 *  @OA\Property(property="web_monthly_rate", type="number", format="float", example="250"),
 *  @OA\Property(property="isChild", type="boolean", example="true"),
 *  @OA\Property(property="ref", type="integer", example="135924"),
 * )
 */
class ChildOfferTransformer extends BaseTransformer
{
    public function transform(Offer $offer)
    {
        return [
            'deposit' => (float) $offer->deposit,
            'duration' => !empty($offer->duration) ? $offer->duration : '',
            'distance' => !empty($offer->distance) ? $offer->distance : '',
            'monthly_rate' => (float) $offer->monthly_rate,
            'web_monthly_rate' => (float) $offer->web_monthly_rate,
            'isChild' => boolval($offer->parent_id),
            'ref' => $offer->id,
        ];
    }
}
