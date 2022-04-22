@extends('layouts.main', ['title' => "Elenco Servizi" ])

@section('app-content')
    <div class="element-box-tp">
        <div class="table-responsive">
            <table class="table table-padded">
                <thead>
                <tr>
                    <th>
                        Ordine
                    </th>
                    <th>
                        Slug
                    </th>
                    <th>
                        Nome
                    </th>
                    <th>
                        Descrizione
                    </th>

                </tr>
                </thead>
                <tbody>
                @foreach($services as $service)
                    <tr>
                        <td class="nowrap">
                            <span>{{ $service->order }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $service->slug }}</span>
                        </td>

                        <td class="cell-with-media">
                            <img alt=" Logo"
                                 src="{{ $service->icon }}"
                                 style="height: 55px;">
                            <span>{{ $service->name }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $service->description }}</span>
                        </td>


                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $services; !!}
@endsection









