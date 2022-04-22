<?php

namespace App\Models;

use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class AgentToken
 * @package App
 * @property integer $agent_id
 * @property string $type
 * @property string $token
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class AgentToken extends Model
{
    public static $_LENGTH = 40;

    protected $table = "agent_tokens";

    protected $fillable = ['agent_id', 'token'];

    public static $TYPES = [
            'ACTIVATION' => 'ACTIVATION',
            'PASSWORD_RESET' => 'PASSWORD_RESET',
            'API_SERVICE' => 'API_SERVICE',
        ];

    public static $DEFAULT_TYPE = 'ACTIVATION';

    protected $hidden = [];

    /**
     * Ritorna l'associazione con l'agente
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo('App\Models\Agent', 'agent_id');
    }

    /**
     * Controlla la validita del token
     * @return bool
     */
    public function isExpired()
    {
        //TODO Sviluppare logica in futuro se neccessario
        return false;
    }

    /**
     * Genera un nuovo token
     * @param Agent $agent
     * @param string $type
     * @return AgentToken
     * @throws \Throwable
     */
    public static function generate(Agent $agent, $type = 'ACTIVATION')
    {
        $token = new self();
        $token->agent_id = $agent->id;
        $token->type = $type;
        $token->token =  Str::random(self::$_LENGTH);
        $token->saveOrFail();
        return $token;
    }

    /**
     * Cancella il record del token
     * @return bool|null
     */
    public function delete()
    {
        return self::where('token', $this->token)->delete();
    }

    /**
     * Invalida tutti i token associati ad un utente
     * @param \App\Agent $agent
     * @return bool|null
     */
    public static function invalidateAll(Agent $agent)
    {
        return self::where('agent_id', $agent->id)->delete();
    }
}
