<?php
namespace App\Traits;
use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

trait AttachableAccount
{

    /**
     * Associa un'offerta all'agente
     * @param Agent $agent
     * @param bool $status
     * @return bool
     */
    public function attach(Agent $agent, $status = TRUE){
        $exists = \DB::table('agent_offer')
                ->where([['agent_id', $agent->id], [ 'offer_id', $this->id]] )
                ->exists();
        //Se l'associazione gia esiste fai nulla
        if($exists) return TRUE;

        $canAttach = TRUE;
        $timestamp = Carbon::now()->toDateTimeString();

        //Se l'offerta e caricata dal cliente ingora i filtri
        if(!empty($this->owner_id)){
            return \DB::table('agent_offer')->insert([
                'agent_id' => $agent->id,
                'offer_id' => $this->id,
                'status' => $status,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
        }

        //controllo filtri offerta
        try{
            $filters = $agent->getFilters();
            $car = $this->car;

            if(isset($filters['brokers'])){
                $canAttach &= in_array($this->broker, $filters['brokers']);
            }
            if(isset($filters['fuels'])){
                $canAttach &= in_array($car->fuel->slug, $filters['fuels']);
            }
            if(isset($filters['categories'])){
                $canAttach &= in_array($car->category->slug, $filters['categories']);
            }

        } catch (Exception $exception){
            Log::error("Trying to associate offer when agent filter exception", [
                'offer_id' => $this->id,
                'agent_id' => $agent->id,
                'exception' => $exception->getMessage(),
                'trace' => $exception->getTrace(),
            ] );
            $canAttach = TRUE;
        }
        //end controllo filtri

        if($canAttach)
           return \DB::table('agent_offer')->insert([
                'agent_id' => $agent->id,
                'offer_id' => $this->id,
                'status' => $status,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);

        return FALSE;
    }

    /**
     * Remove the agent related with this offer
     * @param Agent $agent
     * @return mixed
     */
    public function detach(Agent $agent){
        return \DB::table('agent_offer')
            ->where('offer_id', $this->id)
            ->where('agent_id', $agent->id)
            ->delete();
    }

    /**
     * Associa un elenco di agenti ad un'offerta
     * @param Collection $agents
     * @param bool $status
     * @return bool|mixed
     */
    public function attachAgents(Collection $agents, $status = TRUE){
        $store = TRUE;
        $nAttached = 0;

        Log::info('attachAgents offer ' . $this->id, [ 'agents number' => $agents->count() ]);

        foreach ($agents as $agent) {
            $store &= $this->attach($agent, $status);
            $nAttached = $store ? $nAttached + 1 : $nAttached;
        }

        Log::info('attachAgents offer ' . $this->id, [ 'agents attached' => $nAttached ]);

        return $store;
    }

    /**
     * Remove all agents related with this offer
     * @return mixed
     */
    public function detachAgents(){
        return \DB::table('agent_offer')->where('offer_id', $this->id)->delete();
    }

}
