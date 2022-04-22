<div class="table-responsive">
    <table class="table table-padded">
        <thead>
        <th>Status</th>
        <th>#</th>
        <th style="min-width: 100px">Allestimento</th>
        <th>Importo</th>
        <th>Anticipo</th>
        <th>Durata</th>
        <th>Km/anno</th>
        <th>Broker</th>
        <th class="text-right" width="250px">Action</th>
        </thead>
        <tbody>
        @foreach($offers as $offer)
            @php($offerCar = $offer->car)
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
                <td>{{$offer->id}}</td>

                <td>
                        {{$offerCar->brand->name}}
                        {{$offerCar->modello}}
                        {{$offerCar->allestimento}}
                </td>

                <td>{{ number_format($offer->monthly_rate, 2, ',', '.')}} &euro;</td>
                <td>{{ number_format($offer->deposit, 2, ',', '.')}} &euro;</td>
                <td>{{$offer->duration}} mesi</td>
                <td>{{ number_format($offer->distance, 0, '', '.')}}</td>
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

