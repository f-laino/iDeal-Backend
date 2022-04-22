<?php

namespace App\Models;

use App\Attachment;
use App\Notifications\CustomerCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * Class Customer
 * @package App
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property integer|null $group_id
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $zip_code
 * @property string $fiscal_code
 * @property string|null $iban
 * @property integer $contractual_category_id
 * @property string|null $vat_number
 * @property string|null $business_name
 * @property string|null $notes
 * @property Carbon|null $marketing
 * @property Carbon|null $third_party
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @OA\Schema(
 *  schema="CustomerEntity",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="first_name", type="string"),
 *  @OA\Property(property="last_name", type="string"),
 *  @OA\Property(property="email", type="string", format="email"),
 *  @OA\Property(property="group_id", type="integer"),
 *  @OA\Property(property="phone", type="string"),
 *  @OA\Property(property="address", type="string"),
 *  @OA\Property(property="zip_code", type="string"),
 *  @OA\Property(property="fiscal_code", type="string"),
 *  @OA\Property(property="iban", type="string"),
 *  @OA\Property(property="contractual_category_id", type="integer"),
 *  @OA\Property(property="vat_number", type="string"),
 *  @OA\Property(property="business_name", type="string"),
 *  @OA\Property(property="notes", type="string"),
 *  @OA\Property(property="marketing", type="string"),
 *  @OA\Property(property="third_party", type="string"),
 * )
 */
class Customer extends Model
{
    use Notifiable;

    protected $table = 'customers';

    protected $guarded = ['id'];

    /**
     * Ritorna l'associazione con la categoria contrattuale
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\ContractualCategory', 'contractual_category_id', 'id');
    }

    /**
     * Ritorna le quotation associate al customer
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function quotations()
    {
        return $this->hasManyThrough('App\Models\Quotation', 'App\Models\Proposal', 'customer_id', 'proposal_id');
    }

    /**
     * Ritorna l'ultimo preventivo
     * @return mixed
     */
    public function lastQuotation()
    {
        return $this->quotations->last();
    }

    /**
     * Ritorna il nome e il cognome
     * @return string
     */
    public function getNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    /**
     * Cerca un customer tramite codice fiscale, gruppo e, eventualmente, categoria contrattuale
     * @param string $code
     * @param int $groupId
     * @param string|null $contractualCategory
     * @return mixed
     */
    public static function findByFiscalCode(string $code, int $groupId, $contractualCategory = null)
    {
        $result = self::where("fiscal_code", $code)
                    ->where('group_id', $groupId);

        if (!empty($contractualCategory)) {
            $category = ContractualCategory::where('code', $contractualCategory)->firstOrFail();
            $result->where("contractual_category_id", $category->id);
        }

        return $result->firstOrFail();
    }

    /**
     * Send customer privacy notification email
     */
    public function sendPrivacyEmailNotification(Agent $agent)
    {
        $this->notify(new CustomerCreated($this, $agent));
    }

    /**
     * Restituice il numero di alelgati per tipologia
     * @return mixed
     */
    public function countAttachmentsByType()
    {
        return Attachment::where('entity_id', $this->id)
            ->groupBy('type')->get(['type', \DB::raw('type,  COUNT(*) AS items')]);
    }

    /**
     * Create a new customer from at Http Request
     * @param Request $request
     * @param Agent $agent
     * @return Customer
     * @throws \Throwable
     */
    public static function createFromRequest(Request $request, Agent $agent)
    {
        $employeeCategory = $request->get('employee_category', 'tempo-indeterminato');
        $category = ContractualCategory::findOrFirst($employeeCategory);
        $fiscalCode = $request->get('fiscal_code', null);
        $group = $agent->myGroup;

        try {
            $customer = self::where('fiscal_code', $fiscalCode)
                            ->where('group_id', $group->id)
                            ->where('contractual_category_id', $category->id)
                            ->firstOrFail()
                            ;

            if (!empty($customer)) {
                $customer->update([
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('email'),
                    'vat_number' => $request->get('vat_number', null),
                    'business_name' => $request->get('business_name', null),
                    'address' => $request->get('address', null),
                    'zip_code' => $request->get('postal_code', null),
                    'phone' => $request->get('phone', null),
                ]);
            }
        } catch (ModelNotFoundException $exception) {
            $customer = new self();
            $customer->first_name = $request->get('first_name');
            $customer->last_name = $request->get('last_name');
            $customer->email = $request->get('email');
            $customer->group_id = $group->id;
            $customer->phone = $request->get('phone');
            $customer->address = $request->get('address');
            $customer->zip_code = $request->get('postal_code');
            $customer->contractual_category_id = $category->id;
            $customer->fiscal_code = strtoupper($fiscalCode);
            $customer->vat_number = $request->get('vat_number', null);
            $customer->business_name = $request->get('business_name', null);
            $customer->saveOrFail();

            //Send privacy if customer is new
            $customer->sendPrivacyEmailNotification($agent);
        }

        return $customer;
    }
}
