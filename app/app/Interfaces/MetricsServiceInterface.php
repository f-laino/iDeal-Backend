<?php

namespace App\Interfaces;

interface MetricsServiceInterface
{
    public function measureThis($metricName, $metricValue = 1, $additionalAttributes = null);
}
