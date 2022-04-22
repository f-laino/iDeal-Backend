@extends('layouts.main', [
        'title' => "Elenco documenti",
        "total" => $documents->total(),
        'addRoute' => 'documents.create'
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
                        Titolo
                    </th>
                    <th>
                        Tipologia
                    </th>
                    <th>
                        Link
                    </th>
                    <th>
                        Azioni
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td class="nowrap">
                            <span>{{ $document->id }}</span>
                        </td>
                        <td class="text-truncate" style="max-width: 450px;" data-toggle="tooltip" title="{{ $document->title }}">
                            <span>{{ $document->title }}</span>
                        </td>
                        <td class="nowrap"
                            <span>{{ $document->type }}</span>
                        </td>
                        <td class="nowrap text-center">
                            @if($document->link)
                            <a href="{{ $document->link }}" target="_blank" data-toggle="tooltip"
                                title="@php echo basename($document->link); @endphp">
                                <i class="os-icon os-icon-ui-51"></i>
                            </a>
                            @endif
                        </td>

                        <td class="text-right">
                            <a class="btn btn-outline-info btn-sm"
                               href="{!! route('documents.edit', [$document->id]) !!}" data-toggle="tooltip"
                               title="Modifica documento">
                                <i class="os-icon os-icon-edit-32"></i>
                                Modifica
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

