<div class="row">
    <div class="col">
        <div id="errorArea"></div>
        <table class="table table-lightborder" id="tb">
            <thead>
            <tr>
                <th>Anticipo</th>
                <th>KM/Anno</th>
                <th>Durata</th>
                <th>Rata Mensile</th>
                <th>Azioni</th>
            </tr>
            </thead>
            <tbody class="text-center">
            @foreach($childOffers as $childOffer)
                <tr data-id="{{$childOffer->id}}">
                    <td width="20%">
                        <input class="form-control" name="child_deposit" type="number" min="0"
                               value="{{ intval($childOffer->deposit) }}" disabled>
                    </td>
                    <td width="20%">
                        <input class="form-control" name="child_distance" type="number" min="0" max="100000"
                               value="{{ $childOffer->distance }}" disabled>
                    </td>
                    <td width="20%">
                        <input class="form-control" name="child_duration" type="number" min="0" max="72"
                               value="{{ $childOffer->duration }}" disabled>
                    </td>
                    <td width="20%">
                        <input class="form-control" name="child_monthly_rate" type="number" min="0"
                               value="{{ intval($childOffer->monthly_rate) }}" disabled>
                    </td>
                    <td class="row-actions" width="20%">
                        <span style="color: #e65252" id='removeRow' onclick="return removeRow(this);"
                              title="Elimina variazione"><i class="os-icon os-icon-ui-15"></i></span>
                        <span style="color: #5bc0de" id='setAsMain' title="Imposta come offerta main" onclick="return setAsMain(this);"><i
                                    class="os-icon os-icon-ui-02"></i></span>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td width="20%">
                    <input class="form-control" placeholder="1500" name="child_deposit" type="number" min="0">
                </td>
                <td width="20%">
                    <input class="form-control" placeholder="15000" name="child_distance" type="number" min="0" max="100000">
                </td>
                <td width="20%">
                    <input class="form-control" placeholder="36" name="child_duration" type="number" min="0" max="72">
                </td>
                <td width="20%">
                    <input class="form-control" placeholder="0" name="child_monthly_rate" type="number" min="0">
                </td>
                <td class="row-actions" width="20%">
                    <span style="color: #e65252;display: none;" onclick="return removeRow(this);" id='removeRow'
                          title="Elimina variazione"><i class="os-icon os-icon-ui-15"></i></span>
                    <span style="color: #5bc0de;display: none;" id='setAsMain' title="Imposta come offerta main"><i
                                class="os-icon os-icon-ui-02"></i></span>
                    <span style="color: #1f855f" id="addMore" onclick="return addRow(this);"
                          title="Aggiungi variazione"><i class="os-icon os-icon-ui-22"></i></span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        function addRow(elem) {
            var row = $(elem).closest('tr');
            var data = {};
            $(row).find(":input").each(function () {
                var input = $(this);
                data[input.attr('name')] = input.val();
            });
            $.post("{{route('offer.childs.store', [ 'id' => $offer->id])}}", {
                'deposit': data.child_deposit,
                'distance': data.child_distance,
                'duration': data.child_duration,
                'monthly_rate': data.child_monthly_rate,
            }).fail(function (response) {
                var errors = response.responseJSON.errors;
                if (errors.deposit) {
                    $(row).find('[name ="child_deposit"]').addClass('is-invalid');
                }
                if (errors.distance) {
                    $(row).find('[name ="child_distance"]').addClass('is-invalid');
                }
                if (errors.duration) {
                    $(row).find('[name ="child_duration"]').addClass('is-invalid');
                }
                if (errors.monthly_rate) {
                    $(row).find('[name ="child_monthly_rate"]').addClass('is-invalid');
                }
            }).done(function (response) {
                $(row).find(":input").each(function () {
                    //rimuovo eventuali validazioni
                    $(this).removeClass('is-invalid');
                    //disattivo l'input
                    $(this).prop("disabled", true);
                });
                //aggiungo nuova riga alla tabella
                var newRow = $(row).clone(true);
                $(newRow).appendTo("#tb");
                //azzero il valore dell'input
                $(newRow).find('[name ="child_monthly_rate"]').val(0);
                $(newRow).find(":input").each(function () {
                    //attivo l'input
                    $(this).prop("disabled", false);
                });

                //imposto id child nell'intestazione riga
                $(row).data('id', response.data);
                $(row).find('#removeRow').show();
                $(row).find('#setAsMain').show();
                $(row).find('#addMore').remove();
            });
        }

        function removeRow(elem) {
            var row = $(elem).closest('tr');
            var child = row.data('id');
            $.post("{{route('offer.childs.destroy', [ 'id' => $offer->id])}}", {
                'child': child,
            }).done(function (response) {
                if(response.status === 1)
                    $(row).remove();
            });
        }

        function setAsMain(elem) {
            var row = $(elem).closest('tr');
            var child = row.data('id');
            $.post("{{route('offer.childs.main', ['id'=>$offer->id])}}", {
                "child": child,
            }).done(function (data) {
                $("#errorArea").html('');
                if (data.status === true) {
                    window.location.reload(true);
                } else {
                    var content = ` <div class="alert alert-danger" role="alert"><strong>Caspita! </strong>${data.error}</div>`;
                    $("#errorArea").html(content);
                }
            });
        }

    </script>
@endpush


