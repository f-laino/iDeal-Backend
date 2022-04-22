@extends('layouts.main', [
        'title' => "Elenco Commissioni",
        "total" => $indexers->total(),
        "addRoute" => "commission.create",
         "breadcrumbs" => ["Elenco Commissioni" => "commission.index"]
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
                        Commissioni
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
                        @php($pattern = json_decode($index->pattern, true))
                        <td class="nowrap">
                            <span>Segmento A: </span><strong>&euro; {{number_format($pattern['segment_a'], 0, '.', '.')}}</strong><br/>
                            <span>Segmento B: </span><strong>&euro; {{number_format($pattern['segment_b'], 0, '.', '.')}}</strong><br/>
                            <span>Segmento C: </span><strong>&euro; {{number_format($pattern['segment_c'], 0, '.', '.')}}</strong><br/>
                            <span>Segmento D: </span><strong>&euro; {{number_format($pattern['segment_d'], 0, '.', '.')}}</strong><br/>
                            <span>Segmento E: </span><strong>&euro; {{number_format($pattern['segment_e'], 0, '.', '.')}}</strong>
                        </td>
                        <td class="nowrap"  style="width: 200px">
                            <a class="btn btn-outline-info btn-sm"
                               href="{{ route("commission.edit", [$index->id]) }}"
                               data-toggle="tooltip" title="Modifica i dati relativi al generatore">
                                <i class="os-icon os-icon-edit-32"></i> Modifica
                            </a>
                            <form method="post" action="{!! route('commission.destroy', [$index->id]) !!}" style="display: inline">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button class="btn btn-outline-danger btn-sm float-right" type="submit"
                                        onclick="return confirm('Sei sicuro di voler elimare questo elenco commissioni?')"
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







