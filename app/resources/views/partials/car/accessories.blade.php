<div class="table-responsive">
    <table class="table table-padded">
        <thead>
            <th>{{__('Disponibilità')}}</th>
            <th style="min-width: 100px">{{__('Descrizione')}}</th>
            <th style="min-width: 100px">{{__('Descrizione Standard')}}</th>
            <th style="min-width: 100px">{{__('Descrizione Abbreviata')}}</th>
            <th>{{__('Prezzo')}}</th>
            <th class="text-right" width="50px">{{__('Azioni')}}</th>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <label class="btn-sm">
                            <div class="os-toggler-w {{ $item->available == TRUE ? "on" : "" }}"
                                 data-accessory="{{$item->id}}" onclick="return updateItemStatus(this);">
                                <div class="os-toggler-i" style="background-color: #E1E6F2;">
                                    <div class="os-toggler-pill"></div>
                                </div>
                            </div>
                        </label>
                    </td>
                    <td>{{$item->description}}</td>
                    <td>{{$item->standard_description}}</td>
                    <td>{{$item->short_description}}</td>
                    <td>{{$item->price}} &euro;</td>
                    <td><a class="btn btn-outline-info btn-sm"
                           target="_blank"
                           href="{!! route('cars.accessories.edit', [$item->id]) !!}" data-toggle="tooltip"
                           title="Modifica Dati Accessorio">
                            <i class="os-icon os-icon-edit-32"></i>
                            {{__('Modifica')}}
                        </a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
    <script type="text/javascript">
        function updateItemStatus(event) {
            var accessory = $(event).data('accessory');
            $.post("{{route('cars.accessories.status')}}", {accessory})
                .done(function( data ) {
                    if (data.status === true && !$(event).hasClass('on'))
                        $(event).addClass("on");
                    else  $(event).removeClass("on");
                });
        }
    </script>
@endpush
