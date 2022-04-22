<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PriceIndex
 * @package App
 * @property integer $id
 * @property string $broker
 * @property string segment
 * @property string $pattern
 * @property string $secondary_pattern
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class PriceIndex extends Model
{
    use SoftDeletes;

    protected $table = 'price_indexers';
    protected $fillable = [ 'broker', 'segment', 'pattern', 'secondary_pattern'];

    public static $yearDistances = [ 10000, 15000, 20000, 25000 ];
    public static $durations = [ 24, 36 ];

    public static $smallSegments = [ 'A' => 'A', 'B' => 'B' ];
    public static $mediumSegments = [ 'C' => 'C', 'D' => 'D', 'E' => 'E'];


    /**
     * Ritorna il gruppo dei segmenti di appartenza
     * @return array
     */
    public function getSegmentClassAttribute()
    {
        if (in_array($this->segment, self::$smallSegments)) {
            return self::$smallSegments;
        }
        return self::$mediumSegments;
    }

    /**
     * Ritorna i km annui in base al segmento
     * @param mixed $segment
     *
     * @return array
     */
    public static function getYearDistancesBySegment($segment)
    {
        if (in_array($segment, self::$smallSegments)) {
            return [10000, 15000, 20000];
        }
        return [15000, 20000, 25000];
    }

    /**
     * Ritorna il numero di offerte associabili a questo indexer
     * @return int
     */
    public function getCountOffersAttribute()
    {
        return Offer::whereHas('car', function ($query) {
            $query->where('segmento', $this->segment);
        })->where('broker', $this->broker)->count();
    }
}
