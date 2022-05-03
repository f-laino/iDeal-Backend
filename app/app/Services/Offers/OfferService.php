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

abstract class OfferService extends Responder
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
        $attribute = OfferAttributes::firstOrNew([
            'offer_id' => $offer->id,
            'type' => $type,
        ]);

        $attribute->offer_id = $offer->id;
        $attribute->type = $type;
        $attribute->value =  $value;
        $attribute->description = $description;

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
