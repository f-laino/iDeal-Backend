<?php

namespace App\Helpers\Models;
use App\Helpers\Interfaces\HubSpotHelper;

/**
 * Class ConsentHelper
 * @package App\Helpers\Models
 * @see https://developers.hubspot.com/docs/methods/forms/submit_form
 */
class ConsentHelper implements HubSpotHelper
{

    public function toHubSpotProprieties(): array
    {
        $props = [
            "consentToProcess" => TRUE,
            "text" => "Termini d'uso ed informativa privacy",
            "communications" => [
                [
                    "value" => TRUE,
                    "text" => "Consent iDeal communication",
                    "subscriptionTypeId" => config('hubspot.agent_subscription')
                ],
                [
                    "value" => TRUE,
                    "text" => "Consent One to One emails",
                    "subscriptionTypeId" => config('hubspot.sales_subscription')
                ],
            ],
        ];

        return $props;
    }


}
