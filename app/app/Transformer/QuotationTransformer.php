<?php

namespace App\Transformer;

use App\Models\Agent;
use App\Models\Attachment;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Customer;
use App\Factories\CrmFactory;
use App\Models\Fuel;
use App\Models\Offer;
use App\Models\Proposal;
use App\Models\Quotation;

/**
 * @OA\Schema(
 *  schema="Quotation",
 *  type="object",
 *  @OA\Property(property="TODO", type="string", example="https://github.com/DAICAR/iDeal/pull/17")
 * )
 */
class QuotationTransformer extends BaseTransformer
{
    protected array $defaultIncludes = ['offer', 'attachments'];

    public function transform(Quotation $quotation)
    {
        /** @var Customer $customer */
        $customer = $quotation->proposal->customer;
        /** @var Offer $offer */
        $offer = $quotation->proposal->offer;
        /** @var Car $car */
        $car = $offer->car;
        /** @var Brand $brand */
        $brand = $car->brand;
        /** @var Fuel $fuel */
        $fuel = $car->fuel;
        /** @var Agent $agent */
        $agent = $quotation->proposal->agent;
        $commission = $quotation->getFee();

        //transform stage
        $crmFactory = CrmFactory::create($quotation);
        $stage = $crmFactory->transformStage($quotation->stage);
        /** @var Proposal $proposal */
        $proposal = $quotation->proposal;

        $selectedServices = $this->includeSelectedServices($proposal);

        return [
            'stage' => $stage,
            'broker' =>  (string)$offer->broker,
            'code' => (string)$quotation->id,
            'date' => (string)$quotation->humanDate,
            'customer_name' => "$customer->first_name $customer->last_name",
            'car_name' => "$brand->name $car->modello $car->allestimento",
            'price' => (int)$proposal->monthly_rate,
            'duration' => (string)$proposal->duration,
            'deposit' => (int)$proposal->deposit,
            'distance' => (int)$proposal->distance,
            'selectedServices' => $selectedServices,
            'bollo_auto' => false,
            'accessories' => (array)$quotation->proposal->car_accessories,
            'notes' => (string)$quotation->proposal->notes,
            'car' => $car,
            'offer_color' => $offer->getColorName(),
            'customer' => $customer,
            'category' => $customer->category,
            'commission' => (float)$commission,
            'agent_name' => (string)!empty($agent->business_name) ? $agent->business_name : $agent->name,
            'crm_id' => (string)$quotation->id,
            'customer_last_name' => "$customer->last_name",
            'customer_crm' => (string)$customer->id,
            'max_deposit' => (int)$offer->getMaxDeposit(),
            'last_interaction' => (string)$quotation->last_qualified_step,
            'qualified' => (int)$quotation->qualified,
            'status' => (string)$quotation->status,
            'canUpdateStatus' => (boolean)$offer->isCustom(),
            'hasAssistant' => (boolean)!$offer->isCustom(),
        ];
    }

    public function includeOffer(Quotation $quotation)
    {
        return $this->item($quotation->proposal->offer, new OfferItemTransformer);
    }

    public function includeAttachments(Quotation $quotation)
    {
        $attachments = $quotation->getAttachments();

        return $this->collection($attachments, new QuotationAttachmentTransformer);
    }

    public function includeSelectedServices(Proposal $proposal)
    {
        return $proposal->services()
                ->get(['slug', 'name', 'description', 'icon', 'included', 'price'])
                ->makeHidden('pivot')
                ->toArray();
    }
}
