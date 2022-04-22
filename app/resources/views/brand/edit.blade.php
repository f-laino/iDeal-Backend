@extends('layouts.main', [
        'title' => "Modifica Brand",
         "breadcrumbs" => ["Elenco Brand" => "brand.index", "Modifica" => "brand.edit"],
         "breadcrumbsParams"=>[ "Modifica" => $brand->id ]
         ])


@section('app-content')
    <div class="element-wrapper">
        <div class="element-box">
            <div class="element-info">
                <div class="element-info-with-icon">
                    <div class="element-info-text">
                        <h5 class="element-inner-header">
                            <img alt="{{ $brand->logo_alt }}"
                                 src="{{ $brand->logo }}"
                                 style="height: 45px;"> {{ $brand->name }}
                        </h5>
                    </div>
                </div>
            </div>
            {{ Form::model($brand ,['route' => ['brand.update', $brand->id], 'method' => 'PATCH', "id" => "image-update"]) }}
            <div class="row">
                <div class="col-md-4">
                    <label for="name"> {{ __('Nome') }}</label>
                    {!!  Form::text("name", $brand->name, ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'id' => 'name', 'placeholder' => 'Nome']) !!}
                    @if ($errors->has('name'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('name') }}</strong></div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label for="title"> {{ __('Titolo') }}</label>
                    {!!  Form::text("title", $brand->title, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control', 'id' => 'title', 'placeholder' => 'Titolo']) !!}
                @if ($errors->has('title'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('title') }}</strong></div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label for="logo_alt"> {{ __('Logo alt') }}</label>
                    {!!  Form::text("logo_alt", $brand->logo_alt, ['class' => $errors->has('logo_alt') ? 'form-control is-invalid' : 'form-control', 'id' => 'logo_alt', 'placeholder' => 'Logo alt']) !!}
                    @if ($errors->has('logo_alt'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('logo_alt') }}</strong></div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="logo"> {{ __('Logo') }}</label>
                    {!!  Form::text("logo", $brand->logo, ['class' => $errors->has('logo') ? 'form-control is-invalid' : 'form-control', 'id' => 'logo', 'placeholder' => 'Link ']) !!}
                    @if ($errors->has('logo'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('logo') }}</strong></div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="description"> {{ __('Descrizione') }}</label>
                    {!!  Form::textarea("description",  !empty($brand->description) ? $brand->description : old('description'),
                    ['class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control',
                    'id' => 'description',
                    'rows' => 3,
                    'style' => 'resize:none',
                    'placeholder' => 'Descrizione']) !!}
                    @if ($errors->has('description'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('description') }}</strong></div>
                    @endif
                </div>
            </div>

            <div class="row" style="margin-top: 10px">
                <div class="col-md-12">
                    {!! Form::submit('Aggiorna', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection




