<?php

namespace App\Transformer;

use App\Models\Agent;
use App\Models\Image;
use App\Models\Car;
use App\Models\Offer;
use App\Transformer\Car\CarAccessoryTransformer;

/**
 * @OA\Schema(
 *  schema="Offer",
 *  type="object",
 *  @OA\Property(property="code", type="string", example="lancia-ypsilon-ideal"),
 *  @OA\Property(property="ref", type="integer", example="117274"),
 *  @OA\Property(property="car", type="object", ref="#/components/schemas/CarEntity"),
 *  @OA\Property(property="color", type="string", example=""),
 *  @OA\Property(property="deposit", type="integer", example="4000"),
 *  @OA\Property(property="duration", type="integer", example="36"),
 *  @OA\Property(property="distance", type="integer", example="10000"),
 *  @OA\Property(property="delivery_time", type="string", example="Aprile"),
 *  @OA\Property(property="tag", type="string", example="P.IVA"),
 *  @OA\Property(property="secondaryTag", type="string", example="Privati"),
 *  @OA\Property(property="monthly_rate", type="integer", example="131"),
 *  @OA\Property(property="web_monthly_rate", type="integer", example="131"),
 *  @OA\Property(property="fee", type="integer", example="700"),
 *  @OA\Property(property="hasVariations", type="boolean", example="true"),
 *  @OA\Property(
 *      property="images",
 *      type="object",
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CarImage")
 *       )
 *  ),
 *  @OA\Property(
 *      property="services",
 *      type="object",
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Service")
 *       )
 *  ),
 *  @OA\Property(
 *      property="child",
 *      type="object",
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ChildOffer")
 *       )
 *  ),
 *  @OA\Property(
 *      property="equippedAccessories",
 *      type="object",
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CarAccessory")
 *       )
 *  ),
 *  @OA\Property(
 *      property="optionalAccessories",
 *      type="object",
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CarAccessory")
 *       )
 *  ),
 *  @OA\Property(
 *      property="colors",
 *      type="object",
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CarAccessory")
 *       )
 *  ),
 * )
 */
class OfferTransformer extends BaseTransformer
{
    protected array $defaultIncludes = ['images', 'services', 'child',
        'equippedAccessories', 'optionalAccessories', 'colors'];


    public function transform(Offer $offer)
    {
        $car = $offer->car;
        $brand = $car->brand;
        $fuel = $car->fuel;
        $child = $offer->childOffers;
        $agent = auth('api')->user();
        $additionalServices = $this->includeAdditionalServices($agent);
        $delivery_time_label = $this->includeDeliveryTimeLabel($offer->deliveryTime, $offer->fastDelivery);

        return [
            'code' => $offer->code,
            'ref' => $offer->id,
            'car' => $car,
            'color' => $offer->getColorName(),
            'deposit' => (int) $offer->deposit,
            'duration' => (int)$offer->duration,
            'distance' => (int)$offer->distance,
            'delivery_time_label' => $delivery_time_label,
            'fast_delivery' => !empty($offer->fastDelivery->value),
            'tag' => !empty($offer->leftLabel) ? $offer->leftLabel->description : '',
            'secondaryTag' => !empty($offer->rightLabel) ? $offer->rightLabel->description : '',
            'monthly_rate' => (float)$offer->monthly_rate,
            'web_monthly_rate' => (float) $offer->web_monthly_rate,
            'fee' => (float) $offer->fee($agent),
            'hasVariations' => boolval($offer->highlighted),
            'additionalServices' => $additionalServices
        ];
    }

    public function includeImages(Offer $offer)
    {
        $car = $offer->car;
        $images = $car->sliderImages();
        if (empty($images)) {
            $images = collect(new Image());
        }
        $main = $car->mainImage();
        if (!empty($main)) {
            $images->prepend($main);
        }
        return $this->collection($images, new CarImageTransformer);
    }

    public function includeServices(Offer $offer)
    {
        return $this->collection($offer->services, new ServiceTransformer);
    }

    public function includeChild(Offer $offer)
    {
        $child = $offer->childOffers;
        $child->push($offer);
        if (!$child->isEmpty()) {
            return $this->collection($child, new  ChildOfferTransformer);
        }
    }

    public function includeEquippedAccessories(Offer $offer)
    {
        /** @var Car $car */
        $car = $offer->car;
        $items = $car->getEquippedAccessories(true);
        return $this->collection($items, new CarAccessoryTransformer);
    }

    public function includeOptionalAccessories(Offer $offer)
    {
        /** @var Car $car */
        $car = $offer->car;
        $items = $car->getOptionalAccessories(true);
        return $this->collection($items, new CarAccessoryTransformer);
    }

    public function includeColors(Offer $offer)
    {
        /** @var Car $car */
        $car = $offer->car;
        $items = $car->getAvailableColors(true);
        return $this->collection($items, new CarAccessoryTransformer);
    }

    public function includePacks(Offer $offer)
    {
        /** @var Car $car */
        $car = $offer->car;
        $items = $car->getAvailablePacks(true);
        return $this->collection($items, new CarAccessoryTransformer);
    }

    public function includeAdditionalServices(Agent $agent)
    {
        return $agent->myGroup
                ->services()
                ->get(['slug', 'name', 'description', 'icon', 'included', 'price'])
                ->makeHidden('pivot')
                ->toArray();
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
            return "In arrivo a " . $deliveryTime->description;
        }

        list($deliveryMonth, ) = explode(' ', $deliveryTime->description);

        return "In arrivo a " . $deliveryMonth;
    }
}
