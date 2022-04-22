<?php

namespace App\Notifications;

use App\Mail\Agent\WelcomeEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Agent;

class AgentCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $agent;

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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
         return (new WelcomeEmail($notifiable))->to($this->agent->email);
    }

}
