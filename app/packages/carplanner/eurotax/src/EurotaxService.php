<?php

namespace CarPlanner\Eurotax;

use CarPlanner\Eurotax\Interfaces\EurotaxServiceInterface;
use CarPlanner\Eurotax\Interfaces\EurotaxRequestInterface;
use GuzzleHttp\Client;
use \Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EurotaxService implements EurotaxServiceInterface, EurotaxRequestInterface
{

    private static $_username;
    private static $_password;
    private static $token;
    private static $_uri;

    private $client;

    public function __construct()
    {
        self::$_username = config('eurotax.username');
        self::$_password = config('eurotax.password');
        self::$_uri = config('eurotax.url');

        $this->client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
    }

    public function auth()
    {
        $response = $this->client->post('https://webservice.motornet.it/token.php', [
            'body' => json_encode([
                'grant_type' => 'client_credentials',
                'client_id' => self::$_username,
                'client_secret' => self::$_password,
            ])
        ]);

        $body = json_decode($response->getBody()->getContents(), TRUE);
        if (!array_key_exists('access_token', $body))
            throw new BadRequestHttpException();
        return $body['access_token'];
    }

    public function getBrands()
    {
        $body = $this->makeRequest('getMarche', []);
        return $body->marche;
    }


    public function makeRequest($path, $params)
    {
        if ( empty(self::$token) )
            self::$token = $this->auth();

        $params['access_token'] = self::$token;
        $params['tipo_veicolo'] = 'auto';
        $response = $this->client->post(self::$_uri . $path, [
            'body' => json_encode($params)
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK)
            throw new BadRequestHttpException();

        $arrayResponse = json_decode($response->getBody()->getContents(), TRUE);
        if (array_key_exists('0', $arrayResponse)) {
            self::$token = $this->auth();
            return $this->makeRequest($path, $params);
        }
        return json_decode($response->getBody());
    }


    function getModelli($aconimoBrand, $anno = FALSE)
    {
        $params = ['marca' => $aconimoBrand];
        if ( !empty($anno) )
            $params['anno'] = $anno;
        $body = $this->makeRequest('getModelli', $params);
        return $body->modelli;
    }

    function getAlimentazioni($codGammaModello, $anno = FALSE)
    {
        $params = ['modello' => $codGammaModello];
        if ( !empty($anno) )
            $params['anno'] = $anno;
        $body = $this->makeRequest('getAlimentazioni', $params);
        return $body->alimentzioni;
    }

    function getVersioni($codGammaModello, $aconimoBrand = FALSE, $anno = FALSE, $codiceAlimentazione = FALSE)
    {
        $params = ['modello' => $codGammaModello];
        if ( !empty($aconimoBrand) )
            $params['marca'] = $anno;
        if ( !empty($anno) )
            $params['anno'] = $anno;
        if ( !empty($codiceAlimentazione) )
            $params['alimentazione'] = $codiceAlimentazione;
        $body = $this->makeRequest('getVersioni', $params);
        return $body->versioni;
    }

    function getImmagini($codiceMotornet, $codiceEurotax, $codiceVisuale = '', $resolution = FALSE)
    {
        $params = [
            'codice_motornet' => $codiceMotornet,
            'codice_eurotax' => $codiceEurotax,
        ];
        if ( !empty($codiceVisuale) )
            $params['risoluzione'] = $codiceVisuale;
        if ( !empty($resolution) )
            $params['risoluzione'] = $resolution;

        $body = $this->makeRequest('getImmagini', $params);
        return $body->immagini;
    }

    function getDettaglioAuto($codiceMotornet, $codiceEurotax)
    {
        $body = $this->makeRequest('getDettaglioAuto', [
            'codice_motornet' => $codiceMotornet,
            'codice_eurotax' => $codiceEurotax,
        ]);
        return $body->modello;
    }

    /**
     * Ritorna l'elenco di accessori associabili ad un allestimento
     * @param $codiceMotornet
     * @param $codiceEurotax
     * @param string $anno
     * @param string $mese
     * @return mixed
     */
    function getAccessori($codiceMotornet, $codiceEurotax, $anno = '1', $mese = '1')
    {
        $body = $this->makeRequest('getAccessori', [
            'codice_motornet' => $codiceMotornet,
            'codice_eurotax' => $codiceEurotax,
            'anno' => $anno,
            'mese' => $mese
        ]);
        return [
            "serie" => $body->serie,
            "optional" => $body->optional,
            "pacchetti" => $body->pacchetti,
            "vernici" => $body->vernici
        ];
    }
}
