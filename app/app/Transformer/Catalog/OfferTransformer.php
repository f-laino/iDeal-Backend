<?php namespace App\Transformer\Catalog;

use App\Models\Offer;
use App\Transformer\BaseTransformer;
use App\Transformer\CarTransformer;
use App\Transformer\ChildOfferTransformer;

/**
 * @OA\Schema(
 *  schema="CatalogOffer",
 *  type="object",
 *  @OA\Property(property="car", type="object",
 *      @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Car"
 *       )
 *  ),
 *  @OA\Property(property="code", type="string", example="ltpc--sportback-30-16-tdi-116cv"),
 *  @OA\Property(property="ref", type="integer", example="63800"),
 *  @OA\Property(property="deposit", type="string", example="5000.00"),
 *  @OA\Property(property="duration", type="integer", example="48"),
 *  @OA\Property(property="distance", type="integer", example="24000"),
 *  @OA\Property(property="monthly_rate", type="string", example="999.00"),
 *  @OA\Property(property="services", type="array", @OA\Items()),
 *  @OA\Property(property="options", type="array", @OA\Items()),
 * )
 */
class OfferTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['car', 'childs'];

    public function transform(Offer $offer)
    {
        $services = $offer->services()->pluck('slug')->toArray();
        $options = $this->includeOptions($offer);
        return [
            'code' => $offer->code,
            'ref' => $offer->id,
            'deposit' => (string) $offer->deposit,
            'duration' => (int)$offer->duration,
            'distance' => (int)$offer->distance,
            'monthly_rate' => (string)$offer->monthly_rate,
            'services' => (array)$services,
            'options' => (array)$options,
            'rightLabel' => !empty($offer->rightLabel) ? $offer->rightLabel->value : '',
        ];
    }

    public function includeCar(Offer $offer)
    {
        return $this->item($offer->car, new CarTransformer);
    }

    public function includeChilds(Offer $offer)
    {
        $child = $offer->childOffers;
        if (!$child->isEmpty()) {
            return $this->collection($child, new  ChildOfferTransformer);
        }
    }

    public function includeOptions(Offer $offer)
    {
        $childs = $offer->childOffers();
        if (!$childs->exists()) {
            return [];
        }
        $deposits = $childs->pluck('deposit')->unique();
        $deposits = $deposits->filter(function ($value, $key) use ($offer) {
            return $offer->deposit != $value;
        });
        $durations = $childs->pluck('duration')->unique();
        $durations = $durations->filter(function ($value, $key) use ($offer) {
            return $offer->duration != $value;
        });
        $distances = $childs->pluck('distance')->unique();
        $distances = $distances->filter(function ($value, $key) use ($offer) {
            return $offer->distance != $value;
        });
        return [
            "second_deposit" => (string)$deposits->first(),
            "second_duration" => (int)$durations->first(),
            "second_distance" => (int)$distances->pop(),
            "third_distance" => (int)$distances->pop(),
        ];
    }
}
