<?php

namespace App\Notifications\Agent;

use App\Models\Agent;
use App\Common\Models\Offers\Generic as GenericOffer;
use App\Models\Customer;
use App\Mail\Agent\CustomOfferRequestedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class CustomOfferRequestedNotification extends Notification
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
     * @return CustomOfferRequestedMail
     */
    public function toMail($notifiable): CustomOfferRequestedMail
    {
        $to = $this->agent->email;
        return (
            new CustomOfferRequestedMail(
                $this->agent,
                $this->customer,
                $this->offer)
        )->to($to);
    }

}
