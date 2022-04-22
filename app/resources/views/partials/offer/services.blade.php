<h5 class="form-header">
    {{ __('Servizi aggiuntivi') }}
    <div class="form-desc"></div>
</h5>
<div class="row">
    @foreach($carServices as $carService)
        <div class="col-2">
            <div class="profile-tile "
                 data-service="{{$carService->id}}"
                 onclick="return updateService(this);"
                 data-toggle="tooltip" title=" {{ $carService->name }} "
            >
                <a class="profile-tile-box">
                    <div class="pt-avatar-w @if($carofferServices->contains($carService)) btn-warning @endif"
                         id="card-service-{{$carService->id}}">
                        <img src="{{ $carService->icon }}" alt="{{ $carService->description }}" height="30" width="30">
                    </div>
                    <div class="pt-user-name">
                        <strong>{{ str_limit($carService->name, 15) }}</strong>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
</div>


@push('scripts')
    <script type="application/javascript">
        function updateService(event) {
            var caroffer = $('#offer-update').data('offer');
            var service = $(event).data('service');

            $.post("{{route('offer.service')}}", {caroffer, service})
                .done(function (data) {
                    if (data.status == 200) {
                        $(`#card-service-${service}`).addClass("btn-warning");
                    }
                    else if (data.status == 202) {
                        $(`#card-service-${service}`).removeClass("btn-warning");
                    }
                });
        }
    </script>
@endpush