<?php

namespace App\Abstracts;

use Illuminate\Support\Facades\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

abstract class Responder
{
    protected $fractal;

    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;
    }

    /**
     * @param $collection
     * @param $transformer
     */
    protected function respondWithCollection($collection, $transformer)
    {
        $resource = new Collection($collection, $transformer);

        $data = $this->fractal->createData($resource);

        $dataArray = $data->toArray();

        return $dataArray['data'];
    }

    /**
     * @param $item
     * @param $callback
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param array $array
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array, array $headers = []): \Illuminate\Http\JsonResponse
    {
        $statusCode = $this->statusCode;

        if (isset($array['data']['statusCode'])) {
            $statusCode = $array['data']['statusCode'];
        }

        return Response::json($array, $statusCode, $headers);
    }
}
