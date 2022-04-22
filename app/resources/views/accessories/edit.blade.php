@extends('layouts.main', [
         "breadcrumbs" => [
            "Elenco Allestimenti Auto" => "car.index",
            "Modifica Allestimento" => "car.edit",
            "Modifica Accessorio" => "cars.accessories.edit"
            ],
            "breadcrumbsParams" => [
                "Modifica Allestimento" => $car->id,
                "Modifica Accessorio" => $accessory->id,
            ]
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
                        <div class="form-desc"
                             style="margin-bottom: unset!important; border-bottom: unset; padding-bottom:unset;">
                            Codice Motornet: <code>{{ $car->codice_motornet }}</code>
                            Codice Eurotax: <code>{{ $car->codice_eurotax }}</code>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::model($accessory ,['route' => ['cars.accessories.update', $accessory->id], 'method' => 'PATCH', "id" => "car-accessories-update"]) }}
            <div class="row">
                <div class="col-4">
                    <label for="type"
                           style="text-decoration: underline"
                           data-toggle="tooltip" title="{{__('Indica la dipologia di accessorio')}}"
                    >{{ __('Tipologia') }} </label>
                    {!! Form::select('type', $allowedTypes, $accessory->type,
                        ['class' => $errors->has('type') ? 'form-control is-invalid' : 'form-control', "id" => "type"]) !!}
                    @if ($errors->has('type'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('type') }}</strong></div>
                    @endif
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="price"> {{ __('Prezzo') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">&euro;</div>
                            </div>
                            {!!  Form::text("price", $accessory->price, ['class' => $errors->has('price') ? 'form-control is-invalid' : 'form-control',
                            'id' => 'price',
                            'placeholder' => '0.00']) !!}
                            @if ($errors->has('price'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('price') }}</strong></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <label for="available"
                           style="text-decoration: underline"
                           data-toggle="tooltip" title="{{__('Indica se un accessorio è abbinabilie a questa vettura')}}"
                    >{{ __('Disponibilità') }} </label>
                    {!! Form::select('available', [1 => "Disponibile", 0 => "Non disponibile"], $accessory->available,
                        ['class' => $errors->has('available') ? 'form-control is-invalid' : 'form-control', "id" => "available"]) !!}
                    @if ($errors->has('available'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('available') }}</strong></div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="description"> {{ __('Descrizione') }}</label>
                    {!!  Form::text("description", $accessory->description, ['class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control',
                    'id' => 'description',
                    'placeholder' => 'Descrizione']) !!}
                    @if ($errors->has('description'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('description') }}</strong></div>
                    @endif
                </div>
                <div class="col-6">
                    <label for="standard_description"> {{ __('Descrizione Standard') }}</label>
                    {!!  Form::text("standard_description", $accessory->standard_description, ['class' => $errors->has('standard_description') ? 'form-control is-invalid' : 'form-control',
                    'id' => 'standard_description',
                    'placeholder' => 'Descrizione Standard']) !!}
                    @if ($errors->has('standard_description'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('standard_description') }}</strong></div>
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <label for="short_description"> {{ __('Descrizione Abbreviata') }}</label>
                    {!!  Form::text("short_description", $accessory->short_description, ['class' => $errors->has('short_description') ? 'form-control is-invalid' : 'form-control',
                    'id' => 'short_description',
                    'placeholder' => 'Descrizione Abbreviata']) !!}
                    @if ($errors->has('short_description'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('short_description') }}</strong></div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    {!! Form::submit('Aggiorna', ['class' => 'btn btn-primary pull-right', 'style'=> 'margin-top: 30px']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
