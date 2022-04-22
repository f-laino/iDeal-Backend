@extends('layouts.main', [
        'title' => "Lista documenti per broker"
        ])

@section('app-content')
    <div class="element-box-tp">
        <div class="table-responsive">
            <table class="table table-padded">
                <thead>
                <tr>
                    <th>
                        Broker
                    </th>
                    <th>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($brokers as $brokerCode => $brokerLabel)
                    <tr>
                        <td class="nowrap">
                            <span>{{ $brokerLabel }}</span>
                        </td>
                        <td class="text-right">
                            <a class="btn btn-outline-info btn-sm"
                               href="{!! route('document-list.edit', [$brokerCode]) !!}" data-toggle="tooltip"
                               title="Modifica lista documenti per questo broker">
                                <i class="os-icon os-icon-edit-32"></i>
                                Modifica lista documenti
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

