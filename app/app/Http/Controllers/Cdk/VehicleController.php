<?php

namespace App\Http\Controllers\Cdk;

use App\Services\CdkService;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Find a customer object
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $inputs = $request->all();
        $vehicles = $this->service->makeRequest(self::$_CONTRACT_CODE, self::$_BUSINESS_UNIT, 'vehicles', $inputs);
        return response()->json($vehicles);
    }


    public function inventory(Request $request)
    {
        $inputs = $request->all();
        $vehicles = $this->service->makeRequest(self::$_CONTRACT_CODE, self::$_BUSINESS_UNIT, 'inventory-vehicles', $inputs);
        return response()->json($vehicles);
    }



}
