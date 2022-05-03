<?php

namespace App\Services\Offers;

use App\Models\Car;
use App\Models\Agent;
use App\Models\Offer;
use App\Models\OfferAttributes;
use App\Services\Offers\OfferService;
use App\Traits\DateUtils;
use App\Http\Requests\Api\OfferCreateRequest;
use App\Http\Requests\Api\OfferUpdateRequest;
use League\Fractal\Manager;

class OfferApiService extends OfferService
{
    use DateUtils;

    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
    }

    public function createFromRequest(OfferCreateRequest $request, Agent $agent): Offer
    {
        /** @var Car $car */
        $car = $this->getCarFromRequest($request);

        $code = $car->generateCode();

        /** @var Offer $offer */
        $offer = new Offer();
        $offer->code = $code;
        $offer->car_id = $car->id;
        $offer->monthly_rate = $request->monthly_rate;
        $offer->web_monthly_rate = $request->monthly_rate;
        $offer->deposit = $request->deposit;
        $offer->distance = $request->distance;
        $offer->duration = $request->duration;
        $offer->notes = $request->notes;
        $offer->is_custom = true;
        $offer->status = true;
        $offer->highlighted = false;
        $offer->broker = $agent->myGroup->name;
        $offer->owner_id = $agent->id;

        $offer->saveOrFail();

        if ($request->leftLabel && $request->leftLabel['key'] && $request->leftLabel['label']) {
            $this->addLeftLabel($offer, $request->leftLabel['key'], $request->leftLabel['label']);
        }

        if ($request->rightLabel && $request->rightLabel['key'] && $request->rightLabel['label']) {
            $this->addRightLabel($offer, $request->rightLabel['key'], $request->rightLabel['label']);
        }

        if ($request->reference_code) {
            $this->addOfferAttribute($offer, 'REFERENCE_CODE', $request->reference_code);
        }

        return $offer;
    }

    public function updateFromRequest(OfferUpdateRequest $request, string $code): Offer
    {
        /** @var Car $car */
        $car = Car::find($request->car);

        /** @var Offer $offer */
        $offer = Offer::findByCode($code);

        $offerAttr = [
            $offer->deposit,
            $offer->monthly_rate,
            $offer->distance,
            $offer->duration,
            $offer->deliveryTime->value,
            $offer->fastDelivery->value,
            $offer->broker,
            $offer->car->id,
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
        ];

        $diff = array_merge(array_diff($offerAttr, $requestAttr), array_diff($requestAttr, $offerAttr));

        $newOffer = $offer;

        if (!empty($diff)) {
            $newOffer = $offer->replicate();
            $newOffer->push();

            $attachedAgents = $offer->agents;
            $newOffer->attachAgents($attachedAgents);

            if ($newOffer->car_id !== $car->id) {
                $newOffer->updateCarId($car->id);
            }

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
                "notes" => $request->notes,
            ]);

            $offer->delete();
        }

        if ($request->leftLabel && $request->leftLabel['key'] !== $newOffer->leftLabel) {
            OfferAttributes::deleteOfferAttribute($newOffer, 'LEFT_LABEL');

            if ($request->leftLabel['key'] && $request->leftLabel['label']) {
                $this->addLeftLabel($newOffer, $request->leftLabel['key'], $request->leftLabel['label']);
            }
        }

        if ($request->rightLabel && $request->rightLabel['key'] !== $newOffer->rightLabel) {
            OfferAttributes::deleteOfferAttribute($newOffer, 'RIGHT_LABEL');

            if ($request->rightLabel['key'] && $request->rightLabel['label']) {
                $this->addRightLabel($newOffer, $request->rightLabel['key'], $request->rightLabel['label']);
            }
        }

        if ($request->reference_code) {
            $this->addOfferAttribute($newOffer, 'REFERENCE_CODE', $request->reference_code);
        }

        return $newOffer;
    }
}
