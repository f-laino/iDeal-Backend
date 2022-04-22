@extends('layouts.main', [
        'title' => "Nuovo documento",
         "breadcrumbs" => ["Elenco documenti" => "documents.index", "Nuovo" => "documents.create"]
         ])

@section('app-content')

    <div class="element-wrapper">
        <div class="element-box">
            <div class="element-info">
                <div class="element-info-text">
                    <h5 class="element-inner-header">
                        Crea un nuovo documento
                    </h5>
                </div>
            </div>
            {{ Form::open(['route' => ['documents.store'], 'method' => 'POST', 'id' => "documents-add"]) }}
            <div class="row">
                <div class="col-md-6">
                    <label for="title">{{ __('Titolo') }}</label>
                    {!!  Form::text("title", old('title'), ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control', 'id' => 'title', 'placeholder' => 'Titolo']) !!}
                    @if ($errors->has('title'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('title') }}</strong></div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="link">
                        {{ __('Link') }}
                    </label>
                    {!!  Form::file("link", ['accept' => '.pdf', 'class' => $errors->has('link') ? 'form-control-file is-invalid' : 'form-control-file', 'id' => 'link']) !!}
                    @if ($errors->has('link'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('link') }}</strong></div>
                    @endif
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    {!! Form::submit('Salva', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection
