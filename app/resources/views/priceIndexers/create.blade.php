@extends('layouts.main', [
         "breadcrumbs" => ["Generatori " => "price-indexers.index", "Aggiungi" => "price-indexers.create"],
         ])

@section('app-content')
    <div class="element-wrapper">
        <div class="element-box">
            <div class="element-info">
                <div class="element-info-with-icon">
                    <div class="element-info-text">
                        <h5 class="element-inner-header">
                            Aggiungi un nuovo generatore di prezzi
                        </h5>
                        <div class="form-desc" style="margin-bottom: unset!important; border-bottom: unset; padding-bottom:unset;">
                          I generatori di prezzi sono matrici grazie alla quale e possibile generare i prezzi delle auto
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::open(['route' => ['price-indexers.store'], 'method' => 'post', "id" => "price-indexers-add"]) }}
            <div class="row">
                <div class="col-md-6">
                    <label for="broker"> {{ __('Broker') }}</label>
                    {{ Form::select('broker', ['ALD'=>'ALD', 'Arval'=>'Arval', 'Lease Plan' => 'Lease Plan', 'Leasys' => 'Leasys'], old('broker'),
                               ['class' => $errors->has('broker') ? 'form-control is-invalid' : 'form-control', "id" => "Broker_id"]) }}
                    @if ($errors->has('broker'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('broker') }}</strong></div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label for="segment"> {{ __('Segmento') }}</label>
                    {{ Form::select('segment', $segments, old('segment'), ['class' => $errors->has('segment') ? 'form-control is-invalid' : 'form-control', "id" => "segment"]) }}
                    @if ($errors->has('segment'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('segment') }}</strong></div>
                    @endif
                </div>
                <div class="col-md-2">
                    {!! Form::submit('Aggiungi', ['class' => 'btn btn-primary pull-right', 'style'=> 'margin-top: 30px']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>


@endsection



