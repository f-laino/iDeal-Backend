<?php
namespace App\Interfaces;
use SevenShores\Hubspot\Http\Response as HubSpotResponse;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

interface HubSpotServiceInterface{

    /**
     * @param GuzzleResponse $response
     * @return mixed
     */
    public static function isResponseSuccessful(GuzzleResponse $response);

    /**
     * @param GuzzleResponse $response
     * @return mixed
     */
    public static function isResponseSuccessfulButEmpty(GuzzleResponse $response);

    /**
     * @param GuzzleResponse $response
     * @return mixed
     */
    public static function isResponseNotFound(GuzzleResponse $response);

    /**
     * @param GuzzleResponse $response
     * @param string|NULL $attribute
     * @return mixed
     */
    public static function getResponseContent(GuzzleResponse $response, string $attribute = NULL);

 }