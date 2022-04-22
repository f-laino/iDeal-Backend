<?php

namespace App\Http\Controllers\Cdk;

use App\Services\CdkService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Find a customer object
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $inputs = $request->all();
        $customers = $this->service->makeRequest(self::$_CONTRACT_CODE, self::$_BUSINESS_UNIT, 'customers', $inputs);
        return response()->json($customers);
    }

    /**
     * Create a customer object
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $inputs = $request->all();
        $customer = $this->service->makeRequest(self::$_CONTRACT_CODE, self::$_BUSINESS_UNIT, 'customers', $inputs, CdkService::METHOD_POST);
        return response()->json($customer);
    }
}
