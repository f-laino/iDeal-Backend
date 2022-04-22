<?php

namespace App\Transformer;

use App\Models\Agent;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Fuel;
use App\Models\Offer;
use App\Models\Quotation;
use App\Proposal;
use App\Transformer\QuotationTransformer;
use Artisan;

/**
 * @OA\Schema(
 *  schema="Proposal",
 *  type="object",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="broker", type="string", example="Arval"),
 *  @OA\Property(property="date", type="string", example="29-Jul-2021"),
 *  @OA\Property(property="customer_name", type="string", example="Flavio Laino"),
 *  @OA\Property(property="car_name", type="string", example="Lancia Ypsilon 1.0 Hybrid Silver S&s 70cv"),
 *  @OA\Property(property="price", type="integer", example="141"),
 *  @OA\Property(property="duration", type="integer"),
 *  @OA\Property(property="deposit", type="integer"),
 *  @OA\Property(property="distance", type="integer"),
 *  @OA\Property(property="change_tires", type="boolean"),
 *  @OA\Property(property="car_replacement", type="boolean"),
 *  @OA\Property(property="bollo_auto", type="boolean"),
 *  @OA\Property(property="accessories", type="array", @OA\Items()),
 *  @OA\Property(property="notes", type="string"),
 *  @OA\Property(property="car", ref="#/components/schemas/CarEntity"),
 *  @OA\Property(property="offer_color", type="string", example=""),
 *  @OA\Property(property="customer", ref="#/components/schemas/CustomerEntity"),
 *  @OA\Property(property="category", type="string", example="Flavio"),
 *  @OA\Property(property="commission", type="string", example="Flavio"),
 *  @OA\Property(property="agent_name", type="string", example="Flavio"),
 *  @OA\Property(property="customer_last_name", type="string", example="Flavio"),
 *  @OA\Property(property="customer_crm", type="string", example="Flavio"),
 *  @OA\Property(property="max_deposit", type="string", example="Flavio"),
 *  @OA\Property(property="hasAssistant", type="string", example="Flavio"),
 * )
 */
class ProposalTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['offer', 'quotation'];

    public function transform(Proposal $proposal)
    {
        /** @var Customer $customer */
        $customer = $proposal->customer;
        /** @var Offer $offer */
        $offer = $proposal->offer;
        /** @var Car $car */
        $car = $offer->car;
        /** @var Brand $brand */
        $brand = $car->brand;
        /** @var Fuel $fuel */
        $fuel = $car->fuel;
        /** @var Agent $agent */
        $agent = $proposal->agent;

        $commission = $offer->fee($agent);

        $selectedServices = $this->includeSelectedServices($proposal);

        return [
            'id' => $proposal->id,
            'broker' =>  (string)$offer->broker,
            'date' => (string)$proposal->humanDate,
            'customer_name' => "$customer->first_name $customer->last_name",
            'car_name' => "$brand->name $car->modello $car->allestimento",
            'price' => (int)$proposal->monthly_rate,
            'duration' => (string)$proposal->duration,
            'deposit' => (int)$proposal->deposit,
            'distance' => (int)$proposal->distance,
            'bollo_auto' => false,
            'accessories' => (array)$proposal->car_accessories,
            'notes' => (string)$proposal->notes,
            'car' => $car,
            'offer_color' => $offer->getColorName(),
            'customer' => $customer,
            'category' => $customer->category,
            'commission' => (float)$commission,
            'agent_name' => (string)!empty($agent->business_name) ? $agent->business_name : $agent->name,
            'customer_last_name' => "$customer->last_name",
            'customer_crm' => (string)$customer->id,
            'max_deposit' => (int)$offer->getMaxDeposit(),
            'hasAssistant' => (boolean)!$offer->isCustom(),
            'selectedServices' => $selectedServices,
        ];
    }

    public function includeOffer(Proposal $proposal)
    {
        return $this->item($proposal->offer, new OfferItemTransformer);
    }

    public function includeQuotation(Proposal $proposal)
    {
        $quotation = Quotation::where('proposal_id', $proposal->id)->first();

        if (!empty($quotation)) {
            $quotation->refresh();

            return $this->item($quotation, new QuotationTransformer);
        }
        return $this->null();
    }

    public function includeSelectedServices(Proposal $proposal)
    {
        return $proposal->services()
                ->get(['slug', 'name', 'description', 'icon', 'included', 'price'])
                ->makeHidden('pivot')
                ->toArray();
    }
}
