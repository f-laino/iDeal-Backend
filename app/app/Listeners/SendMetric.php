<?php

namespace App\Listeners;

use App\Events\ProposalCreated;
use App\Events\QuotationCreated;
use App\Events\ProposalPrinted;
use App\Events\QuotationPrinted;
use App\Models\Quotation;
use App\Services\MetricsService;

class SendMetric
{
    protected $metricsService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     *
     * @todo TODO aggiungere (dinamicamente) nuovi servizi aggiuntivi
     */
    public function subscribe($events)
    {
        $events->listen(
            ProposalCreated::class,
            function ($event) {
                $this->metricsService->measureThis('tot_proposals');
                $this->metricsService->measureThis('proposals', 1, [
                    'group' => $event->proposal->agent->myGroup->name,
                    'groupType' => $event->proposal->agent->myGroup->type,
                    'deposit' => (int)$event->proposal->deposit,
                    'duration' => $event->proposal->duration,
                    'distance' => $event->proposal->distance,
                    'change_tires' => (bool)$event->proposal->change_tires, // sostituire
                    'car_replacement' => (bool)$event->proposal->car_replacement, // sostituire
                    'bollo_auto' => (bool)$event->proposal->bollo_auto,
                ]);

                $this->metricsService->measureThis('monthly_rate', $event->proposal->monthly_rate);

                $this->metricsService->measureThis('cars', 1, [
                    'cambio' => $event->proposal->offer->car->descrizione_cambio,
                    'categoria' => $event->proposal->offer->car->category->name,
                    'alimentazione' => $event->proposal->offer->car->alimentazione,
                    'marca' => $event->proposal->offer->car->brand->name,
                    'segmento' => $event->proposal->offer->car->segmento,
                ]);

                $this->metricsService->measureThis('customers', 1, [
                    'categoriaContrattuale' => $event->proposal->customer->category->description,
                    'cap' => $event->proposal->customer->zip_code,
                ]);
            }
        );

        $events->listen(
            QuotationCreated::class,
            function ($event) {
                $this->metricsService->measureThis('tot_quotations');
                $this->metricsService->measureThis('quotations', 1, [
                    'stage' => $event->quotation->stage,
                    'status' => $event->quotation->status ?? Quotation::$STATUS['OPEN'],
                    'qualified' => (bool)$event->quotation->qualified,
                ]);
            }
        );

        $events->listen(
            ProposalPrinted::class,
            function ($event) {
                $this->metricsService->measureThis('prints', 1, ['step' => 'proposal']);
            }
        );

        $events->listen(
            QuotationPrinted::class,
            function ($event) {
                $this->metricsService->measureThis('prints', 1, ['step' => 'quotation']);
            }
        );
    }
}
