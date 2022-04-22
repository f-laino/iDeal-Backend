<?php
namespace App\Interfaces\Crm;

interface Client
{
    /**
     * Crea il client aprendo una connessione con il crm
     * @return mixed
     */
    function auth();

    /**
     * Ritorna il client
     * @return mixed
     */
    function getClient();

    /**
     * Ritorna il proprietario della connessione
     * @return mixed
     */
    function getOwner();
}
