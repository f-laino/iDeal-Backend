<div class="row">
    <div class="col-md-12 mt-2">
        <h5 class="form-header" style="padding-top:0px;">
            Funzionalità e-commerce
        </h5>
        <div class="form-desc" style="margin-bottom:unset; border-bottom: unset">
            La funzionalità <strong>e-commerce</strong> permette l'integrazione del catalogo offerte all'interno del
            propio sito web. <br/>
            Per ulteriori informazioni consultare la
            <a href="{{config('services.ideal_api.domain')}}" target="_blank">
                documentazione ufficiale</a>.
        </div>
        @if(empty($apiServiceToken))
            {!! Form::open(['route' => ['agent.service.api', $agent->id],
            'method' => 'post',
            "id" => "agent-service-api",
            'onsubmit' => 'return confirm("Sei sicuro di voler attivare la funzionalità e-commerce?")',
             ]) !!}
            {!! Form::submit('Attiva funzionalità e-commerce', [
                'class' => 'btn btn-success',
                'style' => 'margin-top: 10px;display: block; width: 100%',
                ]) !!}
            {!! Form::close() !!}
        @else
            <label for="account_service_api_token"> {{ __('Token di accesso') }}</label>
            {!!  Form::text("account_service_api_token", $apiServiceToken, ['class' =>  'form-control', 'id' => 'account-service-api-token', 'disabled' => true]) !!}
        @endif
    </div>
</div>

