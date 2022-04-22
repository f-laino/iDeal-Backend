<?php

namespace App\Mail\ApiService;

use App\Models\AgentToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Agent;

class NewSubscription extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->agent->getName();
        $subject = 'iDEAL - E\' attivo il feed del tuo account';

        $token = $this->token->token;

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->replyTo(config('mail.from.replay'))
                    ->subject($subject)
                    ->markdown('emails.apiService.new')
                    ->with([
                        'name' => $name,
                        'subject' => $subject,
                        'accessToken' => $token,
                    ]);
    }
}
