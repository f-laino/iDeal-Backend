<?php

namespace App\Models;

use App\Constants\HubSpot\AgentProperties;
use App\Helpers\Models\AgentHelper;
use App\Helpers\Models\ConsentHelper;
use App\Interfaces\HubSpotServiceInterface;
use App\Notifications\Agent\AccountSuspendedNotification;
use App\Notifications\Agent\CustomOfferRequestedNotification;
use App\Notifications\AgentCreated;
use App\Notifications\ApiService\SuspendSubscription;
use App\Notifications\ForgotAgentPassword;
use App\Models\Quotation;
use App\Traits\AccountStatus;
use App\Traits\Filters;
use App\Traits\HubSpotIds;
use App\Traits\NameFormatter;
use Carbon\Carbon;
use Devio\Pipedrive\PipedriveFacade as Pipedrive;
use http\Exception\RuntimeException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;
use Mockery\Exception;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

/**
 * Class Agent
 * @package App
 * @property integer $id
 * @property string|null $business_name
 * @property string|null $name
 * @property string $email
 * @property integer|null $hubspot_id
 * @property string|null $logo
 * @property string|null $phone
 * @property string|null $contact_info
 * @property string $password
 * @property integer|null $group
 * @property float $fee_percentage
 * @property string $filters
 * @property Carbon|null $email_verified_at
 * @property string|null $notes
 * @property string|null $remember_token
 * @property string $status
 * @property Carbon|null $login_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Agent extends Authenticatable implements JWTSubject
{
    use SoftDeletes, Notifiable, Filters, AccountStatus, NameFormatter, HubSpotIds;

    protected $table = 'agents';
    protected $guard = 'agent';

    protected $hidden = ['password', 'remember_token'];
    protected $guarded = ['id'];

    public $timestamps = ['created_at', 'updated_at', 'deleted_at', 'email_verified_at', 'login_at'];

    public static $TYPE = [
        'AGENT' => 'agent',
        'LEADER' => 'leader',
    ];

    public static $DEFAULT_LOGO = "https://static.ideal-rent.com/logos/main.png";

    /**
     * Formatta Nome Agente prima di salvare il valore nel db
     * @param string $value
     * @see https://laravel.com/docs/5.6/eloquent-mutators#defining-a-mutator
     */
    public function setNameAttribute($value)
    {
        if (!empty($value)) {
            $value = $this->prettify($value);
        }

        $this->attributes['name'] = $value;
    }

    /**
     * Formatta Business Name prima di salvare il valore nel db
     * @param string $value
     * @see https://laravel.com/docs/5.6/eloquent-mutators#defining-a-mutator
     */
    public function setBusinessNameAttribute($value)
    {
        if (!empty($value)) {
            $value = $this->prettify($value);
        }

        $this->attributes['business_name'] = $value;
    }

    /**
     * Ritorna l'elenco di offerte associate ad un agente
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function offers()
    {
        return $this->belongsToMany('App\Models\Offer', 'agent_offer');
    }

    /**
     * Ritorna l'elenco di proposal associate ad un agente
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proposals()
    {
        return $this->hasMany('App\Models\Proposal', 'agent_id');
    }

    /**
     * Ritorna l'elenco di offerte attive associate ad un agente
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function activeOffers()
    {
        return $this->offers()->where('offers.status', true)->orderBy('monthly_rate', 'asc')->orderBy('code', 'asc')->get();
    }

    /**
     * Ritrona l'elenco di offerte consigliate per questo agente
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function suggestedOffers()
    {
        return $this->offers()
            ->where('offers.status', true)
            ->where('offers.suggested', true)
            ->orderBy('monthly_rate', 'asc')
            ->orderBy('code', 'asc')
            ->get();
    }

    /**
     * Attach all shared offers
     */
    public function attachOffers()
    {
        $offers = Offer::getSharedOffers();

        Log::info('attachOffers agent ' . $this->id, [ 'offers number' => $offers->count() ]);
        $nAttached= 0;

        foreach ($offers as $offer) {
            if ($offer->attach($this)) {
                $nAttached++;
            }
        }

        Log::info('attachOffers agent ' . $this->id, [ 'offers attached' => $nAttached ]);
    }

    /**
     * Detach all agent offers
     */
    public function detachOffers()
    {
        $ownedOffers = Offer::getOwnedOffers($this);
        $items = $ownedOffers->pluck('id');
        $items = $items->toArray();
        return \DB::table('agent_offer')
            ->where('agent_id', $this->id)
            ->whereNotIn('offer_id', $items)
            ->delete();
    }

    /**
     * Ritorna l'elenco dei clienti di questo agente
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function customers()
    {
        return $this->belongsToMany('App\Models\Customer', 'App\Models\Proposal', 'agent_id', 'customer_id');
    }

    /**
     * Ritorna l'elenco delle quotation di questo agente
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function quotations()
    {
        return $this->hasManyThrough('App\Models\Quotation', 'App\Models\Proposal', 'agent_id', 'proposal_id');
    }

    /**
     * Ritorna il numero di quotation aperte di questo agente
     * @return int
     */
    public function openQuotations()
    {
        return $this->quotations()->where('status', 'OPEN')->count();
    }

    /**
     * Ritorna il numero di quotation perse di questo agente
     * @return int
     */
    public function lostQuotations()
    {
        return $this->quotations()->where('status', 'LOST')->count();
    }

    /**
     * Ritorna il numero di quotation vinte di questo agente
     * @return int
     */
    public function wonQuotations()
    {
        return $this->quotations()->where('status', 'WON')->count();
    }

    /**
     * Ritorna il gruppo di appartenenza di questo agente
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function myGroup()
    {
        return $this->belongsTo('App\Models\Group', 'group');
    }

    /**
     * Ritorna la ragione sociale dell'agente o il suo nome
     * @return string
     */
    public function getName()
    {
        return !empty($this->business_name) ? $this->business_name : $this->name;
    }

    /**
     * Ritorna il nome di questo agente o la ragione sociale
     * @return string
     */
    public function getNomeCompleto()
    {
        return !empty($this->name) ? $this->name : $this->business_name;
    }

    /**
     * Ritorna la primary key della tabella (`agents.id`)
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Ritorna l'associazione come caporete del proprio gruppo
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function groupAsLeader()
    {
        return $this->hasOne('App\Models\Group', 'group_leader', 'id');
    }

    /**
     * Determina se l'agente è caporete
     * @return boolean
     */
    public function isGroupLeader()
    {
        return $this->groupAsLeader()->exists();
    }

    /**
     * Ritorna un agente della proprio rete
     * @param $member_id
     * @return mixed
     */
    public function findTeamMember($member_id)
    {
        return $this->where([['id', $member_id], ['group', $this->group]])->firstOrFail();
    }

    /**
     * Ritorna l'elenco degli agenti della propria rete
     * @return mixed
     */
    public function myAgents()
    {
        /** @var Group $group */
        $group = $this->groupAsLeader;
        return $group->agents();
    }

    /**
     * Ritorna la query che seleziona tutte le proposal degli agenti del proprio gruppo
     * @param integer|null $stage
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function quotationsAsLeaderQuery($stage = null)
    {
        $agents = $this->myAgents->pluck('id');

        if (!is_null($stage)) {
            return Quotation::where('stage', $stage)->whereHas('Proposal', function ($query) use ($agents) {
                $query->whereIn('agent_id', $agents->toArray());
            });
        }

        return Quotation::whereHas('Proposal', function ($query) use ($agents) {
            $query->whereIn('agent_id', $agents->toArray());
        });
    }

    /**
     * Ritorna l'elenco di tutte le proposal degli agenti del proprio gruppo
     * @param integer|null $stage
     * @return mixed
     */
    public function quotationsAsLeader($stage = null)
    {
        return $this->quotationsAsLeaderQuery($stage)->orderBy('id', 'desc')->get();
    }

    /**
     * Ritorna l'elenco di tutte le proposal degli agenti del proprio gruppo, raggruppate per mese
     * @return array
     */
    public function quotationsAsLeaderMonthGrouped()
    {
        $quotations = [];
        $agents = $this->myAgents->pluck('id');
        $query = Quotation::with(['proposal' => function ($query) use ($agents) {
            $query->whereIn('agent_id', $agents->toArray());
        }])
            ->select(
                DB::raw('COUNT(id) as quotations'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                'created_at'
            )
            ->groupBy('year', 'month')->get();


        //Riordino i dati
        foreach ($query as $result) {
            $item = $result->quotations;
            if (array_key_exists($result->year, $quotations)) {
                $items = $quotations[$result->year];
                $items[$result->month] = $item;
                $quotations[$result->year] = $items;
            } else {
                $items = [];
                $items[$result->month] = $item;
                $quotations[$result->year] = $items;
            }
        }
        //Fill dei dati mancanti
        $filled = [];
        foreach ($quotations as $key => $value) {
            $items = [];
            for ($i = 1; $i < 13; $i++) {
                if (!empty($value[$i])) {
                    $items[$i] = $value[$i];
                } else {
                    $items[$i] = 0;
                }
                $filled[$key] = $items;
            }
        }

        return $filled;
    }

    /**
     * Send user welcome notification email
     */
    public function sendWelcomeEmailNotification()
    {
        $this->notify(new AgentCreated($this));
    }

    /**
     * Send account suspended notification
     */
    public function sendAccountSuspendedNotification()
    {
        $this->notify(new AccountSuspendedNotification($this));
    }

    /**
     * Invia email con la nuovo password all'utente
     * @param AgentToken $token
     */
    public function sendResetPasswordEmailNotification(AgentToken $token)
    {
        $this->notify(new ForgotAgentPassword($this, $token));
    }

    /**
     * @param Customer $customer
     * @param Common\Models\Offers\Generic $offer
     */
    public function sendCustomOfferRequestedNotification(Customer $customer, Common\Models\Offers\Generic $offer)
    {
        $notification = new CustomOfferRequestedNotification($this, $customer, $offer);
        $this->notify($notification);
    }

    /**
     * Ritorna il token dell'agente
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function token()
    {
        return $this->hasMany('App\Models\AgentToken');
    }

    /**
     * Ritorna il token di attivazione associato all'utente
     * @return string
     * @throws TokenInvalidException
     */
    public function getActivationToken()
    {
        $token = $this->token()->where('type', AgentToken::$TYPES['ACTIVATION'])->firstOrFail();
        if ($token->isExpired()) {
            throw new \RuntimeException("Activation token expired");
        }
        return $token->token;
    }

    /**
     * Ritorna il token di accesso ad iDeal Api
     * @see https:://api.ideal-rent.com;
     * @return string|null
     */
    public function getApiServiceToken()
    {
        $token = $this->token()->where('type', AgentToken::$TYPES['API_SERVICE']);
        return $token->exists() ? $token->first()->token : null;
    }

    /**
     * Attiva un agente
     * @param AgentToken $token
     * @param string $password
     * @return bool|\Exception|Exception|null
     * @throws TokenInvalidException
     */
    public function activate(AgentToken $token, string $password)
    {
        if ($token->isExpired()) {
            throw new TokenInvalidException();
        }
        try {
            $status = $token->delete();
            $status &= $this->update([
                'password' => Hash::make($password),
                'status' => 'ACTIVATED',
                'email_verified_at' => Carbon::now()->toDateTimeString(),
            ]);
            return $status;
        } catch (Exception $exception) {
            return $exception;
        }
    }

    /**
     * Update agent password
     * @param AgentToken $token
     * @param string $password
     * @return bool|\Exception|Exception|null
     * @throws TokenInvalidException
     */
    public function updatePassword(AgentToken $token, string $password)
    {
        if ($token->isExpired()) {
            throw new TokenInvalidException();
        }
        try {
            $status = $token->delete();
            $status &= $this->update([
                'password' => Hash::make($password),
            ]);
            return $status;
        } catch (Exception $exception) {
            return $exception;
        }
    }

    /**
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool|\Exception|UnauthorizedException|null
     */
    public function updateAccessPassword(string $currentPassword, string $newPassword)
    {
        $checkPassword = Hash::check($currentPassword, $this->password);

        throw_if(!$checkPassword, UnauthorizedException::class);

        $newPassword = Hash::make($newPassword);

        return $this->update([
            'password' => $newPassword
        ]);
    }

    /**
     * Registra l'ultimo tentativo di login
     * @return bool
     */
    public function registerLastAccess()
    {
        return $this->update(['login_at' => Carbon::now()->toDateTimeString()]);
    }

    /**
     * Aggiorna informazioni profilo
     *
     * @param string $name
     * @param string|null $businessName
     * @param string|null $phone
     * @param string|null $contactInfo
     *
     * @return bool
     */
    public function updateProfile(string $name, string $businessName = null, string $phone = null, string $contactInfo = null): bool
    {
        $updateData = [
            'name' => $name
        ];

        if (!is_null($businessName)) {
            $updateData['business_name'] = $businessName;
        }

        if (!is_null($phone)) {
            $updateData['phone'] = $phone;
        }

        if (!is_null($contactInfo)) {
            $updateData['contact_info'] = $contactInfo;
        }

        return $this->update($updateData);
    }

    /**
     * Detach offers and delete agent
     * @return int
     */
    public function deleteWithOffers()
    {
        $id = $this->id;
        $this->suspendAccount();
        $status = \DB::table('agent_offer')->where('agent_id', $id)->delete();
        return parent::destroy($id);
    }

    /**
     * Ritorna il path del logo del gruppo
     * @return string
     */
    public function logo()
    {
        $group = $this->myGroup;
        return preg_replace('~[\r\n]+~', '', $group->logo);
    }

    /**
     * Ritorna il fee dell'agente o quello del gruppo di appartenenza
     * @return float
     */
    public function getRealFeePercentageAttribute()
    {
        if ($this->fee_percentage !== 0.0) {
            return $this->fee_percentage;
        }
        return $this->myGroup->fee_percentage;
    }

    /**
     * Determina se l'offerta è di proprietà dell'agente indicato
     * @param Offer $offer
     * @param Agent $agent
     * @return bool
     */
    public static function canUpdateOffer(Offer $offer, Agent $agent)
    {
        return $offer->owner_id === $agent->id;
    }

    /**
     * Ritorna tutti gli agenti nel database
     * @param bool $includeDeleted
     * @return Agent[]|\Illuminate\Database\Eloquent\Collection
     * @deprecated ?
     */
    public static function getAllAgents(bool $includeDeleted = false)
    {
        $query = self::orderBy('id', 'ASC');
        if ($includeDeleted) {
            $query->withTrashed();
        }
        return $query->get();
    }

    /**
     * Ritorna la data di creazione formattata
     * @return string
     */
    public function getHumanDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y');
    }

    /**
     * Ritorna la data di login formattata
     * @return string
     */
    public function getHumanLoginAtAttribute()
    {
        return Carbon::parse($this->login_at)->format('H:m:s d-m-Y');
    }

    /**
     * Ritorna l'insieme dei filtri per le offerte
     * @return array
     */
    public static function getDefaultFilters(): array
    {
        $brokers = array_keys(Offer::$BROKERS);
        $fuels = Fuel::all()->pluck('slug')->toArray();
        $categories = CarCategory::all()->pluck('slug')->toArray();
        return [
            'brokers' => $brokers,
            'fuels' => $fuels,
            'categories' => $categories
        ];
    }

    /**
     * Carica un file del gruppo sulla cdn di carplanner
     * @param UploadedFile $file
     * @param string $filename
     * @return string|null
     */
    public static function uploadLogo(UploadedFile $file, string $filename)
    {
        if ($file->isValid()) {
            $filename = preg_replace('/\s+/', '', $filename);
            $filename .= "." . $file->getClientOriginalExtension();
            $filePath = "ideal/dealers/logo/$filename";
            $s3 = Storage::disk('s3');
            $s3->put($filePath, file_get_contents($file), 'public');
            $url = config('app.cdn.url') . "/$filePath";
            return $url;
        }
        return null;
    }

    /**
     * Ritorna il logo associato all'agente
     * @return string logo url
     */
    public function getLogo(): string
    {
        if (!empty($this->logo)) {
            return $this->logo;
        }

        if ($this->myGroup()->exists()) {
            $group = $this->myGroup;
            return $group->logo;
        }
        return self::$DEFAULT_LOGO;
    }

    /**
     * Ritorna il titolo che identifica la tipologia del gruppo
     * @return string
     */
    public function getTitleText()
    {

        /** @var Group $group */
        $group = $this->myGroup;
        $groupName = 'il concessionario';

        if ($group->type == 'INSURANCE') {
            $groupName = "l'agenzia assicurativa";
        }

        return $groupName;
    }

    /**
     * Invia un agente sul HubSpot
     * @return mixed
     * @deprecated A causa di nuovo processo aziendale. Adesso questa procedura viene fatta manualemnte
     *
     */
    public function createOnHubSpot()
    {
        $consentHelper = new ConsentHelper();
        $helper = new AgentHelper(
            $this->name,
            $this->business_name,
            $this->phone,
            $this->notes
        );
        //send data to HubSpot
        $hubSpotService = app(HubSpotServiceInterface::class);
        return $hubSpotService->createContact($this, $helper, $consentHelper);
    }

    /**
     * Aggiorna i dati di un agente su hubspot
     * @return mixed
     * @deprecated A causa di nuovo processo aziendale. Adesso questa procedura viene fatta manualemnte
     */
    public function updateOnHubSpot()
    {
        $hubSpotService = app(HubSpotServiceInterface::class);
        return $hubSpotService->updateContact($this, [
            AgentProperties::FIRST_NAME => $this->name,
            AgentProperties::COMPANY => $this->business_name,
            AgentProperties::RAGIONE_SOCIALE => $this->business_name,
            AgentProperties::PHONE_NUMBER => $this->phone,
            AgentProperties::NOTE => $this->notes,
        ]);
    }

    /**
     * Sospende un account
     * @return bool
     */
    public function suspendAccount(): bool
    {
        $status = $this->suspend();
        $this->sendAccountSuspendedNotification();

        return $status;
    }


    /**
     * @return bool
     */
    public function isEklyAgent(): bool
    {
        if(!$this->myGroup()->exists())
            return false;


        /** @var Group $group */
        $group = $this->myGroup;
        $name = strtolower($group->name);
        return $name == 'ekly';
    }
}
