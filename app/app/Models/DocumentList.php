<?php

namespace App\Models;

/**
 * Class DocumentList
 * @package App
 * @property integer $id
 * @property integer $contractual_category_id
 * @property string $broker
 * @property integer $document_id
 * @property string|null $title
 * @property string|null $link
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class DocumentList
{
    private $slug;


    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @param mixed $docList
     *
     * @return array
     */
    private function toArray($docList)
    {
        $list = [];
        foreach ($docList as $doc) {
            $list[] = [
                "type" => $doc,
                "filesNumber" => 0,
            ];
        }

        return $list;
    }

    public function getAll()
    {
        $docList = [];
        switch ($this->slug) {
            case 'tempo-indeterminato':
                $docList = [
                    'carta_identita', 'tessera_sanitaria', 'buste_paga', 'moedllo_cud', 'modulo_privacy'
                ];
                break;
            case 'pensionato':
                $docList = ['carta_identita', 'tessera_sanitaria', 'cedolino_pensione', 'modello_730', 'moedllo_cud', 'modulo_privacy'];
                break;
            case 'libero-professionista':
                $docList = [
                    'carta_identita', 'tessera_sanitaria', 'modello_unico_perone_fisiche', 'ultimo_modello_iva', 'modello_irap',
                    'tesserino_iscrizione_albo', 'modulo_privacy'
                ];
                break;
            case 'ditta-individuale':
                $docList = [
                    'carta_identita', 'tessera_sanitaria', 'modello_unico_perone_fisiche',
                    'ultimo_modello_iva', 'visura_cciaa', 'modello_irap', 'modulo_privacy'
                ];
                break;
            case 'societa-persone':
                $docList = ['carta_identita', 'tessera_sanitaria', 'ultimo_bilancio', 'modulo_privacy'];
                break;
            case 'societa-capitale':
                $docList = ['carta_identita', 'tessera_sanitaria', 'ultimo_bilancio', 'modulo_privacy'];
                break;
            case 'associazioni-enti-fondazioni':
                $docList = ['carta_identita', 'tessera_sanitaria', 'modello_unico_enti_non_commerciali',
                    'atto_costitutivo_statuto', 'rendiconto_finanziario', 'statuto', 'modulo_privacy'
                    ];
                break;
            case 'studi-associati':
                $docList = [
                    'carta_identita', 'tessera_sanitaria', 'modello_unico_societa_persone', 'atto_costitutivo',
                    'statuto', 'modulo_privacy'
                ];
                break;
            default:
                break;
        }

        return $this->toArray($docList);
    }
}
