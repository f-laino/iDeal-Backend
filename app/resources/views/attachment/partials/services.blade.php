<table style="border: 0px;">
    <thead>
    <tr style="text-align: center">
        <th width="50%" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">{{__('Servizi inclusi')}}</th>
        <th width="50%" style="border-bottom: 1px solid #000000;">{{__('Descrizione dei Servizi')}}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td width="50%" style="border-right: 1px solid #000000;">{{__('Manutenzione')}}</td>
        <td width="50%">{{__('Ordinaria e straordinaria')}}</td>
    </tr>
    <tr>
        <td width="50%" style="border-right: 1px solid #000000;">{{__('RCA')}}</td>
        <td width="50%">{{__('Franchigia')}} {{$franchigie['rca']->_toString()}}</td>
    </tr>
    <tr>
        <td width="50%" style="border-right: 1px solid #000000;">{{__('Furto e incendio')}}</td>
        <td width="50%">{{__('Franchigia')}} {{$franchigie['f_i']->_toString()}}</td>
    </tr>
    <tr>
        <td width="50%" style="border-right: 1px solid #000000;">{{__('Kasko')}}</td>
        <td width="50%">{{__('Franchigia')}} {{$franchigie['kasko']->_toString()}}</td>
    </tr>
    @if($parentOffer->hasService('tassa-di-proprieta'))
    <tr>
        <td width="50%" style="border-right: 1px solid #000000;">{{__('Tassa di Proprietà')}}</td>
        <td width="50%">{{__('Incluso')}}</td>
    </tr>
    @endif
    @if($parentOffer->hasService('messa-su-strada-e-immatricolazione'))
    <tr>
        <td width="50%" style="border-right: 1px solid #000000;">{{__('Immatricolazione e Messa su Strada')}}</td>
        <td width="50%">{{__('Incluso')}}</td>
    </tr>
    @endif
    @if($parentOffer->hasService('soccorso-stradale'))
    <tr>
        <td width="50%" style="border-right: 1px solid #000000;">{{__('Assistenza e soccorso stradale h24')}}</td>
        <td width="50%">{{__('Incluso')}}</td>
    </tr>
    @endif
    <tr>
        <td width="50%" style="border-right: 1px solid #000000;">{{__('Multe')}}</td>
        <td width="50%">{{__('Inoltro delle multe al cliente')}}</td>
    </tr>
    <tr>
        <td width="50%" style="border-right: 1px solid #000000;">{{__('Modalità invio fattura')}}</td>
        <td width="50%">{{__('Fatturazione elettronica')}}</td>
    </tr>
    @if($parentOffer->hasService('consegna-veicolo'))
    <tr>
        <td width="50%" style="border-right: 1px solid #000000;">{{__('Consegna veicolo')}}</td>
        <td width="50%">
            @if($parentOffer->homeDelivery)
                {{__('Consegna gratuita a casa')}}
            @else
                {{__('Ritiro del veicolo presso un service point')}}
            @endif
        </td>
    </tr>
    @endif
    @foreach ($proposal->services as $service)
        <tr>
            <td width="50%" style="border-right: 1px solid #000000;">{{ $service->name }}</td>
            <td width="50%">{{__('Incluso')}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
