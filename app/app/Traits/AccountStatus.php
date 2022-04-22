<?php
namespace App\Traits;

/**
 * Trait AccountStatus
 * @package App\Traits
 */
trait AccountStatus{

    /**
     * @var array
     */
    public static $_ALLOWED = ['ACTIVATED', 'SUSPENDED', 'DISABLED', 'PENDING_ACTIVATION'];

    /**
     * @param string $status
     * @return bool
     */
    public function updateStatus(string $status){
        if(in_array($status, self::$_ALLOWED) ){
            return $this->update(['status' => $status]);
        }
        return FALSE;
    }

    /**
     * Controlla se un account e' stato attivato
     * @return bool
     */
    public function isActive(){
        return strpos($this->status, 'ACTIVATED') !== FALSE;
    }

    /**
     * @return bool
     */
    public function setAsActive(){
        return $this->updateStatus('ACTIVATED');
    }

    /**
     * @return bool
     */
    public function suspend(){
        return $this->updateStatus('SUSPENDED');
    }

    /**
     * Ritorna l'elenco di tutti gli account attivi
     * @return mixed
     */
    public static function getEnabledAccounts(){
        return self::where('status', 'ACTIVATED')
                ->orWhere('status', 'PENDING_ACTIVATION')
                ->get();
    }
}
