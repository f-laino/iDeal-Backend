@extends('layouts.main', [
        'title' => "Modifica Cateogria: $category->code",
         "breadcrumbs" => ["Elenco Categorie" => "category.index", "Modifica" => "category.edit"],
         "breadcrumbsParams"=>[ "Modifica" => $category->id ]
         ])

@section('app-content')


    <div class="element-wrapper">
        <div class="element-box">
            {{ Form::model($category, ['route' => ['category.update', $category->id], 'method' => 'patch', "id" => "category-update", "data-agent"=>$category->id]) }}

            <div class="row">
                <div class="col-sm-6">
                    <label for="code"> {{ __('Codice') }}</label>
                    {!!  Form::text("code", $category->code, ['class' => $errors->has('code') ? 'form-control is-invalid' : 'form-control', 'id' => 'code', 'placeholder' => 'Codice']) !!}
                    @if ($errors->has('code'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('code') }}</strong></div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <label for="description"> {{ __('Descrizione') }}</label>
                    {!!  Form::text("description", $category->description, ['class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control', 'id' => 'description', 'placeholder' => 'Descrizione']) !!}
                    @if ($errors->has('description'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('description') }}</strong></div>
                    @endif
                </div>
            </div>
            <div class="row" style="margin-top: 10px">
                <div class="col-md-12">
                    {!! Form::submit('Aggiorna', ['class' => 'btn btn-primary pull-right']) !!}
                    {!! Form::close() !!}

                    <form method="post" action="{!! route('category.destroy', [$category->id]) !!}" style="display: inline">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}
                        <button class="btn btn-danger  pull-right" type="submit" style="margin-right: 10px;"
                                onclick="return confirm('USei sicuro di voler elimare questa categoria?')"
                                data-toggle="tooltip" title="Elimina Categoria"
                        >Elimina
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>



@endsection