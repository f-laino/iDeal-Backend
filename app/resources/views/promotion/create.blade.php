@extends('layouts.main', [
        'title' => "Aggiungi Nuova Promozione",
         "breadcrumbs" => ["Elenco Promozioni" => "promotion.index"],
         ])

@section('app-content')
    {!! Form::open( ['route' => ['promotion.store'], 'method' => 'POST', "id" => "promotion-add" ])  !!}
    <div class="row">
        <div class="col-md-8">
            <div class="element-box">
                <h5 class="form-header">
                    {{ __('Informazioni') }}
                    <div class="form-desc"></div>
                </h5>
                <div class="element-box-content example-content">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="title"> {{ __('Titolo') }}</label>
                            {!!  Form::text("title", old('title'), ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control', 'id' => 'title', 'placeholder' => 'Titolo']) !!}
                            @if ($errors->has('title'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('title') }}</strong></div>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="description" data-container="body"
                                   data-placement="right" data-toggle="popover" title="" type="button"
                                   data-html="true"
                                   data-original-title="Placeholder"
                                   data-content="I placeholder sono delle variabili che vengono sostituite con i dati dei clienti in fase di visualizzazione.<br>
                                        <code>##NOME##</code>: indica il campo nome utente.<br>
                                        <code>##TIPOLOGIA##</code>: indica la tipologia del gruppo alla quale appartiene l'utente.<br>
                                        <code>##CERTIFICATO##</code>.<br>
                                        <code>##VENDITORE##</code>."
                            > {{ __('Descrizione') }}</label>
                            {!!  Form::textarea("description",  old('description'),
                                ['class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control',
                                'id' => 'notes',
                                'rows' => 3,
                                'placeholder' => 'Inserisci descrizione promozione'])
                                !!}
                            @if ($errors->has('description'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('description') }}</strong></div>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="attachment_uri"> {{ __('Template') }}</label>
                            {!!  Form::select('attachment_uri', $templates, old('attachment_uri'), ['class' => $errors->has('attachment_uri') ? 'form-control is-invalid' : 'form-control', "id" => "attachment_uri" ] ) !!}
                        @if ($errors->has('attachment_uri'))
                                <div class="help-block form-text with-errors form-control-feedback">
                                    <strong>{{ $errors->first('attachment_uri') }}</strong></div>
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
                        <label for="status"> {{ __('Stato') }}</label>
                       {!!  Form::select('status', $status, old('status'),[
                                 'class' => $errors->has('status') ? 'form-control is-invalid' : 'form-control',
                            ]) !!}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        {!! Form::submit('Aggiungi', ['class' => 'btn btn-primary pull-right', 'style' => 'width:100%', ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
