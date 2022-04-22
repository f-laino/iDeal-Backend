<?php

namespace App\Models;

use App\Traits\NameFormatter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Crypt;

/**
 * Class CarAccessory
 * @package App
 * @property integer $id
 * @property string $type
 * @property integer $car_accessory_groups_id
 * @property integer $car_id
 * @property string|null $description
 * @property string|null $short_description
 * @property string|null $standard_description
 * @property float $price
 * @property bool $available
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CarAccessory extends Model
{
    use NameFormatter;

    protected $table = 'car_accessories';

    //Tipologia accessorio
    public static $ALLOWED_TYPES = ['SERIE', 'OPTIONAL', 'PACCHETTI', 'VERNICI'];
    public static $DEFAULT_TYPE = 'OPTIONAL';

    protected $guarded = ['id'];
    public $timestamps = ['created_at', 'updated_at'];


    /**
     * Salva i accessori di una vettura a partire dalla vettura stessa
     * @param Car $car
     * @throws \Throwable
     */
    public static function storeFromWebservice(Car $car)
    {
        $eurotaxService = app('eurotax');
        $accessori = $eurotaxService->getAccessori($car->codice_motornet, $car->codice_eurotax);
        foreach ($accessori['serie'] as $item) {
            self::storeWebserviceItem($car->id, (array)$item, 'SERIE');
        }
        foreach ($accessori['optional'] as $item) {
            self::storeWebserviceItem($car->id, (array)$item, 'OPTIONAL');
        }
        foreach ($accessori['pacchetti'] as $item) {
            self::storeWebserviceItem($car->id, (array)$item, 'PACCHETTI');
        }
        foreach ($accessori['vernici'] as $item) {
            self::storeWebserviceItem($car->id, (array)$item, 'VERNICI');
        }
    }


    /**
     * Crea un accessorio a partire da un oggetto accessorio restituido dal webservice
     * @param int $carId
     * @param array $webserviceItem
     * @param string|null $type
     * @return CarAccessory
     * @throws \Throwable
     */
    public static function storeWebserviceItem(int $carId, array $webserviceItem, string $type = 'OPTIONAL')
    {
        //Handle Group
        $groupId = intval($webserviceItem['IDMacrogruppo']);
        try {
            $accessoryGroup = CarAccessoryGroup::findOrFail($groupId);
        } catch (ModelNotFoundException $exception) {
            $accessoryGroup = new CarAccessoryGroup();
            $accessoryGroup->id = $groupId;
            $accessoryGroup->code = $webserviceItem['CodMacrogruppo'];
            $accessoryGroup->description = $webserviceItem['Macrogruppo'];
            $accessoryGroup->saveOrFail();
        }

        //create accessory
        /** @var CarAccessory $accessory */
        $accessory = new self();
        $accessory->type = $type;
        $accessory->car_accessory_groups_id = $accessoryGroup->id;
        $accessory->car_id = $carId;
        $accessory->price = floatval($webserviceItem['Prezzo']);
        $accessory->description = html_entity_decode($webserviceItem['Descrizione']);
        $accessory->short_description = html_entity_decode($webserviceItem['DescrizioneBreve']);
        $accessory->standard_description = html_entity_decode($webserviceItem['DescrizioneNormalizzato']);
        $accessory->available = true;
        $accessory->saveOrFail();

        return $accessory;
    }

    /**
     * Set the car accessory description.
     *
     * @param string $value
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = self::formatString($value);
    }

    /**
     * Set the car accessory short description.
     *
     * @param string $value
     */
    public function setShortDescriptionAttribute($value)
    {
        $this->attributes['short_description'] = self::formatString($value);
    }

    /**
     * Set the car accessory standard description.
     *
     * @param string $value
     */
    public function setStandardDescriptionAttribute($value)
    {
        $this->attributes['standard_description'] = self::formatString($value);
    }

    /**
     * Ritorna il codice accessorio che non e' niente altro che un codice hash della description
     * @return string
     * @see https://laravel.com/docs/5.6/encryption
     */
    public function getCodeAttribute()
    {
        return Crypt::encryptString($this->description);
    }

    /**
     * Ritorna la descrizione di un accessorio a partire dal suo codice hash
     * @param string $code
     * @return string
     * @see https://laravel.com/docs/5.6/encryption
     */
    public static function getDescriptionFromCode($code)
    {
        return Crypt::decryptString($code);
    }

    /**
     * Ritorna la relazione fra auto e accessorio
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function car()
    {
        return $this->belongsTo('App\Models\Car');
    }

    /**
     * Ritorna la relazione fra group e accessorio
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Models\CarAccessoryGroup', 'car_accessory_groups_id', 'id');
    }

    /**
     * Cancella gli accessori di una vettura
     * @param int $carId
     * @return mixed
     */
    public static function deleteByCar(int $carId)
    {
        return self::where('car_id', $carId)->delete();
    }
}
