<?php
namespace CarPlanner\Eurotax\Interfaces;

 interface EurotaxRequestInterface{

     /**
      * Ritorna il token di autenticazione neccessario per effetuare le richieste
      * @return mixed
      */
    function auth();

     /**
      * Effetua una richiesta generica al servizio eurotax
      * @param $path il taget delle chiamata
      * @param $params i parametri richiesti
      * @return mixed il contenuto della risposta
      */
    function makeRequest($path, $params);

 }