@extends('layouts.main', [
        'title' => "Aggiungi Nuovo Gruppo Utenti",
         "breadcrumbs" => ["Elenco Gruppo Utenti" => "group.index"],
         ])

@section('app-content')
    {!! Form::open( ['route' => ['group.store'], 'method' => 'POST', "id" => "group-add", 'files'=>true ])  !!}
    <div class="row">
        <div class="col-md-8">
            <div class="element-box">
                <h5 class="form-header">
                    {{ __('Informazioni') }}
                    <div class="form-desc"></div>
                </h5>
                <div class="element-box-content example-content">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name"> {{ __('Nome') }}</label>
                            {!!  Form::text("name", old('name'), ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'id' => 'name', 'placeholder' => 'Nome']) !!}
                            @if ($errors->has('name'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('name') }}</strong></div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="type">{{ __('Tipologia') }}</label>
                            {!!
                            Form::select('type', $types, old('tye'),[
                                 'class' => $errors->has('type') ? 'form-control is-invalid' : 'form-control',
                            ])
                         !!}
                            @if ($errors->has('type'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('type') }}</strong></div>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fee_percentage"> {{ __('Commissioni') }}</label>
                                <div class="input-group">
                                    {!!  Form::text("fee_percentage", old('fee_percentage'), ['class' => $errors->has('fee_percentage') ? 'form-control is-invalid' : 'form-control', 'id' => 'name', 'placeholder' => '100']) !!}
                                    @if ($errors->has('fee_percentage'))
                                        <div class="help-block form-text with-errors form-control-feedback">
                                            <strong>{{ $errors->first('fee_percentage') }}</strong></div>
                                    @endif
                                    <div class="input-group-append">
                                        <div class="input-group-text">%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="notification_email"> {{ __('Email di notifica') }}</label>
                            {!!  Form::text("notification_email", old('notification_email'), ['class' => $errors->has('notification_email') ? 'form-control is-invalid' : 'form-control', 'id' => 'email', 'placeholder' => 'Email']) !!}
                            @if ($errors->has('notification_email'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('notification_email') }}</strong></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="element-box">
                <h5 class="form-header">
                    {{ __('Logo') }}
                    <div class="form-desc"></div>
                </h5>
                <div class="row">
                    <div class="col-md-12">
                        Caratteristiche:
                        <ul>
                            <li>Formato: <code>.png .jpg .jpeg</code></li>
                            <li>Dimensione massima: <code>5MB</code></li>
                            <li>Altezza: <code>170px</code> <small>o simili (non superiore a 300px)</small></li>
                            <li>Larghezza: <code>100px</code> <small>o simili (non superiore a 200px)</small></li>
                        </ul>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="input-group">
                            {!! Form::file('logo', [ 'accept' => 'image/*' ]) !!}
                            @if ($errors->has('logo'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('logo') }}</strong></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        {!! Form::submit('Aggiungi', [
                        'class' => 'btn btn-primary pull-right',
                         'style' => 'width:100%',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>

    </div>
    {!! Form::close() !!}
@endsection
