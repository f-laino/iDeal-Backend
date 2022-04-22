<?php

namespace App\Events;

use App\Models\Quotation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class QuotationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $quotation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }
}
