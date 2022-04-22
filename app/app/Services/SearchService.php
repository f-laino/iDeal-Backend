<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\Customer;
use App\Models\Group;
use App\Interfaces\SearchServiceInterface;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarCategory;
use App\Models\Fuel;
use App\Models\Offer;
use App\Models\OfferAttributes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchService implements SearchServiceInterface
{
    private static $items = 20;

    /**
     * Prepara la ricerca per le offerte
     * @param Request $request
     * @return mixed
     */
    protected function prepareOffersQuery(Request $request, Agent $agent, bool $onlyParent = true)
    {
        $term = $request->input('query', NULL);
        $tags = $request->input('tags', []);

        $fuels = $request->input('fuels', []);
        $categories = $request->input('categories', []);
        $brand = $request->input('brands', []);
        $gear = $request->input('gear', NULL);
        $noviceDriver = array_search('noviceDriver', $tags);

        $deposit = $request->input('deposit', NULL);
        $monthlyRate = $request->input('monthly_rate', NULL);
        $distance = $request->input('distance', NULL);
        $duration = $request->input('duration', NULL);

        $kwh = $request->input('kwh', NULL);

        /* Pagination parms */
        $page = $request->input('page', 1);
        $items = $request->input('items', self::$items);

        //Handle childs offer search
        $onlyWithChilds = FALSE;

        $cars = Car::orderBy('modello', 'asc');
        $brands = Brand::orderBy('name', 'asc');

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
            $brand = Brand::whereIn('name', $brand)->pluck('id');
            $brandIds = ($brand->count() > 0) ? $brand->toArray() : [];
            $onlyParent = FALSE;
        }
        $cars->orWhereIn('brand_id', $brandIds);
        if (!empty($fuels)){
            $fuel = Fuel::whereIn('slug', $fuels)->pluck('id');
            $cars->whereIn('fuel_id', $fuel->toArray());
            $onlyParent = FALSE;
        }

        if (!empty($categories)){
            $categoria = CarCategory::whereIn('name', $categories)->pluck('id');
            $cars->whereIn('category_id', $categoria->toArray());
            $onlyParent = FALSE;
        }

        if(!empty($gear)){
            $cars->where('descrizione_cambio', 'like', "%$gear%");
        }

        if ($noviceDriver !== false) {
            $cars->where('neo_patentati', true);
            unset($tags[$noviceDriver]);
        }

        //Remove highlighted tag if exists
        if(!empty($tags) && ($tagKey = array_search( "multiofferta", $tags)) !== false){
            //Filter only offers with childs
            $onlyWithChilds = TRUE;
            //Remove value from tag search
            unset($tags[$tagKey]);
        }

        if (!empty($kwh)) {
            if ($kwh[0] === '>') {
                $cars->where('batteria_kwh', '>', trim($kwh, '>'));
            } else {
                list($rangeStart, $rangeEnd) = explode('-', $kwh);
                $cars->whereBetween('batteria_kwh', [$rangeStart, $rangeEnd]);
            }
        }

        $cars = $cars->pluck('id');
        $carIds = ($cars->count() > 0) ? $cars->toArray() : [];

        $agentOffers = $agent->offers()->pluck('id');
        $activeOffers = \DB::table('agent_offer')->where([['agent_id', $agent->id], [ 'status', TRUE]])->pluck('offer_id');
        $agentOffers = $agentOffers->intersect($activeOffers);

        $query = Offer::where('status', TRUE)->whereIn('car_id', $carIds);

        if (!is_null($deposit)) {
            $query->whereBetween('deposit', $deposit);
            $onlyParent = FALSE;
        }

        if (!is_null($monthlyRate)) {
            $query->whereBetween('monthly_rate', $monthlyRate);
            $onlyParent = FALSE;
        }

        if (!is_null($distance)) {
            $query->whereBetween('distance', $distance);
            $onlyParent = FALSE;
        }
        if (!is_null($duration)) {
            $query->whereBetween('duration', $duration);
            $onlyParent = FALSE;
        }

        if(!empty($tags)){
            $offersWithLeftTags = OfferAttributes::whereIn("type", OfferAttributes::$FILTERS)
                                                    ->whereIn('value', $tags )
                                                    ->pluck('offer_id');
            $query->whereIn('id', $offersWithLeftTags->toArray());
        }
        /* Ignore child offers */
        if ( $onlyParent ){
            $query->whereNull('parent_id')->whereIn('id', $agentOffers);
        } else {
            $agentChildOffers = Offer::whereIn('parent_id', $agentOffers)->pluck('id');
            $agentOffers = $agentOffers->merge($agentChildOffers);
            $query->whereIn('id', $agentOffers);
        }
        /* Get only offers that has child offers */
        if ( $onlyWithChilds ){
            $query->where('highlighted', TRUE);
        }

        $query->whereIn('id', $agentOffers);

        return $query;
    }

    /**
     * Gestisce la ricerca offerte sul sito web
     * @param Request $request
     * @return mixed
     */
    public function api(Request $request, Agent $agent)
    {
        $query = $this->prepareOffersQuery($request, $agent);

        /* Pagination parms */
        $page = $request->input('page', 1);
        $items = $request->input('items', self::$items);

        /* Order result by input */
        $sort = $request->input('sort', 'price-up');
        $order = $sort == 'price-down'? 'desc': 'asc';
        $query->orderBy('suggested', 'desc')
                ->orderBy('monthly_rate', $order);

        return $query->groupBy('code')->paginate($items, ['*'], 'page', $page);
    }

    /**
     * Gestisce la ricerca offerte senza paginazione né ordinamento
     * @param Request $request
     * @return mixed
     */
    public function offers(Request $request, Agent $agent)
    {
        $query = $this->prepareOffersQuery($request, $agent, false);

        return $query->groupBy('code');
    }

    /**
     * @param Request $request
     * @param $pagination
     * @return mixed
     */
    public function web(Request $request, $pagination)
    {
        $term = $request->input('q', NULL);

        $brand = $request->get('brands', []);
        $model = $request->get('model', NULL);
        $broker = $request->get('broker', NULL);
        $rata = $request->get('importo', NULL);
        $durata_noleggio = $request->get('durata', NULL);
        $suggested = $request->get('suggested', FALSE);

        $cars = Car::orderBy('modello', 'asc');

        $brands = Brand::orderBy('name', 'asc');
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

        $query = Offer::whereIn('car_id', $carIds);

        if(!empty($term)) {
            $query->where('code', 'like', "%$term%");
        }

        if( !empty($broker) ){
            $query->where('broker', 'like', "%$broker%");
        }

        if(!empty($rata)){
            $rata = floatval($rata);
            $query->where('monthly_rate', '=',$rata);
        }


        if( intval($durata_noleggio) > 0) {
            $query->where('duration', '=',$durata_noleggio);
        }

        if($suggested){
            $query->where('suggested', TRUE);
        }

        $query->where('is_custom', FALSE)->whereNull('parent_id');

        $query->orderBy('updated_at', 'desc');

        return $query->paginate($pagination)->appends($request->all());
    }

    /**
     * @param Request $request
     * @param Agent $agent
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function quotationsApi(Request $request, Agent $agent, $withPagination = true){
        $parameters = $request->get('parameters', []);
        $order = $request->get('order', NULL);

        /* Pagination params */
        $page = $request->input('page', 1);
        $items = $request->input('items', self::$items);

        $query = $agent->proposals();

        switch ($order){
            case 'number':
                $query->orderBy('id', 'DESC');
                break;
            case 'monthly_rate':
                $query->orderBy('monthly_rate', 'ASC');
                break;
            default:
                $query->orderBy('created_at', 'DESC');
                break;
        }

        if (empty($parameters)) {
            return $withPagination ? $query->paginate($items, ['*'], 'page', $page) : $query->get();
        }

        $queryString = !empty($parameters['query']) ? $parameters['query'] : '';
        $fiscalCode = !empty($parameters['fiscal_code']) ? $parameters['fiscal_code'] : null;
        $quotationId = !empty($parameters['quotation_id']) ? $parameters['quotation_id'] : null;
        $startDate = !empty($parameters['startDate']) ? $parameters['startDate'] : null;
        $endDate = !empty($parameters['endDate']) ? $parameters['endDate'] : null;

        $customers = Customer::orderBy('id', 'ASC');

        if (!empty($quotationId)) {
            $query->where('id', $quotationId);
        } else {
            if (!empty($queryString)) {
                $queryStringParams = explode(' ', $queryString);

                foreach ($queryStringParams as $queryStringParam) {
                    $customers->orWhere('first_name', 'LIKE', "$queryStringParam%")
                        ->orWhere('last_name', 'LIKE', "$queryStringParam%")
                        ->orWhere('email', 'LIKE', "$queryStringParam%")
                        ->orWhere('business_name', 'LIKE', "%$queryStringParam%");
                }
            }

            if (!empty($fiscalCode)) {
                $customers->orWhere('fiscal_code', 'LIKE', $fiscalCode)
                          ->orWhere('vat_number', 'LIKE', $fiscalCode);
            }

            if (!empty($startDate) && !empty($endDate)) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        $query = $query->whereIn('customer_id', $customers->pluck('id'));
        return $withPagination ? $query->paginate($items, ['*'], 'page', $page) : $query->get();

    }

    /**
     * @param Request $request
     * @param Agent $agent
     */
    public static function catalogApi(Request $request, Agent $agent){

        $sort = $request->input('sort', 'date');

        $fuels = $request->input('fuels', []);
        $brand = $request->input('brands', []);
        $broker = $request->input('broker', NULL);
        $deposit = $request->input('deposit', NULL);
        $monthlyRate = $request->input('monthly_rate', NULL);

        /* Pagination params */
        $page = $request->input('page', 1);
        $items = $request->input('items', self::$items * 20);

        $onlyParent = TRUE;
        $onlyWithChilds = FALSE;

        $cars = Car::orderBy('modello', 'asc');
        $brands = Brand::orderBy('name', 'asc');
        $brands = $brands->pluck('id');

        if(empty($brand)){
            $brandIds = ($brands->count() > 0) ? $brands->toArray() : [];
        }
        else {
            $brand = Brand::whereIn('name', $brand)->pluck('id');
            $brandIds = ($brand->count() > 0) ? $brand->toArray() : [];
        }
        $cars->orWhereIn('brand_id', $brandIds);

        if (!empty($fuels)){
            $fuel = Fuel::whereIn('slug', $fuels)->pluck('id');
            $cars->whereIn('fuel_id', $fuel->toArray());
        }

        if (!empty($categories)){
            $categoria = CarCategory::whereIn('name', $categories)->pluck('id');
            $cars->whereIn('category_id', $categoria->toArray());
        }

        $cars = $cars->pluck('id');
        $carIds = ($cars->count() > 0) ? $cars->toArray() : [];

        $agentOffers = $agent->offers()->pluck('id');
        $query = Offer::where('offers.status', TRUE)->whereIn('car_id', $carIds)
                    ->where(function ($subQuery) use ($agent) {
                   //     $subQuery->where('owner_id', $agent->id)
                     //       ->orWhereNull('owner_id');
                        $subQuery->where('owner_id', $agent->id);
                    });

        if( !is_null($deposit) ){
            $query->where('deposit', '<=', intval($deposit));
        }

        if(!is_null($monthlyRate)){
            $monthlyRate = intval($monthlyRate);
            $query->where(function ($q) use ($monthlyRate){
                $q->where('monthly_rate', '<=',$monthlyRate);
            });
        }
        if(!empty($broker)){
            $query->where('broker', $broker);
        }

        $query->whereNull('parent_id')->whereIn('id', $agentOffers);
        $query->groupBy('code');
        $query->join("agent_offer", function($join) use ($agent)
        {
            $join->on("agent_offer.offer_id", '=' ,'offers.id');

        });

        /* Order result by input */
        $query->orderBy('highlighted', 'desc');

        if( $sort == 'price-down' )
            $query->orderBy('monthly_rate', 'desc');
        elseif ( $sort == 'price-down' )
            $query->orderBy('monthly_rate', 'asc');
        else
            $query->orderBy('offers.created_at', 'desc');

        return $query->paginate($items, ['*', 'agent_offer.status as agent_offer_status', 'agent_offer.agent_id as agent_id'], 'page', $page);
    }

    /**
     * Ricerca gli agenti
     * @param Request $request
     * @param $pagination
     * @return mixed
     */
    public function webAgents(Request $request, $pagination){
        $term = $request->input('q', NULL);

        $query = Agent::where('name', 'like', "%$term%")
                ->orWhere('business_name', 'like', "%$term%")
                ->orWhere('email', 'like', "%$term%")
                ->orWhere('phone', 'like', "%$term%");
        $query->orderBy('id', 'DESC');

        return $query->paginate($pagination)->appends($request->all());
    }

    /**
     * Ricerca groupi agenti
     * @param Request $request
     * @param $pagination
     * @return mixed
     */
    public function webGroups(Request $request, $pagination){
        $term = $request->input('q', NULL);

        $query = Group::where('name', 'like', "%$term%")
            ->orWhere('fee_percentage', 'like', "%$term%");

        $query->orderBy('id', 'DESC');

        return $query->paginate($pagination)->appends($request->all());
    }

}
