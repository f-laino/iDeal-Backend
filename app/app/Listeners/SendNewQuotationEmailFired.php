<?php

namespace App\Listeners;

use App\Notifications\QuotationCreated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\SendNewQuotationEmail;

class SendNewQuotationEmailFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendNewQuotationEmail  $event
     * @return void
     */
    public function handle(SendNewQuotationEmail $event)
    {
        $quotation = $event->quotation;
        $agent = $quotation->proposal->agent;
        $group = $agent->myGroup;
        if(!empty($group) && !empty($group->notification_email))
            Notification::send($group->notification_email, new QuotationCreated($quotation));
    }
}
