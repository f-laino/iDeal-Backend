<?php

namespace App\Http\Controllers\Cms;

use App\Models\Agent;
use App\Models\Brand;
use App\Models\Car;
use App\Common\Models\Activity\Logger;
use App\Http\Controllers\CmsController;
use App\Http\Requests\Cms\OfferGenerateChildRequest;
use App\Http\Requests\Cms\OfferStoreRequest;
use App\Http\Requests\Cms\OfferUpdateRequest;
use App\Models\Image;
use App\Models\Offer;
use App\Models\OfferAttributes;
use App\Models\PriceIndex;
use App\Models\Promotion;
use App\Models\Service;
use App\Models\WebsiteBrand;
use App\Models\WebsiteOffer;
use App\Traits\DateUtils;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Facades\Search;
use Validator;

class OfferController extends CmsController
{
    use DateUtils;

    private $eurotaxService;

    public function __construct()
    {
        parent::__construct();
        $this->eurotaxService = app('eurotax');

    }

    public function index(Request $request)
    {
        $offers = Search::web($request, self::$pagination);
        $brands = Brand::pluck('name', 'id');
        return view('offer.index', compact('offers', 'brands'));
    }

    public function create()
    {
        $brands = Brand::pluck('name', 'id');
        $brands->prepend('Seleziona', '' );
        $segments = ['' => 'Seleziona'] + Car::$segments;
        $brokers = Offer::$BROKERS;
        $customCars =  Car::getCustomCars()->pluck('fullModel', 'id');
        $customCars->prepend('Seleziona', '' );

        // list delivery times
        $deliveryTimes = ['' => ''] + $this->getMonthsYearsFromToday();

        return view('offer.create', compact('brands', 'segments', 'brokers', 'customCars', 'deliveryTimes'));
    }

    public function store(OfferStoreRequest $request)
    {
        Logger::request('OfferController@Store', $request);

        $customCar = $request->get('custom_car', NULL);
        $suggested = $request->get('suggested', FALSE);

        $isCustomEnabled = $request->get('custom-car-enabled', NULL);
        $isCustomEnabled = boolval($isCustomEnabled);

        if(!empty($customCar) && $isCustomEnabled){
            $car = Car::findOrFail($customCar);
        } else {
            list($motornet, $eurotax) = explode( '-', $request->carversion);
            $car = new Car;
            $options = [
                'codice_motornet' => $motornet,
                'codice_eurotax' => $eurotax,
            ];
            $car = $car->saveOrFail($options);
        }

        $offer = new Offer;
        $offer->code = $request->code;
        $offer->car_id = $car->id;
        $offer->broker = $request->broker;
        $offer->deposit = $request->deposit;
        $offer->distance = $request->distance;
        $offer->duration = $request->duration;
        $offer->monthly_rate = $request->monthly_rate;
        $offer->web_monthly_rate = $request->monthly_rate;
        $offer->status = TRUE;
        $offer->suggested = $suggested;
        $offer->highlighted = FALSE;

        try{
            $offer->saveOrFail();

            $offer->attachDefaultServices();

            $offer->attachAllAgents();
            Logger::activity('OfferController@Store', $request, $offer);
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect(route('offer.edit', ['offer' => $offer->id]))->with('success', 'Offerta aggiunta con successo');

    }

    public function show($id)
    {
        return redirect()->route('offer.edit', ['offer'=>$id]);
    }

    public function edit(Offer $offer)
    {
        $car = $offer->car;
        $images = $car->images;
        $models = $car->models()->pluck('desc_gamma_mod', 'cod_gamma_mod');
        $versions = $car->pluckVersions();
        $duration = $offer->duration;
        $delivery_time = $offer->deliveryTime->description;
        $yearDistance = $offer->yearDistance;
        $totalDistance = $offer->totalDistance;
        $carColor = $offer->color;
        $brands = Brand::pluck('name', 'id');
        $carServices = Service::all();
        $carofferServices = $offer->services;
        $labels = ['' => ''] + OfferAttributes::$rentLabels;

        // list delivery times
        $deliveryTimes = ['' => ''] + $this->getMonthsYearsFromToday();

        $fastDelivery = !empty($offer->fastDelivery->value);

        //get offer promotions
        $promotions = $offer->promotions()->pluck('id');

        //get car segments
        $carSegment = $offer->car->segmento;
        $segments = ['' => 'Seleziona'] + Car::$segments;
        $brokers = Offer::$BROKERS;

        $childOffers = $offer->childOffers;

        $activePromotions = Promotion::getAsList();
        $countSuggested = Offer::countSuggested();
        $countSuggested = max(0, ($countSuggested - 1));

        $customCars = Car::getCustomCars()->pluck('fullModel', 'id');
        $customCars->prepend('Seleziona', '' );

        $availableColors = $car->getAvailableColors()->pluck('description', 'description');
        if(!empty($availableColors)){
            $availableColors->prepend('Nessuno', NULL );
        }


        return view('offer.edit', compact('offer', 'brands', 'segments', 'carServices', 'carofferServices', 'car', 'brokers', 'fastDelivery',
            'models','versions','images', 'labels', 'duration', 'deliveryTimes', 'delivery_time', 'yearDistance', 'totalDistance',
            'carSegment', 'childOffers', 'activePromotions', 'promotions','customCars', 'availableColors', 'carColor', 'countSuggested'));
    }



    public function update(OfferUpdateRequest $request, Offer $offer)
    {
        Logger::request('OfferController@Update', $request);

        $customCar = $request->get('custom_car', NULL);

        $promotions = $request->get('promotions', []);

        $isCustomEnabled = $request->get('custom-car-enabled', NULL);
        $isCustomEnabled = boolval($isCustomEnabled);

        if (!empty($customCar) && $isCustomEnabled) {
            $car = Car::findOrFail($customCar);
        } else {
            list($motornet, $eurotax) = explode('-', $request->carversion);
            $car = new Car;
            $options = [
                'codice_motornet' => $motornet,
                'codice_eurotax' => $eurotax,
            ];
            $car = $car->saveOrFail($options);
        }

        $carSegment = $offer->car->segmento;

        $carColor = optional(OfferAttributes::where([
            "offer_id" => $offer->id,
            "type" => "CAR_COLOR",
        ])->first())->value;

        $offerAttr = [
            $offer->deposit,
            $offer->monthly_rate,
            $offer->distance,
            $offer->duration,
            $offer->deliveryTime->value,
            $offer->fastDelivery->value,
            $offer->broker,
            $offer->car->id,
            $carSegment,
            $carColor,
        ];

        $requestAttr = [
            $request->deposit,
            $request->monthly_rate,
            $request->distance,
            $request->duration,
            $request->delivery_time,
            $request->fast_delivery,
            $request->broker,
            $car->id,
            $request->segment,
            $request->car_color !== '0' ? $request->car_color : NULL,
        ];

        $diff = array_merge(array_diff($offerAttr, $requestAttr), array_diff($requestAttr, $offerAttr));

        $newOffer = $offer;

        if (!empty($diff)) {
            try {
                /** @var Offer $newOffer */
                $newOffer = $offer->replicate();
                $newOffer->push();

                $attachedAgents = $offer->agents;
                $newOffer->attachAgents($attachedAgents);

                $attachedServices = $offer
                                        ->services()
                                        ->pluck('slug')
                                        ->toArray();
                $newOffer->attachServices($attachedServices);

                $childOffers = $offer->childOffers;
                $newOffer->attachChilds($newOffer->id, $childOffers);

                //Aggiorno i dati offerta
                $newOffer->update([
                    "deposit" => $request->deposit,
                    "monthly_rate" => $request->monthly_rate,
                    "web_monthly_rate" => $request->monthly_rate,
                    "distance" => $request->distance,
                    "duration" => $request->duration,
                ]);

                // Aggiorno i dati dell'allestimento
                $offer->car->update(['segmento' => $request->segment]);

                //utilizzo funzioni custom per aggiornare anche le variazioni
                $newOffer->updateBroker($request->broker);
                $newOffer->updateCarId($car->id);

                //handle colors
                if (!empty($request->car_color)) {
                    $offerColor = OfferAttributes::firstOrNew([
                        "offer_id" => $newOffer->id,
                        "type" => "CAR_COLOR",
                    ]);
                    $offerColor->offer_id = $newOffer->id;
                    $offerColor->type = "CAR_COLOR";
                    $offerColor->value = $request->car_color;
                    $offerColor->description = null;
                    $offerColor->saveOrFail();
                } else {
                    $offerColor = OfferAttributes::where([
                        "offer_id" => $newOffer->id,
                        "type" => "CAR_COLOR"
                    ])->delete();
                }

                $newOffer->updateDeliveryTime($request->delivery_time);
                $newOffer->updateFastDelivery($request->delivery_time ? $request->get('fast_delivery', false) : false);

                Logger::activity('OfferController@Update', $request, $newOffer, $offer);

                $offer->delete();
            } catch (\Exception $exception) {
                return back()->with('error', $exception->getMessage());
            }
        }


        if (!empty($request->get('left_label'))) {
            $leftLabel = OfferAttributes::firstOrNew([
                "offer_id" => $newOffer->id,
                "type" => "LEFT_LABEL",
            ]);
            $leftLabel->offer_id = $newOffer->id;
            $leftLabel->type = "LEFT_LABEL";
            $leftLabel->value =  $request->get('left_label');
            $leftLabel->description = OfferAttributes::$rentLabels[$request->get('left_label')];
            $leftLabel->saveOrFail();
        } else {
            $leftLabel = OfferAttributes::where([
                "offer_id" => $newOffer->id,
                "type" => "LEFT_LABEL"
            ])->delete();
        }

        if (!empty($request->get('right_label'))) {
            $rightLabel = OfferAttributes::firstOrNew([
                "offer_id" => $newOffer->id,
                "type" => "RIGHT_LABEL",
            ]);
            $rightLabel->offer_id = $newOffer->id;
            $rightLabel->type = "RIGHT_LABEL";
            $rightLabel->value =  $request->get('right_label');
            $rightLabel->description = OfferAttributes::$rentLabels[$request->get('right_label')];
            $rightLabel->saveOrFail();
        } else {
            $rightLabel = OfferAttributes::where([
                "offer_id" => $newOffer->id,
                "type" => "RIGHT_LABEL"
            ])->delete();
        }

        if (!empty($promotions)) {
            foreach ($promotions as $promotionId) {
                /** @var Promotion $promo */
                $promo = Promotion::find($promotionId);
                $promo->attachOffer($newOffer);
            }
        } else {
            $newOffer->detachFromAllPromotions();
        }

        return redirect()->route('offer.edit',['offer' => $newOffer->id])->with('success', 'Offerta aggiornata con successo.');
    }


    public function attachAgent($id){
        $agents = Agent::paginate(self::$pagination);
        try{
            $offer = Offer::findOrFail($id);
            $offer_agents = $offer->agents()->pluck('id')->toArray();
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return view('offer.agents', compact('agents', 'offer', 'offer_agents'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @deprecated
     */
    public function updateAgent(Request $request, $id){
        try{
            /** @var Offer $offer */
            $offer = Offer::findOrFail($id);
            /** @var Agent $agent */
            $agent = Agent::findOrFail($request->agent);

            if(boolval($request->exist) ){
               $status = $offer->detach($agent);
            } else{
                $status = $offer->attach($agent);
            }
        } catch (\Exception $exception){
            return response()->json(['status' => 404, 'message' => $exception->getMessage()]);
        }

        return response()->json(['status' => $status]);
    }


    public function destroy(Request $request, $id){
        Logger::request('OfferController@destroy', $request);
        //Detach all agents
        $offer = Offer::find($id);

        Logger::activity('OfferController@destroy', $request, $offer);

        $result = $offer->detachAgents();

        //Remove offers
        $result &= Offer::where('id', $id)->orWhere('parent_id', $id)->delete();

        if ($result)
            return redirect()->route('offer.index');
        return redirect()->route('offer.index');
    }

    public function service(Request $request){
        $status = null;
        $offer = Offer::find($request->caroffer);
        $service = Service::find($request->service);
        if( $offer->services->contains($service)){
            $offer->detachService($service->id);
            $status = Response::HTTP_ACCEPTED;
        } else {
            $offer->attachService($service->id);
            $status = Response::HTTP_OK;
        }
        return response()->json(['status' => $status]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request){
        Logger::request('OfferController@status', $request);

        /** @var Offer $carOffer */
        $offer = Offer::find($request->offer);

        $newStatus = !$offer->status;
        $status = $offer->changeStatus($newStatus) > FALSE;

        return response()->json(['status' => $status]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggested(Request $request){
        Logger::request('OfferController@suggested', $request);

        /** @var Offer $carOffer */
        $offer = Offer::find($request->offer);

        $suggested = !$offer->suggested;
        $status = $offer->changeSuggested($suggested) > FALSE;

        return response()->json(['status' => $status]);
    }




    public function getVersions(Request $request){
        $models = $this->eurotaxService->getVersioni($request->cod_gamma_mod, NULL, NULL, NULL);
        return response()->json($models);
    }

    public function getModels(Request $request){
        $brand = Brand::find($request->brand);
        $models = $this->eurotaxService->getModelli($brand->slug, NULL);
        return response()->json($models);
    }


    /**
     * @param OfferGenerateChildRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @deprecated
     */
    public function generateChildOffers(OfferGenerateChildRequest $request, $id){
        try{
            $offer = Offer::findOrFail($id);

            if( empty($offer->car->segmento) )
                return back()->with('error', 'Per poter generare le variazioni devi prima impostare il Segmento Auto');

            elseif (!in_array($offer->duration, PriceIndex::$durations) )
                return back()->with('error', 'Per poter generare le variazioni la durata dell\'offerta deve essere  24 Mesi o 36 Mesi');

            elseif ( intval( $offer->deposit ) < 1 )
                return back()->with('error', "Per poter generare le variazioni il valore dell\'anticipo deve essere superiore a Zero");

            elseif ( !in_array($offer->distance, PriceIndex::$yearDistances) )
                return back()->with('error', 'Per poter generare le variazioni il valore Km/Anno dell\'offerta deve essere 15.000 KM o 20.000 KM o 25.000 KM');


        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        try{

            $indexer = PriceIndex::where('broker', $offer->broker)
                ->where('segment', $offer->car->segmento)
                ->firstOrFail();

            $offer->generateChildOffers($indexer);

        } catch (ModelNotFoundException $exception){
            return back()->with('error', 'Generatore prezzi non trovato. Prima di generare le variazioni di quest\'offerta assicurarisi che esiste un generatore per il Broker: '
                . $offer->broker . ' Segmento: ' . $offer->car->segmento);
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return  redirect()->route('offer.edit',['offer' => $id])->with('success', 'Variazioni generate con successo.');
    }


    public function deleteChildOffers($id){
        try{
            $offer = Offer::findOrFail($id);
            $offer->childOffers()->delete();
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return  redirect()->route('offer.edit',['offer' => $id])->with('success', 'Variazioni eliminati con successo.');
    }


    public function import(Request $request){
        $brands = WebsiteBrand::pluck('name', 'id');
        $offers = WebsiteOffer::search($request, self::$pagination);
        return view('offer.import', compact('offers', 'brands'));
    }

    public function showFromImport($id){
       try{
           $offer = WebsiteOffer::findOrFail($id);
           if( Offer::where('code', $offer->code)->whereNull('parent_id')->exists() ) {
               return back()->with('error', "L'offerta che stai importando e' gia presente. Per forzare il processo di importazione, procedere prima all'eliminazione dell'offerta attuale.");
           }
           $brands = Brand::pluck('name', 'id');
           $brands->prepend('Seleziona', '' );
           $segments = ['' => 'Seleziona'] + Car::$segments;
           $car = $offer->car;
           $models = $car->models()->pluck('desc_gamma_mod', 'cod_gamma_mod');
           $versions = $car->pluckVersions();
           return view('offer.createFromImport', compact('offer', 'segments', 'brands', 'car', 'models', 'versions'));
        } catch (\Exception $exception){
           return back()->with('error', $exception->getMessage());
       }
    }

    public function updateChildOffer(Request $request){
        try{
            $childOffer = Offer::where("id", $request->childOffer)->whereNotNull('parent_id')->firstOrFail();
            $oldValue = $childOffer->monthly_rate;
            $status = $childOffer->update([
                "monthly_rate" => intval($request->newPrice)
            ]);
        } catch (\Exception $exception){
            $status = FALSE;
        }


        return response()->json(['status' => $status]);
    }

    public function addChildOffers(Request $request){
        $error = '';

        $validator = Validator::make($request->all(), [
            'first_distance' => 'required|numeric|min:5000|max:100000',
            'second_distance' => 'required|numeric|min:5000|max:100000',
            'third_distance' => 'required|numeric|min:5000|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => FALSE, 'error'=> "I valori delle distanze non sono validi assicurati di aver inserito valori compresi fra 5.000 e 100.000 Km"]);
        }

        try{
            $caroffer = Offer::findOrFail($request->offer);
            $rows = $request->get('data', []);
            $rows = json_decode($rows, true);

            $offerDuration = $caroffer->duration;
            $offerDeposit = $caroffer->deposit;
            $offerDistance = $caroffer->distance;
            $childOffers = [];
            $mainOfferPrice = 0;

            $offerDistances = [];
            $offerDistances[0] = intval($request->first_distance);
            $offerDistances[1] = intval($request->second_distance);
            $offerDistances[2] = intval($request->third_distance);

            //Valido ordine degli input
            if( $offerDistances[0] > $offerDistances[1] || $offerDistances[1] > $offerDistances[2] || $offerDistances[0] > $offerDistances[2]){
                return response()->json(['status' => FALSE, 'error'=> "I valori delle distanze devono essere inseriti in ordine crescente all'interno delle colonne. Esempio corretto: 5,6,7. Esempio errato 6,5,7"]);
            }

            //Controllo le distanze inserite
            if(!in_array($offerDistance, $offerDistances))
                return response()->json(['status' => FALSE, 'error'=> "Nessuno dei 3 chilometraggi inseriti &egrave; uguale all'offerta main! Per aggiungere la multiofferta &egrave; che uno dei 3 valori sia uguale ad: $offerDistance." ]);


            //validation variables
            $depositValues = [];
            $durationValues = [];


            $cntWithDeposit = 0;
            $cntWithoutDeposit = 0;

            foreach ($rows as $row){
                $deposit = (float)$row[0];
                if($deposit != 0 && $deposit < 1000)
                    return response()->json(['status' => FALSE, 'error'=> "Valore errato trovato! Deposito: $deposit. L'importo dell'anticipo puo essere 0 o superiore a 1000." ]);
                $depositValues[] = $deposit;

                $firstPrice = (float)$row[1];
                if($firstPrice < 100)
                    return response()->json(['status' => FALSE, 'error'=> "Valore errato trovato! Rata: $firstPrice. L'importo non puo essere inferiore a 100." ]);

                $secondPrice = (float)$row[2];
                if($secondPrice < 100)
                    return response()->json(['status' => FALSE, 'error'=> "Valore errato trovato! Rata: $secondPrice. L'importo non puo essere inferiore a 100." ]);

                $thirdPrice = (float)$row[3];
                if($thirdPrice < 100)
                    return response()->json(['status' => FALSE, 'error'=> "Valore errato trovato! Rata: $thirdPrice. L'importo non puo essere inferiore a 100." ]);

                $duration = (int)$row[4];

                if (!in_array($duration, Offer::$ALLOWED_DURATIONS))
                    return response()->json(['status' => FALSE, 'error'=> "Valore errato trovato! Durata: $duration Mesi. La durata del noleggio puo essere solo ". implode(",", Offer::$ALLOWED_DURATIONS)." mesi" ]);

                if(empty($deposit)){
                    $cntWithoutDeposit ++;
                } else{
                    $cntWithDeposit ++;
                }

                $durationValues[] = $duration;

                // add childs to childs array
                $this->pushOffer($offerDistances[0], $offerDuration, $offerDeposit, $offerDistance, $childOffers, $deposit, $firstPrice, $duration, $mainOfferPrice);
                $this->pushOffer($offerDistances[1], $offerDuration, $offerDeposit, $offerDistance, $childOffers, $deposit, $secondPrice, $duration,$mainOfferPrice);
                $this->pushOffer($offerDistances[2], $offerDuration, $offerDeposit, $offerDistance, $childOffers, $deposit, $thirdPrice, $duration, $mainOfferPrice);
            }


            $depositValues = array_unique($depositValues);
            if(count($depositValues) != 2)
                return response()->json(['status' => FALSE, 'error'=> "I dati inseriti devenono contenere 2 anticipi. Tu hai inserito: " . implode(', ', $depositValues) ]);

            if( !in_array(intval($offerDeposit), $depositValues) ){
                return response()->json(['status' => FALSE, 'error'=> "L'anticipo dell'offerta main e non e presente nei valori da te inseriti. Questo accade quanto l'aniticpo dell'offerta main non e presente nella tabella da te inserita." ]);
            }

            $durationValues = array_unique($durationValues);
            if(count($durationValues) != 2)
                return response()->json(['status' => FALSE, 'error'=> "I dati inseriti devenono contenere 2 durate. Tu hai inserito: " . implode(', ', $durationValues) ]);

            if( $cntWithoutDeposit != 2 || $cntWithDeposit != 2){
                return response()->json(['status' => FALSE, 'error'=> "Il numero delle occorenze degli anticipi non &grave; corretto. Hai inserito $cntWithDeposit prezzi con anticipo e $cntWithoutDeposit prezzi senza anticipo" ]);
            }
            if( $cntWithoutDeposit != 2){
                return response()->json(['status' => FALSE, 'error'=> "Il numero delle occorrenze delle durate non &grave; corretto." ]);
            }

            $status = $caroffer->addChildOffers($childOffers);

            if($mainOfferPrice > 0){
                $caroffer->update([
                    "monthly_rate" => $mainOfferPrice,
                ]);
            }

        } catch (\Exception $exception){
            $status = FALSE;
            $error = $exception->getMessage();
        }

        return response()->json(['status' => $status, 'error'=> $error]);
    }

    public function showChildOffers(Request $request, $id){
        $data = [];
        $error = '';
        $status = TRUE;
        try{
            $caroffer = Offer::findOrFail($id);
            $offerDuration = $caroffer->duration;
            $offerDeposit = $caroffer->deposit;
            $offerYearDistance = $caroffer->distance;

            if($caroffer->childOffers->count() < 1)
                return response()->json(['status' => $status, 'data'=> $data, 'error'=> $error]);
            //Prepare data for transformation
            $offerDistances = [];
            foreach ($caroffer->childOffers as $child){
                $childYearDistance = $child->distance;
                $offerDistances[] = $childYearDistance;
                $data[] = [
                    "deposit" => intval($child->deposit),
                    "distance" => intval($childYearDistance),
                    "monthly_rate" => intval($child->monthly_rate),
                    "duration" => intval($child->duration),
                ];
            }
            $data[] = [
                "deposit" => intval($offerDeposit),
                "distance"=> intval($offerYearDistance),
                "monthly_rate" => intval($caroffer->monthly_rate),
                "duration" => intval($offerDuration)
            ];

            //Transform data for display
            $offerDistances = array_unique($offerDistances);
            sort($offerDistances);
            $newData = [];
            foreach ($data as $item){
                $key = $item['deposit'] . '-'. $item['duration'];
                $index = array_search($item['distance'], $offerDistances) + 1;
                if(array_key_exists($key, $newData)){
                    $element = $newData[$key];
                    $element[$index] = $item['monthly_rate'];
                    $newData[$key] = $element;
                } else {
                    $element = [
                        0 => $item['deposit'],
                        1 => 0,
                        2 => 0,
                        3 => 0,
                        4 => $item['duration']
                    ];
                    $element[$index] = $item['monthly_rate'];
                    $newData[ $key ] = $element;
                }
            }
            //Remove key from associative array
            $transformData = [];
            foreach ($newData as $key=>$value){
                $transformData[] = (object)$value;
            }

            $data = $transformData;
        }catch (\Exception $exception){
            dd($exception->getMessage());
            $status = FALSE;
            $error = $exception->getMessage();
        }
        return response()->json(['status' => $status, 'data'=> $data, 'error'=> $error]);
    }


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
            ]);
            $child->update([
                "deposit" => $oldOffer->deposit,
                "monthly_rate" => $oldOffer->monthly_rate,
                "web_monthly_rate" => $oldOffer->monthly_rate,
            ]);



            $offer->duration->update([ "value" => $child->duration ]);
            $offer->yearDistance->update([ "value" => $child->distance ]);

            $child->duration->update([ "value" => $oldOffer->duration ]);
            $child->yearDistance->update([ "value" => $oldOffer->distance ]);

        } catch (\Exception $exception){
            $status = FALSE;
            $error = $exception->getMessage();
        }
        return response()->json(['status' => $status, 'error'=> $error]);
    }


    private function pushOffer($distance, $offerDuration, $offerDeposit, $offerDistance, &$childOffers, $deposit, $monthlyRate, $duration, &$mainOffer){
        if( $deposit != $offerDeposit || $distance != $offerDistance || $offerDuration != $duration){
            $childOffers[] = ["deposit"=>$deposit, "monthly_rate"=>$monthlyRate, "duration"=>$duration, "distance"=>$distance];
        } elseif ($deposit == $offerDeposit && $distance == $offerDistance && $offerDuration == $duration){
            $mainOffer = $monthlyRate;
        }

    }

    public function regenerateImages($id){
        try{
            $offer = Offer::findOrFail($id);
            $car = $offer->car;
            $regenerate = Image::regenerateAll($car);
        } catch (\Exception $exception){
            return back()->withErrors( ['custom_error', $exception->getMessage()] );
        }
        return  redirect()->route('offer.edit',['offer' => $id]);
    }

}
