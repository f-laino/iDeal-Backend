<?php

namespace App\Common\Models\Offers;

/**  */
class Generic
{

    private $brand;
    private $model;
    private $version;
    private $monthlyRate;
    private $duration;
    private $distance;
    private $deposit;
    private $services;
    private $note;

    /**  */
    public function __construct(
        string $brand,
        string $model,
        string $version,
        float $monthlyRate = 0,
        int $duration = 0,
        int $distance = 0,
        int $deposit = 0,
        string $services = '',
        string $note = ''
    )
    {
     $this->brand = $brand;
     $this->model = $model;
     $this->version = $version;
     $this->monthlyRate = $monthlyRate;
     $this->duration = $duration;
     $this->distance = $distance;
     $this->deposit = $deposit;
     $this->services = $services;
     $this->note = $note;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return ucwords($this->brand);
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        $model = strtolower($this->model);
        return ucwords($model);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return float
     */
    public function getMonthlyRate(): float
    {
        return $this->monthlyRate;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

    /**
     * @return int
     */
    public function getDeposit(): int
    {
        return $this->deposit;
    }

    /**
     * @return string
     */
    public function getServices(): string
    {
        return $this->services;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

}
