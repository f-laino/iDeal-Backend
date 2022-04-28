<?php

namespace App\Services\Storages;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use SplFileInfo;


class TemporaryStorageService
{

    /** @var string $disk */
    const DISK = 'tmp';

    /**
     * @param UploadedFile $uploadedFile
     * @param Attachment $attachment
     * @return SplFileInfo
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function storeAndRetrieve(UploadedFile $uploadedFile, Attachment $attachment): SplFileInfo
    {
        $contents = $uploadedFile->get();
        $fileName = "{$attachment->entity_id}/{$attachment->description}-{$attachment->name}";

        //store file on disk
        Storage::disk(self::DISK)->put($fileName, $contents);

        //retrieve from storage
        $filePath = Storage::disk(self::DISK)->path($fileName);

        return new SplFileInfo($filePath);
    }

    /**
     * @param string $filePath
     */
    public function removeFile(string $filePath): void
    {
        Storage::disk(self::DISK)->delete($filePath);
    }
}
