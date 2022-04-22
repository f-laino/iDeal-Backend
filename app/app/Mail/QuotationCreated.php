<?php

namespace App\Mail;

use App\Models\Agent;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Group;
use App\Models\Offer;
use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuotationCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

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
    public function build(): self
    {
        /** @var Offer $offer */
        $offer = $this->quotation->proposal->offer;

        /** @var Car $car */
        $car = $offer->car;

        /** @var Brand $brand */
        $brand = $car->brand;

        /** @var Agent $agent */
        $agent =  $this->quotation->proposal->agent;

        /** @var Group $group */
        $group = $agent->myGroup;

        /** @var Agent $receiver */
        $receiver = $group->leader;

        return $offer->isEklyOffer() ?
            $this->buildEklyEmail($car, $brand, $agent, $receiver)
            : $this->buildEmail($car, $brand, $agent, $receiver);
    }

    /**
     * Costruisco email di default
     * @param Car $car
     * @param Brand $brand
     * @param Agent $agent
     * @param Agent $receiver
     * @return $this
     */
    private function buildEmail(
        Car $car, Brand $brand,
        Agent $agent, Agent $receiver
    ): self
    {
        $subject = "iDEAL - Nuova richiesta di preventivo Noleggio a Lungo Termine";
        return $this->from(config('mail.default.from.address'), config('mail.default.from.name'))
            ->replyTo(config('mail.default.from.replay'))
            ->subject($subject)
            ->markdown('emails.quotation.new')
            ->with([
                'brand' => $brand,
                'car' => $car,
                'agent' => $agent,
                'subject' => $subject,
                'receiver' => $receiver,
            ]);
    }

    /**
     * Costruisco email per ekly
     * @param Car $car
     * @param Brand $brand
     * @param Agent $agent
     * @param Agent $receiver
     * @return $this
     */
    private function buildEklyEmail(
        Car $car, Brand $brand,
        Agent $agent, Agent $receiver
    ): self
    {
        $subject = "Flee for Ekly - Nuova richiesta di preventivo Noleggio a Lungo Termine";
        return $this->from(config('mail.ekly.from.address'), config('mail.ekly.from.name'))
            ->replyTo(config('mail.ekly.from.replay'))
            ->subject($subject)
            ->markdown('emails.ekly.quotation.new')
            ->with([
                'brand' => $brand,
                'car' => $car,
                'agent' => $agent,
                'subject' => $subject,
                'receiver' => $receiver,
            ]);
    }
}
