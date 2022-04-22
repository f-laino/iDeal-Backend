@extends('layouts.main', ['title' => "Elenco Brand Auto" ])

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
                        Nome
                    </th>
                    <th>
                        Titolo
                    </th>
                    <th>
                        Azioni
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($brands as $brand)
                    <tr>
                        <td class="nowrap"  style="width: 20px">
                            <span>{{ $brand->id }} </span>
                        </td>
                        <td class="cell-with-media" style="width: 200px">
                            <img alt="{{ $brand->name }} Logo"
                                 src="{{ $brand->logo }}"
                                 style="height: 25px;">
                            <span>{{ $brand->name }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $brand->title }}</span>
                        </td>
                        <td class="text-right bolder nowrap"  style="width: 50px">
                            <a class="btn btn-outline-info btn-sm"
                               href="{{ route("brand.edit", [$brand->id]) }}"
                               data-toggle="tooltip" title="Modifica i dati relativi al Brand">
                                <i class="os-icon os-icon-edit-32"></i> Modifica
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $brands; !!}
@endsection







