<?php

namespace App\Models;

/**
 * Class PipedriveFile
 * @package App
 * @property integer $reference
 * @property string $name
 * @property string|null $uri
 * @property string $type
 * @property string|null $description
 * @property Carbon|null $lastUpdate
 */
class PipedriveFile
{
    private $reference;
    private $name;
    private $uri;
    private $type;
    private $description;
    private $lastUpdate;

    /**
     * PipedriveFile constructor.
     * @param int $reference
     * @param string $name
     * @param string $type
     * @param string $description
     * @param string $lastUpdate
     */
    public function __construct(int $reference, string $name, string $type, string $uri = null, string $description = null, string $lastUpdate = null)
    {
        $this->reference = $reference;
        $this->name = $name;
        $this->uri = $uri;
        $this->type = $type;
        $this->description = $description;
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'reference' => $this->reference,
            'name' => $this->name,
            'uri' => $this->uri,
            'type' => $this->type,
            'description' => $this->description,
            'lastUpdate' => $this->lastUpdate,
        ];
    }
}
