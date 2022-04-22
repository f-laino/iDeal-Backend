<?php
namespace App\Abstracts\Crm;

use App\Models\CrmConnection;

abstract class Client implements \App\Interfaces\Crm\Client
{
    private $client;
    protected $connection;

    /**
     * Client constructor.
     * @param CrmConnection $connection
     */
    public final function __construct(CrmConnection $connection)
    {
        $this->connection = $connection;
        $this->client = $this->auth();
    }

    /**
     * @inheritDoc
     */
    public function getClient()
    {
      return $this->client;
    }

    /**
     * @inheritDoc
     */
    function getOwner(){
        return $this->connection->owner;
    }
}
