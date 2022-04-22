<?php

namespace App\Http\Controllers\Crm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as DefaultController;

class Controller extends DefaultController
{
    public function __construct()
    {
        $this->middleware('request.auth');
    }

    protected function getDealFieldKey($value){
        $dealFields = [
            "title" => "title",
            "user_id" => "user_id",
            "person_id" => "person_id",
            "org_id" => "org_id",
            "stage_id" => "stage_id",
            "status" => "status",
            "visible_to" => "visible_to",
            "value" => "value",
            "active" => "active",
            "deleted" => "deleted",
            "lost_reason" => "lost_reason",
            "24457430cb5492928d759a63263548570d33fa2d" => "brand",
            "0dc28262bcf8b1aa81ce349c8c2810d59c4f3bbf" => "model",
            "8deac6cf5f9debfa4364ee0453975897c36b8d20" =>"source" ,
            "189a1a7863d3ca6b02492f12885a99cfb8236136" => "referrer",
            "22f86c0f186baeb5be4ac5f459ad93748146376d" => "medium",
            "b46017cd82577d4c4bb96f649f1f8d4903856a0a" => "documenti_inviati_intermediario",
            "fcab4389284d610a96509c3270fea8452282b59d" => "anticipo",
            "141f9ce569227a1533b2ebdaf4827d1b6f24ec14" => "durata",
            "647de315ffd074a6bd2d9c402a53ccf085d82503" => "distanza",
            "e326f4915b5135977c8d4328a26f5cc69b96d424" => "canone_mensile_no_iva",
            "7a038512cb6da5ec171c27d5ec754bb53efd3802" => "note",
            "885ac7290537f283cab9f5b55b58c76b2f45d22b" => "franchigia_kasko",
            "b0c5c2209a1ce81935c0312b58f0aa27ae499d74" => "franchigia_furto_incendio",
            "560a13164b9cb5378df3d802a7010f0fd16ba4b5" => "auto_sostitutiva",
            "a4d2f933cc0015d45374c410edec8aa43a9ed6d2" => "cambio_gomme",
            "a83395d56a2a4f522f2a297a69eb3696ab8da8d3" => "optional_richiesti",
            "f32462b9410c6c5aa6c7ec275b7ebaf5dc190a0f" => "durata_richiesta",
            "525618a0c3bdfbbe339988cf0d16a056ffd7310d" => "anticipo_richiesto",
            "6f9f3fb8c11f2a18179129433479b876ede0b3a4" => "nuovo_canone_relativo_anticipo",
            "95605c6c32ba11c2c8cce98f670f82483b9a1d28" => "km_anno_richiesti",
            "46640d7018d2736a5736cb10968f933fe8995613" => "nuovo_anticipo",
            "d5ab101072fa5d484fcc644b967ff6dc3c1403ea" => "nuovo_canone_mensile",
            "dc8894c5fa32d8000a432edeeafe2d73c7386286" => "priorita",
            "1f0eccb34c0c6334d5d6ff3ec7887fa2446b5b16" => "salesbot",
        ];
        $value = (string)$value;
        return $dealFields[$value];
    }

    protected function getCustomerFieldKey($value){
        $customerFields = [
            "name" => "name",
            "owner_id" => "owner_id",
            "org_id" => "org_id",
            "email" => "email",
            "phone" => "phone",
            "visible_to" => "visible_to",
            "be0733b7722dbcbffa0c519965af18bbaad14932" => "address",
            "286018265854db941614c7eb964377ae98207a1f" => "fiscal_code",
            "bf2eedc1cee113bce7a175a1d5fb68d9f90cd576" => "vat_number",
            "b05a4a67da780fc703c08b700d0948ca60610d8b" => "company_name",
            "60f9c10bae32d233d735fda1fa1a7f3afbf7ba74" => "contractual_category",
            "a8328afe7f73094f0b1465f40a131182c8a9c013" => "tempo_consegna_richiesto",
            "5774fe998fe6edbe23160ac28aa5a8c5cffd30ff" => "garante",
            "a08d304b7f490aa036a9fbcab4a7970897f6f0dd" => "altro_intestatario",
            "4faf9bacd6f56dce3926eb1dbadd925eaf8f5cc2" => "protestato",
            "889040dfe6d565ea902342bef08c8a5258f88888" => "reddito_netto_mensile",
            "3fb90f5fcc18fe87373eec8bd4f15df494551ba1" => "prestiti_personali",
            "88bbdf1c866652bf0cae3ab40538397d4f7a9d70" => "fatturato_annuo",
            "4bf286190b72252fbfff53f59f81574220464260" => "utile_annuo",
            "4298bec9bf3028b0d9f52430766370adfab8d272" => "rata_sostenibile",
        ];

        $value = (string)$value;
        return $customerFields[$value];
    }



}
