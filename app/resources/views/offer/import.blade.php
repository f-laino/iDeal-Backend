@extends('layouts.main', [
        'title' => "Elenco Offerte Noleggio Sito CarPlanner",
        "total" => $offers->total(),
        "searchRoute" => "offer.import",
        "breadcrumbs" => ["Offerte Auto Noleggio" => "offer.index", "Elenco Offerte Noleggio presenti sul Sito CarPlanner" => "offer.import"]
         ])


@section('searchExtraFields')
    <div class="ssg-header">
        <div class="ssg-icon">
            <div class="os-icon os-icon-agenda-1"></div>
        </div>
        <div class="ssg-name">
            Marca
        </div>
    </div>
    <div class="ssg-content">
        <div class="ssg-items ssg-items-boxed">
            {{ Form::select('brands[]', $brands, null,
    [
        "class" => "form-control selectpicker",
        "id" => "search-form",
        "data-live-search" => "true",
         "data-dropup-auto"=> "true",
         "data-size"=> "5",
         "multiple" => "true"
    ]) }}
        </div>
    </div>


    <div class="ssg-header">
        <div class="ssg-icon">
            <div class="os-icon os-icon-ui-54"></div>
        </div>
        <div class="ssg-name">
            Modelli
        </div>
    </div>
    <div class="ssg-content">
        <div class="ssg-items ssg-items-boxed">
            {{ Form::text("model", null, ['class' => 'form-control', 'id' => 'model']) }}
        </div>
    </div>


    <div class="ssg-header">
        <div class="ssg-icon">
            <div class="os-icon os-icon-delivery-box-2"></div>
        </div>
        <div class="ssg-name">
            Broker
        </div>
    </div>
    <div class="ssg-content">
        <div class="ssg-items ssg-items-boxed">
            {{ Form::text("broker", null, ['class' => 'form-control', 'id' => 'broker']) }}
        </div>
    </div>



@endsection


@section('app-content')
    <div class="element-box-tp">
        <div class="table-responsive">
            <table class="table table-padded">
                <thead>
                <th>#</th>
                <th width="350px">Allestimento</th>
                <th>Importo</th>
                <th>Anticipo</th>
                <th>Durata</th>
                <th>Broker</th>
                <th width="150px">Ultimo Aggiornamento</th>
                <th class="text-right" width="100px">Azioni</th>
                </thead>
                <tbody>
                @foreach($offers as $offer)
                    <tr>
                        <td>{{$offer->id}}</td>
                        <td>
                            @if(!empty($offer->car))
                                <a href="https://www.carplanner.com/noleggio-a-lungo-termine/{{$offer->code}}" target="_blank" title="Visualizza Offerta">
                                {{$offer->car->brand->name}}
                                {{$offer->car->modello}}
                                {{$offer->car->allestimento}}
                                    <i class="fa fa-external-link"></i>
                                </a>
                            @else
                                Automobile non impostata
                            @endif
                        </td>
                        <td>{{$offer->monthly_rate}}&euro;</td>
                        <td>{{$offer->deposit}}&euro;</td>
                        <td>{{$offer->duration->value}} mesi</td>
                        <td>{{$offer->broker}}</td>
                        <td>{{$offer->lastUpdate}}</td>

                        <td class="text-right">
                            <a class="btn btn-outline-primary btn-sm float-right" onclick="return confirm('Sei sicuro di voler importare quest\' offerta dal sito web di CarPlanner?')"
                               href="{!! route('offer.import.create', ['route'=>$offer->id]) !!}" target="_blank"
                               data-toggle="tooltip" title="Importa offerta">
                                <i class="os-icon os-icon-grid-18"></i> Importa
                            </a>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $offers; !!}
@endsection

