@extends('layouts.main', [
        'title' => "Aggiuni Immagine Allestimento",
         "breadcrumbs" => ["Allestimento" => "car.edit", "Aggiuni Immagine" => "car.image.create"],
         "breadcrumbsParams"=>[ "Allestimento" => $car->id, "Aggiuni Immagine" => $car->id ]
         ])


@section('app-content')
    <div class="element-wrapper">
        <div class="element-box">
            <div class="element-info">
                <div class="element-info-with-icon">
                    <div class="element-info-text">
                        <h5 class="element-inner-header">
                            <img alt="{{ $car->brand->name }} Logo"
                                 src="{{ $car->brand->logo }}"
                                 style="height: 20px;">
                            <span>{{ $car->brand->name }} {{ $car->descrizione_serie_gamma }}</span>
                        </h5>
                        <div class="form-desc" style="margin-bottom: unset!important; border-bottom: unset; padding-bottom:unset;">
                            Codice Motornet: <code>{{ $car->codice_motornet }}</code>
                            Codice Eurotax: <code>{{ $car->codice_eurotax }}</code>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::open( ['route' => ['car.image.upload', $car->id ], 'method' => 'POST', "id" => "image-add", 'files'=>true ])  !!}
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="type" data-container="body"
                                   data-placement="right" data-toggle="popover" title="" type="button"
                                   data-html="true"
                                   data-original-title="Cosa è la Posizione?"
                                   data-content="La posizione dell'imagine definisce il suo ruolo all'interno del sito.<br>
                                        <strong>Main</strong>: l'immagine è presente nella card dell'elenco offerte.<br>
                                        <strong>Slider</strong>: l'immagine è presente nello slider della pagina dell'offerta.<br>
                                        <strong>Cover</strong>: l'immagine in evidenza nella pagina dell'offerta.<br>
                                        <strong>Promotions</strong>: l'immagine viene utilizzata nel materiale marketing.<br>
                                        <strong>Other</strong>: da definire."
                            >
                                {{ __('Posizione') }}
                                <i class="fa fa-question"></i>
                            </label>
                            {!!  Form::select('type', $positions, old('type'), ['class' => $errors->has('type') ? 'form-control is-invalid' : 'form-control', "id" => "type" ] ) !!}
                            @if ($errors->has('type'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('type') }}</strong></div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="image_alt"> {{ __('Descrizione') }}</label>
                            {!!  Form::text("image_alt", old('image_alt'), ['class' => $errors->has('image_alt') ? 'form-control is-invalid' : 'form-control', 'id' => 'image_alt', 'placeholder' => 'Descrizione']) !!}
                            @if ($errors->has('image_alt'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('image_alt') }}</strong></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <label for="image"> {{ __('Immagine') }}</label>
                        {!! Form::file('image', [ 'accept' => 'image/*' ]) !!}
                        @if ($errors->has('image'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('image') }}</strong></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    {!! Form::submit('Aggiungi', [
                    'class' => 'btn btn-primary pull-right',
                    ]) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection




