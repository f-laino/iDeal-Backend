<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class Image
 * @package App
 * @property integer $id
 * @property integer $car_id
 * @property string $code
 * @property string $path
 * @property string|null $image_alt
 * @property string $type
 * @property Carbon|null $deleted_at
 *
 */
class Image extends Model
{
    use SoftDeletes;

    protected $table = 'car_images';
    public $timestamps = false;
    protected $guarded = ['id'];

    public static $_POSITIONS = [
        'MAIN' => 'MAIN',
        'COVER' => 'COVER',
        'SLIDER' => 'SLIDER',
        'PROMOTIONS' => 'PROMOTIONS',
        'NEWSLETTER' => 'NEWSLETTER',
        'OTHER' => 'OTHER',
    ];

    public static $_DEFAULT_POSITION = 'SLIDER';

    /**
     * Ritorna l'allestimento associato a questa immagine
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function car()
    {
        return $this->belongsTo('App\Models\Car');
    }

    /**
     * Recupera le immagini di un allestimento dal servizio Eurotax e le memorizza sul database e sulla cdn
     * @param string $codiceMotornet
     * @param string $codiceEurotax
     * @param int $car_id
     * @param string $path
     * @throws \Throwable
     */
    public static function saveAll($codiceMotornet, $codiceEurotax, $car_id, $path = 'cars')
    {
        $eurotaxService = app('eurotax');
        $images = $eurotaxService->getImmagini($codiceMotornet, $codiceEurotax);
        $s3 = Storage::disk('s3');
        foreach ($images as $image) {
            try {
                //Controlla che l'immagine sia presente nel nostro db
                self::withTrashed()->where('code', $image->codice_fotografia."".$image->risoluzione)->firstOrFail();
            } catch (ModelNotFoundException $exception) {
                //Se non e' presente la importo su s3 ed salvo i dati nel db
                try {
                    $url = str_replace(' ', "%20", $image->url);

                    $file = file_get_contents($url);
                    $filePath = "$path/$image->codice_fotografia"."$image->risoluzione.jpg";
                    $s3->put($filePath, $file, 'public');

                    $carImage = new self();
                    $carImage->car_id = $car_id;
                    $carImage->code = $image->codice_fotografia;
                    $carImage->path = config('app.cdn.url')."/$filePath";
                    $carImage->image_alt = $image->descrizione_visuale;

                    switch ($image->codice_visuale) {
                        case "0009":
                            $type = "MAIN";
                            break;
                        default:
                            $type = "SLIDER";
                            break;
                    }
                    $carImage->type = $type;
                    $carImage->saveOrFail();
                } catch (\Exception $exception) {
                    Log::channel('eurotax')->error($exception->getMessage() . " - Codice Immagine: ". $image->codice_fotografia . " - Url Immagine: " . $image->url);
                }
            }
        }
    }

    /**
     * Memorizza un'immagine associata ad un allestimento
     * @param Car $car
     * @param UploadedFile $file
     * @param string|null $image_alt
     * @param string $type
     *
     * @return Image
     */
    public static function add(Car $car, UploadedFile $file, $image_alt = null, $type= 'SLIDER')
    {
        if (is_null($image_alt)) {
            $image_alt = $file->getClientOriginalName();
        }

        $brand = $car->brand;

        $path = "cars/{$brand->name}/{$car->descrizione_gruppo_storico}";
        $codice_fotografia = $brand->slug . $car->codice_modello;
        $time = Carbon::now()->timestamp;
        $filePath = "$path/$codice_fotografia"."_CUSTOM_$time." . $file->getClientOriginalExtension();

        //store file on s3
        $s3 = Storage::disk('s3');
        $s3->put($filePath, file_get_contents($file), 'public');

        $carImage = new self();
        $carImage->car_id = $car->id;
        $carImage->code = $car->generateCode();
        $carImage->path = config('app.cdn.url')."/$filePath";
        $carImage->image_alt = $image_alt;
        $carImage->type = $type;
        $carImage->saveOrFail();
        return $carImage;
    }

    /**
     * Regenerate all car images
     * @param Car $car
     */
    public static function regenerateAll(Car $car)
    {
        $brand = $car->brand->name;
        $path = "cars/$brand/$car->descrizione_gruppo_storico";
        self::withTrashed()->where('car_id', $car->id)->forceDelete();
        return self::saveAll($car->codice_motorner, $car->codice_eurotax, $car->id, $path);
    }

    /**
     * Ritorna l'elenco delle possibili posizioni per le immagini
     * @return array
     */
    public static function getPositions()
    {
        return array_keys(self::$_POSITIONS);
    }

    /**
     * Cerca un'immagine utilizzando il codice immagine
     * @param string $code
     * @throws ModelNotFoundException
     * @return mixed
     */
    public static function findByCode($code)
    {
        return self::where('code', $code)->orderBy('id', 'DESC')->firstOrFail();
    }
}
