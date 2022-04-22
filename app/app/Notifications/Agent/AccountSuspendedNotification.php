<?php

namespace App\Notifications\Agent;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Mail\Agent\AccountSuspendedMail;
use App\Models\Agent;

/**
 * Class SuspendSubscription
 * @package App\Notifications
 */
class AccountSuspendedNotification extends Notification
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
     * @return AccountSuspendedMail
     */
    public function toMail($notifiable)
    {
        $to = $this->agent->email;
        return (new AccountSuspendedMail($notifiable))->to($to);

    }

}
