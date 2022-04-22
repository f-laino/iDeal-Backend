@extends('layouts.main', [
        'title' => "Elenco Offerte Noleggio",
        "total" => $offers->total(),
        "searchRoute" => "offer.index",
        "addRoute" => "offer.create",
         "breadcrumbs" => ["Offerte Auto Noleggio" => "offer.index"]
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
            Modelli
        </div>
    </div>
    <div class="ssg-content">
        <div class="ssg-items ssg-items-boxed">
            {{ Form::text("model", null, ['class' => 'form-control', 'id' => 'model']) }}
        </div>
    </div>


    <div class="ssg-header">
        <div class="ssg-icon">
            <div class="os-icon os-icon-delivery-box-2"></div>
        </div>
        <div class="ssg-name">
            Broker
        </div>
    </div>
    <div class="ssg-content">
        <div class="ssg-items ssg-items-boxed">
            {{ Form::text("broker", null, ['class' => 'form-control', 'id' => 'broker']) }}
        </div>
    </div>



    <div class="ssg-header">
        <div class="ssg-icon">
            <div class="os-icon os-icon-calendar-time"></div>
        </div>
        <div class="ssg-name">
            Durata
        </div>
    </div>
    <div class="ssg-content">
        <div class="ssg-items ssg-items-boxed">
            {{ Form::text("durata", null, ['class' => 'form-control', 'id' => 'durata']) }}
        </div>
    </div>

    <div class="ssg-header">
        <div class="ssg-icon">
            <div class="os-icon os-icon-wallet-loaded"></div>
        </div>
        <div class="ssg-name">
            Importo
        </div>
    </div>

    <div class="ssg-content">
        <div class="ssg-items ssg-items-boxed">
            {{ Form::text("importo", null, ['class' => 'form-control', 'id' => 'importo']) }}
        </div>
    </div>

    <div class="ssg-header">
        <div class="ssg-icon">
            {{ Form::checkbox('suggested', null, false, ['class' => 'form-control', 'id' => 'suggested']) }}
        </div>
        <div class="ssg-name">
            In Evidenza ?
        </div>
    </div>

@endsection


@section('app-content')
    <div class="element-box-tp">
        <div class="table-responsive">
            <table class="table table-padded">
                <thead>
                <th>Status</th>
                <th>#</th>
                <th style="min-width: 100px">Allestimento</th>
                <th>Importo</th>
                <th>Anticipo</th>
                <th>Durata</th>
                <th>Broker</th>
                <th class="text-right" width="250px">Action</th>
                </thead>
                <tbody>
                @foreach($offers as $offer)
                    <tr>
                        <td>
                            <label class="btn-sm">
                                <div class="os-toggler-w {{ $offer->status == TRUE ? "on" : "" }}"
                                     data-offer="{{$offer->id}}" onclick="return updateStatus(this);">
                                    <div class="os-toggler-i" style="background-color: #E1E6F2;">
                                        <div class="os-toggler-pill"></div>
                                    </div>
                                </div>
                            </label>
                        </td>
                        <td width="15%">
                            {{$offer->id}}
                            @if($offer->hasChildOffers())
                                <i class="os-icon os-icon-grid-10" title="Quest'offerta presenta variazioni"></i>
                            @endif
                        </td>
                        <td>
                            @if(!empty($offer->car))
                                {{$offer->car->brand->name}}
                                {{$offer->car->modello}}
                                {{$offer->car->allestimento}}
                            @else
                                Automobile non impostata
                            @endif
                        </td>

                        <td>
                            {{$offer->monthly_rate}}&euro;
                        </td>
                        <td>{{$offer->deposit}}&euro;</td>
                        <td>{{$offer->duration}} mesi</td>
                        <td>{{$offer->broker}}</td>

                        <td class="text-right">
                            <a class="btn btn-outline-info btn-sm"
                               href="{!! route('offer.edit', [$offer->id]) !!}" data-toggle="tooltip"
                               title="Modifica Offerta">
                                <i class="os-icon os-icon-edit-32"></i> Edit</a>
                            <form method="post" action="{!! route('offer.destroy', [$offer->id]) !!}" style="display: inline">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button class="btn btn-outline-danger btn-sm float-right" type="submit"
                                        onclick="return confirm('Sei sicuro di voler elimare questa offerta?')"
                                        data-toggle="tooltip" title="Elimina Offerta"
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
    {!! $offers; !!}
@endsection



@push('scripts')
    <script type="text/javascript">
        function updateStatus(event) {
            var offer = $(event).data('offer');
            $.post("{{route('offer.status')}}", {offer})
                .done(function( data ) {
                    if (data.status === true && !$(event).hasClass('on'))
                        $(event).addClass("on");
                    else  $(event).removeClass("on");
                });
        }
    </script>
@endpush

