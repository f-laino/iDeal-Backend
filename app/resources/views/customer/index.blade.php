@extends('layouts.main', [
        'title' => "Elenco Clienti",
        "total" => $customers->total(),
         "breadcrumbs" => ["Elenco Clienti" => "customer.index"]
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
                        Nome
                    </th>
                    <th>
                       Email
                    </th>
                    <th>
                        Telefono
                    </th>
                    <th>
                        C.F.
                    </th>
                    <th>
                        IBAN
                    </th>

                </tr>
                </thead>
                <tbody>
                @foreach($customers as $customer)
                    <tr>
                        <td class="nowrap">
                            <span>{{ $customer->id }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $customer->first_name }} {{ $customer->last_name }} </span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $customer->email }} </span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $customer->phone }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $customer->phone }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $customer->phone }}</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $customers; !!}
@endsection

