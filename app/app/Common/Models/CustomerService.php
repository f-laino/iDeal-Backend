<?php

namespace App\Common\Models;
use Illuminate\Notifications\Notifiable;

/**
 * Class CustomerService
 * Rappresenta il back office di iDEAL
 * @package App\Common\Models
 */
class CustomerService
{
    use Notifiable;

    /** @var string $email */
    private $email;

    /**
     * CustomerService constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Ritorna l'indirizzo email del customer service
     * @return string
     */
    public function getEmailAddress(){
        return $this->email;
    }
}
