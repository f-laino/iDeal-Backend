<p>
    In questa sezione è possibile filtrare le offerte visualizzate dall'agente <code>{{$agent->getName()}}</code>
    in base al <code>Broker, Allimentazione</code> e <code>Categoria</code>.
</p>

{!! Form::open(['route' => ['agent.filters', $agent->id], 'method' => 'post', "id" => "agent-filters" ]) !!}
<div class="row">
    <div class="col-md-12 mt-2">
        <label for="broker_filter">{{ __('Broker visibili') }}</label>
        {!! Form::select('broker_filter[]', $brokersList, $activeBrokers,
            [   'class' => $errors->has('broker_filter') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                "id" => "broker_filter",
                "multiple" =>  "true",
                "data-live-search" => "true",
                "data-dropup-auto"=> "true",
                "data-size"=> "7",
                ]
         ) !!}
        @if ($errors->has('broker_filter'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('broker_filter') }}</strong></div>
        @endif
    </div>
    <div class="col-md-12 mt-2">
        <label for="fuel_filter">{{ __('Allimentazioni visibili') }}</label>
        {!! Form::select('fuel_filter[]', $fuelsList, $activeFuels,
            [   'class' => $errors->has('fuel_filter') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                "id" => "fuel_filter",
                "multiple" =>  "true",
                "data-live-search" => "true",
                "data-dropup-auto"=> "true",
                "data-size"=> "7",
                ]
         ) !!}
        @if ($errors->has('fuel_filter'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('fuel_filter') }}</strong></div>
        @endif
    </div>
    <div class="col-md-12 mt-2">
        <label for="category_filter">{{ __('Categorie visibili') }}</label>
        {!! Form::select('category_filter[]', $categoriesList, $activeCategories,
            [   'class' => $errors->has('broker_filter') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                "id" => "category_filter",
                "multiple" =>  "true",
                "data-live-search" => "true",
                "data-dropup-auto"=> "true",
                "data-size"=> "7",
                ]
         ) !!}
        @if ($errors->has('category_filter'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('category_filter') }}</strong></div>
        @endif
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-12">
        {!! Form::submit('Aggiorna filtri', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

{!! Form::close() !!}

