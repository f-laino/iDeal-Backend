<?php


namespace App\Services;

use App\Interfaces\CdkServiceInterface;
use \Illuminate\Http\Response;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use \Psr\Http\Message\ResponseInterface;
use Cache;

class CdkService implements CdkServiceInterface
{

    /** @var string */
    public static $_uri;
    /** @var  string*/
    public static $_apiVersion;

    /** @var string  */
    public static $_service;

    /** @var string */
    private static $_clientId;
    /** @var string */
    private static $_clientSecret;

    /** @var Client */
    private $client;


    public function __construct(string $_clientId, string $_clientSecret, string $_uri, string $_apiVersion, string $_service)
    {
        self::$_clientId = $_clientId;
        self::$_clientSecret = $_clientSecret;

        self::$_uri = $_uri;
        self::$_apiVersion = $_apiVersion;
        self::$_service = $_service;

        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Connection' => 'close',
                'Cache-Control' => 'no-cache'
            ],
            //'http_errors' => FALSE
        ]);
    }

    public function auth(string $grantType = "client_credentials") : string
    {
        //oAuth
        $authUri = self::$_uri . "/oauth/client_credential/accesstoken?grant_type=$grantType";

        $credentials = base64_encode(self::$_clientId . ":" . self::$_clientSecret);
        $response = $this->client->post($authUri, [
            'headers' => ['Authorization' => 'Basic ' . $credentials],
        ]);

        $body = json_decode($response->getBody()->getContents(), TRUE);
        if (!array_key_exists('access_token', $body))
            throw new BadRequestHttpException();

        $token = $body['access_token'];
        //Store token for 60 * 60 seconds
        Cache::put('cdk_token', $token, 60 * 60);

        return $token;
    }

    public function makeRequest(string $contractCode, string $businessUnit, string $path, array $params = [], $method = self::METHOD_GET)
    {
        //Handle token
        $token = Cache::get('cdk_token', FALSE);
        if (empty($token)) $token = $this->auth();

        $uri = self::$_uri . '/' . self::$_service . "/$contractCode/$businessUnit/" . self::$_apiVersion . "/$path" ;
        $response =
            $method == self::METHOD_GET ?
                $this->makeGetRequest($uri, $token, $params) :
                $this->makePostRequest($uri, $token, $params);

        if ($response->getStatusCode() === Response::HTTP_UNAUTHORIZED){
            $this->auth();
            return $this->makeRequest($contractCode, $businessUnit, $path, $params, $method);
        }
        elseif ($response->getStatusCode() !== Response::HTTP_OK && $response->getStatusCode() !== Response::HTTP_CREATED)
            throw new BadRequestHttpException();

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Make a GET Request to Cdk Service API
     * @param string $uri
     * @param string $token
     * @param array $params
     * @return ResponseInterface
     */
    private function makeGetRequest(string $uri, string $token, array $params = []) : ResponseInterface
    {
        $queryString = "?";
        foreach ($params as $key=>$value)
            $queryString .= "$key=".urlencode($value);
        return $this->client->get( $uri . $queryString, [
            'headers' => ['Authorization' => 'Bearer ' . $token ],
        ]);
    }

    /**
     * Make a POST Request to Cdk Service API
     * @param string $uri
     * @param string $token
     * @param array $params
     * @return ResponseInterface
     */
    private function makePostRequest(string $uri, string $token, array $params = []): ResponseInterface
    {
        return $this->client->post($uri, [
            'body' => json_encode($params),
            'headers' => ['Authorization' => 'Bearer ' . $token],
        ]);
    }


}
