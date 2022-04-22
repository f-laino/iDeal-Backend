<?php
namespace App\Interfaces;

 interface CdkServiceInterface{

     const METHOD_GET     = 'GET';
     const METHOD_POST     = 'POST';
     /**
      * Ritorna il token di autenticazione neccessario per effetuare le richieste
      * @param string $grantType
      * @return string
      */
     function auth(string $grantType) : string;

     /**
      * Effetua una richiesta generica al servizio CDK
      * @see https://portal.online-test.cdkapps.eu/?urls.primaryName=Sales%20API
      * @param string $contractCode CDK contactcode (Concessionario)
      * @param string $businessUnit CDK businessunit (Sites/Store)
      * @param string $path il taget delle chiamata
      * @param array $params i parametri richiesti
      * @param string $method il method della richiesta GET | POST
      * @return mixed
      */
     function makeRequest(string $contractCode, string $businessUnit, string $path, array $params = [], $method = 'GET');

 }
