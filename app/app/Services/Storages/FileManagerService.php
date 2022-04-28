<?php

namespace App\Services\Storages;

use App\Interfaces\Files\FileManagerServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileManagerService implements FileManagerServiceInterface
{

    /** @var int  */
    public const SIGNED_URI_TTL = 10;

    /** @var int  */
    public const MAX_ALLOWED_SIZE_MB = 20;

    /** @var string  */
    const DISK = 'files';


    /** @inheritDoc
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     */
    public function putObject(UploadedFile $file, string $path, array $metaData = []): bool
    {
        $metaData = $this->addDefaultMetaData($metaData);
        $content = $file->get();

        $success = Storage::disk(self::DISK)->put($path, $content, $metaData);

        if(!$success) {
            $message = "Cannot put object into store disk: " . self::DISK;
            throw new \Exception($message);
        }

        return $success;
    }


    /** @inheritDoc */
    public function createAccessUri(string $path): string
    {
        $date = Carbon::now();
        $date->addMinutes(self::SIGNED_URI_TTL);

        return Storage::disk(self::DISK)->temporaryUrl($path, $date);
    }

    /**
     * @inheritDoc
     */
    public static function validateObjectSize(UploadedFile $file): bool
    {
        $fileSize = $file->getSize();
        $fileSize = number_format($fileSize / 1048576, 1);
        return $fileSize <= self::MAX_ALLOWED_SIZE_MB;
    }

    private function addDefaultMetaData(array $metaData = []): array
    {
        $default = [];
        return array_merge($default, $metaData);
    }
}
