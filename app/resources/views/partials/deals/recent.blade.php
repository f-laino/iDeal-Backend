<div class="element-wrapper">
        @isset($title)
            <h6 class="element-header">
                {{ $title }}
            </h6>
        @endisset
    <div class="element-box-tp">
        <div class="table-responsive">
            <table class="table table-padded">
                <thead>
                <tr>
                    <th>
                        Data
                    </th>
                    <th>
                        Stato
                    </th>
                    <th>
                        Offerta
                    </th>
                    <th>
                        Nome
                    </th>
                    <th class="text-center">
                        Email
                    </th>
                    <th class="text-right">
                       Pipedrive
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach( $deals as $deal)

                    <tr>
                        <td class="nowrap">
                            <span>{!!  $deal->HTMLDate !!} </span>
                        </td>
                        <td>{{ $deal->stageDescription }}</td>
                        <td>
                            @php($car = $deal->offer->car)
                            <span>{{$car->brand->name}}</span><br>
                            <span class="smaller lighter">{{$car->modello}}</span>
                        </td>
                        <td class="cell-with-media">
                            <span>{{ $deal->customer->name }}</span>
                        </td>
                        <td class="text-center">
                            <a href="mailto:{{ $deal->customer->email }}">{{ $deal->customer->email }}</a>
                        </td>
                        <td class="text-right bolder nowrap">
                            <a class="badge badge-secondary" href="https://daicar.pipedrive.com/deal/{{ $deal->crm_id }}" target="_blank">Visualizza</a>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

