<?php

namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Models\Brand;
use App\Common\Models\GenericFilter;
use App\Facades\Search;
use App\Models\Fuel;
use App\Http\Controllers\ApiController;
use App\Models\Offer;
use App\Services\Offers\FiltersService;
use App\Traits\DateUtils;
use App\Transformer\BrandTransformer;
use App\Transformer\Catalog\OffersItemTransformer as CatalogOffersItemTransformer;
use App\Transformer\FuelTransformer;
use App\Transformer\GenericFilterTransformer;
use App\Transformer\OffersItemTransformer;
use App\Transformer\ProposalItemTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use League\Fractal\Manager;

class SearchController extends ApiController
{
    use DateUtils;

    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
    }

    /**
     * Get list of filters to use in search
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filters(Request $request, FiltersService $filtersServices)
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();

        $filters = $filtersServices->getOffersFiltersByRequest($request, $agent);

        return Response::json($filters);
    }

    /**
     * Search offers
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function offers(Request $request)
    {
        $agent = auth('api')->user();
        return $this->respondWithPaginatedCollection(Search::api($request, $agent), new OffersItemTransformer);
    }

    /**
     * Get list of filters to use in offers search
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function quotationsFilters()
    {
        $orders = [
            new GenericFilter('date', 'Piu recenti'),
            new GenericFilter('number', 'Numero'),
            new GenericFilter('monthly_rate', 'Rata mensile'),
        ];//dd($this->respondWithCollection($orders, new GenericFilterTransformer, true));
        return Response::json(
            [
                'orders' => $this->respondWithCollection($orders, new GenericFilterTransformer, true)->getData(),
            ]
        );
    }

    /**
     * Get list of filters to use in catalog search
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function catalogFilters()
    {
        $agent = auth('api')->user();
        $orders = [
            new GenericFilter('date', 'Data di pubblicazione'),
        ];
        $brands = Brand::active($agent);
        $fuels = Fuel::all();
        $deposits = [
            new GenericFilter(0, 'Zero'),
            new GenericFilter(1000, 'Fino a €1.000'),
            new GenericFilter(2000, 'Fino a €2.000'),
            new GenericFilter(3000, 'Fino a €3.000'),
            new GenericFilter(5000, 'Fino a €5.000'),
        ];
        $monthly_rates = [
            new GenericFilter(300, 'Fino a €{VALUE}'),
            new GenericFilter(500, 'Fino a €{VALUE}'),
            new GenericFilter(700, 'Fino a €{VALUE}'),
            new GenericFilter(1000, 'Fino a €1.000'),
        ];
        $distances = [
            new GenericFilter(10000, '10.000'),
            new GenericFilter(15000, '15.000'),
            new GenericFilter(20000, '20.000'),
            new GenericFilter(25000, '25.000'),
            new GenericFilter(30000, '30.000'),
            new GenericFilter(35000, '35.000'),
            new GenericFilter(40000, '40.000'),
            new GenericFilter(45000, '45.000'),
            new GenericFilter(50000, '50.000'),
        ];

        $brokers = [
            new GenericFilter($agent->myGroup->name, 'Esclusiva')
        ];
        foreach (Offer::$BROKERS as $key => $value) {
            $brokers[] =  new GenericFilter($key, $value);
        }
        return Response::json(
            [
                'orders' => $this->respondWithCollection($orders, new GenericFilterTransformer, true)->getData(),
                'filters' => [
                    'fuels' => $this->respondWithCollection($fuels, new FuelTransformer, true)->getData(),
                    'brokers' => $this->respondWithCollection($brokers, new GenericFilterTransformer, true)->getData(),
                    'brands' => $this->respondWithCollection($brands, new BrandTransformer, true)->getData(),
                    'deposits' => $this->respondWithCollection($deposits, new GenericFilterTransformer, true)->getData(),
                    'monthly_rates' => $this->respondWithCollection($monthly_rates, new GenericFilterTransformer, true)->getData(),
                    'distances' => $this->respondWithCollection($distances, new GenericFilterTransformer, true)->getData(),
                ],
            ]
        );
    }

    /**
     * Search quotations
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function quotations(Request $request)
    {
        $agent = auth('api')->user();
        return $this->respondWithPaginatedCollection(Search::quotationsApi($request, $agent), new ProposalItemTransformer);
    }

    /**
     * Search catalog offers
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function catalog(Request $request)
    {
        $agent = auth('api')->user();
        $offers = Search::catalogApi($request, $agent);
        return $this->respondWithCollection($offers, new CatalogOffersItemTransformer);
    }
}
