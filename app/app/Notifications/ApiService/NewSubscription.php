<?php

namespace App\Notifications\ApiService;

use App\Models\AgentToken;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Agent;

/**
 * Class NewSubscription
 * @package App\Notifications
 */
class NewSubscription extends Notification
{
    use Queueable;

    /** @var Agent  */
    public $agent;

    /** @var AgentToken  */
    public $token;

    /**
     * NewSubscription constructor.
     * @param Agent $agent
     * @param AgentToken $token
     */
    public function __construct(Agent $agent, AgentToken $token)
    {
        $this->agent = $agent;
        $this->token = $token;
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
     * @return \App\Mail\ApiService\NewSubscription
     */
    public function toMail($notifiable)
    {
        return (new \App\Mail\ApiService\NewSubscription($notifiable, $this->token))->to($this->agent->email);

    }

}
