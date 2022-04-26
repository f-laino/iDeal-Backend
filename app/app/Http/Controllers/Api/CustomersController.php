<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Http\Controllers\ApiController;
use App\Transformer\CustomerFormTransformer;
use App\Transformer\CustomerItemTransformer;
use App\Transformer\CustomerTransformer;
use App\Transformer\ErrorResponseTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;

class CustomersController extends ApiController
{
    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
    }

    /**
     * List of customers
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Customer"},
     *   path="/customers",
     *   summary="list of customers",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/CustomerItem")
     *       )
     *     )
     *   )
     * )
     */
    public function index()
    {
        $agent = auth('api')->user();
        $customers = $agent->customers;
        return $this->respondWithCollection($customers, new CustomerItemTransformer());
    }

    /**
     * Get a customer
     *
     * @param int $code
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Customer"},
     *   path="/customers/{code}",
     *   summary="get a customer",
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/Customer"
     *       )
     *     )
     *   )
     * )
     */
    public function show(int $code)
    {
        try {
            $agent = auth('api')->user();
            $customer = $agent->customers()->where("customer_id", $code)->firstOrFail();
            return $this->respondWithItem($customer, new CustomerTransformer());
        } catch (\Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }
    }

    /**
     * Search for customer by fiscal code and, optionally, contractual category
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Customer"},
     *   path="/customers/search",
     *   summary="search for customer by fiscal code and, optionally, contractual category",
     *   @OA\Parameter(
     *     name="code",
     *     in="query",
     *     required=true
     *   ),
     *   @OA\Parameter(
     *     name="category",
     *     in="query"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/CustomerForm"
     *       )
     *     )
     *   ),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function search(Request $request)
    {
        try {
            $code = $request->get('code', '');
            $contractualCategory = $request->get('category', null);
            $agent = auth('api')->user();
            $group = $agent->myGroup;

            $customer = Customer::findByFiscalCode($code, $group->id, $contractualCategory);

            return $this->respondWithItem($customer, new CustomerFormTransformer());
        } catch (\Exception $exception) {
            return $this->errorNotFound();
        }
    }
}
