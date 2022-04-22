@extends('layouts.main', [
         "breadcrumbs" => ["Elenco Generatori" => "price-indexers.index", "Modifica" => "price-indexers.edit"],
         "breadcrumbsParams"=>[ "Modifica" => $index->id ]
         ])


@section('app-content')
    <div class="element-wrapper" style="padding-bottom: unset!important;">
    <div class="element-wrapper">
        <div class="element-box">
            <div class="element-info">
                <div class="element-info-with-icon">
                    <div class="element-info-text">
                        <h5 class="element-inner-header">
                            Modifica generatore di prezzi
                        </h5>
                        <div class="form-desc" style="margin-bottom: unset!important; border-bottom: unset; padding-bottom:unset;">
                            I generatori di prezzi sono matrici grazie alla quale e possibile generare i prezzi delle auto
                        </div>
                    </div>
                </div>
            </div>
            {{Form::model($index, ['route' => ['price-indexers.update', $index->id], 'method' => 'patch', "id" => "price-indexers-update"]) }}
            <div class="row">
                <div class="col-md-6">
                    <label for="broker"> {{ __('Broker') }}</label>
                    {{ Form::select('broker', ['ALD'=>'ALD', 'Arval'=>'Arval', 'Lease Plan' => 'Lease Plan', 'Leasys' => 'Leasys'], $index->broker,
                             ['class' => $errors->has('broker') ? 'form-control is-invalid' : 'form-control', "id" => "Broker_id"]) }}
                     @if ($errors->has('broker'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('broker') }}</strong></div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="segment"> {{ __('Segmento') }}</label>
                    {{ Form::select('segment', $index->segmentClass, $index->segment,
                                ['class' => $errors->has('segment') ? 'form-control is-invalid' : 'form-control', "id" => "segment_id", 'disabled']) }}
                    @if ($errors->has('segment'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('segment') }}</strong></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @includeWhen(count($index->segmentClass) <= 2, 'priceIndexers.segments.small')
    @includeWhen(count($index->segmentClass) > 2, 'priceIndexers.segments.medium')


    <div class="row">
        <div class="col-md-12">
            {!! Form::submit('Aggiorna', ['class' => 'btn btn-primary pull-right']) !!}
            {!! Form::close() !!}

            <form method="post" action="{!! route('price-indexers.calculate', [$index->id]) !!}" style="display: inline;">
                {!! csrf_field() !!}
                {!! method_field('POST') !!}
                <button class="btn btn-warning float-right" type="submit"
                        onclick="return confirm('Sei sicuro di voler utilizzare questo generatore di prezzi? Tutti i prezzi presenti sul sito verrano modificati in base a questa matrice prezzi.')"
                        data-toggle="tooltip" title="Ricolcola prezzi offerte noleggio" style="margin-left: .7rem;margin-right: .7rem;"
                > Ricalcola Prezzi </button>
            </form>

            <form method="post" action="{!! route('price-indexers.destroy', [$index->id]) !!}" style="display: inline">
                {!! csrf_field() !!}
                {!! method_field('DELETE') !!}
                <button class="btn btn-danger float-right" type="submit"
                        onclick="return confirm('Sei sicuro di voler elimare questo generatore?')"
                        data-toggle="tooltip" title="Elimina generatore"
                > Delete </button>
            </form>

        </div>
    </div>

@endsection




