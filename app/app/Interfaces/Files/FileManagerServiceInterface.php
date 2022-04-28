<?php

namespace App\Interfaces\Files;

use Illuminate\Http\UploadedFile;

interface FileManagerServiceInterface
{

    /**
     * Carica un oggetto all'interno dello storage
     * @param UploadedFile $file
     * @param string $path
     * @param array $metaData
     * @return bool
     */
    function putObject(UploadedFile $file, string $path, array $metaData = []): bool;

    /**
     * Crea un url di accesso temporaneo
     * @param string $path
     * @return string
     */
    public function createAccessUri(string $path): string;

    /**
     * Valida la dimensione dell'oggetto
     * @param UploadedFile $file
     * @return bool
     */
    static function validateObjectSize(UploadedFile $file): bool;
}
