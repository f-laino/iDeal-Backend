<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Group
 * @package App
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $logo
 * @property string|null $notification_email
 * @property integer|null $group_leader
 * @property float $fee_percentage
 * @property string|null $crm_settings
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 */
class Group extends Model
{
    use Notifiable;

    protected $table = 'groups';
    protected $guarded = ['id'];

    public static $_TYPES = [
        'DEALER' => 'Concessionario',
        'INSURANCE' => 'Assicurazione',
        'UTILITIES' => 'Utilities',
    ];

    public static $_DEFAULT_TYPE = 'DEALER';

    /**
     * Ritorna il caporete
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leader()
    {
        return $this->belongsTo('App\Models\Agent', 'group_leader');
    }

    /**
     * Ritorna gli agenti che fanno parte di questo gruppo
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agents()
    {
        return $this->hasMany('App\Models\Agent', 'group', 'id');
    }

    /**
     * Ritorna i preventivi associati ad agenti di questo gruppo
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function quotations()
    {
        $agents = $this->agents()->pluck('id');
        return Quotation::whereIn('agent_id', $agents->toArray())->get();
    }

    /**
     * Ritorna l'associazione con i servizi
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany('App\Models\Service', 'group_service');
    }

    /**
     * Attach one service to the group
     * @param int $serviceId
     * @return mixed
     */
    public function attachService($serviceId)
    {
        if (\DB::table('group_service')->where([['service_id', $serviceId], [ 'group_id', $this->id]])->exists()) {
            return true;
        }

        $timestamp = Carbon::now()->toDateTimeString();

        return  \DB::table('group_service')->insert([
            'service_id' => $serviceId,
            'group_id' => $this->id,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);
    }

    /**
     * Detach all group services
     * @return mixed
     */
    public function detachAllServices()
    {
        return \DB::table('group_service')->where('group_id', $this->id)->delete();
    }

    /**
     * Aggiorna i servizi aggiuntivi del gruppo
     */
    public function replaceAdditionalServices(array $services)
    {
        $this->detachAllServices();

        if (!empty($services)) {
            foreach ($services as $service) {
                $this->attachService($service);
            }
        }
    }

    /**
     * Associa tutti i servizi di default ad un gruppo
     */
    public function attachDefaultServices()
    {
        $services = Service::getDefaultServices();

        foreach ($services as $service) {
            $this->attachService($service->id);
        }
    }

    /**
     * Associa tutti i servizi a pagamento di default ad un gruppo
     */
    public function attachDefaultPaidServices()
    {
        $services = Service::getDefaultPaidServices();

        foreach ($services as $service) {
            $this->attachService($service->id);
        }
    }

    /**
     * Ritorna gli altri agenti del gruppo di un agente
     * @param Agent $agent
     * @return \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public static function getMembers(Agent $agent)
    {
        return $agent->isGroupLeader() ? $agent->myAgents : collect([$agent]);
    }

    /**
     * Ritorna la query per selezionare tutti gli agenti, compresi i cancellati
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getAllAgentsQuery()
    {
        return Agent::withTrashed()->where('group', $this->id);
    }

    /**
     * Ritorna l'elenco dei tipi di gruppo
     * @return array
     */
    public static function getTypes()
    {
        return array_keys(self::$_TYPES);
    }

    /**
     * Codifica i dati in JSON prima di assegnarli
     * @param string $value
     */
    public function setCrmSettingsAttribute($value): void
    {
        $this->attributes['crm_settings'] = json_encode($value);
    }

    /**
     * Decodifica i dati in JSON prima di assegnarli
     * @param string $value
     * @return mixed
     */
    public function getCrmSettingsAttribute($value)
    {
        return json_decode($value);
    }
}
