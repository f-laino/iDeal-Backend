<?php

namespace App\Mail\Requests;

use App\Models\Agent;
use App\Common\Models\Offers\Generic as GenericOffer;
use App\Models\ContractualCategory;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOfferEmail extends Mailable
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

        /** @var ContractualCategory $contractualCategory */
        $contractualCategory = $this->customer->category;

        return $this->agent->isEklyAgent() ?
            $this->buildEklyEmail($contractualCategory) :
            $this->buildEmail($contractualCategory);
    }

    /**
     * @param ContractualCategory $contractualCategory
     * @return $this
     */
    private function buildEmail(ContractualCategory $contractualCategory): self
    {
        $subject = "iDEAL - Richiesta preventivo nuova Offerta Noleggio a Lungo Termine";

        return $this->from(config('mail.default.from.address'), config('mail.default.from.name'))
            ->replyTo($this->agent->email)
            ->subject($subject)
            ->markdown('emails.requests.offer')
            ->with([
                'subject' => $subject,
                'customer' => $this->customer,
                'agent' => $this->agent,
                'offer' => $this->offer,
                'category' => $contractualCategory,
            ]);
    }

    /**
     * @param ContractualCategory $contractualCategory
     * @return $this
     */
    private function buildEklyEmail(ContractualCategory $contractualCategory): self
    {
        $subject = "Flee for Ekly - Richiesta preventivo nuova Offerta Noleggio a Lungo Termine";

        return $this->from(config('mail.ekly.from.address'), config('mail.ekly.from.name'))
            ->replyTo($this->agent->email)
            ->subject($subject)
            ->markdown('emails.ekly.requests.offer')
            ->with([
                'subject' => $subject,
                'customer' => $this->customer,
                'agent' => $this->agent,
                'offer' => $this->offer,
                'category' => $contractualCategory,
            ]);
    }
}
