
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

    </style>
</head>
<body style="max-height: 842px; max-width: 595px; margin: 0 auto;font-family: 'Montserrat', sans-serif;; color: #555555; ">
<div style="width: 100%; max-height: 102px;">
    <img src="{{$agent->getLogo()}}" style="max-height: 90px">
</div>
<div style="text-align:left;padding: 0 24px; margin-bottom: 40px;">
    <div style="font-size: 16px; width: 100%;">
        <strong>{{$agent->getNomeCompleto()}}</strong> è <strong>{{$group->type != 'INSURANCE' ? 'certificato' : 'certificata' }}</strong> {{$group->type != 'INSURANCE' ? 'venditore' : 'venditrice' }} di <strong>Noleggio a Lungo Termine</strong>.
    </div>
    <div style="padding-top: 34px; font-size: 13px; text-align: left;">
        <span style="display: block; width: 100%;">
                <span style="display: block; font-weight: bold;">Un unico canone mensile che comprende:
                </span>
                <ul style="list-style: none; margin-top: 0;">
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
    <div style="padding: 34px 52px">
        <table style="border-spacing: 0;">
            <tr style="width: 595px;">
                <td style="width: 50%; text-align: left;">
                    <img src="{{ $image->path }}" style="max-height: 150px;">
                </td>
                <td style="width: 50%;">
                    <h2 style="color: #376eb5; font-size: 20px; font-weight: 800;">{{ $carName }}</h2>
                    <span style="display: inline-block; font-size: 13px; font-weight: bold; color: #376eb5">{{$offer->duration}} mesi - {{ number_format($offer->distance, 0, '.', '.') }} km/anno<br>
                            € {{ number_format($offer->deposit, 0, '.', '.') }} anticipo - € {{ number_format($offer->monthly_rate, 0) }} al mese</span>
                </td>
            </tr>
        </table>
    </div>
@endforeach
<img src="https://cdn1.carplanner.com/icons/divisore.jpg" style="width: 595px;">
<div style="text-align: left; line-height: 10px; font-size: 10px; padding: 40px 24px;">
    Il Noleggio a Lungo Termine incluso nel canone mensile ti offre: assicurazione (RCA, furto e incendio, kasko, infortunio conducente, cristalli, danni accidentali, eventi naturali ed eventi socio-politici); bollo auto; manutenzione ordinaria e straordinaria; assistenza e soccorso stradale h24.I prezzi indicati sono da considerarsi iva esclusa.L’offerta è valida fino ad esaurimento scorte.
</div>
<div style="display: block; width: 100%; text-align: center; margin: 0 auto; color: #376eb5; font-weight: 500; font-size: 13px;">
    {{$agent->contact_info}}
</div>

</body>
</html>
