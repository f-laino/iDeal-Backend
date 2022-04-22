<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class Document
 * @package App
 * @property integer $id
 * @property string $title
 * @property string $type
 * @property string|null $link
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Document extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public static $rules = [
        'title' => 'required|string'
    ];

    /**
     * Ritorna l'associazione con i broker e le categorie contrattuali
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documentList()
    {
        return $this->hasMany('App\Common\Models\DocumentList');
    }

    /**
     * Ritorna tutti i documenti
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAll()
    {
        return self::get();
    }

    /**
     * Ritorna il documento richiesto con i broker e le categorie contrattuali
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getWithDocumentList(int $documentId)
    {
        return self::where('id', $documentId)
            ->with('documentList')
            ->first();
    }

    /**
     * Formatta il tipo prima di memorizzarlo nel database
     * @param string $value
     */
    public function setTypeAttribute($value)
    {
        if (!empty($value)) {
            $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
            $value = preg_replace('/[^a-z0-9]/i', '_', $value);
            $value = preg_replace('/_+/i', '_', $value);
            $value = strtolower($value);
        }

        $this->attributes['type'] = $value;
    }

    /**
     * Carica un file sulla cdn di carplanner
     * @param UploadedFile $file
     * @return string|null
     * @throws Exception
     */
    public static function uploadFile(UploadedFile $file)
    {
        if ($file->isValid()) {
            $filename = preg_replace('/\s+/', '', $file->getClientOriginalName());
            $filePath = "docs/$filename";
            $s3 = Storage::disk('s3');
            $s3->put($filePath, file_get_contents($file), 'public');
            $url = config('app.cdn.url') . "/$filePath";
            return $url;
        }

        throw new Exception($file->getError());
    }
}
