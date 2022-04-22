@extends('layouts.main', [
        'title' => "Modifica Offerta Noleggio",
         "breadcrumbs" => ["Offerte Auto Noleggio" => "offer.index", "Modifica" => "offer.edit"],
         "breadcrumbsParams"=>[ "Modifica" => $offer->id ]
         ])

@push('stylesheets')
    <style>
        legend {
            margin-bottom: .1rem;
        }

        fieldset {
            margin-top: .1rem !important;
        }
    </style>
@endpush

@section('app-content')
    {{ Form::model($offer, ['route' => ['offer.update', $offer->id], 'method' => 'patch', "id" => "offer-update", "data-offer"=>$offer->id]) }}
    <div class="row">
        <div class="col-md-8">
            <div class="element-box">
                <h5 class="form-header">
                    {{ __('Dettagli') }}
                    <div class="form-desc"></div>
                </h5>
                <div class="element-box-content example-content">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="code">
                                {{ __('Codice offerta') }}
                            </label>
                            {!!  Form::text("code", $offer->code, ['class' => $errors->has('code') ? 'form-control is-invalid' : 'form-control', 'id' => 'code', 'placeholder' => 'Codice', 'disabled' => 'true']) !!}
                            @if ($errors->has('code'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('code') }}</strong></div>
                            @endif
                        </div>
                        <div class="col-sm-3">
                            <label for="Broker_id"> {{ __('Broker') }} </label>
                            {{ Form::select('broker', $brokers, $offer->broker,
                                ['class' => $errors->has('broker') ? 'form-control is-invalid' : 'form-control', "id" => "Broker_id"]) }}
                            @if ($errors->has('broker'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('broker') }}</strong></div>
                            @endif
                        </div>
                        <div class="col-sm-3">
                            <label for="Segment_id"
                                   style="text-decoration: underline"
                                   data-toggle="tooltip"
                                   title="I segmenti auto permettono di stabilire a quale categoria appartiene ogni singola vettura sulla scorta delle dimensioni o della tipologia della carrozzeria."
                            >{{ __('Segmento Auto') }} </label>
                            {{ Form::select('segment', $segments,  !empty($car->segmento) ? $car->segmento : old('segment'),
                                ['class' => $errors->has('segment') ? 'form-control is-invalid' : 'form-control', "id" => "Segment_id"]) }}
                            @if ($errors->has('segment'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('segment') }}</strong></div>
                            @endif
                        </div>
                    </div>

                    @include('partials.offer.car')

                </div>
            </div>

            <div class="element-box">


                <div class="os-tabs-w">
                    <div class="os-tabs-controls">
                        <ul class="nav nav-tabs bigger" style="font-size: 1rem">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab" href="#features">
                                    {{ __('Caratteristiche') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#childs">
                                    {{ __('Variazioni') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#services">
                                    {{ __('Servizi') }}
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#images">
                                    {{ __('Immagini') }}
                                </a>
                            </li>
                        </ul>

                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active show" id="features">
                            @include('partials.offer.features')
                        </div>
                        <div class="tab-pane" id="childs">
                            @include('partials.offer.childs')
                        </div>
                        <div class="tab-pane" id="images">
                            @include('partials.offer.images')
                        </div>
                        <div class="tab-pane" id="services">
                            @include('partials.offer.services')
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="col-md-4">
            <div class="element-box">
                <h5 class="form-header">
                    {{ __('Attributi') }}
                    <div class="form-desc"></div>
                </h5>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                                <div class="os-toggler-w {{ $offer->status == TRUE ? "on" : "" }}"
                                     data-offer="{{$offer->id}}" onclick="return updateStatus(this);">
                                    <div class="os-toggler-i" style="background-color: #E1E6F2;">
                                        <div class="os-toggler-pill"></div>
                                    </div>
                                </div>
                                <h6 style="display: inline; margin:auto">{{ __('Visibile sul sito') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                                <div class="os-toggler-w {{ $offer->suggested == TRUE ? "on" : "" }}"
                                     data-offer="{{$offer->id}}" onclick="return updateSuggested(this);">
                                    <div class="os-toggler-i" style="background-color: #E1E6F2;">
                                        <div class="os-toggler-pill"></div>
                                    </div>
                                </div>
                                <h6 style="display: inline; margin:auto">{{ __('Offerta in Evidenza') }}</h6>
                            <p>Ci sono {{$countSuggested}} offerte oltre a questa in evidenza. <br/>👉
                                <a href="{{route('offer.index', ['suggested' => 'on'])}}" target="_blank">Visualizza tutte</a>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <legend>
                                <span>{{ __('Tipologia cliente') }}</span>
                            </legend>
                            {{ Form::select('left_label', $labels, !empty($offer->leftLabel)? $offer->leftLabel->value : null,
                                        [   'class' => $errors->has('left_label') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                                            "id" => "left_label",
                                            "data-live-search" => "true",
                                            "data-dropup-auto"=> "true",
                                            "data-size"=> "5",
                                            "data-max-options" => "1"
                                            ]
                                     ) }}
                            @if ($errors->has('left_label'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('left_label') }}</strong></div>
                            @endif
                        </fieldset>
                        <fieldset class="form-group">
                            <legend>
                                <span>{{ __('Promozioni') }}</span>
                            </legend>
                            {{ Form::select('promotions[]', $activePromotions, $promotions,
                                      [   'class' => $errors->has('promotions') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                                          "id" => "promotions",
                                          "data-live-search" => "true",
                                          "data-dropup-auto"=> "true",
                                          "data-size"=> "5",
                                          "multiple" => "true"
                                          ]
                                   ) }}
                            @if ($errors->has('promotions'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('promotions') }}</strong></div>
                            @endif
                        </fieldset>

                    </div>
                    <div class="col-md-12">
                        {!! Form::submit('Aggiorna', [
                        'class' => 'btn btn-primary',
                         "onclick" => "return confirm('Attenzione modificando questi dati modificherai anche lo storico dei preventivi associati a questa offerta. Se l'offerta è cambiata cancella questa e crea una nuova offerta ?')",
                        'style' => 'width:100%'
                        ]) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-12 mt-2">
                        <form method="post" action="{!! route('offer.destroy', [$offer->id]) !!}">
                            {!! csrf_field() !!}
                            {!! method_field('DELETE') !!}
                            <button class="btn btn-danger float-right" type="submit" style="width: 100%"
                                    onclick="return confirm('Sei sicuro di voler elimare questa offerta?')"
                                    data-toggle="tooltip" title="Elimina Offerta"
                            >
                                {{ __('Elimina Offerta') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-12 mt-2">
                        {!! Form::open(['route' => ['offer.regenerate', $offer->id], 'method' => 'post', "id" => "image-rigenerate"]) !!}
                        {!! Form::submit('Rigenera immagini', [
                       'class' => 'btn btn-warning',
                       "onclick" => "return confirm('Sei sicuro di voler rigenerare le immagini di quest\'allestimento?')",
                       "data-toggle" => "tooltip",
                       "title" => "Rigenera immagini allestimento",
                       "style" => "width: 100%",
                       ]) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-12 mt-2">
                        <a class="btn btn-success" style="width: 100%"
                           href="{!! route('offer.agents', [$offer->id]) !!}" data-toggle="tooltip"
                           title="Associa agenti all'Offerta Noleggio">
                            {{ __('Associa agenti') }}
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div>
@endsection


@push('scripts')
    <script type="text/javascript">
        function updateStatus(event) {
            var offer = $(event).data('offer');
            $.post("{{route('offer.status')}}", {offer})
                .done(function( data ) {
                    if (data.status === true && !$(event).hasClass('on'))
                        $(event).addClass("on");
                    else  $(event).removeClass("on");
                });
        }

        function updateSuggested(event) {
            var offer = $(event).data('offer');
            $.post("{{route('offer.suggested')}}", {offer})
                .done(function( data ) {
                    if (data.status === true && !$(event).hasClass('on'))
                        $(event).addClass("on");
                    else  $(event).removeClass("on");
                });
        }
    </script>
@endpush
