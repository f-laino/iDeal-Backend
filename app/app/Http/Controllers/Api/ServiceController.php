<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Service;
use App\Transformer\ServiceTransformer;

class ServiceController extends ApiController
{

    /**
     * Get services list
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Service"},
     *   path="/services",
     *   summary="get services list",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/Service")
     *       )
     *     )
     *   )
     * )
     */
    public function index()
    {
        $services = Service::all();
        return $this->respondWithCollection($services, new ServiceTransformer);
    }
}
