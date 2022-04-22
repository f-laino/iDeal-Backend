@extends('layouts.main', [
        'title' => "Elenco Promozioni",
        "total" => $promotions->total(),
        'addRoute' => 'promotion.create',
        "breadcrumbs" => ["Elenco Promozioni" => "promotion.index"]
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
                        {{__('Titolo')}}
                    </th>
                    <th>
                        {{__('Descrizione')}}
                    </th>
                    <th>
                        {{__('Stato')}}
                    </th>
                    <th>
                        {{__('Ultima modifica')}}
                    </th>
                    <th class="text-center">
                        {{__('Azioni')}}
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach( $promotions as $promotion)
                    <tr>
                        <td class="nowrap">
                            <span>{{ $promotion->id }} </span>
                        </td>
                        <td width="20%">
                            <span>{{ $promotion->short_title }}</span>
                        </td>
                        <td width="30%">
                            <span>{{ $promotion->short_description }}...</span>
                        </td>
                        <td width="15%">
                            <span>
                                @if($promotion->status)
                                    {{ __('Attiva') }}
                                @else
                                    {{ __('Non attiva') }}
                                @endif
                            </span>
                        </td>
                        <td width="20%">
                            <span>{{ $promotion->updated_at }}</span>
                        </td>
                        <td class="text-right bolder nowrap">
                            <a class="btn btn-outline-info btn-sm"
                               href="{{ route("promotion.edit", [$promotion->id]) }}"
                               data-toggle="tooltip" title="Modifica i dati relativi alla promozione">
                                <i class="os-icon os-icon-edit-32"></i>
                                {{__('Modifica')}}
                            </a>
                            {!! Form::open([
                               'route' => ['promotion.destroy', $promotion->id],
                               'method' => 'DELETE',
                               "id" => "promotion-delete",
                               "style" => "display: inline"])
                           !!}
                            <button class="btn btn-outline-danger btn-sm"
                                    type="submit"
                                    data-toggle="tooltip" title="Elimina Promozione"
                                    onclick="return confirm('Sei sicuro di voler elimare questa promozione?')">
                                <i class="os-icon os-icon-ui-15"></i>
                                {{__('Elimina')}}
                            </button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            {!! $promotions; !!}
        </div>
    </div>

@endsection
