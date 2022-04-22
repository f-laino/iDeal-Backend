<?php

namespace App\Mail;

use App\Models\Agent;
use App\Models\Brand;
use App\Models\Car;
use App\Models\ContractualCategory;
use App\Models\Customer;
use App\Models\Offer;
use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuotationAdded extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    /** @var Quotation  */
    protected $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /** @var Offer $offer */
        $offer = $this->quotation->proposal->offer;
        /** @var Car $car */
        $car = $offer->car;
        /** @var Brand $brand */
        $brand = $car->brand;
        /** @var Agent $agent */
        $agent = $this->quotation->proposal->agent;
        /** @var Customer $subject */
        $customer = $this->quotation->proposal->customer;
        /** @var ContractualCategory $subject */
        $category = $customer->category;

        $carName = $brand->name . " " . $car->descrizione_serie_gamma;
        $carModel = $car->allestimento;

        return $offer->isEklyOffer() ?
            $this->buildEklyEmail($offer, $car, $brand, $agent,
                $customer, $category, $carName, $carModel) :
            $this->buildEmail($offer, $car, $brand, $agent,
                $customer, $category, $carName, $carModel);
    }

    private function buildEmail(
        Offer $offer, Car $car, Brand $brand, Agent $agent,
        Customer $customer, ContractualCategory $category,
        string $carName, string $carModel
    ): self
    {
        $subject = "iDEAL - Nuova richiesta di preventivo Noleggio a Lungo Termine";
        return $this->from(config('mail.default.from.address'), config('mail.default.from.name'))
            ->replyTo(config('mail.default.from.replay'))
            ->subject($subject)
            ->markdown('emails.quotation.add')
            ->with([
                'brand' => $brand,
                'offer' => $offer,
                'carName' => $carName,
                'carModel' => $carModel,
                'car' => $car,
                'subject' => $subject,
                'quotation' => $this->quotation,
                'customer' => $customer,
                'category' => $category,
                'name' => $agent->getName(),
            ]);
    }

    private function buildEklyEmail(
        Offer $offer, Car $car, Brand $brand, Agent $agent,
        Customer $customer, ContractualCategory $category,
        string $carName, string $carModel
    ): self
    {
        $subject = "Flee for Ekly - Nuova richiesta di preventivo Noleggio a Lungo Termine";
        return $this->from(config('mail.ekly.from.address'), config('mail.ekly.from.name'))
            ->replyTo(config('mail.ekly.from.replay'))
            ->subject($subject)
            ->markdown('emails.ekly.quotation.add')
            ->with([
                'brand' => $brand,
                'offer' => $offer,
                'carName' => $carName,
                'carModel' => $carModel,
                'car' => $car,
                'subject' => $subject,
                'quotation' => $this->quotation,
                'customer' => $customer,
                'category' => $category,
                'name' => $agent->getName(),
            ]);
    }
}
