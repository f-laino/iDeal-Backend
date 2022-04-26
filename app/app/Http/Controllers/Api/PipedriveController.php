<?php

namespace App\Http\Controllers\Api;

use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

/**
 * Class PipedriveController
 * @package App\Http\Controllers\Api
 */
class PipedriveController extends Controller
{
    /**
     * @param Request $request
     * @param $crmCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $requestBody = json_decode($request->getContent(), true);

        try {
            $deal = $requestBody["current"];
            $quotation = Quotation::where('crm_id', $deal['id'])->firstOrFail();
            $stage = $deal['stage_id'];

            $quotation->update([
                'stage' => $stage,
                'monthly_rate' => $deal['e326f4915b5135977c8d4328a26f5cc69b96d424'],
                'deposit' => $deal['fcab4389284d610a96509c3270fea8452282b59d'],
                //'duration' => $deal['141f9ce569227a1533b2ebdaf4827d1b6f24ec14'],
                'distance' => $deal['647de315ffd074a6bd2d9c402a53ccf085d82503'],
                'status' => strtoupper($deal['status']),
            ]);
            Log::channel('pipedrive')->info("Update from Pipedrive: Quotation CRM Ref " . $deal['id']);
            return response()->json(['message' => 'Quotation data updated'], 200);
        } catch (\Exception $exception) {
            Log::channel('pipedrive')->error("PIPEDRIVE_UPDATE_VALUES " . $exception->getMessage());
            return response()->json(['message' => 'Quotation not found on crm'], 200);
        }
    }
}
