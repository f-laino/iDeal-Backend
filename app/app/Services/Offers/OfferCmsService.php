<?php

namespace App\Services\Offers;

use App\Models\Car;
use App\Models\Offer;
use App\Models\OfferAttributes;
use App\Models\Promotion;
use App\Common\Models\Activity\Logger;
use App\Services\Offers\OfferService;
use App\Traits\DateUtils;
use App\Http\Requests\Cms\OfferStoreRequest;
use App\Http\Requests\Cms\OfferUpdateRequest;
use League\Fractal\Manager;

class OfferCmsService extends OfferService
{
    use DateUtils;

    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
    }

    public function createFromRequest(OfferStoreRequest $request): Offer
    {
        /** @var Car $car */
        $car = $this->getCarFromRequest($request);

        /** @var Offer $offer */
        $offer = new Offer();
        $offer->code = $request->code;
        $offer->car_id = $car->id;
        $offer->broker = $request->broker;
        $offer->deposit = $request->deposit;
        $offer->distance = $request->distance;
        $offer->duration = $request->duration;
        $offer->monthly_rate = $request->monthly_rate;
        $offer->web_monthly_rate = $request->monthly_rate;
        $offer->status = true;
        $offer->suggested = $request->get('suggested', false);
        $offer->highlighted = false;

        $offer->saveOrFail();

        $offer->attachDefaultServices();

        $offer->attachAllAgents();

        return $offer;
    }

    public function updateFromRequest(OfferUpdateRequest $request, Offer $offer): Offer
    {
        $customCar = $request->get('custom_car', null);

        $promotions = $request->get('promotions', []);

        $isCustomEnabled = $request->get('custom-car-enabled', null);
        $isCustomEnabled = boolval($isCustomEnabled);

        if (!empty($customCar) && $isCustomEnabled) {
            $car = Car::findOrFail($customCar);
        } else {
            list($motornet, $eurotax) = explode('-', $request->carversion);
            $car = new Car;
            $options = [
                'codice_motornet' => $motornet,
                'codice_eurotax' => $eurotax,
            ];
            $car = $car->saveOrFail($options);
        }

        $carSegment = $offer->car->segmento;

        $carColor = optional(OfferAttributes::where([
            "offer_id" => $offer->id,
            "type" => "CAR_COLOR",
        ])->first())->value;

        $offerAttr = [
            $offer->deposit,
            $offer->monthly_rate,
            $offer->distance,
            $offer->duration,
            $offer->deliveryTime->value,
            $offer->fastDelivery->value,
            $offer->broker,
            $offer->car->id,
            $carSegment,
            $carColor,
        ];

        $requestAttr = [
            $request->deposit,
            $request->monthly_rate,
            $request->distance,
            $request->duration,
            $request->delivery_time,
            $request->fast_delivery,
            $request->broker,
            $car->id,
            $request->segment,
            $request->car_color !== '0' ? $request->car_color : null,
        ];

        $diff = array_merge(array_diff($offerAttr, $requestAttr), array_diff($requestAttr, $offerAttr));

        $newOffer = $offer;

        if (!empty($diff)) {
            /** @var Offer $newOffer */
            $newOffer = $offer->replicate();
            $newOffer->push();

            $attachedAgents = $offer->agents;
            $newOffer->attachAgents($attachedAgents);

            $attachedServices = $offer
                ->services()
                ->pluck('slug')
                ->toArray();
            $newOffer->attachServices($attachedServices);

            $childOffers = $offer->childOffers;
            $newOffer->attachChilds($newOffer->id, $childOffers);

            $newOffer->update([
                "deposit" => $request->deposit,
                "monthly_rate" => $request->monthly_rate,
                "web_monthly_rate" => $request->monthly_rate,
                "distance" => $request->distance,
                "duration" => $request->duration,
            ]);

            $offer->car->update(['segmento' => $request->segment]);

            $newOffer->updateBroker($request->broker);
            $newOffer->updateCarId($car->id);

            if (!empty($request->car_color)) {
                $offerColor = OfferAttributes::firstOrNew([
                    "offer_id" => $newOffer->id,
                    "type" => "CAR_COLOR",
                ]);
                $offerColor->offer_id = $newOffer->id;
                $offerColor->type = "CAR_COLOR";
                $offerColor->value = $request->car_color;
                $offerColor->description = null;
                $offerColor->saveOrFail();
            } else {
                $offerColor = OfferAttributes::where([
                    "offer_id" => $newOffer->id,
                    "type" => "CAR_COLOR"
                ])->delete();
            }

            $newOffer->updateDeliveryTime($request->delivery_time);
            $newOffer->updateFastDelivery($request->delivery_time ? $request->get('fast_delivery', false) : false);

            Logger::activity('OfferController@Update', $request, $newOffer, $offer);

            $offer->delete();
        }

        if ($request->get('left_label') && $request->get('left_label') !== $newOffer->leftLabel) {
            OfferAttributes::deleteOfferAttribute($newOffer, 'LEFT_LABEL');

            if (!empty($request->get('left_label'))) {
                $this->addLeftLabel(
                    $newOffer,
                    $request->get('left_label'),
                    OfferAttributes::$rentLabels[$request->get('left_label')]
                );
            }
        }

        if ($request->get('right_label') && $request->get('right_label') !== $newOffer->rightLabel) {
            OfferAttributes::deleteOfferAttribute($newOffer, 'RIGHT_LABEL');

            if (!empty($request->get('right_label'))) {
                $this->addRightLabel(
                    $newOffer,
                    $request->get('right_label'),
                    OfferAttributes::$rentLabels[$request->get('right_label')]
                );
            }
        }

        if (!empty($promotions)) {
            foreach ($promotions as $promotionId) {
                /** @var Promotion $promo */
                $promo = Promotion::find($promotionId);
                $promo->attachOffer($newOffer);
            }
        } else {
            $newOffer->detachFromAllPromotions();
        }

        return $newOffer;
    }
}
