<?php

namespace App\Http\Controllers\Crm;

use App\Factories\CrmFactory;
use App\Models\Quotation;
use App\Models\Customer;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Validator;
use Log;

class QuotationsController extends Controller
{

    public function update(Request $request)
    {
        $status = FALSE;
        $validator = Validator::make($request->all(), [
            'crm' => 'required|exists:quotations,id',
            'key' => 'required|min:1|max:255',
            'value' => 'required|min:1|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $not_mutable = ['stage_id'];

        $deal = Quotation::find($request->crm);
        $crm = CrmFactory::create($deal);


        $key = $request->get('key');
        $value = $request->get('value');
        $crmKey = $this->getDealFieldKey($key);

        if (!in_array($key, $not_mutable)) {
            if($key == 'duration') $value = $this->getDurationValueFromPipedriveId($value);
            $fields = [ $crmKey => $value ];
            $status = $crm->updateDeal($deal, $fields);
        }

        if (($key == config('pipedrive.deal.anticipo_richiesto') || $key == config('pipedrive.deal.nuovo_anticipo')) && intval($value) > 0) {
            $old_deal = $deal->replicate();
            $deal->update([
                'deposit' => intval($value)
            ]);
            Log::channel('quotations')->info("Deal $deal->id details updated.", ['request' => $request->all(), 'headers' => $request->headers->all(), 'before' => $old_deal, 'after' => $deal]);
        }

        if ($key == config('pipedrive.deal.nuovo_canone_relativo_anticipo') && intval($value) > 0) {
            $old_deal = $deal->replicate();
            $deal->update([
                'monthly_rate' => intval($value),
            ]);
            Log::channel('quotations')->info("Deal $deal->id details updated.", ['request' => $request->all(), 'headers' => $request->headers->all(), 'before' => $old_deal, 'after' => $deal]);
        }

        if ( $key == config('pipedrive.deal.durata_richiesta') && intval($value) > 0) {
            $old_deal = $deal->replicate();
            $duration = $this->getDurationValueFromPipedriveId($value);
            $deal->update([
                'duration' => $duration,
            ]);
            Log::channel('quotations')->info("Deal $deal->id details updated.", ['request' => $request->all(), 'headers' => $request->headers->all(), 'before' => $old_deal, 'after' => $deal]);
        }

        if ( $key == 'status' && $value == 'lost' ){
            $old_deal = $deal->replicate();
            $deal->update([
                'qualified' => TRUE,
            ]);
            Log::channel('quotations')->info("Deal $deal->id qualified.", ['request' => $request->all(), 'headers' => $request->headers->all(), 'before' => $old_deal, 'after' => $deal]);
        }


        return response()->json(["status" => $status, 'msg' => "Deal updated: " . json_encode($status)], Response::HTTP_OK);
    }

    public function updateStage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'crm' => 'required|exists:quotations,id',
            'stage' => 'required|min:1|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $deal = Quotation::find($request->crm);
        $crm = CrmFactory::create($deal);
        $fields = [ 'stage_id' => $request->stage ];
        $status = $crm->updateDeal($deal, $fields);

        return response()->json(["status" => $status, 'msg' => "Deal stage updated: " . json_encode($status)], Response::HTTP_OK);
    }


    public function calculateDealSustainability(Request $request){
        $validator = Validator::make($request->all(), [
            'deal' => 'required|exists:quotations,id',
//            'user' => 'required|exists:customers,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $deal = Quotation::find($request->crm);
//        $user = Customer::where('id', $request->user)->first();

        $crm = CrmFactory::create($deal);

        //Questa funzione non esiste e non e' mai esistita
//        $userCrmData = $user->getCrmData();
//        $userRataSostenibile = !empty( $userCrmData[config('pipedrive.person.rata_sostenibile')] ) ? $userCrmData[config('pipedrive.person.rata_sostenibile')] : 0;
        $userRataSostenibile = 0;
        $userRataSostenibile = intval($userRataSostenibile);
        $userRataSostenibile = 0.5 * $userRataSostenibile + $userRataSostenibile;

        $old_deal = $deal->replicate();
        if($deal->monthly_rate <= $userRataSostenibile){
            $fields = [
                'status' => 'open',
                'priorita' => 2,
            ];
            $crm->updateDeal($deal, $fields);
            $deal->update([ 'qualified' => TRUE ]);
            Log::channel('quotations')->info("Deal $deal->id qualified.", ['request' => $request->all(), 'headers' => $request->headers->all(), 'before' => $old_deal, 'after' => $deal]);
            return response()->json(["priority" => 2, 'status'=>'open', 'msg' => "Deal priority updated: 3" ], Response::HTTP_OK);
        } else {
            $fields = [
                'status' => 'lost',
                'lost_reason' => "SCARTO AUTOMATICO - RATA NON SOSTENIBILE",
            ];
            $crm->updateDeal($deal, $fields);
            $deal->update([ 'qualified' => TRUE , 'status' => 'CLOSED']);
            Log::channel('quotations')->info("Deal $deal->id qualified.", ['request' => $request->all(), 'headers' => $request->headers->all(), 'before' => $old_deal, 'after' => $deal]);
            return response()->json(["priority" => 'lost', 'status'=>'lost', 'msg' => "Deal priority updated: lost. Reason: RATA NON SOSTENIBILE "], Response::HTTP_OK);
        }

    }

    public function getLastBlock(Request $request){
        $validator = Validator::make($request->all(), [
            'crm' => 'required|exists:quotations,id',
            'stage' => 'required|min:1|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $deal = Quotation::find($request->crm);

        $last_qualified_step = $deal->last_qualified_step;
        if(boolval($deal->qualified)){
            $last_qualified_step = null;
        }
        return response()->json(['interaction_id' => $last_qualified_step ], Response::HTTP_OK);
    }


    private function getDurationValueFromPipedriveId($pipedrive_id)
    {
        switch (intval($pipedrive_id)){
            case 118:
                $value = 24;
                break;
            case 119:
                $value = 36;
                break;
            case 120:
                $value = 48;
                break;
            case 121:
                $value = 60;
                break;
            default:
                $value = 18;
        }
        return $value;

    }

}
