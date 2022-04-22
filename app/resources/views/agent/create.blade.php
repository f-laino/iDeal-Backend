@extends('layouts.main', [
        'title' => "Aggiungi Nuovo Account",
         "breadcrumbs" => ["Elenco Account" => "agent.index"],
         ])

@section('app-content')

{!! Form::open( ['route' => ['agent.store'], 'method' => 'POST', "id" => "agent-add", 'files'=>true])  !!}
<div class="row">
    <div class="col-md-8">
        <div class="element-box">
            <h5 class="form-header">
                {{ __('Informazioni')  }}
                <div class="form-desc"></div>
            </h5>
            <div class="element-box-content example-content">
                <div class="row">
                    <div class="col-md-6">
                        <label for="last_name"> {{ __('Ragione Sociale') }}</label>
                        {!!  Form::text("business_name", old('business_name'), ['class' => $errors->has('business_name') ? 'form-control is-invalid' : 'form-control', 'id' => 'business_name', 'placeholder' => 'Ragione Sociale']) !!}
                        @if ($errors->has('business_name'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('business_name') }}</strong></div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="first_name"> {{ __('Nome Completo') }}</label>
                        {!!  Form::text("name", old('name'), ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'id' => 'name', 'placeholder' => 'Nome Completo']) !!}
                        @if ($errors->has('name'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('name') }}</strong></div>
                        @endif
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="email"> {{ __('Email') }}</label>
                        {!!  Form::text("email", old('email'), ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control', 'id' => 'email', 'placeholder' => 'Email']) !!}
                        @if ($errors->has('email'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('email') }}</strong></div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="phone"> {{ __('Telefono') }}</label>
                        {!!  Form::text("phone", old('phone'), ['class' => $errors->has('phone') ? 'form-control is-invalid' : 'form-control', 'id' => 'phone', 'placeholder' => 'Telefono']) !!}
                        @if ($errors->has('phone'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('phone') }}</strong></div>
                        @endif
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fee_percentage"> {{ __('Commissioni') }}</label>
                            <div class="input-group">
                                {!!  Form::text("fee_percentage", old('fee_percentage'), ['class' => $errors->has('fee_percentage') ? 'form-control is-invalid' : 'form-control', 'id' => 'name', 'placeholder' => '100']) !!}
                                <div class="input-group-append">
                                    <div class="input-group-text">%</div>
                                </div>
                            </div>
                            @if ($errors->has('fee_percentage'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('fee_percentage') }}</strong></div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="group"> {{ __('Gruppo') }}</label>
                        {!!  Form::select('group',
                            $groups,
                            old('group'),
                            [
                            'class' => $errors->has('group') ? 'form-control selectpicker is-invalid' : 'form-control selectpicker',
                            "id" => "group",
                            "data-live-search" => "true",
                            "data-dropup-auto"=> "true",
                            "data-size"=> "5",
                            ] ) !!}

                        @if ($errors->has('group'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('group') }}</strong></div>
                        @endif
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-12">
                        <label for="contact_info"> {{ __('Informazioni di contatto') }}</label>
                        {!!  Form::textarea("contact_info", old('contact_info'),
                         ['class' => $errors->has('contact_info') ? 'form-control is-invalid' : 'form-control',
                         'id' => 'contact_info',
                         'rows' => 2,
                         'placeholder' => 'Inserisci informazioni di contatto.'])
                         !!}
                        @if ($errors->has('contact_info'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('contact_info') }}</strong></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="element-box">
            <div class="os-tabs-w">
                <div class="os-tabs-controls">
                    <ul class="nav nav-tabs bigger" style="font-size: 1rem">
                        <li class="nav-item">
                            <a class="nav-link nav-link active show" data-toggle="tab" href="#notes">
                                {{ __('Note') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active show" id="notes">
                        {!!  Form::textarea("notes",  old('notes'),
                          ['class' => $errors->has('notes') ? 'form-control is-invalid' : 'form-control',
                          'id' => 'notes',
                          'rows' => 3,
                          'style' => 'resize:none',
                          'placeholder' => 'Inserisci informazioni aggiuntive che potrebbero aiutare i tuoi colleghi a gestire più semplicemente le pratiche di questo agente.'])
                          !!}
                        @if ($errors->has('notes'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('notes') }}</strong></div>
                        @endif
                    </div>
                </div>
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
                        Caratteristiche:
                        <ul>
                            <li>Formato: <code>.png .jpg .jpeg</code></li>
                            <li>Dimensione massima: <code>5MB</code></li>
                            <li>Altezza: <code>170px</code> <small>o simili (non superiore a 300px)</small></li>
                            <li>Larghezza: <code>100px</code> <small>o simili (non superiore a 200px)</small></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
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

@endsection
