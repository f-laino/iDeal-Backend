<?php

namespace App\Mail\Agent;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Agent;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $agent;

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * Build the message.
     *
     * @return $this
     * @throws \Tymon\JWTAuth\Exceptions\TokenInvalidException
     */
    public function build()
    {
        $name = $this->agent->getName();
        $token = $this->agent->getActivationToken();

        return $this->agent->isEklyAgent() ?
            $this->buildEklyEmail($name, $token):
            $this->buildEmail($name, $token);
    }

    /**
     * @param string $name
     * @param string $token
     * @return $this
     */
    private function buildEmail(string $name, string $token): self
    {
        $subject = 'Hai un nuovo account iDEAL';
        $uri = config('frontend.url');
        $uri .= "/account/activate/$token";

        return $this->from(config('mail.default.from.address'), config('mail.default.from.name'))
            ->replyTo(config('mail.default.from.replay'))
            ->subject($subject)
            ->markdown('emails.agent.welcome')
            ->with([
                'name' => $name,
                'uri' => $uri,
                'subject' => $subject,
            ]);
    }

    /**
     * @param string $name
     * @param string $token
     * @return $this
     */
    private function buildEklyEmail(string $name, string $token): self
    {
        $subject = 'Hai un nuovo account Flee for Ekly';
        $uri = config('frontend.ekly.url');
        $uri .= "/account/activate/$token";

        return $this->from(config('mail.ekly.from.address'), config('mail.ekly.from.name'))
            ->replyTo(config('mail.ekly.from.replay'))
            ->subject($subject)
            ->markdown('emails.ekly.agent.welcome')
            ->with([
                'name' => $name,
                'uri' => $uri,
                'subject' => $subject,
            ]);
    }
}
