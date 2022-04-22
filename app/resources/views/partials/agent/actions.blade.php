<button class="btn btn-primary" onclick="return submitAgentForm();" type="button" style="width: 100%">
    {{ __('Aggiorna Account') }}
</button>


@if($agent->isActive())
    {!! Form::open(['route' => ['agent.suspend', $agent->id],
    'method' => 'post',
    "id" => "agent-suspend",
    'onsubmit' => 'return confirm("Sei sicuro di voler sospendere questo account?")',
     ]) !!}
    {!! Form::submit('Sospendi Account', [
        'class' => 'btn btn-warning',
        'style' => 'margin-top: 10px;display: block; width: 100%'
        ]) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(['route' => ['agent.activate', $agent->id],
    'method' => 'post',
    "id" => "agent-activate",
    'onsubmit' => 'return confirm("Sei sicuro di voler attivare questo account?")',
     ]) !!}
    {!! Form::submit('Attiva Account', [
        'class' => 'btn btn-success',
        'style' => 'margin-top: 10px;display: block; width: 100%',
        ]) !!}
    {!! Form::close() !!}
@endif

{!! Form::open(['route' => ['agent.destroy', $agent->id],
           'method' => 'delete',
           "id" => "agent-delete",
             'onsubmit' => 'return confirm("Sei sicuro di voler elimare questo account?")',
           ]) !!}
{!! Form::submit('Elimina Account', [
    'class' => 'btn btn-danger',
    'style' => 'margin-top: 10px;display: block; width: 100%'
    ]) !!}
{!! Form::close() !!}

@push('scripts')
    <script type="text/javascript">
        function submitAgentForm() {
            return $('#agent-update').submit();
        }
    </script>
@endpush
