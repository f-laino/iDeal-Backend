<?php

namespace App\Http\Controllers\Cms;

use App\Models\Agent;
use App\Models\AgentToken;
use App\Models\CarCategory;
use App\Facades\Search;
use App\Models\Fuel;
use App\Models\Group;
use App\Http\Controllers\CmsController;
use App\Http\Requests\Cms\AgentStoreRequest;
use App\Http\Requests\Cms\AgentUpdateRequest;
use App\Notifications\ApiService\NewSubscription;
use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class AgentController
 * @package App\Http\Controllers\Cms
 */
class AgentController extends CmsController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $agents = Search::webAgents($request, self::$pagination);
        return view('agent.index', compact('agents'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        $groups = Group::pluck('name','id');
        return view('agent.create', compact('groups'));
    }


    /**
     * @param AgentStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function store(AgentStoreRequest $request){

        $agent = new Agent;
        $agent->business_name = $request->business_name;
        $agent->name = $request->name;
        $agent->email = $request->email;
        $agent->fee_percentage = $request->fee_percentage;
        $agent->phone = $request->phone;
        $agent->notes = $request->notes;
        $agent->contact_info = $request->contact_info;
        $logo = $request->file('logo', NULL);

        $agent->password = Hash::make(Str::random(12));

        if(!is_null($logo)){
            $fileName = $agent->getName() . '-' . Carbon::now()->timestamp;
            $image = $request->file('logo');
            $logo = Agent::uploadLogo($image, $fileName);
            $agent->logo = $logo;
        }

        try{
            /* Create agent */
            $agent->saveOrFail();

            /* Create agent on HubSpot */
            //$agent->createOnHubSpot();
            /* Non piu utile nel nuovo processo aziendale */

            if (!empty( $request->group )){
                $group = Group::findOrFail($request->group);
                if( empty( $group->group_leader) )
                    $group->update([
                        'group_leader' => $agent->id
                    ]);
                $agent->update([
                    "group" => $request->group,
                ]);
            }

            /* Create agent token */
            $token = AgentToken::generate($agent);
            /* Send agent email */
            $agent->sendWelcomeEmailNotification();
            /* End agent creation */

            /* Handle Offer Agents*/
            $filters = Agent::getDefaultFilters();
            $agent->storeFilters($filters);
            //associo le nuove offerte
            $agent->attachOffers();
            /* End Offer Agents*/

        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('agent.edit',[ 'agent' => $agent->id])->with('success', 'Agente aggiunto con successo');
    }

    /**
     * @param Agent $agent
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Agent $agent){
        $groups = Group::pluck('name','id');
        $brokersList = Offer::$BROKERS;
        $fuelsList = Fuel::asOptions();
        $categoriesList = CarCategory::asOptions();

        //get active filters values
        $filters = $agent->getFilters();
        $activeBrokers = isset($filters['brokers'])? $filters['brokers'] : [];
        $activeFuels = isset($filters['fuels'])? $filters['fuels'] : [];
        $activeCategories = isset($filters['categories'])? $filters['categories'] : [];

        $apiServiceToken = $agent->getApiServiceToken();

        return view('agent.edit',
            compact('agent', 'groups', 'brokersList', 'fuelsList', 'categoriesList',
            'activeBrokers', 'activeFuels', 'activeCategories', 'apiServiceToken'));
    }

    public function update(AgentUpdateRequest $request, Agent $agent){

        try{
            $groupRef = NULL;

            if ($agent->isGroupLeader() && $agent->myGroup->id !== $request->group) {
                $agent->myGroup()
                    ->update(['group_leader' => null]);
            }

            if (!empty( $request->group )){
                $group = Group::findOrFail($request->group);
                if( empty( $group->group_leader) )
                    $group->update([
                        'group_leader' => $agent->id
                    ]);
                $groupRef = $group->id;
            }

            $image = $request->file('logo', NULL);
            //Update agent local info
            $fields = [
                'business_name' => $request->business_name,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'fee_percentage' => $request->fee_percentage,
                'group' => $groupRef,
                'notes' => $request->notes,
                'contact_info' => $request->contact_info,
            ];
            $fileName = $agent->getName() . '-' . Carbon::now()->timestamp;

            if(!empty($image))
                $fields['logo'] = Agent::uploadLogo($image, $fileName);

            $agent->update($fields);

            /* Update Hubspot values */
            //$agent->updateOnHubSpot();
            /* Non piu utile nel nuovo processo aziendale */

        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('agent.edit',[ 'agent' => $agent->id])->with('success', 'Agente aggiornato con successo');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id){

        try{
            /** @var Agent $agent */
            $agent = Agent::findOrFail($id);
            $agent->deleteWithOffers();

        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
                ->route('agent.index')
                ->with('success', 'Agente aggiornato con successo');
    }

    /**
     * Sospende un account attivo
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function suspend($id){
        try{
            /** @var Agent $agent */
            $agent = Agent::findOrFail($id);
            if($agent->suspendAccount()){
                //dissocio le offerte dall'account
                $agent->detachOffers();

                //invalido tutti i token utente
                AgentToken::invalidateAll($agent);

                return redirect()->route('agent.edit',[ 'agent' => $agent->id])->with('success', 'Account sospeso con successo');
            }
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

    }

    /**
     * Attiva un account non attivo
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate($id){
        try{
            /** @var Agent $agent */
            $agent = Agent::findOrFail($id);
            if($agent->setAsActive()){
                //associo le offerte all'account
                $agent->attachOffers();
                return redirect()->route('agent.edit',[ 'agent' => $agent->id])->with('success', 'Account attivato con successo');
            }
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('agent.edit', ['agent' => $id])->with('error', "Impossibile attivato l'account selezionato");
    }


    /**
     * Gestisce l'update della sezione filtri
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function filters(Request $request,  $id){
        $brokers = $request->get('broker_filter', []);
        $fuels = $request->get('fuel_filter', []);
        $categories = $request->get('category_filter', []);

        $filters = [
            'brokers' => $brokers,
            'fuels' => $fuels,
            'categories' => $categories
        ];
        try{
            /** @var Agent $agent */
            $agent = Agent::findOrFail($id);
            $agent->storeFilters($filters);
            //rimuovo le offerte associate
            $agent->detachOffers();
            //associo le nuove offerte
            $agent->attachOffers();
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('agent.edit',[ 'agent' => $agent->id])->with('success', 'Filtri agente aggiornati con successo.');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function createApiServiceToken(Request $request, $id){
        try{
            /** @var Agent $agent */
            $agent = Agent::findOrFail($id);

            /* Create api service token */
            $token = AgentToken::generate($agent, AgentToken::$TYPES['API_SERVICE']);

            //Send user notification
            $agent->notify(new NewSubscription($agent, $token));

        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('agent.edit',[ 'agent' => $agent->id])->with('success', 'Funzionalità attivata con successo');
    }



}
