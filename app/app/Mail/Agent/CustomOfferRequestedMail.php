<?php

namespace App\Mail\Agent;

use App\Models\Agent;
use App\Common\Models\Offers\Generic as GenericOffer;
use App\Models\ContractualCategory;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomOfferRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Agent $agent */
    public $agent;

    /** @var Customer $customer */
    public $customer;

    /** @var GenericOffer $offer */
    public $offer;

    /**
     * NewOfferEmail constructor.
     * @param Agent $agent
     * @param Customer $customer
     * @param GenericOffer $offer
     */
    public function __construct(Agent $agent, Customer $customer, GenericOffer $offer)
    {
        $this->agent = $agent;
        $this->customer = $customer;
        $this->offer = $offer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $contractualCategory = $this->customer->category;

        return $this->agent->isEklyAgent() ?
            $this->buildEklyEmail($this->agent, $this->customer, $this->offer, $contractualCategory):
            $this->buildEmail($this->agent, $this->customer, $this->offer, $contractualCategory);
    }

    private function buildEmail(
        Agent $agent, Customer $customer, GenericOffer $offer,
        ContractualCategory $contractualCategory
    ): self
    {
        $subject = "iDEAL - Richiesta preventivo nuova Offerta Noleggio a Lungo Termine";
        return $this->from(config('mail.default.from.address'), config('mail.default.from.name'))
            ->cc($agent->email)
            ->replyTo($agent->email)
            ->subject($subject)
            ->markdown('emails.agent.customOfferRequested')
            ->with([
                'subject' => $subject,
                'customer' => $customer,
                'agent' => $agent,
                'offer' => $offer,
                'category' => $contractualCategory,
            ]);
    }

    private function buildEklyEmail(
        Agent $agent, Customer $customer, GenericOffer $offer,
        ContractualCategory $contractualCategory
    ): self
    {
        $subject = "Flee for Ekly - Richiesta preventivo nuova Offerta Noleggio a Lungo Termine";

        return $this->from(config('mail.ekly.from.address'), config('mail.ekly.from.name'))
            ->cc($agent->email)
            ->replyTo($agent->email)
            ->subject($subject)
            ->markdown('emails.ekly.agent.customOfferRequested')
            ->with([
                'subject' => $subject,
                'customer' => $customer,
                'agent' => $agent,
                'offer' => $offer,
                'category' => $contractualCategory,
            ]);
    }
}
