<?php
namespace App\Interfaces\Crm;

/**
 * Interface Fields
 * @package App\Interfaces\Crm
 */
interface Fields
{
    /**
     * Ritorna la chiave di un di un campo a partire dalla sua label.
     * Ogni crm gestice i campi degli ogetti associando delle chiavi generati in modo differente
     * @param array $fields
     * @param string|int $label
     * @return string
     */
    public function getFieldKey(array $fields, $label): string;

    /**
     * Ritorna la label di un campo a partire dal
     * valore della chiave di quel campo
     * @param array $labels
     * @param $value
     * @return string
     */
    public function getFieldLabelByValue(array $labels, $value): string;
}
