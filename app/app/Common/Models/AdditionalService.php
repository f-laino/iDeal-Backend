<?php


namespace App\Common\Models;


use App\Models\Offer;

class AdditionalService
{

    public static function getSevices(Offer $offer)
    {
        return $offer->isEklyOffer() ? self::getEklyServices() : self::getDefaultServices();
    }

    private static function getDefaultServices(): array
    {
        return [
            [
                'code' => 'pneumatici',
                'title' => 'Pneumatici',
                'description' => '15€ al mese',
                'value' => 15,
            ],
            [
                'code' => 'vettura_sostitutiva',
                'title' => 'Vettura Sostitutiva',
                'description' => '25€ al mese',
                'value' => 25,
            ],
        ];
    }

    private static function getEklyServices(): array
    {
        $ekly = [
            [
                'code' => 'pneumatici',
                'title' => 'Pneumatici',
                'description' => '10€ al mese',
                'value' => 10,
            ],
            [
                'code' => 'vettura_sostitutiva',
                'title' => 'Vettura Sostitutiva',
                'description' => '5€ al mese',
                'value' => 5,
            ],
            [
                'code' => 'pai',
                'title' => 'Pai Top',
                'description' => '3€ al mese',
                'value' => 3,
            ],
            [
                'code' => 'tutela_legale',
                'title' => 'Tutela Legale',
                'description' => '3€ al mese',
                'value' => 3,
            ],
            [
                'code' => 'assistenza_stradale',
                'title' => 'Assistenza Stradale',
                'description' => '2€ al mese',
                'value' => 2,
            ],
        ];
        return $ekly;
    }
}

