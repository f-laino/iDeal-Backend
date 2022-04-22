<?php

namespace App\Notifications\Requests;

use App\Models\Agent;
use App\Common\Models\CustomerService;
use App\Common\Models\Offers\Generic as GenericOffer;
use App\Models\Customer;
use App\Mail\Requests\NewOfferEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class NewOffer
 * @package App\Notifications\Requests
 */
class NewOffer extends Notification
{
    use Queueable;

    /** @var Agent $agent */
    public $agent;

    /** @var Customer $customer */
    public $customer;

    /** @var GenericOffer $offer */
    public $offer;

    /**
     * NewOffer constructor.
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
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     * @param $notifiable
     * @return NewOfferEmail
     */
    public function toMail($notifiable)
    {
        $to = $notifiable->getEmailAddress();
        return (
            new NewOfferEmail(
                $this->agent,
                $this->customer,
                $this->offer)
        )->to($to);
    }

}
