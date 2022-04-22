<button class="btn btn-primary" onclick="return submitGroupForm();" type="button" style="width: 100%">
    {{ __('Aggiorna Gruppo') }}
</button>

{!! Form::open(['route' => ['group.destroy', $group->id],
           'method' => 'delete',
           "id" => "group-delete",
             'onsubmit' => 'return confirm("Sei sicuro di voler elimare questo gruppo?")',
           ]) !!}
{!! Form::submit('Elimina Gruppo', [
    'class' => 'btn btn-danger',
    'style' => 'margin-top: 10px;display: block; width: 100%'
    ]) !!}
{!! Form::close() !!}



@push('scripts')
    <script type="text/javascript">
        function submitGroupForm() {
            return $('#group-update').submit();
        }
    </script>
@endpush
