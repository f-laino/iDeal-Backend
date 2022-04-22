<?php

namespace App\Http\Controllers\Cms;

use App\Models\Agent;
use App\Models\CrmConnection;
use App\Facades\Search;
use App\Models\Group;
use App\Models\Service;
use App\Http\Controllers\CmsController;
use App\Http\Requests\Cms\GroupCreateRequest;
use App\Http\Requests\Cms\GroupUpdateRequest;
use App\Models\Offer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class GroupController extends CmsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = Search::webGroups($request, self::$pagination);
        return view('group.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Group::$_TYPES;
        return view('group.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupCreateRequest $request)
    {
        $group = new Group;
        $group->name = $request->name;
        $group->type = $request->type;
        $group->notification_email = $request->notification_email;
        $group->fee_percentage = floatval($request->fee_percentage);
        $logo = $request->file('logo', NULL);

        if(!is_null($logo)){
            $fileName = $request->get($request->name, $group->name);
            $image = $request->file('logo');
            $logo = Agent::uploadLogo($image, $fileName);
            $group->logo = $logo;
        }

        try{
            $group->saveOrFail();

            $group->attachDefaultServices();

            $group->attachDefaultPaidServices();
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('group.edit',[ 'group' => $group->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('group.edit', ['group'=>$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $agents = new Collection;
        $collection = $group->agents;
        foreach ($collection as $item) {
            $agents[$item->id] = $item->getName();
        }
        $agents->prepend('Seleziona', '' );
        $types = Group::$_TYPES;

        $additionalServices = Service::getPaidServices();

        $brokers = Offer::$BROKERS;
        $connections = CrmConnection::pluck('name', 'id');
        $connections->prepend('Default', 0);

        $selectedConnection = NULL;
        $selectedBrokers = [];

        $selectedServices = $group->services->pluck('id');

        if(!empty($group->crm_settings)){

            /** @var stdClass $settings */
            $settings = $group->crm_settings;
            if(!empty($settings->connection))
                $selectedConnection = $settings->connection;

            if(!empty($settings->rules)){
                $rules = (array)$settings->rules;
                if(!empty($rules['brokers'])){
                    $selectedBrokers = $rules['brokers'];
                }
            }
        }

        return view('group.edit',
            compact('group', 'agents', 'types', 'brokers', 'connections', 'selectedBrokers', 'selectedConnection', 'additionalServices', 'selectedServices')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupUpdateRequest $request, Group $group)
    {

        $fileName = $request->get($request->name, $group->name);
        $image = $request->file('logo', NULL);

        try{
            $fields = [
                'name' => $request->name,
                'notification_email' => $request->get('notification_email', NULL),
                'group_leader' => $request->group_leader,
                'fee_percentage' => floatval($request->fee_percentage),
                'type' => $request->type,
            ];

            if(!empty($image))
                $fields['logo'] =  Agent::uploadLogo($image, $fileName);

            $group->update($fields);

            $group->replaceAdditionalServices($request->services);
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('group.edit',[ 'group' => $group->id])->with('success', 'Gruppo aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            /** @var  Group $group */
            $group = Group::findOrFail($id);
            $agents = $group->getAllAgentsQuery()->count();
            if($agents == FALSE){
                $result = Group::destroy($id);
                if ($result)
                    return redirect()->route('group.index')->with('success', 'Gruppo eliminate con successo');
            }
        } catch (ModelNotFoundException $exception){
            $result = Group::destroy($id);
            if ($result)
                return redirect()->route('group.index')->with('success', 'Gruppo eliminate con successo');
        }

        return redirect()->route('group.edit',[ 'group' => $group->id])->with('error', "Impossibile eliminare il gruppo. Assicurarsi che il gruppo non abbia alcun agente associato. $agents agenti trovati!");
    }

    public function filters(Request $request,  $id){
        $connection = $request->get('crm_connection', NULL);
        $brokers = $request->get('crm_broker', []);
        $crm_setting = NULL;

        try {
            /** @var Group $group */
            $group = Group::findOrFail($id);

            if(!empty($connection)){
               $crm_setting = [
                   "connection" => $connection,
                   "rules" => ["brokers" => $brokers]
               ];
            }
            $group->update( ["crm_settings" => $crm_setting] );
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('group.edit',[ 'group' => $id])->with('success', 'Impostazioni CRM aggiornate con successo.');

    }


}
