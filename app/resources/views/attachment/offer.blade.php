<!DOCTYPE html>

<html lang="it">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <meta content="width=device-width" name="viewport"/>
    <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
    <title>Preventivo - {{ $carName }}</title>

    <style type="text/css">
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
        }

        table{
            width: 100%;
            border-spacing: 0px;
        }

        .quotation-author p{
            line-height: 4px;
            font-size: 12px;
        }

        .quotation-car{
            text-align: center;
            max-width: 70%;
            margin-left: auto;
            margin-right: auto;
        }

        .quotation-car p{
            line-height: 10px;
            font-size: 15px;
        }

        .quotation-car .price{
            font-size: 20px;
            margin-top: 0px;
        }

        .quotation-car .price span{
            font-size: 14px;
        }

        .services{
            padding-top: 10px;
            margin-left: auto;
            margin-right: auto;
            padding-bottom: 10px;
            width: 80%;
        }


        .services td{
            padding-left: 10px;
            line-height: 15px;
            font-size: 12px;
        }

        .accessories{
            font-size: 12px;
            text-align: center;
        }

        .terms{
            padding: 10px;
            font-size: 10px;
            position: absolute;
            bottom: -25px;
            height: 100px;
        }

        hr{
            border: none;
            height: 1px;
            width: 30%;
            color: #000000;
            background-color: #000000;
            margin-right: auto;
            margin-left: auto;
        }

        h5{
            font-size: 14px;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 4px;
        }
        h4{
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
            margin-top: 4px;
        }
        ul{
            width: 70%;
            max-width: 70%;
            margin-top: 5px;
            text-align: center;
            margin-right: auto;
            margin-left: auto;
        }
        ul > li{
            display:inline;
        }


    </style>
</head>

<body>
<table>
    <tbody>
    <tr>
        <td width="10%"></td>
        <td width="50%">
            <strong>
                @if(!empty($customerName))
                Spett.le<br/>{{ $customerName }}
                @endif
            </strong>
        </td>
        <td width="40%">
            <div class="quotation-author">
                <img
                    src="{{$agent->logo()}}"
                    width="150px"
                />
                <p style="margin-top: 10px">
                    <strong>{{__('Data')}}:</strong> {{ $date }}
                </p>
                <p>
                    <strong>{{__('Telefono')}}:</strong> {{ $agent->phone  }}
                </p>
                <p>
                    <strong>{{__('E-Mail')}}:</strong> {{ $agent->email }}
                </p>
            </div>
        </td>
    </tr>
    </tbody>
</table>

<div class="quotation-car">
    <p>
        <strong>{{ $carName }}</strong>
    </p>
    <p>
        {{ $carModel }}
        @if(!empty($color))
            &nbsp;{{ $color }}
        @endif
    </p>
    <img
        src="{{ $image }}"
        height="150px"
        style="margin-top: 20px;"
    />
    <p class="price">
        {{ number_format($monthlyRate, 0) }} &euro; <span>{{__('iva esclusa')}}</span>
    </p>
    <p>
        <strong>{{__('Durata')}}:</strong> {{$offer->duration}} {{__('mesi')}} |
        <strong>{{__('Km/Anno')}}:</strong> {{ number_format($offer->distance, 0, '.', '.') }} |
        <strong>{{__('Anticipo')}}:</strong> {{ number_format($offer->deposit, 0, '.', '.') }}&euro;
    </p>
</div>

<div class="services">
    @include('attachment.partials.services')
</div>
<div class="accessories">
    <h4>Principali equipaggiamenti:</h4>
    <ul>
        @foreach($accessories as $key => $item)
            <li>{{$item}}{{$key == $limit - 1 ? '' : ' ·' }}</li>
        @endforeach
    </ul>
    <hr/>
</div>
@if(!empty($accessoriesList))
    <div class="accessories">
        <h4>Optional scelti:</h4>
        <ul>
            @foreach($accessoriesList as $key => $item)
                <li>{{$item}}{{$key == count($accessoriesList) - 1 ? '' : ' ·' }}</li>
            @endforeach
        </ul>
        <hr/>
    </div>
@endif

@if(!empty($documents))
<div class="accessories">
    <h4>Documenti richiesti:</h4>
    <ul>
        @foreach($documents as $key => $document)
            <li>{{$document['title']}}{{$key == $limit - 1 ? '' : ' ·' }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="terms">
    <h5>TERMINI E CONDIZIONI</h5>
    <p style="margin-top: 0;">
        Offerta economica limitata valida fino ad esaurimento scorte,  da considerarsi unicamente a titolo informativo ed in ogni caso subordinata alla effettiva disponibilità del veicolo sopra descritto. Il presente documento non costituisce offerta contrattuale. I canoni sono stati formulati in base ai listini ufficiali delle Case Costruttrici attualmente in vigore, incluse le dotazioni di serie e potrebbero essere suscettibili di variazioni. Tutti gli importi sono da intendersi IVA esclusa. L‘imposta da applicare è pari al 22% salvo differenti disposizioni di legge.
    </p>
</div>

</body>
</html>
