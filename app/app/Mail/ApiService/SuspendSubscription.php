<?php

namespace App\Mail\ApiService;

use App\Models\AgentToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Agent;

/**
 * Class SuspendSubscription
 * @package App\Mail\ApiService
 */
class SuspendSubscription extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Agent
     */
    public $agent;

    /**
     * SuspendSubscription constructor.
     * @param Agent $agent
     */
    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->agent->getName();
        $subject = 'iDEAL - E\' stato disattivato il feed del tuo account';

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->replyTo(config('mail.from.replay'))
                    ->subject($subject)
                    ->markdown('emails.apiService.suspend')
                    ->with([
                        'name' => $name,
                        'subject' => $subject,
                    ]);
    }
}
