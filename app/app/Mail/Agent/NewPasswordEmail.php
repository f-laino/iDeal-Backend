<?php

namespace App\Mail\Agent;

use App\Models\AgentToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Agent;

class NewPasswordEmail extends Mailable
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
        $token = $this->token->token;

        return $this->agent->isEklyAgent() ?
            $this->buildEklyEmail($name, $token):
            $this->buildEmail($name, $token);
    }

    private function buildEmail(string $name, string $token): self
    {
        $subject = 'Recupero Password iDEAL';
        $uri = config('frontend.url');
        $uri .= "/account/password/$token";

        return $this->from(config('mail.default.from.address'), config('mail.default.from.name'))
            ->replyTo(config('mail.default.from.replay'))
            ->subject($subject)
            ->markdown('emails.agent.password')
            ->with([
                'name' => $name,
                'uri' => $uri,
                'subject' => $subject,
            ]);
    }


    private function buildEklyEmail(string $name, string $token): self
    {
        $subject = 'Recupero Password Flee for Ekly';
        $uri = config('frontend.ekly.url');
        $uri .= "/account/password/$token";

        return $this->from(config('mail.ekly.from.address'), config('mail.ekly.from.name'))
            ->replyTo(config('mail.ekly.from.replay'))
            ->subject($subject)
            ->markdown('emails.ekly.agent.password')
            ->with([
                'name' => $name,
                'uri' => $uri,
                'subject' => $subject,
            ]);
    }
}
