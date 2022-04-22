<?php
namespace App\Traits;

/**
 * Trait Filters
 * @package App\Traits
 */
trait Filters{

    /**
     * Ritorna l'elenco dei filtri
     * @return array
     */
    public function getFilters(){
        $filters = [];
        if (!empty($this->filters)) {
            foreach (json_decode($this->filters) as $key => $values){
                $filters[$key] = explode(',', $values);
            }
        }
        return $filters;
    }

    /**
     * Salva i filtri attivi
     * @param array $filters
     * @return mixed
     */
    public function storeFilters(array $filters){
        $list = [];
        foreach ($filters as $key => $values){
            $list[$key] = implode(',', (array)$values);
        }
        return $this->update( [ "filters" => json_encode($list) ] );
    }

    /**
     * Aggiorna i filtri attivi
     * @param array $filters
     * @return mixed
     */
    public function updateFilters(array $filters){
        return $this->storeFilters($filters);
    }


    /**
     * Rimuove i filteri attivi
     * @return mixed
     */
    public function removeFilters(){
        return $this->update( [ "filters" => NULL ] );
    }


}
