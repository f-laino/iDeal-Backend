<?php

namespace App\Models;

use Illuminate\Http\Request;

class WebsiteOffer extends Offer
{
    protected $connection = "mysql2";

    public static function selectImportable(){
      return self::orderBy('id', 'DESC')->whereNull('parent_id')->where('type', 'RENT');
    }

    public function car()
    {
        return $this->belongsTo('App\WebsiteCar');
    }

    public function duration(){
        return $this->hasOne('App\WebsiteOfferAttributes', 'offer_id')->where('type', 'DURATION');
    }

    public function yearDistance(){
        return $this->hasOne('App\WebsiteOfferAttributes', 'offer_id')->where('type', 'KM_ANNO');
    }

    public static function search(Request $request, int $pagination){
        $term = $request->input('q', NULL);
        $brand = $request->get('brands', []);
        $model = $request->get('model', NULL);
        $broker = $request->get('broker', NULL);

        $offerType = "RENT";
        $cars = WebsiteCar::orderBy('modello', 'asc');
        $brands = WebsiteBrand::orderBy('name', 'asc');

        if(!empty($term)) {
            $params = explode(" ", $term);
            foreach ($params as $param) {
                $cars->orWhere("modello", 'LIKE', "%$param%")
                    ->orWhere('allestimento', 'LIKE', "%$param%");
                $brands->orWhere("name", 'LIKE', "%$param%");
            }
        }
        $brands = $brands->pluck('id');

        if(empty($brand)){
            $brandIds = ($brands->count() > 0) ? $brands->toArray() : [];
        }
        else {
            $brandIds = $brand;
        }
        $cars->orWhereIn('brand_id', $brandIds);

        if(!empty($model))
            $cars->where('descrizione_modello', 'like', "%$model%" );

        $cars = $cars->pluck('id');

        $carIds = ($cars->count() > 0) ? $cars->toArray() : [];

        $query = WebsiteOffer::where("type", $offerType)
            ->whereIn('car_id', $carIds);

        if( !empty($broker) ){
            $query->where('broker', 'like', "%$broker%");
        }

        $query->whereNull('parent_id');

        return $query->paginate($pagination)->appends($request->all());
    }


}
