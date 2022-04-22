<?php

namespace App\Http\Controllers\Quoter;

use App\Models\Car;
use App\Models\CarAccessory;
use App\Models\CarAccessoryGroup;
use App\Common\Models\ErrorResponse;
use App\Http\Requests\Quoter\OfferUpdateRequest;
use App\Models\Image;
use App\Models\Offer;
use App\Models\OfferAttributes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Mockery\Exception;
use Validator;
use Log;

/**
 * Class OfferController
 * @package App\Http\Controllers\Quoter
 * @deprecated
 */
class OfferController extends Controller
{
    public function update(OfferUpdateRequest $request)
    {

        Log::channel('quoter')
            ->info("New request from quoter",
                ['request' => $request->all(),
                    'headers' => $request->headers->all()
                ]
            );

        $code = $request->input('offer.code');

        try{
            $offer = Offer::withTrashed()->where('code', $code)->firstOrFail();
            $exists = true;
        } catch (ModelNotFoundException $exception){
            $offer = new Offer();
            $offer->code = $code;
            $exists = false;
        }

        $offer->parent_id =  $request->input('offer.parent_id', NULL);

        $car = $this->storeCarData($request->input('car'));
        //Handle Car Images
        $images = $this->storeCarImages($car, $request->input('images'));
        //Handle Car Accessories

        $accessoriesErrors = $this->storeAccessories($car, $request->input('accessories'));
        if(!empty($accessoriesErrors))
            Log::channel('quoter')->warning("Errors occurs when trying to synch Car ID $car->id accessories.", ['errors' => $accessoriesErrors]);


        $offer->car_id = $car->id;
        $offer->monthly_rate = $request->input('offer.monthly_rate');
        $offer->web_monthly_rate = $request->input('offer.monthly_rate');
        $offer->deposit = $request->input('offer.deposit');
        $offer->distance = $request->input('offer.distance');
        $offer->duration = $request->input('offer.duration');
        $offer->broker = $request->input('offer.broker');
        $offer->status = $request->input('offer.status', TRUE);
        //$offer->highlighted = $request->input('offer.highlighted', FALSE);
        $offer->highlighted = FALSE;

        $deleted_at = $request->input('offer.deleted', NULL);
        if( empty($deleted_at) || strtolower($deleted_at) == 'null')
            $deleted_at = NULL;

        $offer->deleted_at = $deleted_at;

       try{
           $offer->saveOrFail();
           Log::channel('quoter')->info("Offer $offer->id updated.", ['offer' => $offer]);

       } catch (Exception $exception){
           Log::channel('quoter')->info("Error occurred on update offer: $offer->id.", ['exception' => $exception->getMessage(), 'exception_trace' => $exception->getTraceAsString()]);
           return response([ 'msg' => $exception->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR)->json();
       }


       if(!$exists){
           /* Handle Offer Agents*/
            $offer->attachAllAgents();
           /* End Offer Agents*/

           /* Handle Offer Services*/
           $offer->attachDefaultServices();
           /* End Offer Services*/
       }


       /* Handle Tag*/
        $tag = $request->input('tag.value', NULL);
        if(!empty($tag)){
            $label = OfferAttributes::firstOrNew([
                "offer_id" => $offer->id,
                "type" => "LEFT_LABEL",
            ]);
            $label->offer_id = $offer->id;
            $label->type = "LEFT_LABEL";
            $label->value =  $tag;
            $label->description = $request->input('tag.description', NULL);
            $label->saveOrFail();
        } else {
             OfferAttributes::where([
                "offer_id" => $offer->id,
                "type" => "LEFT_LABEL",
            ])->delete();
        }
        /* End Tag Handling*/

        /* Handle Child Offers*/
        $childs = $request->input('childs', []);

        $offer->deleteChilds();

        foreach ($childs as $child){
            $monthly_rate = floatval($child['monthly_rate']);
            $web_monthly_rate = floatval($child['monthly_rate']);
            $deposit = intval($child['deposit']);
            $distance = intval($child['distance']);
            $duration = intval($child['duration']);
            $deleted = !empty($child['deleted']) && strtolower($child['deleted']) != 'null'  ? $child['deleted'] : NULL;
            $offer->addChildOffer($duration, $distance, $deposit, $monthly_rate, $web_monthly_rate, $deleted);
        }
        /* End Child Offers */
        Log::channel('quoter')->info("Offer $offer->id with code $offer->code was successfully updated.");

        return response()->json(
            [
                'msg' => "Offer with code $offer->code was successfully updated."
            ], JsonResponse::HTTP_OK
        );
    }


    /**
     * @param $carRequest
     * @return Car $car
     * @throws \Throwable
     */
    private function storeCarData($carRequest){
        $carRequest = (object)$carRequest;
        $carCodes = [
            'codice_motornet' => $carRequest->codice_motornet,
            'codice_eurotax' => $carRequest->codice_eurotax,
        ];
       try{
           $car = Car::where($carCodes)->firstOrFail();

       } catch (ModelNotFoundException $exception){
           $car = new Car();
           $car->saveOrFail($carCodes, false);
       }
        $car->update([
            'descrizione_serie_gamma' => $carRequest->descrizione_serie_gamma,
            'modello' => $carRequest->modello,
            'allestimento' => $carRequest->allestimento,
        ]);

       return $car;
    }

    /**
     * @param Car $car
     * @param $images
     * @return array
     * @throws \Throwable
     */
    private function storeCarImages(Car $car, $images){
        $exceptions = [];
        foreach ($images as $image){
            $image = (object)$image;
            $deleted_at = $image->deleted_at;

            if( empty($image->deleted_at) || strtolower($image->deleted_at) == 'null')
                $deleted_at = NULL;

            try{
                $im = Image::where('code', $image->code)->withTrashed()->firstOrFail();
                $im->update([
                        'path' => $image->path,
                        'image_alt' => $image->image_alt,
                        'type' => $image->type,
                        'deleted_at' => $deleted_at,
                    ]
                );
            } catch ( ModelNotFoundException $exception ){
                $img = new Image();
                $img->car_id = $car->id;
                $img->code = $image->code;
                $img->path = $image->path;
                $img->image_alt = $image->image_alt;
                $img->type = $image->type;
                $img->deleted_at = $deleted_at;
                $img->saveOrFail();
            } catch ( \Exception $exception){
                $exceptions[$image->code] = $exception->getMessage();
            }
        }

        return $exceptions;
    }

    /**
     * @param Car $car
     * @param $accessories
     * @return array
     * @throws \Throwable
     */
    private function storeAccessories(Car $car, $accessories){
        $exceptions = [];

        //Remove old accessories
        CarAccessory::deleteByCar($car->id);
        if(empty($accessories)) return $exceptions;

        foreach($accessories as $accessory){
            //cast array to object
            $accessory = (object)$accessory;
            //Handle Group
            try{
                $accessoryGroup = CarAccessoryGroup::findOrFail($accessory->group_id);
            } catch (ModelNotFoundException $exception){
                $accessoryGroup = new CarAccessoryGroup();
                $accessoryGroup->id = $accessory->group_id;
                $accessoryGroup->code = $accessory->group_code;
                $accessoryGroup->description = $accessory->group_description;
                $accessoryGroup->saveOrFail();
            } catch ( \Exception $exception){
                $exceptions["group_$accessory->group_id"] = $exception->getMessage();
            }
            //Handle Accessory Item
            try {
                $carAccessory = new CarAccessory();
                $carAccessory->type = $accessory->type;
                $carAccessory->car_accessory_groups_id = $accessoryGroup->id;
                $carAccessory->car_id = $car->id;
                $carAccessory->price = $accessory->price;
                $carAccessory->description = $accessory->description;
                $carAccessory->short_description = $accessory->short_description;
                $carAccessory->standard_description = $accessory->standard_description;
                $carAccessory->available = $accessory->available;
                $carAccessory->saveOrFail();
            } catch ( \Exception $exception){
                $exceptions[$accessory->description] = $exception->getMessage();
            }
        }
        return $exceptions;
    }
}
