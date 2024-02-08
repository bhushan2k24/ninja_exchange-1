<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\Nex_Market;
use App\Models\Nex_level;
use App\Models\Nex_max_quantity;
use App\Models\Nex_script;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;


class MaxQuantityController extends Controller
{
    public function view_max_quantity(Request $request)
    {
      

      $max_quantity_data = nex_max_quantity::where('max_quantity_status', 'active')->paginate(1);
       
  
     
      $data = ['max_quantity_data' => $max_quantity_data];
  

      $marketdata = Nex_Market::where('market_status', 'active')->where('is_expiry','yes')->get();
      $leveldata = nex_level::where('level_status', 'active')->get();
      $file['marketdata'] = $marketdata;
      $file['leveldata'] = $leveldata;
 
      $file['max_quantity_data'] = $max_quantity_data;
      $file['title'] = ucwords("Level Wise Max Quantity");
  
      return view('settings.max_quantity.max_quantity', $file);
    }


    public function max_quantity_store(Request $request)
    {
        //  return($request->all());
        $validated = Validator::make($request->all(), [
            'market_id' => 'required', // Make sure market_id is required
            'script_id' => 'required',
            'level_id'=> 'required',
            'max_quantity_position'=> 'required',
            'max_quantity_max_order'=> 'required',

            
        ]);
    
        if ($validated->fails()) {
            return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);
        }
        $market = Nex_Market::find($request->market_id);
        $level = nex_level::find($request->level_id);
        $script = Nex_script::find($request->script_id);
        $dataToUpdate = nex_max_quantity::find($request->id);
    
        // Prepare the data for update or create
        $data = [
            'level_id' => $request->level_id,
            'level_name' => $level->level_name,
            'market_id' => $request->market_id,
            'market_name' => $market->market_name,
            'script_name' =>$script->script_name,
            'script_id' => $request->script_id,
            'max_quantity_position' => $request->max_quantity_position,
            'max_quantity_max_order' => $request->max_quantity_max_order
            
        ];
    
        if ($dataToUpdate) {
            // If the script exists, update it
            $dataToUpdate->update($data);
        } else {
            // If the script doesn't exist, create a new one
            nex_max_quantity::create($data);
        }
    
        return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('view.max_quantity')]);
    }

    public function fetchmaxquantitydata(Request $request)
    {
        $levelId = $request->input('level_id');

        $condition=$levelId > 0 ? '=' : '>';
        $max_quantity_data = nex_max_quantity::where('level_id', $condition,$levelId)->limit(10)->get();
        return response()->json($max_quantity_data);
    }


    public function max_quantity_paginate_data(Request $request)
    {

        if ($request->ajax()) {
            
            $Data = DB::table('nex_max_quantities');
            $thead = ['LEVEL NAME','MARKET NAME','SCRIPT NAME','POSITION LIMIT','MAX ORDER','UPDATED AT'];
            if(!empty($request->level_name))
                $Data->where('level_id','=',$request->input('level_name'));
            
            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('script_name','LIKE','%'.$request->search."%")->orWhere('market_name','LIKE','%'.$request->search."%")->orWhere('level_name','LIKE','%'.$request->search."%")->orWhere('max_quantity_max_order','LIKE','%'.$request->search."%")->orWhere('max_quantity_position','LIKE','%'.$request->search."%")->orWhere('updated_at','LIKE','%'.$request->search."%");
                });
            }
            $tbody = $Data->paginate(10);
            $tbody_data = $tbody->items();
            foreach ($tbody_data as $key => $data) {
                $tbody_data[$key] = 
                    [
                        $data->level_name,
                        $data->market_name,
                        $data->script_name,
                        $data->max_quantity_position,
                        $data->max_quantity_max_order,
                        $data->updated_at
                        
                       
                    ];
          }

          $tbody->setCollection(new Collection($tbody_data));

            return view('datatable.datatable', compact('tbody','thead'))->render();
        }
       
    }
    #----------------------------------------------------------------

    #----------------------------------------------------------------
    #import Level Wise Max Quantity      
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), ['level_import' => 'required|mimes:xlsx,xls,csv']);

        if ($validator->fails()) 
            return faildResponse(['Message'=>'Validation Warning', 'Data'=>$validator->errors()->toArray()]);       

        $file = $request->file('level_import');      

        try {
            $spreadsheet = IOFactory::load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $error_msg = '';

            if(!array_slice($sheetData, 1))
                return faildResponse(['Message' => 'Failed!', 'Data' => ['level_import'=>['[!] Inserted File is Empty']]]);

            // Process $sheetData and store it in the database or perform other actions
            // For example, assuming the first row contains headers, you can start from the second row:
            foreach (array_slice($sheetData, 1) as $key => $row) {
                $index=$key+2;
                $market = Nex_Market::where('market_name', $row['B'])->first();
                if($market=== null)
                {
                    $error_msg .= '['.$index.'] '. $row['B'].' Market Not Found!<br>';
                    continue;
                }
                $market_id = $market ? $market->id : 0;
        
                // Get script id based on script name
                $script = Nex_script::where('script_name', $row['C'])->where('market_id',$market_id)->first();
                if($script=== null)
                {
                    $error_msg .= '['.$index.'] '. $row['C']. ' Script Not Found!<br>';
                    continue;
                }
                elseif($script->market_id!=$market_id){
                    $error_msg .= '['.$index.'] '. $row['C'].' Script Not Found For Market You Entered!<br>';
                    continue;
                }

                $script_id = $script ? $script->id : 0;
                 // Get level id based on level name
                 $level = nex_level::where('level_name', $row['A'])->first();
                 if($level=== null){
                     $level= nex_level::create([
                      'level_name'=>$row['A']
                       ]);
                   }
                 $level_id = $level ? $level->id : null;       

                // $row['column_name'] represents the data in each column of the Excel sheet
                // Insert the data into the database or perform other actions
                $nex_max_quantity=nex_max_quantity::updateOrCreate(
                    ['script_id' =>  $script->id,'market_id'=>$market->id,'level_id'=>$level->id],
                    [
                        'level_name' => $row['A'],
                        'market_name' => $row['B'],
                        'script_name' => $row['C'],
                        'max_quantity_position' => $row['D'],
                        'max_quantity_min_order' => $row['E'],
                        'max_quantity_max_order' => $row['F'],
                        'market_id' => $market_id,
                        'script_id' => $script_id,
                        'level_id' => $level_id,
                    ]
                );      
            }
            if($error_msg!='')
                return faildResponse(['Message' => 'Failed!', 'Data' => ['level_import'=>[$error_msg]]]);

            // Redirect back with a success message
            return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('view_max_quantity')]);
        } catch (\Exception $e) {
            // Handle import errors, e.g., invalid file format
            // return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
            return faildResponse(['Message' => 'Failed!', 'Data' => ['level_import'=>['Error importing data: ' . $e->getMessage()]]]);
        }    
    }

    
}
