<div class="table-responsive">
    <table class="table table-padded">
        <thead>
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
                        <i class="os-icon os-icon-edit-32"></i> {{__('Modifica')}}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
