<div class="row">
    <div class="col-md-4 mt-2">
        <label for="brand">{{ __('Marca') }}</label>
        {{ Form::select('brand', $brands, !empty($car)? $car->brand_id : old('brand'),
            [   'class' => $errors->has('brand') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                "id" => "brand",
                "data-live-search" => "true",
                "data-dropup-auto"=> "true",
                "data-size"=> "5",
                "data-max-options" => "1",
                ]
         ) }}
        @if ($errors->has('brand'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('brand') }}</strong></div>
        @endif
    </div>


    <div class="col-md-4 mt-2">
        <label for="category">{{ __('Categoria') }} </label>
        {{ Form::select('category', $carCategories,!empty($car)? $car->category_id : old('category'),
            ['class' => $errors->has('category') ? 'form-control is-invalid' : 'form-control', "id" => "category"]) }}
        @if ($errors->has('category'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('category') }}</strong></div>
        @endif
    </div>


    <div class="col-md-4 mt-2">
        <label for="fuel">{{ __('Carburante') }} </label>
        {{ Form::select('fuel', $fuels, !empty($car)? $car->fuel_id : old('fuel'),
            ['class' => $errors->has('fuel') ? 'form-control is-invalid' : 'form-control', "id" => "fuel"]) }}
        @if ($errors->has('fuel'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('fuel') }}</strong></div>
        @endif
    </div>

    <div class="col-md-4 mt-2">
        <label for="segment"
               data-toggle="tooltip"
               title="I segmenti auto permettono di stabilire a quale categoria appartiene ogni singola vettura sulla scorta delle dimensioni o della tipologia della carrozzeria."
        >{{ __('Segmento Auto') }} </label>
        {{ Form::select('segmento', $segments,!empty($car)? $car->segmento : old('segmento'),
            ['class' => $errors->has('segmento') ? 'form-control is-invalid' : 'form-control', "id" => "segmento"]) }}
        @if ($errors->has('segmento'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('segmento') }}</strong></div>
        @endif
    </div>

    <div class="col-md-4 mt-2">
        <label for="modello">{{ __('Modello') }} </label>
        {{ Form::text("modello",!empty($car)? $car->descrizione_serie_gamma : old('modello'), ['class' => $errors->has('modello') ? 'form-control is-invalid' : 'form-control', 'id' => 'modello', 'placeholder' => 'Modello']) }}
        @if ($errors->has('modello'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('modello') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="allestimento">{{ __('Allestimento') }} </label>
        {{ Form::text("allestimento",!empty($car)? $car->allestimento : old('allestimento'), ['class' => $errors->has('allestimento') ? 'form-control is-invalid' : 'form-control', 'id' => 'allestimento', 'placeholder' => 'Allestimento']) }}
        @if ($errors->has('allestimento'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('allestimento') }}</strong></div>
        @endif
    </div>

    <div class="col-md-4 mt-2">
        <label for="cilindrata">{{ __('Cilindrata') }} </label>
        {{ Form::text("cilindrata",!empty($car)? $car->cilindrata : old('cilindrata'), ['class' => $errors->has('cilindrata') ? 'form-control is-invalid' : 'form-control', 'id' => 'cilindrata', 'placeholder' => '']) }}
        @if ($errors->has('cilindrata'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('cilindrata') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="cavalli_fiscali">{{ __('Cavalli') }} </label>
        {{ Form::text("cavalli_fiscali",!empty($car)? $car->cavalli_fiscali : old('cavalli_fiscali'), ['class' => $errors->has('cavalli_fiscali') ? 'form-control is-invalid' : 'form-control', 'id' => 'cavalli_fiscali', 'placeholder' => '']) }}
        @if ($errors->has('cavalli_fiscali'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('cavalli_fiscali') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="descrizione_trazione">{{ __('Trazione') }} </label>
        {{ Form::text("descrizione_trazione",!empty($car)? $car->descrizione_trazione : old('descrizione_trazione'), ['class' => $errors->has('descrizione_trazione') ? 'form-control is-invalid' : 'form-control', 'id' => 'descrizione_trazione', 'placeholder' => '']) }}
        @if ($errors->has('descrizione_trazione'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('descrizione_trazione') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="desc_motore">{{ __('Motore') }} </label>
        {{ Form::text("desc_motore",!empty($car)? $car->desc_motore : old('desc_motore'), ['class' => $errors->has('desc_motore') ? 'form-control is-invalid' : 'form-control', 'id' => 'desc_motore', 'placeholder' => '']) }}
        @if ($errors->has('desc_motore'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('desc_motore') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="hp">{{ __('HP') }} </label>
        {{ Form::text("hp",!empty($car)? $car->hp : old('hp'), ['class' => $errors->has('hp') ? 'form-control is-invalid' : 'form-control', 'id' => 'hp', 'placeholder' => '']) }}
        @if ($errors->has('hp'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('hp') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="consumo_medio">{{ __('Consumo Medio') }} </label>
        {{ Form::text("consumo_medio",!empty($car)? $car->consumo_medio : old('consumo_medio'), ['class' => $errors->has('consumo_medio') ? 'form-control is-invalid' : 'form-control', 'id' => 'consumo_medio', 'placeholder' => '']) }}
        @if ($errors->has('consumo_medio'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('consumo_medio') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="kw">{{ __('KW') }} </label>
        {{ Form::text("kw",!empty($car)? $car->kw : old('kw'), ['class' => $errors->has('kw') ? 'form-control is-invalid' : 'form-control', 'id' => 'kw', 'placeholder' => '']) }}
        @if ($errors->has('kw'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('kw') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="euro">{{ __('Euro') }} </label>
        {{ Form::text("euro",!empty($car)? $car->euro : old('euro'), ['class' => $errors->has('euro') ? 'form-control is-invalid' : 'form-control', 'id' => 'euro', 'placeholder' => '6']) }}
        @if ($errors->has('euro'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('euro') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="emissioni_co2">{{ __('Emissioni CO2') }} </label>
        {{ Form::text("emissioni_co2",!empty($car)? $car->emissioni_co2 : old('emissioni_co2'), ['class' => $errors->has('emissioni_co2') ? 'form-control is-invalid' : 'form-control', 'id' => 'emissioni_co2', 'placeholder' => '']) }}
        @if ($errors->has('emissioni_co2'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('emissioni_co2') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="descrizione_cambio">{{ __('Descrizione Cambio') }} </label>
        {{ Form::text("descrizione_cambio",!empty($car)? $car->descrizione_cambio : old('descrizione_cambio'), ['class' => $errors->has('descrizione_cambio') ? 'form-control is-invalid' : 'form-control', 'id' => 'descrizione_cambio', 'placeholder' => '']) }}
        @if ($errors->has('descrizione_cambio'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('descrizione_cambio') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="larghezza">{{ __('Larghezza') }} </label>
        {{ Form::text("larghezza",!empty($car)? $car->larghezza : old('larghezza'), ['class' => $errors->has('larghezza') ? 'form-control is-invalid' : 'form-control', 'id' => 'larghezza', 'placeholder' => '']) }}
        @if ($errors->has('larghezza'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('larghezza') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="lunghezza">{{ __('Lunghezza') }} </label>
        {{ Form::text("lunghezza",!empty($car)? $car->lunghezza : old('lunghezza'), ['class' => $errors->has('lunghezza') ? 'form-control is-invalid' : 'form-control', 'id' => 'lunghezza', 'placeholder' => '']) }}
        @if ($errors->has('lunghezza'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('lunghezza') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4 mt-2">
        <label for="bagagliaio">{{ __('Bagagliaio') }} </label>
        {{ Form::text("bagagliaio",!empty($car)? $car->bagagliaio : old('bagagliaio'), ['class' => $errors->has('bagagliaio') ? 'form-control is-invalid' : 'form-control', 'id' => 'bagagliaio', 'placeholder' => '']) }}
        @if ($errors->has('bagagliaio'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('bagagliaio') }}</strong></div>
        @endif
    </div>

    <div class="col-md-4 mt-2">
        <label for="posti">{{ __('Posti') }} </label>
        {{ Form::text("posti",!empty($car)? $car->posti : old('posti'), ['class' => $errors->has('posti') ? 'form-control is-invalid' : 'form-control', 'id' => 'posti', 'placeholder' => '5']) }}
        @if ($errors->has('posti'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('posti') }}</strong></div>
        @endif
    </div>

    <div class="col-md-4 mt-2">
        <label for="porte">{{ __('Porte') }} </label>
        {{ Form::text("porte",!empty($car)? $car->porte : old('porte'), ['class' => $errors->has('porte') ? 'form-control is-invalid' : 'form-control', 'id' => 'porte', 'placeholder' => '5']) }}
        @if ($errors->has('porte'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('porte') }}</strong></div>
        @endif
    </div>

    <div class="col-md-4 mt-2">
        <label for="kwh">{{ __('Capacità batteria kWh') }} </label>
        {{ Form::text("kwh",!empty($car)? $car->batteria_kwh : old('kwh'), ['class' => $errors->has('kwh') ? 'form-control is-invalid' : 'form-control', 'id' => 'kwh']) }}
        @if ($errors->has('kwh'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('kwh') }}</strong></div>
        @endif
    </div>

    <div class="col-md-4 mt-2">
        <label for="posti">{{ __('Neopatentati') }} </label>
        {{ Form::select('neo_patentati', [ false => "No", true => "Si" ],!empty($car)? $car->neo_patentati : old('neo_patentati'),
                    ['class' => $errors->has('neo_patentati') ? 'form-control is-invalid' : 'form-control', "id" => "neo_patentati"]) }}
        @if ($errors->has('neo_patentati'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('neo_patentati') }}</strong></div>
        @endif
    </div>

</div>

