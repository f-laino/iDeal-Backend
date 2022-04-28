<?php

namespace App\Services\Files;

class FileNameService
{

    /**
     * @param string $customerId
     * @param string $fileType
     * @param string $fileExtension
     * @param int $offset
     * @return string
     */
    public function getPath(
        string $customerId, string $fileType,
        string $fileExtension, int $offset = 0
    ): string
    {
        $path = $this->getDirectory($customerId, $fileType);
        $name = $this->getName($fileExtension, $offset);

        return $path . $name;
    }

    /**
     * @param string $fileExtension
     * @param int $offset
     * @return string
     */
    public function getName(string $fileExtension, int $offset = 0): string
    {
        $offset += 1;
        $fileName = str_pad($offset, 3, '0', STR_PAD_LEFT);

        return "{$fileName}.{$fileExtension}";
    }

    /**
     * @param string $customerId
     * @param string $fileType
     * @return string
     */
    public function getDirectory(string $customerId, string $fileType): string
    {
        return "{$customerId}/{$fileType}/";
    }
}
