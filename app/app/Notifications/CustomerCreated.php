<?php

namespace App\Notifications;

use App\Models\Agent;
use App\Models\Customer;
use App\Mail\PrivacyPolicyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomerCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
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
     *
     * @param  mixed  $notifiable
     * @return PrivacyPolicyEmail
     */
    public function toMail($notifiable)
    {
        $to = $this->customer->email;
        return (
            new PrivacyPolicyEmail(
                $this->customer,
                $this->agent)
            )->to($to);
    }

}
