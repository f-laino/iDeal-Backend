<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

/**
 * Class CrmConnection
 * @package App
 * @property integer $id
 * @property string $name
 * @property string $driver
 * @property string $uri
 * @property string $token
 * @property string $owner
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class CrmConnection extends Model
{
    use SoftDeletes;

    protected $table = 'crm_connections';

    public static $DRIVERS = ['PIPEDRIVE', 'OFFLINE'];
    public static $DEFAULT_DRIVE = 'PIPEDRIVE';

    public $timestamps = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Codifica il token prima di salvarlo nel db
     * @see https://laravel.com/docs/5.6/encryption
     * @param string $value
     */
    public function setTokenAttribute($value)
    {
        $this->attributes['token'] = Crypt::encryptString($value);
    }

    /**
     * Decodifica il token prima di restituirlo
     * @see https://laravel.com/docs/5.6/encryption
     * @param string $value
     * @return string
     */
    public function getTokenAttribute($value): string
    {
        return Crypt::decryptString($value);
    }
}
