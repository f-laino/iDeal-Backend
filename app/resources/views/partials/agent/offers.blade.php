<p>
    {{ __('L\'agente ') }}
    <code>
        {{ $agent->getName() }}
    </code>
    {{ __('attualmente visualizza un catalogo contenente ') }}
    <code>{{ $agent->activeOffers()->count() }}</code>
    {{ __('offerte noleggio.') }}
</p>

@if(false)
    <a class="btn btn-secondary btn-outline-secondary" style="width: 100%" href="#" target="_blank">
        {{ __('Visualizza le offerte presenti nel catalogo agente') }}
    </a>
@endif
