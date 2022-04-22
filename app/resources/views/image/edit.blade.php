@extends('layouts.main', [
        'title' => "Modifica Immagine Allestimento",
         "breadcrumbs" => ["Allestimento" => "car.edit", "Modifica Immagine" => "image.edit"],
         "breadcrumbsParams"=>[ "Allestimento" => $car->id, "Modifica Immagine" => $image->id ]
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
            {{ Form::model($image ,['route' => ['image.update', $image->id], 'method' => 'PATCH', "id" => "image-update"]) }}
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
                            {!!  Form::select('type', $positions, $image->type, [
                            'class' => $errors->has('type') ? 'form-control is-invalid' : 'form-control', "id" => "type" ] ) !!}
                            @if ($errors->has('type'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('type') }}</strong></div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="image_alt"> {{ __('Descrizione') }}</label>
                            {!!  Form::text("image_alt", $image->image_alt, ['class' => $errors->has('image_alt') ? 'form-control is-invalid' : 'form-control', 'id' => 'image_alt', 'placeholder' => 'Descrizione']) !!}
                            @if ($errors->has('image_alt'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('image_alt') }}</strong></div>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="code"> {{ __('Codice') }}</label>
                            {!!  Form::text("code", $image->code, ['class' => $errors->has('code') ? 'form-control is-invalid' : 'form-control', 'id' => 'code', 'placeholder' => 'Code', 'disabled' => true]) !!}
                            @if ($errors->has('code'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('code') }}</strong></div>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="path"> {{ __('Link CDN') }}</label>
                            {!!  Form::text("path", $image->path, ['class' => $errors->has('path') ? 'form-control is-invalid' : 'form-control', 'id' => 'path', 'placeholder' => 'Link', 'disabled' => true]) !!}
                            @if ($errors->has('path'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('path') }}</strong></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="{{$image->path}}"
                         class="img-fluid"
                         alt="{{$image->image_alt}}"
                         width="100%"
                         style="display:block;margin: 10px;border: 2px solid #dde2ec!important;"/>
                </div>
            </div>

            <div class="row" style="margin-top: 10px">
                <div class="col-md-12">
                    {!! Form::submit('Aggiorna', ['class' => 'btn btn-primary pull-right']) !!}
                    {!! Form::close() !!}
                    <form method="post" action="{!! route('image.destroy', [$image->id]) !!}" style="display: inline">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}
                        <button class="btn btn-danger  pull-right" type="submit" style="margin-right: 10px;"
                                onclick="return confirm('Una volta eliminata l\'immagine non verrà più importata. Sei sicuro di voler elimare questa immagine?')"
                                data-toggle="tooltip" title="Elimina Immagine"
                        >Delete
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection




