<?php

namespace App\Services;

use App\Interfaces\MetricsServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Log;

class MetricsService implements MetricsServiceInterface
{
    private $client;

    protected function getClient()
    {
        if (!$this->client || !($this->client instanceof Client)) {
            $stack = HandlerStack::create();
            $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
                $contentsRequest = (string)$request->getBody();
                Log::channel('metric')->info('metric sent payload',
                    json_decode($contentsRequest, true)
                );
                return $request;
            }));

            $this->client = new Client([
                'base_uri' => 'https://push.databox.com',
                'headers'  => [
                    'User-Agent'   => 'databox-php/2.0',
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/vnd.databox.v2+json'
                ],
                'auth' => [config('app.databox.token'), '', 'Basic'],
                'handler' => $stack,
            ]);
        }

        return $this->client;
    }

    public function measureThis($metricName, $metricValue = 1, $additionalAttributes = null)
    {
        try {
            $payload = $this->preparePayload($metricName, $metricValue, $additionalAttributes);

            $postData = ['json' => ['data' => [$payload]]];

            $response = $this->getClient()->post('/', $postData);
            $responseBody = $response->getBody();
            $responseBody = json_decode($responseBody, true);

            Log::channel('metric')->info(__FUNCTION__, [
                'response' => $responseBody,
                'params' => [$metricName, $metricValue, $additionalAttributes]
            ]);
        } catch (\Exception $exception) {
            Log::channel('metric')->error(__FUNCTION__, [
                'error' => $exception->getMessage(),
                'in' => $exception->getFile() . ':' . $exception->getLine(),
                'params' => [$metricName, $metricValue, $additionalAttributes],
            ]);
        }
    }

    public function sendHistory(array $data, $withinTotal = true)
    {
        if (!empty($data)) {
            try {
                $payload = [];

                foreach ($data as $item) {
                    list($metricName, $metricValue, $date, $additionalAttributes) = $item;
                    $payload[] = $this->preparePayload($metricName, $metricValue, $additionalAttributes, $date);

                    if ($withinTotal) {
                        $payload[] = $this->preparePayload('tot_' . $metricName, $metricValue, [], $date);
                    }
                }

                $postData = ['json' => ['data' => $payload]];

                $response = $this->getClient()->post('/', $postData);
                $responseBody = $response->getBody();
                $responseBody = json_decode($responseBody, true);

                Log::channel('metric')->info('sendHistory', [
                    'response' => $responseBody
                ]);
            } catch (\Exception $exception) {
                Log::channel('metric')->error('sendHistory', [
                    'error' => $exception->getMessage(),
                    'in' => $exception->getFile() . ':' . $exception->getLine(),
                    'data' => $data,
                ]);
            }
        }
    }

    protected function preparePayload($metricName, $metricValue, $additionalAttributes, $date = null)
    {
        $payload = [sprintf('$%s', trim($metricName, '$')) => $metricValue];

        if (!empty($date)) {
            $payload['date'] = $date;
        }

        if (!empty($additionalAttributes)) {
            $payload += $additionalAttributes;
        }

        return $payload;
    }
}
