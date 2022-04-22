@extends('layouts.main', [
        'title' => "Elenco Account",
        "total" => $agents->total(),
        "searchRoute" => "agent.index",
        'addRoute' => 'agent.create',
        "breadcrumbs" => ["Elenco Account" => "agent.index"]
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
                        Gruppo
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Telefono
                    </th>
                    <th class="text-center">
                        Azioni
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach( $agents as $agent)
                    <tr>
                        <td class="nowrap">
                            <span>{{ $agent->id }} </span>
                        </td>
                        <td>
                            <span>{{ $agent->getName() }}</span>
                        </td>
                        <td class="text-center">
                            <span>

                                @if(!empty($agent->myGroup))
                                    <a href="{{route('group.edit', [$agent->myGroup->id])}}" data-toggle="tooltip"
                                       title="Modifica Gruppo"> {{ $agent->myGroup->name}} </a>
                                @else -
                                @endif
                            </span>
                        </td>
                        <td>
                            <a href="mailto:{{ $agent->email }}">{{ $agent->email }}</a>
                        </td>
                        <td class="text-center">
                            <span>{{ $agent->phone }} </span>
                        </td>
                        <td class="text-right bolder nowrap">
                            <a class="btn btn-outline-info btn-sm"
                               href="{{ route("agent.edit", [$agent->id]) }}"
                               data-toggle="tooltip" title="Modifica i dati relativi al agente">
                                <i class="os-icon os-icon-edit-32"></i> Modifica
                            </a>
                            {!! Form::open([
                                'route' => ['agent.destroy', $agent->id],
                                'method' => 'DELETE',
                                "id" => "user-delete",
                                "style" => "display: inline"])
                            !!}
                             <button class="btn btn-outline-danger btn-sm"
                                     type="submit"
                                     data-toggle="tooltip" title="Elimina Agente"
                                     onclick="return confirm('Sei sicuro di voler elimare quest\' agente?')">
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
            {!! $agents; !!}
        </div>
    </div>

@endsection
