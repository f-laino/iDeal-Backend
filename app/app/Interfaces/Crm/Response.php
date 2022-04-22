<?php
namespace App\Interfaces\Crm;


/**
 * Interface Response
 * @package App\Interfaces\Crm
 */
interface Response
{
    /**
     * Controlla se la richiesta al crm ha esito positivo
     * @return bool
     */
    function isSuccess(): bool;

    /**
     * Restituisce il contenuto della risposta del crm
     * @return mixed
     */
    function getContent();

    /**
     * Ritorna il data content della risposta
     * @return mixed[]|\stdClass
     */
    function getData();

    /**
     * Restituisce l'id dell'entita di referimento
     * @return mixed
     */
    function getEntityId();

    /**
     * Ritrona il status code della richiesta
     * @return integer
     */
    function getStatusCode(): int;

    /**
     * Ritronai header della richiesta come array
     * @return array
     */
    function getHeaders(): array;
}
