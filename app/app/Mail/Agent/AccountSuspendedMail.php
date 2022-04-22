<?php

namespace App\Mail\Agent;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Agent;

/**
 * Class SuspendSubscription
 * @package App\Mail\ApiService
 */
class AccountSuspendedMail extends Mailable
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
        return $this->agent->isEklyAgent() ?
            $this->buildEklyEmail($this->agent) :
            $this->buildEmail($this->agent);
    }

    /**
     * @param Agent $agent
     * @return $this
     */
    private function buildEmail(Agent $agent): self
    {
        $subject = 'iDEAL - Il tuo account è stato disattivato';
        return $this->from(config('mail.default.from.address'), config('mail.default.from.name'))
            ->replyTo(config('mail.default.from.replay'))
            ->subject($subject)
            ->markdown('emails.agent.suspend')
            ->with([
                'name' => $agent->getName(),
                'subject' => $subject,
            ]);
    }

    /**
     * @param Agent $agent
     * @return $this
     */
   private function buildEklyEmail(Agent $agent): self
    {
        $subject = 'Flee for Ekly - Il tuo account è stato disattivato';
        return $this->from(config('mail.ekly.from.address'), config('mail.ekly.from.name'))
            ->replyTo(config('mail.ekly.from.replay'))
            ->subject($subject)
            ->markdown('emails.ekly.agent.suspend')
            ->with([
                'name' => $agent->getName(),
                'subject' => $subject,
            ]);
    }
}
