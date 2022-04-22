@extends('layouts.main', [
        'title' => "Elenco Allestimenti Auto",
        "total" => $cars->total(),
        "searchRoute" => "car.index",
         'addRoute' => 'car.create',
        "breadcrumbs" => ["Elenco Allestimenti Auto" => "car.index"]
         ])

@section('app-content')
    <div class="element-box-tp">
        <div class="table-responsive">
            <table class="table table-padded">
                <thead>
                <tr>
                    <th>
                        Automobile
                    </th>
                    <th>
                        Allestimento
                    </th>
                    <th>
                        Riferimenti
                    </th>
                    <th>
                        Azioni
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($cars as $car)
                    <tr>
                        <td class="cell-with-media">
                            <img alt="{{ $car->brand->name }} Logo"
                                 src="{{ $car->brand->logo }}"
                                 style="height: 20px;">
                            <span>{{ $car->brand->name }} {{ $car->descrizione_serie_gamma }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $car->allestimento }}</span>
                        </td>
                        <td class="nowrap">
                            <span>
                                Motornet: <code>{{ $car->codice_motornet }}</code>
                                Eurotax: <code>{{ $car->codice_eurotax }}</code>
                            </span>
                        </td>
                        <td class="text-right bolder nowrap">
                            <a class="btn btn-outline-secondary btn-sm"
                               href="{{ route("car.edit", [$car->id]) }}"
                               data-toggle="tooltip" title="Visualizza tutte le offerte associate">
                                <i class="os-icon os-icon-bar-chart-stats-up"></i> Offerte
                            </a>
                            <a class="btn btn-outline-info btn-sm"
                               href="{{ route("car.edit", [$car->id]) }}"
                               data-toggle="tooltip" title="Modifica i dati relativi al Brand">
                                <i class="os-icon os-icon-edit-32"></i> Modifica
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $cars; !!}
@endsection







