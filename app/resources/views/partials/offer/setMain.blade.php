<!-- Modal -->
<div class="modal fade" id="setMainModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Seleziona nuova nuova offerta main</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="childErrorAreaModal"></div>
                <div class="form-group">
                        <label for="ChildAsMain">{{ __('Seleziona Nuova Main') }} </label>
                        {{ Form::select('newChild', $childsAsList,  $childsAsList,
                            ['class' =>  'form-control', "id" => "ChildAsMain"]) }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" onclick="setNewMain()">Salva</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script type="text/javascript">
        function setNewMain() {
            var childOffer = $("select#ChildAsMain option").filter(":selected").val();

            $.post("{{route('offer.asMain', ['id'=>$offer->id])}}", {
                "child": childOffer,
            })
                .done(function( data ) {
                    $("#childErrorAreaModal").html('');
                   if(data.status === true){
                       window.location.reload(true);
                   }
                   else {
                       var content = ` <div class="alert alert-danger" role="alert"><strong>Caspita! </strong>${data.error}</div>`;
                       $("#childErrorAreaModal").html(content);
                   }
                });
        }
    </script>
@endpush()
