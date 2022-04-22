@extends('layouts.main', [
        'title' => "Aggiungi Offerta Noleggio",
         "breadcrumbs" => ["Offerte Auto Noleggio" => "offer.index", "Aggiungi" => "offer.create"],
         ])

@push('stylesheets')
    <style>
        legend{
            margin-bottom: .1rem;
        }

        fieldset {
            margin-top: .1rem!important;
        }
    </style>
@endpush
@section('app-content')

@include('partials.errors')
    {{ Form::open(['route' => ['offer.store'], 'method' => 'post', "id" => "offer-add"]) }}
    <div class="row">
        <div class="col-md-8">
            <div class="element-box">
                <h5 class="form-header">
                    {{ __('Dettagli') }}
                    <div class="form-desc"></div>
                </h5>
                <div class="element-box-content example-content">
                    <div class="row">
                        <div class="col-sm-7">
                            <label for="code">
                                {{ __('Codice offerta') }}
                            </label>
                            {!!  Form::text("code", old('code'), ['class' => $errors->has('code') ? 'form-control is-invalid' : 'form-control', 'id' => 'code', 'placeholder' => 'Codice']) !!}
                            @if ($errors->has('code'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('code') }}</strong></div>
                            @endif
                        </div>
                        <div class="col-sm-5">
                            <label for="Broker_id"> {{ __('Broker') }} </label>
                            {{ Form::select('broker', $brokers, old('broker'),
                                ['class' => $errors->has('broker') ? 'form-control is-invalid' : 'form-control', "id" => "Broker_id"]) }}
                            @if ($errors->has('broker'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('broker') }}</strong></div>
                            @endif
                        </div>

                    </div>

                    @include('partials.offer.car')

                </div>
            </div>

            <div class="element-box">

                <div class="os-tabs-w">
                    <div class="os-tabs-controls">
                        <ul class="nav nav-tabs bigger">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab" href="#features">
                                    {{ __('Caratteristiche') }}
                                </a>
                            </li>
                        </ul>

                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active show" id="features">
                            @include('partials.offer.features')
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
                <div class="form-group">
                    {{ Form::checkbox('status', NULL, true, ['id' => 'status', 'class'=> 'form-check-input']) }}
                    {{ Form::label('status', "Visibile sul sito") }}
                </div>

                <div class="row">
                    {!! Form::submit('Aggiungi', ['class' => 'btn btn-primary float-right']) !!}
                </div>

            </div>

        </div>
    </div>
    {!! Form::close() !!}


@endsection

