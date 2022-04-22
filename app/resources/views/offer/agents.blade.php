@extends('layouts.main', [
        'title' => "Associa Agenti all'Offerta Noleggio",
         "breadcrumbs" => ["Offerte Auto Noleggio" => "offer.index", "Modifica Offerta" => "offer.edit", "Associa Agenti" => "offer.agents"],
         "breadcrumbsParams"=>[ "Modifica Offerta" => $offer->id, "Associa Agenti" => $offer->id ]
         ])


@section('app-content')


    <div class="element-box-tp">
        <div class="table-responsive">
            <table class="table table-padded">
                <thead>
                <tr>
                    <th>
                        Associa
                    </th>
                    <th>
                        Nome
                    </th>
                    <th>
                        E-Mail
                    </th>
                    <th>
                        Gruppo
                    </th>
                    <th>
                        Comissione
                    </th>

                </tr>
                </thead>
                <tbody>
                @foreach( $agents as $agent)
                    @php ($hasOffer = in_array($agent->id, $offer_agents))
                    <tr>
                        <td class="nowrap">
                            <label class="btn-sm">
                                <div class="os-toggler-w {{ $hasOffer ? "on" : "" }}"
                                     data-agent="{{$agent->id}}" data-agent-update="{{ $hasOffer ? '1' : '0' }}"
                                     onclick="return attachAgent(this);">
                                    <div class="os-toggler-i" style="background-color: #E1E6F2;">
                                        <div class="os-toggler-pill"></div>
                                    </div>
                                </div>
                            </label>
                        </td>
                        <td>
                            <span>{{ $agent->getName() }}</span>
                        </td>
                        <td>
                            <a>{{ $agent->email }}</a>
                        </td>
                        <td class="text-center">
                            <span>
                                @if(!empty($agent->myGroup))
                                    {{ $agent->myGroup->name}}
                                @endif
                            </span>
                        </td>
                        <td>
                            &euro; <span>{{number_format($offer->fee($agent), 0, '.', '.')}}</span>
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

@push('scripts')
    <script type="application/javascript">
        function attachAgent(event) {
            var agent = $(event).data('agent');
            var exist = $(event).data('agent-update');
            $.post("{{route('offer.updateAgent',  [$offer->id])}}", {agent, exist})
                .done(function (data) {
                    if (data.status === true && !$(event).hasClass('on')) {
                        $(event).addClass("on");
                        $(event).data('agent-update', 1);
                    }
                    else {
                        $(event).removeClass("on");
                        $(event).data('agent-update', 0);
                    }
                });
        }
    </script>
@endpush
