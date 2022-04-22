<?php

namespace App\Http\Controllers\Cms;

use App\Common\Models\Activity\Logger;
use App\Http\Requests\Cms\ChildOfferCreate;
use App\Http\Requests\Cms\ChildOfferDestroy;
use App\Models\Offer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class RentChildOfferController
 * @package App\Http\Controllers\Admin
 */
class ChildOfferController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(ChildOfferCreate $request, $id){
        Logger::request('ChildOfferController@Store', $request);

        $error = '';
        $status = TRUE;
        $data = 0; //imposto id offer a zero

        $deposit = $request->get('deposit');
        $distance = $request->get('distance');
        $duration = $request->get('duration');
        $monthlyRate = $request->get('monthly_rate');

        try{
            /** @var Offer $offer */
            $offer = Offer::findOrFail($id);

            $child = $offer->addChildOffer($duration, $distance, $deposit, $monthlyRate);
            $data = $child->id;

            //segno offerta main come aggiornata
            $offer->touch();

        }catch (\Exception $exception){
            $status = FALSE;
            $error = $exception->getMessage();
        }
        return response()->json(['status' => $status, 'error'=> $error, 'data' => $data]);
    }

    /**
     * Cancella un'offerta child
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ChildOfferDestroy $request){
        Logger::request('ChildOfferController@Destroy', $request);

        $error = '';
        $status = TRUE;

        $child = $request->get('child');

        try {
            //get offers
            /** @var Offer $childOffer */
            $childOffer = Offer::findOrFail($child);
            /** @var Offer $childOffer */
            $mainOffer = Offer::findOrFail($childOffer->parent_id);
            //segno offerta main come aggiornata
            $mainOffer->touch();
            $user = auth()->user();
            Logger::entity('ChildOfferController@Destroy', $user, $childOffer);

            $status = Offer::destroy($child);

            //rimuovo il flag se non ci sono altre child associate
            if(!$mainOffer->hasChildOffers()){
                $mainOffer->update(['highlighted' => FALSE]);
            }

        } catch (\Exception $exception){
            $status = FALSE;
            $error = $exception->getMessage();
        }
        return response()->json(['status' => $status, 'error'=> $error]);
    }

    /**
     * Imposta un'offerta come offerta main
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAsMain(Request $request, $id){
        $error = '';
        $status = TRUE;
        try{
            $offer = Offer::findOrFail($id);
            $child = Offer::findOrFail($request->child);

            $oldOffer = $offer->replicate();

            $offer->update([
                "deposit" => $child->deposit,
                "monthly_rate" => $child->monthly_rate,
                "web_monthly_rate" => $child->monthly_rate,
                "duration" => $child->duration,
                "distance" => $child->distance,
            ]);
            $child->update([
                "deposit" => $oldOffer->deposit,
                "monthly_rate" => $oldOffer->monthly_rate,
                "web_monthly_rate" => $oldOffer->monthly_rate,
                "duration" => $oldOffer->duration,
                "distance" => $oldOffer->distance,
            ]);

        } catch (\Exception $exception){
            $status = FALSE;
            $error = $exception->getMessage();
        }
        return response()->json(['status' => $status, 'error'=> $error]);
    }

}
