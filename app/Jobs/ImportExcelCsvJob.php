<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ScriptImport;


class ImportExcelCsvJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $uploadFile;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uploadFile)
    {
        $this->uploadFile = $uploadFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::import(new ScriptImport, $this->uploadFile);
        // dd(Excel::toArray(new ScriptImport, $this->uploadFile));

    }
}
