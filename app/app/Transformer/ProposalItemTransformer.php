<?php

namespace App\Transformer;

use App\Models\Agent;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Customer;
use App\Factories\CrmFactory;
use App\Models\Offer;
use App\Models\Proposal;
use App\Models\Quotation;

class ProposalItemTransformer extends BaseTransformer
{
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

        /** @var Agent $agent */
        $agent = $proposal->agent;
        $commission = $offer->fee($agent);

        $quotation = Quotation::where('proposal_id', $proposal->id)->first();

        if (!empty($quotation)) {
            $crmFactory = CrmFactory::create($quotation);
            $stage = $crmFactory->transformStage($quotation->stage);
        } else {
            $stage = null;
        }

        return [
            'stage' => $stage,
            'hasAttachments' => !empty($quotation) ? $quotation->hasMandatoryDocuments() : false,
            'agent_name' => (string)!empty($agent->business_name) ? $agent->business_name : $agent->name,
            'code' => !empty($quotation) ? (string)$quotation->id : null,
            'proposal_id' => (int)$proposal->id,
            'crm_id' => !empty($quotation) ? (string)$quotation->id : null,
            'date' => (string)$proposal->humanDate,
            'customer_code' => $customer->id,
            'customer_name' => "$customer->first_name $customer->last_name",
            'customer_last_name' => "$customer->last_name",
            'customer_phone' => $customer->phone,
            'customer_crm' => (string)$customer->id,
            'car_name' => "$brand->name $car->modello",
            'price' => (float)$proposal->monthly_rate,
            'duration' => (string)$proposal->duration,
            'distance' => (string)$proposal->distance,
            'commission' => (float)$commission,
            'deposit' => (int)$proposal->deposit,
            'max_deposit' => (int)$offer->getMaxDeposit(),
            'broker' => (string)$offer->broker,
            'category' => $customer->category,
            'last_interaction' => !empty($quotation) ? (string)$quotation->last_qualified_step : null,
            'qualified' => !empty($quotation) ? (int)$quotation->qualified : null,
            'status' => !empty($quotation) ? (string)$quotation->status : null,
            'hasAssistant' => (boolean)!$offer->isCustom(),
            'stillValid' => !$offer->trashed(),
        ];
    }
}
