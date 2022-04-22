@extends('layouts.main', [
        'title' => "Elenco Gruppo Utenti",
        "total" => $groups->total(),
        "searchRoute" => "group.index",
        'addRoute' => 'group.create',
        "breadcrumbs" => ["Elenco Gruppo Utenti" => "group.index"]
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
                        Capo Gruppo
                    </th>
                    <th>
                       Email Notifica
                    </th>
                    <th class="text-center">
                        Azioni
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach( $groups as $group)
                    <tr>
                        <td class="nowrap">
                            <span>{{ $group->id }} </span>
                        </td>
                        <td>
                            <span>{{ $group->name }}</span>
                        </td>
                        <td>
                            <span>
                                @if( !empty($group->leader) )
                                    {{ !empty($group->leader->name) ? $group->leader->name : $group->leader->business_name }}
                                @else -
                                @endif
                            </span>
                        </td>
                        <td>
                            <span>
                                @if( !empty($group->notification_email) )
                                    {{ $group->notification_email }}
                                @else -
                                @endif
                            </span>
                        </td>
                        <td class="text-right bolder nowrap">
                            <a class="btn btn-outline-info btn-sm"
                               href="{{ route("group.edit", [$group->id]) }}"
                               data-toggle="tooltip" title="Modifica i dati relativi al gruppo">
                                <i class="os-icon os-icon-edit-32"></i> Modifica
                            </a>
                            {!! Form::open([
                               'route' => ['group.destroy', $group->id],
                               'method' => 'DELETE',
                               "id" => "group-delete",
                               "style" => "display: inline"])
                           !!}
                            <button class="btn btn-outline-danger btn-sm"
                                    type="submit"
                                    data-toggle="tooltip" title="Elimina Gruppo"
                                    onclick="return confirm('Sei sicuro di voler elimare questo gruppo?')">
                                <i class="os-icon os-icon-ui-15"></i>
                                Elimina
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
            {!! $groups; !!}
        </div>
    </div>

@endsection
