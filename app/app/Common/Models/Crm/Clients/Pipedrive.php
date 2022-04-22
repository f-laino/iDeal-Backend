<?php
namespace App\Common\Models\Crm\Clients;

use App\Abstracts\Crm\Client;

class Pipedrive extends Client
{
    /**
     * @inheritDoc
     */
    function auth(): \Devio\Pipedrive\Pipedrive
    {
        $token = $this->connection->token;
        return new \Devio\Pipedrive\Pipedrive($token);
    }


}
