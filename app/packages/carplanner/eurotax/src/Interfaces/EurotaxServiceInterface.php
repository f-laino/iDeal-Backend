<?php
namespace CarPlanner\Eurotax\Interfaces;

 interface EurotaxServiceInterface{

     /**
      * Restituisce l'elenco dei brand presenti nel sistema
      * @return mixed Elenco dei brand
      */
     function getBrands();

     /**
      * Restituisce l'elenco dei modelli relativi all'anno ed al brand
      * @param $aconimoBrand Aconimo brand resituito da getBrands.
      * @param $anno Parametro opzionale da utilizzare solo in caso di permuta
      * @return mixed Resituisce l'elnco dei modelli
      */
     function getModelli( $aconimoBrand, $anno);

     /**
      * Restituisce l’elenco delle alimentazioni dato il modello e l’anno
      * @param $codGammaModello cod_gamma_mod resituito dal getModelli
      * @param $anno opzionale da utilizzare solo in caso di permuta
      * @return mixed
      */
     function getAlimentazioni( $codGammaModello, $anno);

     /**
      * Restituisce l'elenco delle versioni relative al modello
      * @param $codGammaModello cod_gamma_mod restituito dal metodo getModelli
      * @param $aconimoBrand Aconimo brand resituito da getBrands.
      * @param $anno opzionale
      * @param $codiceAlimentazione opzionale codice restituito da getAlimentazioni
      * @return mixed
      */
     function getVersioni( $codGammaModello, $aconimoBrand, $anno, $codiceAlimentazione);

     /**
      * Restituisce l'elenco delle immagini per una data versione versione selezionata
      * @param $codiceMotornet codice_motornet restituito dal metodo getVersioni
      * @param $codiceEurotax codice_eurotax restituito dal metodo getVersioni
      * @param $codiceVisuale opzionale viene utilizzato per indentificare la tipologia di fotografia
      * @param $resolution (L = LOW, M = MEDIUM, H = HIGH) opzionale ritorna le solo foto nella risoluzione indicata
      * @return mixed
      */
     function getImmagini( $codiceMotornet, $codiceEurotax, $codiceVisuale, $resolution );

     /**
      * Restituisce i dati tecnici di un automobile
      * @param $codiceMotornet codice_motornet restituito dal metodo getVersioni
      * @param $codiceEurotax codice_eurotax restituito dal metodo getVersioni
      * @return mixed
      */
     function getDettaglioAuto($codiceMotornet, $codiceEurotax );

 }