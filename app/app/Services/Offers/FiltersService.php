<?php

namespace App\Services\Offers;

use App\Models\Agent;
use App\Models\Brand;
use App\Models\Fuel;
use App\Models\CarCategory;
use App\Models\OfferAttributes;
use App\Facades\Search;
use App\Traits\DateUtils;
use App\Common\Models\RangeFilter;
use App\Common\Models\GenericFilter;
use App\Transformer\BrandTransformer;
use App\Transformer\CarsCategoryTransformer;
use App\Transformer\FilterTransformer;
use App\Transformer\FuelTransformer;
use App\Transformer\GenericFilterTransformer;
use App\Abstracts\Responder;
use Illuminate\Http\Request;
use League\Fractal\Manager;

class FiltersService extends Responder
{
    use DateUtils;

    /** @var Agent $agent */
    protected $agent;

    /** @var Request $request */
    protected $request;

    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
    }

    public function getOffersFiltersByRequest(Request $request, Agent $agent): array
    {
        $this->setRequest($request);

        $agentOffers = $agent->offers()
            ->where('offers.status', true);

        $offers = Search::offers($request, $agent);

        $orders = $this->getOrders();

        $tags = OfferAttributes::activeLabels();
        $brands = Brand::active($agent);
        $fuels = Fuel::all();
        $categories = CarCategory::all();

        $monthly_rates = $this->getMonthlyRatesFilterFromOffers($agentOffers, $offers);

        $deposits = $this->getDepositsFilterFromOffers($agentOffers, $offers);

        $durations = $this->getDurationsFilterFromOffers($agentOffers, $offers);

        $distances = $this->getDistancesFilterFromOffers($agentOffers, $offers);

        $delivery_times = $this->getDeliveryTimesFilter();

        $gear = $this->getGearsFilter();

        $noviceDriver = $this->getNoviceDriverFilter();

        $kwh_range = $this->getKwhFilter();

        return [
            'tags' => $this->respondWithCollection($tags, new FilterTransformer),
            'orders' => $this->respondWithCollection($orders, new GenericFilterTransformer),
            'filters' => [
                'noviceDriver' => $this->respondWithCollection($noviceDriver, new GenericFilterTransformer),
                'fuels' => $this->respondWithCollection($fuels, new FuelTransformer),
                'categories' => $this->respondWithCollection($categories, new CarsCategoryTransformer),
                'brands' => $this->respondWithCollection($brands, new BrandTransformer),
                'deposits' => $deposits->getData(),
                'monthly_rates' => $monthly_rates->getData(),
                'distances' => $distances,
                'durations' => $durations,
                'delivery_times' => $this->respondWithCollection($delivery_times, new GenericFilterTransformer),
                'gear' => $this->respondWithCollection($gear, new GenericFilterTransformer),
                'kwh_range' => $this->respondWithCollection($kwh_range, new GenericFilterTransformer),
            ],
        ];
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    private function getMonthlyRatesFilterFromOffers($offers, $filteredOffers): RangeFilter
    {
        $offersRepo = $this->request->has('monthly_rate') ? $offers : $filteredOffers->get();

        $min = $this->getMinValueFromOffersByKey($offersRepo, 'monthly_rate');
        $max = $this->getMaxValueFromOffersByKey($offersRepo, 'monthly_rate');

        return new RangeFilter($min, $max, 1, '€');
    }

    private function getDepositsFilterFromOffers($offers, $filteredOffers): RangeFilter
    {
        $offersRepo = $this->request->has('deposit') ? $offers : $filteredOffers->get();

        $min = $this->getMinValueFromOffersByKey($offersRepo, 'deposit');
        $max = $this->getMaxValueFromOffersByKey($offersRepo, 'deposit');

        return new RangeFilter($min, $max, 1000, '€');
    }

    private function getDurationsFilterFromOffers($offers, $filteredOffers): array
    {
        $offersRepo = $this->request->has('duration') ? $offers : $filteredOffers;

        $durations_items = $offersRepo->distinct(['duration'])
            ->pluck('duration')
            ->toArray();

        sort($durations_items);

        $durations = [];

        foreach ($durations_items as $durations_item) {
            $durations[$durations_item] = ['label' => (int)$durations_item . ' mesi'];
        }

        return $durations;
    }

    private function getDistancesFilterFromOffers($offers, $filteredOffers): array
    {
        $offersRepo = $this->request->has('distance') ? $offers : $filteredOffers;

        $distances_items = $offersRepo->distinct(['distance'])
            ->pluck('distance')
            ->toArray();

        sort($distances_items);

        $distances = [];

        foreach ($distances_items as $distances_item) {
            $distances[$distances_item] = ['label' => $distances_item . ' Km'];
        }

        return $distances;
    }

    private function getDeliveryTimesFilter(): array
    {
        $delivery_times_items = $this->getMonthsYearsFromToday(true);
        $delivery_times = [];

        foreach ($delivery_times_items as $delivery_times_item) {
            $delivery_times[] = new GenericFilter($delivery_times_item, $delivery_times_item);
        }

        return $delivery_times;
    }

    public function getOrders(): array
    {
        return [
            new GenericFilter('price-up', 'Rata crescente'),
            new GenericFilter('price-down', 'Rata decrescente'),
        ];
    }

    public function getGearsFilter(): array
    {
        return [
            new GenericFilter('Automatico', 'Automatico'),
            new GenericFilter('Manuale', 'Manuale')
        ];
    }

    public function getNoviceDriverFilter(): array
    {
        return [
            new GenericFilter('noviceDriver', 'Neopatentato'),
        ];
    }

    public function getKwhFilter(): array
    {
        return [
            new GenericFilter('0-30', 'da 0 a 30 kWh'),
            new GenericFilter('31-40', 'da 31 a 40 kWh'),
            new GenericFilter('41-70', 'da 41 a 70 kWh'),
            new GenericFilter('>70', 'da 71 kWh in su'),
        ];
    }

    private function getMinValueFromOffersByKey($offers, $key): int
    {
        $value = $offers->min($key);
        return (int)$value;
    }

    private function getMaxValueFromOffersByKey($offers, $key): int
    {
        $value = $offers->max($key);
        return (int)$value;
    }

    private function getRoundedMaxValueFromOffersByKey($offers, $key, $roundNum): int
    {
        $value = $offers->max($key);
        $value = round($value, $roundNum);
        return (int)$value;
    }
}
