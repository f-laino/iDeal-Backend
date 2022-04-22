<div class="row">
    <div class="table-responsive">
        <table class="table table-padded">
            <thead>
            <tr>
                <th>
                    Nome
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
            @foreach( $group->agents as $agent)
                <tr>
                    <td>
                        <span>{{ $agent->getName() }}</span>
                    </td>
                    <td>
                        <a href="mailto:{{ $agent->email }}">{{ $agent->email }}</a>
                    </td>
                    <td>
                        <span>{{ $agent->phone }}</span>
                    </td>
                    <td class="text-right bolder nowrap">
                        <a class="btn btn-outline-info btn-sm"
                           href="{{ route("agent.edit", [$agent->id]) }}"
                           target="_blank"
                        >
                            <i class="os-icon os-icon-edit-32"></i> Visualizza
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <a class="btn btn-primary btn-sm pull-right"
           href="{{route('agent.create')}}"
           style="margin-right: 3%"
        >
            <i class="os-icon os-icon-ui-22"></i>
            <span>  {{__('Aggiungi Agente')}} </span>
        </a>

    </div>
</div>


