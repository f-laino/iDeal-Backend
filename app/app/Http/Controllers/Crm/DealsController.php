<?php

namespace App\Http\Controllers\Crm;

use App\Models\Quotation;
use App\Models\Offer;
use App\Models\ContractualCategory;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Validator;
use Log;

class DealsController extends Controller
{

    /**
     * Aggiorna i prametri di qualigica relativi ad un deal
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateQualificationStep(Request $request){
        $validator = Validator::make($request->all(), [
            'crm' => 'required|exists:quotations,id',
            'block' => 'nullable|string',
            'qualified' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $deal = Quotation::find($request->crm);

        $deal->last_qualified_step = $request->get('block', NULL);
        $status = $deal->save();

        return response()->json(["status"=> $status, 'msg'=> "Deal updated: " . json_encode($status)], Response::HTTP_OK);
    }

    /**
     * Aggiorna la categoria contrattuale di un cliente
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateContractualCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'crm' => 'required|exists:quotations,id',
            'category' => 'required|exists:contractual_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $deal = Quotation::find($request->crm);
        $customer = $deal->customer;
        $category = ContractualCategory::find($request->category);

        $status = $customer->update([
            "contractual_category_id" => $category->id,
        ]);

        return response()->json(["status"=> $status, 'msg'=> "Deal category updated: " . json_encode($status)], Response::HTTP_OK);
    }


    /**
     * Aggiorna i dettagli di una quotazione
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDealDetails(Request $request){
        $validator = Validator::make($request->all(), [
            'crm' => 'required|exists:quotations,id',
            'monthly_rate' => 'nullable|numeric',
            'duration' => 'nullable|numeric',
            'deposit' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $deal = Quotation::find($request->crm);
        $old_deal = $deal->replicate();

        $monthly_rate = $request->get('monthly_rate', 0);
        $deposit = $request->get('deposit', 0);
        $duration = $request->get('duration', 0);

        if ($monthly_rate > 0){
            $deal->monthly_rate = $monthly_rate;
        }

        if (in_array($duration,  Offer::$ALLOWED_DURATIONS)){
            $deal->duration = $duration;
        }

        if ($deposit > 0){
            $deal->deposit = $deposit;
        }

        $status = $deal->update();
        if ($status){
            \Log::channel('deals')->info("Deal $deal->id details updated.", [ 'request' => $request->all(),  'headers' => $request->headers->all(), 'before' => $old_deal, 'after'=> $deal ]);
        }

        return response()->json(["status"=> $status, 'msg'=> "Deal details updated: " . json_encode($status)], Response::HTTP_OK);
    }

    /**
     * Aggiorna lo stato di qualifica di una quotazione
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDealQualification(Request $request){
        $validator = Validator::make($request->all(), [
            'crm' => 'required|exists:quotations,id',
            'qualified' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $deal = Quotation::find($request->crm);
        $old_deal = $deal->replicate();
        $qualified = boolval($request->qualified);

        $status = $deal->update([
            "qualified" => $qualified,
        ]);

        if ($status){
            \Log::channel('deals')->info("Deal $deal->id qualified.", [ 'request' => $request->all(),  'headers' => $request->headers->all(), 'before' => $old_deal, 'after'=> $deal ]);
        }
        return response()->json(["status"=> $status, 'msg'=> "Deal category updated: " . json_encode($status)], Response::HTTP_OK);

    }

}
