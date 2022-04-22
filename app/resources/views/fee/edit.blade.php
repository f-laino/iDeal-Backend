@extends('layouts.main', [
         "breadcrumbs" => ["Elenco Commissioni" => "commission.index", "Modifica" => "commission.edit"],
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
                            Modifice elenco commissioni #{{ $index->broker }}
                        </h5>
                    </div>
                </div>
            </div>
            {{ Form::model($index, ['route' => ['commission.update', $index->id], 'method' => 'patch', "id" => "price-indexers-update"]) }}
            {!! Form::hidden('index_id', $index->id) !!}
            <div class="row">
                <div class="col-2">
                    <label for="broker"> {{ __('Broker') }}</label>
                    {!!  Form::text("broker", $index->broker, ['class' => $errors->has('broker') ? 'form-control is-invalid' : 'form-control', 'id' => 'broker', 'placeholder' => 'Nome Broker']) !!}
                    @if ($errors->has('broker'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('broker') }}</strong></div>
                    @endif
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="segment_a"> {{ __('Segmento A') }}</label>
                        <div class="input-group">
                            {!!  Form::text("segment_a", $pattern['segment_a'], ['class' => $errors->has('segment_a') ? 'form-control is-invalid' : 'form-control', 'id' => 'segment_a', 'placeholder' => '0']) !!}
                            <div class="input-group-append">
                                <div class="input-group-text">€</div>
                            </div>
                        </div>
                        @if ($errors->has('segment_a'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('segment_a') }}</strong></div>
                        @endif
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="segment_b"> {{ __('Segmento B') }}</label>
                        <div class="input-group">
                            {!!  Form::text("segment_b", $pattern['segment_b'], ['class' => $errors->has('segment_b') ? 'form-control is-invalid' : 'form-control', 'id' => 'segment_b', 'placeholder' => '0']) !!}
                            <div class="input-group-append">
                                <div class="input-group-text">€</div>
                            </div>
                        </div>
                        @if ($errors->has('segment_b'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('segment_b') }}</strong></div>
                        @endif
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="segment_c"> {{ __('Segmento C') }}</label>
                        <div class="input-group">
                            {!!  Form::text("segment_c", $pattern['segment_c'], ['class' => $errors->has('segment_c') ? 'form-control is-invalid' : 'form-control', 'id' => 'segment_c', 'placeholder' => '0']) !!}
                            <div class="input-group-append">
                                <div class="input-group-text">€</div>
                            </div>
                        </div>
                        @if ($errors->has('segment_c'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('segment_c') }}</strong></div>
                        @endif
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="segment_d"> {{ __('Segmento D') }}</label>
                        <div class="input-group">
                            {!!  Form::text("segment_d", $pattern['segment_d'], ['class' => $errors->has('segment_d') ? 'form-control is-invalid' : 'form-control', 'id' => 'segment_d', 'placeholder' => '0']) !!}
                            <div class="input-group-append">
                                <div class="input-group-text">€</div>
                            </div>
                        </div>
                        @if ($errors->has('segment_d'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('segment_d') }}</strong></div>
                        @endif
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="segment_e"> {{ __('Segmento E') }}</label>
                        <div class="input-group">
                            {!!  Form::text("segment_e", $pattern['segment_e'], ['class' => $errors->has('segment_e') ? 'form-control is-invalid' : 'form-control', 'id' => 'segment_e', 'placeholder' => '0']) !!}
                            <div class="input-group-append">
                                <div class="input-group-text">€</div>
                            </div>
                        </div>
                        @if ($errors->has('segment_e'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('segment_e') }}</strong></div>
                        @endif
                    </div>
                </div>


            </div>
            <div class="row">
                <div class="col-12">
                    {!! Form::submit('Aggiorna', ['class' => 'btn btn-primary float-right', 'style'=> 'margin-top: 30px']) !!}
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>


@endsection




