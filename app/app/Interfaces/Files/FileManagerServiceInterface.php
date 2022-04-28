<?php

namespace App\Interfaces\Files;

use SplFileInfo;

interface FileManagerServiceInterface
{

    /**
     * Carica un oggetto all'interno dello storage
     * @param SplFileInfo $file
     * @param string $path
     * @param array $metaData
     * @return bool
     */
    function putObject(SplFileInfo $file, string $path, array $metaData = []): bool;

    /**
     * Crea un url di accesso temporaneo
     * @param string $path
     * @return string
     */
    public function createAccessUri(string $path): string;

    /**
     * Valida la dimensione dell'oggetto
     * @param SplFileInfo $file
     * @return bool
     */
    static function validateObjectSize(SplFileInfo $file): bool;
}
