<?php

namespace Database\Seeders;

use App\Models\Nex_Market;
use App\Models\Nex_script;
use App\Models\Nex_script_expire;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ScriptImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $error_msg = '';

    public function run()
    {

        
        // if (filled($totalRows)) {
        //     cache()->forever("import_script_total_rows", array_values($totalRows)[0]);
        //     cache()->forever("import_script_start_date", now()->unix());
        // }
        // cache(["import_script_end_date" => now()], now()->addMinute());
        
        // cache()->forget("import_script_total_rows");
        // cache()->forget("import_script_start_date");
        // cache()->forget("import_script_current_row");
        // sleep(5);
        // cache()->forget("import_script_end_date");    


       
        LazyCollection::make(function () {
            $excelFilePath = public_path("/upload/seed/scriptlist.csv");
            $spreadsheet = IOFactory::load($excelFilePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $totalRows = $worksheet->getHighestRow();

            cache()->forget("import_script_end_date");

            if (filled($totalRows)) {
                cache()->forever("import_script_total_rows", $totalRows);
                cache()->forever("import_script_start_date", now()->unix());
            }

            foreach ($worksheet->getRowIterator() as $key => $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }
                $rowData['index'] =  $key;
                yield $rowData;
                // Do something with $rowData
                // dd($rowData);
            }
        })
        ->skip(1)
        ->chunk(1000)
        ->each(function (LazyCollection $chunk)  {
            $records = $chunk->map(function ($row) {
                $index = $row['index'];
                cache()->forever("import_script_current_row", $index);

                #tradingsymbol                                        
                $tradingsymbol = trim($row[2]);

                #name(NIFTY)(script)
                $filescript_name = trim($row[3]);

                #extension
                $fileextension = trim($row[4]);

                #expiry                                                 
                $fileexpiry_date = trim($row[6]); 

                #strike
                $filestrike_price = trim($row[7]); 

                #lot_size (qty)       
                $filelot_size_qty = trim($row[9]);

                #segment (Market)
                $filemarket = trim($row[11]);

                #instrument Type
                $fileinstrument_type = trim($row[10]);
                
                $trading_extension = ($filemarket=='NSEOPT')?$tradingsymbol:$filescript_name.'-'.$fileextension;

                $market = Nex_Market::where('market_name', $filemarket)->first();
                if($market===null)
                {
                    $this->error_msg .= '['.$index.'] '. $filemarket.' Market Not Found!<br>';
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

            })->toArray();
        });
        cache()->forever("import_script_error_msg", $this->error_msg);

        cache(["import_script_end_date" => now()], now()->addMinute());
        
        cache()->forget("import_script_total_rows");
        cache()->forget("import_script_start_date");
        cache()->forget("import_script_current_row");
        sleep(5);

        cache()->forget("import_script_end_date");  


        // $csvFilePath =  public_path("/upload/seed/scriptlist.csv");
        // $handle = fopen($csvFilePath, 'r');
        // while (($line = fgetcsv($handle, 4096)) !== false) {
        //     // Process each row here
        //     $dataString = implode(", ", $line);
        //     $row = explode(',', $dataString);
        //     // Do something with $row
        //     dd($row);
        // }

        // fclose($handle);


        // DB::disableQueryLog();
        // $error_msg = '';
        // LazyCollection::make(function () {
        //     $handle = fopen(public_path("/upload/seed/scriptlist.csv"), 'r');
            
        //     while (($line = fgetcsv($handle, 4096)) !== false) {
        //         $dataString = implode(", ", $line);
        //         $row = explode(',', $dataString);
        //         yield $row;
        //     }
  
        //     fclose($handle);
  
        // })
        // ->skip(1)
        // ->chunk(1000)
        // ->each(function (LazyCollection $chunk)  {
        // $error_msg='';
        // $records = $chunk->map(function ($row) {
        // $index = 1;
        // #tradingsymbol                                        
        // $tradingsymbol = trim($row[2]);

        // #name(NIFTY)(script)
        // $filescript_name = trim($row[3]);

        // #extension
        // $fileextension = trim($row[4]);

        // #expiry                                                 
        // $fileexpiry_date = trim($row[6]); 

        // #strike
        // $filestrike_price = trim($row[7]); 

        // #lot_size (qty)       
        // $filelot_size_qty = trim($row[9]);

        // #segment (Market)
        // $filemarket = trim($row[11]);

        // #instrument Type
        // $fileinstrument_type = trim($row[10]);

        
        // $trading_extension = ($filemarket=='NSEOPT')?$tradingsymbol:$filescript_name.'-'.$fileextension;

        // $market = Nex_Market::where('market_name', $filemarket)->first();
        // if($market===null)
        // {
        //     $error_msg = '['.$index.'] '. $filemarket.' Market Not Found!<br>';
        //     return null;
        // }
        // $market_id = $market ? $market->id : 0;

        // // // Get script id based on script name
        // // $script = Nex_script::where('script_name', $row['B'])->where('market_id',$market_id)->first();

        // $nexscript = Nex_script::updateOrCreate(
        //     ['script_name' => $filescript_name,'market_id'=>$market_id],
        //     [
        //         'market_name' => $filemarket,
        //         'script_name' => $filescript_name,
        //         'script_full_name' => $filescript_name,
        //         'script_quantity' => $filelot_size_qty,
        //         'market_id' => $market_id
        //     ]
        // );  

        // $script_id = $nexscript ? $nexscript->id : 0;
        // $expiry_date = Carbon::createFromFormat('d/m/Y', $fileexpiry_date)->format('Y-m-d');
        // $Nex_script_expire = Nex_script_expire::updateOrCreate(
        //     ['script_id' =>  $nexscript->id,'market_id'=>$market_id,'expiry_date'=>$expiry_date,'script_trading_symbol'=>$tradingsymbol],
        //     [              
        //         'market_id' => $market_id,
        //         'script_id' => $script_id,                         
        //         'expiry_date'=>$expiry_date,
        //         'script_trading_symbol'=>$tradingsymbol,
        //         'script_extension'=>$trading_extension,
        //         'script_strike_price'=>$filestrike_price,
        //         'script_instrument_type'=>$fileinstrument_type,
        //         'market_name' => $filemarket,
        //         'script_name' => $filescript_name,
        //     ]
        // );        


        //               })->toArray();
            
            // DB::table('products')->insert($records);
  
        // });
    }
}
