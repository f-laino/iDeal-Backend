<?php

namespace App\Notifications\ApiService;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Agent;

/**
 * Class SuspendSubscription
 * @package App\Notifications
 */
class SuspendSubscription extends Notification
{
    use Queueable;

    /** @var Agent  */
    public $agent;

    /**
     * NewSubscription constructor.
     * @param Agent $agent
     */
    public function __construct(Agent $agent)
    {
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
     * @return \App\Mail\ApiService\SuspendSubscription
     */
    public function toMail($notifiable)
    {
        return (new \App\Mail\ApiService\SuspendSubscription($notifiable))->to($this->agent->email);

    }

}
