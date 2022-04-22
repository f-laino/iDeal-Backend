
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{$title}}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        ul {
            list-style: none;
        }

        ul li::before {
            content: "\2022";
            color:#376eb5;;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -35px;
        }
        td {padding: 0;}

    </style>
</head>
<body style="max-height: 842px; max-width: 595px; margin: 0 auto; font-family: 'Montserrat', sans-serif; color: #555555;">
<div style="width: 100%; max-height: 102px;">
    <img src="{{$agent->getLogo()}}" style="max-height: 90px">
</div>
<div style="text-align:left;padding: 0 24px; margin-bottom: 20px;">
    <div style="display: block; font-size: 16px; line-height: 16px;">
        Scopri le <strong>migliori offerte</strong> di questa settimana per il <strong>Noleggio a Lungo Termine</strong> presso <strong>{{$agent->getNomeCompleto()}}</strong>.
    </div>
    <div style="padding-top: 15px; font-size: 13px; text-align: left;">
        <span style="display: block;">
                <span style="display: inline-block; font-weight: bold;">Un unico canone mensile che comprende:
                </span>
                <ul style="list-style: none; margin-top: 0; line-height: 13px; width: 100%;">
                    <li>Assicurazione RCA, furto/incendio e Kasko integrale.</li>
                    <li>Bollo auto.</li>
                    <li>Manutenzione ordinaria e straordinaria.</li>
                </ul>
            </span>

        <span style="display: block;">
                Ti aspettiamo in sede oppure contattaci per maggiori informazioni.
            </span>
    </div>
</div>
@foreach($offers as $offer)
    @php
        $car = $offer->car;
        $carName = $car->brand->name . " " . $car->descrizione_serie_gamma;
        $image = $offer->getNewsletterImage();
    @endphp
    <img src="https://cdn1.carplanner.com/icons/divisore.jpg" style="width: 595px;">
    <div style="padding: 10px 52px">
        <table style="border-spacing: 0;">
            <tr style="width: 595px;">
                <td style="width: 50%; text-align: center;">
                    <img src="{{ $image->path }}" style="max-height: 150px;">
                </td>
                <td style="width: 50%; padding-left: 30px;">
                    <h2 style="color: #376eb5; font-size: 17px; font-weight: 800; margin: 0 0 10 0;">{{ $carName }}</h2>
                    <span style="display: inline-block; font-size: 11px; font-weight: bold; color: #376eb5">{{$offer->duration}} mesi - {{ number_format($offer->distance, 0, '.', '.') }} km/anno<br>
                            € {{ number_format($offer->deposit, 0, '.', '.') }} anticipo - € {{ number_format($offer->monthly_rate, 0) }} al mese</span>
                </td>
            </tr>
        </table>
    </div>
@endforeach
<img src="https://cdn1.carplanner.com/icons/divisore.jpg" style="width: 595px;">
<div style="text-align: left; line-height: 10px; font-size: 10px; padding: 20px 24px;">
    Il Noleggio a Lungo Termine incluso nel canone mensile ti offre: assicurazione (RCA, furto e incendio, kasko, infortunio conducente, cristalli, danni accidentali, eventi naturali ed eventi socio-politici); bollo auto; manutenzione ordinaria e straordinaria; assistenza e soccorso stradale h24.I prezzi indicati sono da considerarsi iva esclusa.L’offerta è valida fino ad esaurimento scorte.
</div>
<div style="display: block; width: 100%; text-align: center; margin: 0 auto; color: #376eb5; font-weight: 500; font-size: 13px;">
    {{$agent->contact_info}}
</div>

</body>
</html>
