<?php
namespace App\Common\Models\Crm;

use App\Models\Quotation;

class RentMax extends IDeal
{
    /**
     * @inheritDoc
     */
    public $customerFields = [
        "name" => "name",
        "owner_id" => "owner_id",
        "org_id" => "org_id",
        "email" => "email",
        "phone" => "phone",
        "visible_to" => "visible_to",
        "address" => "19fe3a82a75a98ebe53a6ad190d7fa1194755362",
        "fiscal_code" => "a31230670c89008760a076c5764fe987a4073d77",
        "vat_number" => "82a8f8cabf6200890c0f98512cd49799b93a1485",
        "company_name" => "2f15abcae8d98a8cbe4d9eec1fb6ac8c095ca241",
        "contractual_category" => "517323a78ea4a62b1709217124408944841da639",
        "tempo_consegna_richiesto" => "64149fed0effdcce7adaf1caeaba1f0ed78471d0",
        "garante" => "ff2fd29188593c52536ddc323775ad15f331a78f",
        "altro_intestatario" => "98bbea70862418dadc639dbaeeddb2ae6ff2dcab",
        "protestato" => "5b612254a8592b646d00f1897a92a9d0b90b00fc",
        "reddito_netto_mensile" => "5b612254a8592b646d00f1897a92a9d0b90b00fc",
        "prestiti_personali" => "77c9541266fd8d8b8fce9ac071044327cc70762f",
        "fatturato_annuo" => "05fe93006ee19692f8597c0f9d31917eeb187850",
        "utile_annuo" => "23645c06b96040af1262bbf21a078ca17264d1df",
        "rata_sostenibile" => "774437a9a6a5ef8f454591c47a2dc82a23fad3c0",
    ];

    /**
     * @inheritDoc
     */
    public $dealFields = [
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
        "brand" => "71815f265184577a232850dc96d4b1aea40fae33",
        "model" => "cde7576c9a3765bfed542b1a257d7d4559276574",
        "source" => "f2bae2c5d63efbe87354ae470fe471200ed2367d",
        "referrer" => "d7fe5727b8b51af598a70636d1a80c7fddb2cbe8",
        "medium" => "c9c4ad0830f2164befe539abec619d8792f14ab3",
        "documenti_inviati_intermediario" => "0d642711c423dea843f55ba96800b97261662355",
        "anticipo" => "c12c8cd765fdc808c4873fb00bb5a684d671a905",
        "durata" => "6c85d27f6f2f31660f9a094ac4c4b19a0cc0b48f",
        "distanza" => "46d4b1b8b16ac533367aa89db93acbc784028610",
        "canone_mensile_no_iva" => "abc62abf4cdd9cbe9b399f687a6dba2715f57dab",
        "note" => "d465856b7eb39762dd9ce4aa99bb685d7f99f380",
        "franchigia_kasko" => "89ea93a32d01c4382103acecdd5eedc30dd063f6",
        "franchigia_furto_incendio" => "77b14d444b1aa8fd929b6b123f91111b3f7d6a7b",
        "auto_sostitutiva" => "18eaa62a817f7ae677c164ea6d732214ecc33642",
        "cambio_gomme" => "2f61807b8546428289df87d274c2f74b70d6d579",
        "bollo_auto" => "8d85c9dda6a598506cf2af26060b89be31f8c1a8",
        "optional_richiesti" => "98af8a95b4f329919fb0398eb5540114a19d7984",
        "durata_richiesta" => "98af8a95b4f329919fb0398eb5540114a19d7984",
        "anticipo_richiesto" => "2c1733ecfa3848578195752b6c9959f8b8787320",
        "nuovo_canone_relativo_anticipo" => "dac7c2d2032058ea73cf8f128a09a57d29714024",
        "km_anno_richiesti" => "cf93d53dbbf75849637aa352516b3637a094b3fa",
        "nuovo_anticipo" => "aab66761c067b517677750bab372afd22928866c",
        "nuovo_canone_mensile" => "3fda6707947206d77b28490adea7fd5df0551209",
        "priorita" => "471534984522490bed0d7a71ef91ed0fb305ba0f",
        "salesbot" => "1cafb7f330b05dd5253d870202a1b6db28c648af",
    ];

    /**
     * @inheritDoc
     */
    public $organizationFields = [
        "name" => "name",
        "visible_to" => "visible_to",
        "owner_id" => "owner_id",
        "agente" => "495696ccfc30b61b01f21bfdd1316cae4421f319",
        "email" => "917f3de3aa3d7366bd1ef745ea2c75300952cc81",
        "telefono" => "b55b907b215dec3ac0bda3a4f548eda561fd7732",
    ];

    /**
     * @inheritDoc
     */
    public $productFields = [
        "name" => "name",
        "prices" => "prices",
        "code" => "code",
        "visible_to" => "visible_to",
        "brand" => "e7d3196a7083b8cff8423bf6adccc5deb1694b5a",
        "model" => "bdf18c0949995b7b2219dc8ac6a1ae4583de9fb4",
        "broker" => "dede84179a35734cd57d0c1740ec8830947a2d4b",
        "anticipo" => "fcb2d6d475faa1a7ce24d322d71242a39f5cbf57",
        "distanza" => "6c3b134fa2b15a934bf02da9011dad1ef575d392",
        "durata" => "09450803113443bff22b2af8b3b21128ed9b5335",
        "allimentazione" => "a4a83f6c571728451bf82cf357929fe20530d1b5",
    ];

    /**
     * @inheritDoc
     */
    public $dealStages = [
        1 => "Nuova Richiesta",
        2 => "Nuova Richiesta",
        3 => "Nuova Richiesta",
        4 => "Nuova Richiesta",
        5 => "Nuova Richiesta",
        6 => "Nuova Richiesta",
        7 => "Nuova Richiesta",
        8 => "Scoring",
        9 => "Attesa di Contratto",
        10 => "Completato",
    ];

    /**
     * @inheritDoc
     */
    public $dealMutableStages = [1, 4, 5, 6];

    /**
     * @inheritDoc
     */
    protected function getDurationId(Quotation $quotation)
    {
        return  $quotation->proposal->duration;
    }

    /**
     * @inheritDoc
     */
    public function transformStage($crm_stage): int
    {
        switch($crm_stage){
            case 9:
                return 5;
                break;
            case 8:
                return 4;
                break;
            case 7:
                return 3;
                break;
            case 6:
            case 5:
                return 2;
                break;
            default:
                return 1;
                break;
        }
    }
}
