<?php

namespace App\Notifications;

use App\Models\AgentToken;
use App\Mail\Agent\NewPasswordEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Agent;

class ForgotAgentPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $agent;
    public $token;

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
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
         return (new NewPasswordEmail($notifiable, $this->token))->to($this->agent->email);
    }

}
