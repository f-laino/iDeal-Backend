<?php

namespace App\Transformer;

use App\Proposal;
use Illuminate\Support\Carbon;

class QuotationsExportItemTransformer extends BaseTransformer
{
    public function transform(Proposal $proposal)
    {
        $services = $this->includeSelectedServices($proposal);

        return [
            $proposal->id,
            $proposal->quotation ? 'Sì' : 'No',
            $proposal->quotation->status ?? 'n.d.',
            $proposal->customer->first_name,
            $proposal->customer->last_name,
            $proposal->customer->email,
            $proposal->customer->phone,
            $proposal->customer->address,
            $proposal->customer->zip_code,
            $proposal->customer->category->description,
            $proposal->customer->iban,
            $proposal->agent->getNomeCompleto(),
            Carbon::parse($proposal->created_at)->format('d/m/Y H:i'),
            $proposal->deposit,
            $proposal->monthly_rate,
            $proposal->duration,
            $proposal->distance,
            !empty($proposal->quotation) ? ($proposal->quotation->upload_documents ? 'Sì' : 'No') : 'n.d.',
            $proposal->notes,
            (string)$proposal->print_count,
            implode(PHP_EOL, $services),
            $proposal->offer->car->brand->name,
            $proposal->offer->car->modello,
            $proposal->offer->car->alimentazione,
            $proposal->offer->car->category->name,
            $proposal->offer->car->segmento,
            $proposal->offer->car->descrizione_cambio,
            $proposal->offer->car->posti,
            $proposal->offer->car->cilindrata,
        ];
    }

    public function includeSelectedServices(Proposal $proposal)
    {
        $services = $proposal->services;

        $arrServices = [];

        foreach ($services as $service) {
            if (!empty($service->price)) {
                $price = number_format($service->price, 2, ',', '.') . ' €';
            } else {
                $price = 'incluso';
            }

            $arrServices[] = $service->name . ' ' . $price;
        }

        return $arrServices;
    }
}
