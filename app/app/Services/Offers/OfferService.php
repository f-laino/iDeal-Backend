<?php

namespace App\Services\Offers;

use App\Models\Car;
use App\Models\Agent;
use App\Models\Group;
use App\Models\Offer;
use App\Models\OfferAttributes;
use App\Traits\DateUtils;
use App\Http\Requests\Api\OfferCreateRequest;
use App\Http\Requests\Cms\OfferStoreRequest;
use App\Abstracts\Responder;
use League\Fractal\Manager;
use Illuminate\Http\Request;

class OfferService extends Responder
{
    use DateUtils;

    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
    }

    public function getAgentOfferByCode(string $code, Agent $agent): Offer
    {
        return $agent->offers()
            ->where("code", $code)
            ->firstOrFail();
    }

    public function createFromRequest(OfferCreateRequest|OfferStoreRequest $request, Agent $agent = null): Offer
    {
        /** @var Car $car */
        $car = $this->getCarFromRequest($request);

        if ($request->code) {
            $code = $request->code;
        } else {
            $code = $car->generateCode();
        }

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
        $offer->is_custom = !empty($agent);
        $offer->suggested = $request->get('suggested', false);
        $offer->status = true;
        $offer->highlighted = false;

        if (!empty($agent)) {
            $offer->broker = $agent->myGroup->name;
            $offer->owner_id = $agent->id;
        } else {
            $offer->broker = $request->broker;
        }

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

    public function attachAgentMembersToOffer(Offer $offer, Agent $agent): bool
    {
        $members = Group::getMembers($agent);
        return $offer->attachAgents($members, true);
    }

    public function addLeftLabel(Offer $offer, $labelValue, $labelDescription): bool
    {
        return $this->addOfferAttribute($offer, 'LEFT_LABEL', $labelValue, $labelDescription);
    }

    public function addRightLabel(Offer $offer, $labelValue, $labelDescription): bool
    {
        return $this->addOfferAttribute($offer, 'RIGHT_LABEL', $labelValue, $labelDescription);
    }

    protected function addOfferAttribute(Offer $offer, $type, $value = null, $description = null): bool
    {
        $attribute = new OfferAttributes([
            'offer_id' => $offer->id,
            'type' => $type,
            'value' => $value,
            'description' => $description
        ]);

        return $attribute->saveOrFail();
    }

    protected function getCarFromRequest(OfferCreateRequest|OfferStoreRequest $request): Car
    {
        if ($request->car) {
            return Car::find($request->car);
        }

        $customCar = $request->get('custom_car', null);
        $isCustomEnabled = $request->get('custom-car-enabled', false);

        if (!empty($customCar) && $isCustomEnabled !== false) {
            return Car::findOrFail($customCar);
        } else {
            list($motornet, $eurotax) = explode('-', $request->carversion);

            $car = new Car();

            return $car->saveOrFail([
                'codice_motornet' => $motornet,
                'codice_eurotax' => $eurotax,
            ]);
        }
    }
}
