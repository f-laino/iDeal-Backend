<?php

namespace App\Mail;

use App\Models\Agent;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrivacyPolicyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $customer;

    public $agent;

    public function __construct(Customer $customer, Agent $agent)
    {
        $this->customer = $customer;
        $this->agent = $agent;
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build(): self
    {
       return $this->agent->isEklyAgent() ? $this->buildEklyEmail() : $this->buildEmail();
    }

    /**
     * @return $this
     */
    private function buildEmail(): self
    {
        $subject = 'La tua privacy per noi è importante!';
        $uri = 'https://static.ideal-rent.com/documents/customer-privacy-policy.pdf';
        return $this->from(config('mail.default.from.address'), config('mail.default.privacy.name'))
            ->cc(config('mail.default.privacy.address'))
            ->replyTo(config('mail.default.privacy.replay'))
            ->subject($subject)
            ->markdown('emails.customer.privacy')
            ->with([
                'name' => $this->customer->first_name,
                'subject' => $subject,
                'uri' => $uri,
            ]);
    }


    private function buildEklyEmail(): self
    {
        $subject = 'La tua privacy per noi è importante!';
        $uri = 'https://static.ideal-rent.com/documents/customer-privacy-policy.pdf';
        return $this->from(config('mail.ekly.from.address'), config('mail.ekly.privacy.name'))
            ->cc(config('mail.ekly.privacy.address'))
            ->replyTo(config('mail.ekly.privacy.replay'))
            ->subject($subject)
            ->markdown('emails.ekly.customer.privacy')
            ->with([
                'name' => $this->customer->first_name,
                'subject' => $subject,
                'uri' => $uri,
            ]);
    }
}
