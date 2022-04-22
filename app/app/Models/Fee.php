<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Fee
 * @package App
 *
 * @property integer $id
 * @property string $broker
 * @property string $pattern
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Fee extends Model
{
    use SoftDeletes;

    protected $table = 'fees';
    protected $fillable = [ 'id','broker', 'pattern'];
}
