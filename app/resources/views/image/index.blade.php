@extends('layouts.main', [
        'title' => "Elenco Immagini Auto",
        "total" => $images->total(),
        "searchRoute" => "image.index",
         "breadcrumbs" => ["Elenco Immagini Auto" => "image.index"]
         ])

@section('searchExtraFields')
    <div class="ssg-header">
        <div class="ssg-icon">
            <div class="os-icon os-icon-agenda-1"></div>
        </div>
        <div class="ssg-name">
            Marca
        </div>
    </div>
    <div class="ssg-content">
        <div class="ssg-items ssg-items-boxed">
            {{ Form::select('brands[]', $brands, null,
    [
        "class" => "form-control selectpicker",
        "id" => "search-form",
        "data-live-search" => "true",
         "data-dropup-auto"=> "true",
         "data-size"=> "5",
         "multiple" => "true"
    ]) }}
        </div>
    </div>


    <div class="ssg-header">
        <div class="ssg-icon">
            <div class="os-icon os-icon-ui-54"></div>
        </div>
        <div class="ssg-name">
            Indentificativo Auto
        </div>
    </div>
    <div class="ssg-content">
        <div class="ssg-items ssg-items-boxed">
            {{ Form::text("car", null, ['class' => 'form-control', 'id' => 'car']) }}
        </div>
    </div>


@endsection


@section('app-content')
    <div class="element-box-tp">
        <div class="table-responsive">
            <table class="table table-padded">
                <thead>
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        Automobile
                    </th>
                    <th>
                        Path
                    </th>
                    <th>
                        Tipologia
                    </th>
                    <th>
                        Azioni
                    </th>
                </tr>
                </thead>
                <tbody>
                @if( !empty($images) )
                @foreach($images as $image)
                    <tr data-href=" {{ route("image.edit", [$image->id]) }} ">
                        <td class="nowrap">
                            <span>{{ $image->id }} </span>
                        </td>
                        <td class="cell-with-media">
                            <img alt=" Logo"
                                 src="{{ $image->path }}"
                                 style="height: 25px;">
                            <span>{{ $image->car->brand->name }} {{ $image->car->allestimento }}</span>
                        </td>
                        <td class="nowrap">
                            <a
                                    href="{{ $image->path }}"
                                    target="_blank"
                                    data-toggle="tooltip" title="{{ $image->path }}">
                                {{ str_limit($image->path,30 )}}
                            </a>
                        </td>
                        <td class="nowrap">
                            <span class="{{ $image->type == "MAIN" ? 'badge badge-success': '' }} ">{{ $image->type }}</span>
                        </td>
                        <td class="text-right bolder nowrap">
                            <a class="btn btn-outline-info btn-sm"
                               href="{{ route("image.edit", [$image->id]) }}"
                               data-toggle="tooltip" title="Modifica i dati relativi all'immagine">
                                <i class="os-icon os-icon-edit-32"></i> Modifica
                            </a>
                        </td>
                    </tr>
                @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {!! $images; !!}
@endsection



