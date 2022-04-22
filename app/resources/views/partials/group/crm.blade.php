<p>
    In questa sezione è possibile selezionare il tool CRM attraverso il quale verrano gestite le pratiche
    degli utenti in base al <code>Broker</code>.<br>
    Nel caso in cui non venga specificato alcun valore le pratiche verrano
    gestite dal backoffice di iDEAL.
</p>

{!! Form::open(['route' => ['group.filters', $group->id], 'method' => 'post', "id" => "crm-settings" ]) !!}


<div class="row">
    <div class="col-md-12 mt-2">
        <label for="crm_connection">{{ __('Nome Connessione') }}</label>
        {!! Form::select('crm_connection', $connections, $selectedConnection,
            [   'class' => $errors->has('crm_connection') ? 'form-control is-invalid' : 'form-control',
                "id" => "crm_connection"
                ]
         ) !!}
        @if ($errors->has('crm_connection'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('crm_connection') }}</strong></div>
        @endif
    </div>
    <div class="col-md-12 mt-2">
        <label for="crm_broker">{{ __('Broker') }}</label>
        {!! Form::select('crm_broker[]', $brokers, $selectedBrokers,
            [   'class' => $errors->has('crm_broker') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                "id" => "crm_broker",
                "multiple" =>  "true",
                "data-live-search" => "true",
                "data-dropup-auto"=> "true",
                "data-size"=> "5",
                ]
         ) !!}
        @if ($errors->has('crm_broker'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('crm_broker') }}</strong></div>
        @endif
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-12">
        {!! Form::submit('Aggiornare il CRM', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

{!! Form::close() !!}
