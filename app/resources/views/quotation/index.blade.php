@extends('layouts.main', [
        'title' => "Elenco Preventivi",
        "total" => $quotations->total(),
         "breadcrumbs" => ["Elenco Preventivi" => "quotation.index"]
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
                        Stage
                    </th>
                    <th>
                        Cliente
                    </th>
                    <th>
                        Rata
                    </th>
                    <th>
                        Deposito
                    </th>
                    <th>
                        Durata
                    </th>
                    <th>
                        Distanza
                    </th>
                    <th>
                        Franchiggia
                    </th>
                    <th>
                        F.Kasko
                    </th>
                    <th>
                        Azioni
                    </th>

                </tr>
                </thead>
                <tbody>
                @foreach($quotations as $quotation)
                    <tr>
                        <td class="nowrap">
                            <span>{{ $quotation->id }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $quotation->stageDescription }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $quotation->proposal->customer->first_name }} </span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $quotation->proposal->monthly_rate }} &euro;</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $quotation->proposal->deposit }} &euro;</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $quotation->proposal->duration }} mesi</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $quotation->proposal->distance }} KM</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $quotation->proposal->franchise_insurance }} &euro;</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $quotation->proposal->franchise_kasko }} &euro;</span>
                        </td>
                        <td class="text-right bolder nowrap">
                            <a class="badge badge-secondary" href="https://daicar.pipedrive.com/deal/{{ $quotation->crm_id }}" target="_blank">Visualizza</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $quotations; !!}
@endsection

