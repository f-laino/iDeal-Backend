@extends('layouts.main', [
        'title' => "Aggiungi Allestimenti Auto",
         "breadcrumbs" => ["Elenco Allestimenti Auto" => "car.index"],
         ])

@section('app-content')
    {{ Form::open(['route' => ['car.store'], 'method' => 'POST', "id" => "car-add"]) }}
    <div class="row">
        <div class="col-md-8">
            <div class="element-box">
                <h5 class="form-header">
                    {{ __('Informazioni') }}
                    <div class="form-desc"></div>
                </h5>
                <div class="element-box-content">
                    @include('partials.car.features')
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="element-box">
                <h5 class="form-header">
                    {{ __('Azioni') }}
                    <div class="form-desc"></div>
                </h5>
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::submit('Aggiungi',
                        [
                            'class' => 'btn btn-primary pull-right',
                            'style' => 'width:100%'
                         ]) !!}
                    </div>
                </div>
            </div>
        </div>


    </div>
    {!! Form::close() !!}


@stop
