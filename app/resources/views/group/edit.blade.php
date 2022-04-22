@extends('layouts.main', [
        'title' => "Modifica Gruppo Utenti",
         "breadcrumbs" => ["Elenco Gruppo Utenti" => "group.index", "Modifica" => "group.edit"],
         "breadcrumbsParams"=>[ "Modifica" => $group->id ]
         ])

@section('app-content')

{!! Form::model($group, ['route' => ['group.update', $group->id], 'method' => 'patch', "id" => "group-update", "data-agent"=>$group->id, 'files'=>true ]) !!}
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
                        <label for="name"> {{ __('Nome') }}</label>
                        {!!  Form::text("name", $group->name, ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'id' => 'first_name', 'placeholder' => 'None']) !!}
                        @if ($errors->has('name'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('name') }}</strong></div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="group_leader"> {{ __('Capo Gruppo') }}</label>
                        {!!  Form::select('group_leader',
                        $agents, $group->group_leader, [
                        'class' => $errors->has('group_leader') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                        "id" => "group_leader",
                        "data-live-search" => "true",
                        "data-dropup-auto"=> "true",
                        "data-size"=> "5",
                        "multiple" => "false"
                        ] ) !!}
                        @if ($errors->has('group_leader'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('group_leader') }}</strong></div>
                        @endif
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <label for="type"> {{ __('Tipologia') }}</label>
                        {!!
                            Form::select('type', $types, $group->type,[
                                 'class' => $errors->has('type') ? 'form-control is-invalid' : 'form-control',
                            ])
                         !!}
                        @if ($errors->has('type'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('type') }}</strong></div>
                        @endif
                   </div>
                   <div class="col-md-3">
                       <div class="form-group">
                           <label for="fee_percentage"> {{ __('Commissioni') }}</label>
                           <div class="input-group">
                               {!!  Form::text("fee_percentage", $group->fee_percentage, ['class' => $errors->has('fee_percentage') ? 'form-control is-invalid' : 'form-control', 'id' => 'name', 'placeholder' => '100']) !!}
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
                    <div class="col-md-5">
                        <label for="notification_email"> {{ __('Email di notifica') }}</label>
                        {!!  Form::text("notification_email", $group->notification_email, ['class' => $errors->has('notification_email') ? 'form-control is-invalid' : 'form-control', 'id' => 'email', 'placeholder' => 'Email']) !!}
                        @if ($errors->has('notification_email'))
                            <div class="help-block form-text with-errors form-control-feedback">
                                <strong>{{ $errors->first('notification_email') }}</strong></div>
                        @endif
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        <!-- Tab section -->
        <div class="element-box">
            <div class="os-tabs-w">
                <div class="os-tabs-controls">
                    <ul class="nav nav-tabs bigger" style="font-size: 1rem">
                        <li class="nav-item">
                            <a class="nav-link nav-link active show" data-toggle="tab" href="#agents">
                                {{ __('Agenti') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link show" data-toggle="tab" href="#additional-services">
                                {{ __('Servizi aggiuntivi') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link show" data-toggle="tab" href="#crm">
                                {{ __('CRM') }}
                            </a>
                        </li>
                    </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active show" id="agents">
                            @include('partials.group.agents')
                        </div>
                        <div class="tab-pane show" id="crm">
                            @include('partials.group.crm')
                        </div>
                        <div class="tab-pane show" id="additional-services">
                            @include('partials.group.additional-services')
                        </div>
                    </div>
                </div>
            </div>
        <!-- End Tab section -->
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
            <div class="row">
                <div class="col-md-12">
                    <img src="{{$group->logo}}" class="img-fluid" width="100%" style="display:block;margin: 10px;border: 2px solid #dde2ec!important;"/>
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
                    @include('partials.group.actions')
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
