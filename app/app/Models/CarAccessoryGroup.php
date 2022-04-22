<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CarAccessoryGroup
 * @package App
 * @property integer $id
 * @property string $code
 * @property string|null $description
 */
class CarAccessoryGroup extends Model
{
    protected $table = 'car_accessory_groups';

    public $timestamps = false;

    /**
     * Ritorna l'elenco degli accessori appartenenti a questa categoria
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accessories()
    {
        return $this->hasMany('App\Models\CarAccessory');
    }
}
