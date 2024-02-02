<?php

namespace App\Imports;

use App\Models\Nex_Market;
use App\Models\Nex_script;
use App\Models\Nex_script_expire;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithStartRow;

use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

use App\Events\ExcelImportProgressEvent;
use Illuminate\Support\Facades\Broadcast;


class ScriptImport implements ToModel,WithHeadingRow,WithChunkReading,WithEvents,ShouldQueue, SkipsOnFailure, SkipsEmptyRows, SkipsOnError
// ,WithStartRow
{
    use Importable,RemembersRowNumber,SkipsFailures, SkipsErrors;
 
    public $error_msg = '';

    public function model(Array $row)
    {
        cache()->forever("import_script_error_msg", $this->error_msg);

        $index = $this->getRowNumber();
        cache()->forever("import_script_current_row", $index);

        // cache(["import_script_current_row" => $index], now()->addMinute());

        $progressData = [
            'current_row' => $this->getRowNumber(),
            'total_rows' => cache("import_script_total_rows"),
        ];
    
        // Broadcast::event(new ExcelImportProgressEvent($progressData))->toOthers();

        // event(new ExcelImportProgressEvent($progressData));

        
        #tradingsymbol                                        
        $tradingsymbol = trim($row['tradingsymbol']);

        #name(NIFTY)(script)
        $filescript_name = trim($row['name']);

        #extension
        $fileextension = trim($row['extension']);

        #expiry                                                 
        $fileexpiry_date = trim($row['expiry']); 

        #strike
        $filestrike_price = trim($row['strike']); 

        #lot_size (qty)       
        $filelot_size_qty = trim($row['lot_size']);

        #segment (Market)
        $filemarket = trim($row['segment']);

        #instrument Type
        $fileinstrument_type = trim($row['instrument_type']);

        // if($index=='5' || $index==8 || $index== 10)
        //     $this->error_msg .= '['.$index.']  Market Not Found!<br>';
        
        $trading_extension = ($filemarket=='NSEOPT')?$tradingsymbol:$filescript_name.'-'.$fileextension;

        $market = Nex_Market::where('market_name', $filemarket)->first();
        if($market===null)
        {
            $this->error_msg .= '['.$index.'] '. $filemarket.' Market Not Found!<br>';
            cache()->forever("import_script_error_msg", $this->error_msg);            
            return null;
        }
        $market_id = $market ? $market->id : 0;

        // // Get script id based on script name
        // $script = Nex_script::where('script_name', $row['B'])->where('market_id',$market_id)->first();

        $nexscript = Nex_script::updateOrCreate(
            ['script_name' => $filescript_name,'market_id'=>$market_id],
            [
                'market_name' => $filemarket,
                'script_name' => $filescript_name,
                'script_full_name' => $filescript_name,
                'script_quantity' => $filelot_size_qty,
                'market_id' => $market_id
            ]
        );  

        $script_id = $nexscript ? $nexscript->id : 0;
        $expiry_date = Carbon::createFromFormat('d/m/Y', $fileexpiry_date)->format('Y-m-d');
        $Nex_script_expire = Nex_script_expire::updateOrCreate(
            ['script_id' =>  $nexscript->id,'market_id'=>$market_id,'expiry_date'=>$expiry_date,'script_trading_symbol'=>$tradingsymbol],
            [              
                'market_id' => $market_id,
                'script_id' => $script_id,                         
                'expiry_date'=>$expiry_date,
                'script_trading_symbol'=>$tradingsymbol,
                'script_extension'=>$trading_extension,
                'script_strike_price'=>$filestrike_price,
                'script_instrument_type'=>$fileinstrument_type,
                'market_name' => $filemarket,
                'script_name' => $filescript_name,
            ]
        );        
    }

    // public function startRow(): int
    // {
    //     return (int) cache("import_script_current_row"); // Start reading from the second row (skipping the header row).
    // }

    public function registerEvents(): array
    {
        return [    
            \Maatwebsite\Excel\Events\ImportFailed::class => function(\Maatwebsite\Excel\Events\ImportFailed $event) {

                cache(["import_script_end_date" => now()], now()->addMinutes(10));                
                
                cache()->forget("import_script_total_rows");
                cache()->forget("import_script_start_date");
                cache()->forget("import_script_current_row");
                cache()->forget("all_import_status_error");
                
            },    
            \Maatwebsite\Excel\Events\BeforeImport::class => function (\Maatwebsite\Excel\Events\BeforeImport $event) {
                $totalRows = $event->getReader()->getTotalRows();
                cache()->forget("import_script_end_date");
                cache()->forever("all_import_status_error", "All Scripts Not imported somthing went wrong!");
                if (filled($totalRows)) {
                    cache(["import_script_current_row" => 1], now()->addMinute());
                    cache()->forever("import_script_total_rows", array_values($totalRows)[0]);
                    cache()->forever("import_script_start_date", now()->unix());
                }
            },            
            \Maatwebsite\Excel\Events\AfterImport::class => function (\Maatwebsite\Excel\Events\AfterImport $event) {
                cache(["import_script_end_date" => now()], now()->addMinutes(10));
                
                
                cache()->forget("import_script_total_rows");
                cache()->forget("import_script_start_date");
                cache()->forget("import_script_current_row");
                cache()->forget("all_import_status_error");
                sleep(5);
                // cache()->forget("import_script_end_date");     

            },
        ];
    }
    public function batchSize(): int
    {
        return 1000;
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }
}
