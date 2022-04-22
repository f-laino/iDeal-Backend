@extends('layouts.main', [
        'title' => "Elenco Generatori di Prezzi",
        "total" => $indexers->total(),
        "searchRoute" => "price-indexers.index",
        "addRoute" => "price-indexers.create",
         "breadcrumbs" => ["Elenco Generatori di Prezzi" => "price-indexers.index"]
         ])


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
                      Broker
                    </th>
                    <th>
                        Segmento
                    </th>
                    <th>
                        Offerte applicabili
                    </th>
                    <th style="width: 200px">
                        Azioni
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($indexers as $index)
                    <tr>
                        <td class="nowrap"  style="width: 20px">
                            <span>{{ $index->id }} </span>
                        </td>
                        <td style="width: 200px">
                            <span>{{ $index->broker }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $index->segment }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $index->countOffers }}</span>
                        </td>
                        <td class="nowrap"  style="width: 200px">
                            <a class="btn btn-outline-info btn-sm"
                               href="{{ route("price-indexers.edit", [$index->id]) }}"
                               data-toggle="tooltip" title="Modifica i dati relativi al generatore">
                                <i class="os-icon os-icon-edit-32"></i> Modifica
                            </a>
                            <form method="post" action="{!! route('price-indexers.destroy', [$index->id]) !!}" style="display: inline">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button class="btn btn-outline-danger btn-sm float-right" type="submit"
                                        onclick="return confirm('Sei sicuro di voler elimare questo generatore?')"
                                        data-toggle="tooltip" title="Elimina generatore"
                                ><i class="os-icon os-icon-ui-15"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $indexers; !!}
@endsection







