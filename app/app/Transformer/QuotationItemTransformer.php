<?php

namespace App\Transformer;

use App\Models\Agent;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Customer;
use App\Factories\CrmFactory;
use App\Models\Offer;
use App\Models\Quotation;

class QuotationItemTransformer extends BaseTransformer
{
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

        /** @var Agent $agent */
        $agent = $quotation->proposal->agent;
        $commission = $quotation->getFee();

        //transform stage
        $crmFactory = CrmFactory::create($quotation);
        $stage = $crmFactory->transformStage($quotation->stage);

        return [
            'stage' => $stage,
            'hasAttachments' => $quotation->hasAttachments(),
            'agent_name' => (string)!empty($agent->business_name) ? $agent->business_name : $agent->name,
            'code' => (string)$quotation->id,
            'proposal_id' => (int)$quotation->proposal_id,
            'crm_id' => (string)$quotation->id,
            'date' => (string)$quotation->humanDate,
            'customer_code' => $customer->id,
            'customer_name' => "$customer->first_name $customer->last_name",
            'customer_last_name' => "$customer->last_name",
            'customer_phone' => $customer->phone,
            'customer_crm' => (string)$customer->id,
            'car_name' => "$brand->name $car->modello",
            'price' => (float)$quotation->proposal->monthly_rate,
            'duration' => (string)$quotation->proposal->duration,
            'distance' => (string)$quotation->proposal->distance,
            'commission' => (float)$commission,
            'deposit' => (int)$quotation->proposal->deposit,
            'max_deposit' => (int)$offer->getMaxDeposit(),
            'broker' => (string)$offer->broker,
            'category' => $customer->category,
            'last_interaction' => (string)$quotation->last_qualified_step,
            'qualified' => (int)$quotation->qualified,
            'status' => (string)$quotation->status,
            'hasAssistant' => (boolean)!$offer->isCustom(),
        ];
    }
}
