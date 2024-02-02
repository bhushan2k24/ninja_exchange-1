<?php

namespace App\Listeners;

use App\Events\ExcelImportProgressEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ExcelImportProgressListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(ExcelImportProgressEvent $event)
    {
        // Handle the event here
        return [$event->current_row, $event->total_rows];
    }
}
