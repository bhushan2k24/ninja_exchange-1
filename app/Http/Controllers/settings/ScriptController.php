<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\Nex_Market;
use App\Models\Nex_max_quantity;
use App\Models\Nex_script;
use App\Models\Nex_script_expire;
use Carbon\Carbon;
use COM;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image;

use App\Imports\ScriptImport;


use App\Jobs\ImportExcelCsvJob;
class ScriptController extends Controller
{
    public $batchId;
    public $importFile;
    public $importing = false;
    public $importFilePath;
    public $importFinished = false;
    /**
     * Prepares data for the script management page view, including breadcrumbs, market data, and a script list form.
     * Returns the view with the processed data.
     */
    public function viewscript()
    {

        $file['breadcrumbs'] = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => 'Scripts']];      

        $file['marketdata'] = marketData();
        $file['tableFormData'] = [
            'name'=>'scriptlist-form',
            'action'=>route('script_paginate_data'),
            'btnGrid'=>2,
            'no_submit'=>1,
            'fieldData'=>[
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'Market',
                    'name'=>'marketname',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=> $file['marketdata'],
                    'outer_div_classes'=>'mb-0',
                    'element_extra_classes'=>'select2',
                    'element_extra_attributes'=>' '
                ]
            ],
        ];  
        $file['title'] = ucwords("Script");        
        return view('settings.script.viewscript', $file);
    }
    #----------------------------------------------------------------
    #----------------------------------------------------------------
    #Method to get script pagination data 
    public function script_paginate_data(Request $request)
    {
        if ($request->ajax()) 
        {            
            $Data = Nex_script::select('*')->addSelect('nex_scripts.id as id')->where('is_ban', 'no')->join('nex_markets', 'nex_markets.id', '=', 'nex_scripts.market_id');
            
            $thead = ['SCRIPT NAME','SYMBOL','MARKET NAME','QUANTITY','UPDATED AT','ACTION'];

            if(!empty($request->marketname))
                $Data->where('market_id','=',$request->marketname);
            
            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('script_name', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('nex_markets.market_name', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('script_quantity', 'LIKE', '%' . $request->search . '%');
                });
            }

            $limit = $request->limit ? $request->limit : 10;
            $tbody = $Data->paginate($limit);
            $tbody_data = $tbody->items();
            
            foreach ($tbody_data as $key => $data) {
                $tbody_data[$key] = 
                [                     
                    $data->script_full_name,
                    $data->script_name,
                    $data->market_name,
                    $data->script_quantity,
                    $data->updated_at,
                    '<a href="javascript:void(0);" class="avatar avatar-status bg-light-danger delete_record" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" deleteto="'.encrypt_to('nex_scripts').'/'.encrypt_to($data->id).'">
                        <span class="avatar-content">
                            <i data-feather=\'trash-2\' class="avatar-icon"></i>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="avatar avatar-status bg-light-primary edit2-button openmodal-ajaxModel" recordof="'.encrypt_to('nex_scripts').'/'.encrypt_to($data->id).'" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" id="'.$data->id.'">
                        <span class="avatar-content">
                            <i data-feather=\'edit\' class="avatar-icon"></i>
                        </span>
                    </a>'                        
                ];
          }
          $tbody->setCollection(new Collection($tbody_data));
          return view('datatable.datatable', compact('tbody','thead'))->render();
        }
    }
    #----------------------------------------------------------------
    #----------------------------------------------------------------
    #Script Data Storation Method
    public function script_store(Request $request)
    {
        // Validate the incoming request data
        $validated = Validator::make($request->all(), [
            'market_id' => 'required',
            'script_name' => 'required',
            'script_quantity' => 'required|numeric',
        ]);
        if ($validated->fails()) {
            return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);
        }
        $market = Nex_Market::find($request->market_id);
        $nex_max_script = Nex_script::updateOrCreate(
            ['id' => $request->id,'market_id'=>$request->market_id],
            [
                'market_id' => $request->market_id,
                'market_name' => $market->market_name,
                'script_name' => $request->script_name,
                'script_quantity' => $request->script_quantity,
            ]
        );   
        
        return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('view.script')]);
    }
    #----------------------------------------------------------------
    #----------------------------------------------------------------
    /**
     * Prepares data for the "Ban Scripts" page view, including breadcrumbs and an empty script list form.
     * Sets the page title, retrieves market data, and returns the view with processed data.
     */
     public function banscript(Request $request)
     {
         $file['breadcrumbs'] = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => 'Ban Scripts']];             
 
         $file['tableFormData'] = [
             'name'=>'scriptlist-form',
             'action'=>route('banscript_paginate_data'),
             'btnGrid'=>2,
             'no_submit'=>1,
             'fieldData'=>[],
         ];
   
         $file['title'] = ucwords("Ban Script");
         $file['marketdata'] = marketData();
         
         return view('settings.script.banscript', $file); 
    }
    #----------------------------------------------------------------
    #----------------------------------------------------------------
    #Ban Script DadaTable 
    public function banscript_paginate_data(Request $request)
    {
        if ($request->ajax()) {            
            $Data = DB::table('nex_scripts')->where('is_ban', 'yes');
            $thead = ['MARKET NAME','SCRIPT NAME','UPDATED AT','ACTION'];
            if(!empty($request->market_name))
                $Data->where('market_id','=',$request->input('market_name'));
            
            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('script_name','LIKE','%'.$request->search."%")->orWhere('market_name','LIKE','%'.$request->search."%")->orWhere('updated_at','LIKE','%'.$request->search."%");
                });
            }
            $tbody = $Data->paginate(10);
            $tbody_data = $tbody->items();
            foreach ($tbody_data as $key => $data) {
                $tbody_data[$key] = 
                [
                    $data->market_name,
                    $data->script_name,
                    $data->updated_at,
                    ' <button class="btn-success  delete-button" data-id="{{ $data->id }}" onclick="confirmunban(' . $data->id . ')">Unban</button>'
                    
                ];
          }
          $tbody->setCollection(new Collection($tbody_data));

          return view('datatable.datatable', compact('tbody','thead'))->render();
        }     
    }
    #----------------------------------------------------------------
    #----------------------------------------------------------------
    #Add ban Script 
    public function banscript_action(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'market_id' => 'required',
            'script_id' => 'required'
        ]);

        if ($validated->fails()) {
            return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);
        }

        $dataToUpdate = Nex_script::find($request->script_id);
        if (!$dataToUpdate)
            return faildResponse(['Message' => 'Script not found']);

        $dataToUpdate->update(['is_ban' => 'yes']);
        return successResponse(['Message' => 'Script banned successfully!', 'Data' => [], 'Redirect' => route('banscript')]);
    }
    #----------------------------------------------------------------
    #----------------------------------------------------------------
    #Method to unbanscript
    public function unbanscript(Request $request)
    {
        $script = Nex_script::find($request->id);
        $script->update(['is_ban' => 'no']);
        return successResponse(['Message' => 'Script unbanned successfully!', 'Data' => [], 'Redirect' => route('banscript')]);
    }
    #----------------------------------------------------------------
    #----------------------------------------------------------------
    #Method To get market wise script
    public function fetchScriptData(Request $request)
    {
        $marketId = $request->input('market_id');

        $condition=$marketId > 0 ? '=' : '>';
        $scriptData = Nex_script::where('market_id', $condition, $marketId)->limit(10) 
        ->get();
        return response()->json($scriptData);
    }
    #----------------------------------------------------------------
    #----------------------------------------------------------------
    #method to import Script    
    public function importScript(Request $request)
    {
        $validator = Validator::make($request->all(), ['import_file' => 'required|file|mimes:xlsx,xls,csv']);

        if ($validator->fails()) 
            return faildResponse(['Message'=>'Validation Warning', 'Data'=>$validator->errors()->toArray()]);  
            
        $file = $request->file('import_file'); 

        if ($request->hasFile('import_file')) 
        {
            try {

                $file = $request->file('import_file');     
                               
                $profile_picture = $filename = 'scriptlist.'.$file->getClientOriginalExtension();
                $request->import_file->move(public_path('/upload/seed/'), $filename);

                Excel::queueImport(new ScriptImport, public_path('/upload/seed/').$filename);

                $errorMsg = cache("import_script_error_msg");
                if (!$errorMsg) 
                    return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' =>url()->previous()]);

                return faildResponse(['Message' => 'Failed!', 'Data' => ['import_file'=>[$errorMsg]]]);

            } catch (\Exception $e) {
                cache("import_script_end_date",now());
                cache("import_script_current_row")->forget();
                // Handle exceptions during seeder execution
                return faildResponse(['Message' => 'Failed!', 'Data' => ['import_file'=>['Error importing data: ' . $e->getMessage()]]]);
            }
        }   

        die;
        $import = new ScriptImport();
        try {
            $data = Excel::queueImport($import, $file);
            if($import->error_msg!='')
            return faildResponse(['Message' => 'Failed!', 'Data' => ['import_file'=>[$import->error_msg]]]);

            // Redirect back with a success message
            return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' =>url()->previous()]);

        } catch (\Exception $e) {
            return faildResponse(['Message' => 'Failed!', 'Data' => ['import_file'=>['Error importing data: ' . $e->getMessage()]]]);
        }    

    }

    public function scriptImportStatus(Request $request )
    {   
        // $batch = null;
        // if($request->batch_id)
            // $batch = Bus::findBatch(cache("import_script_batchId"));
       
            $is_started = (int)cache("import_script_current_row")?:0;    
  
            $error = cache()->pull("import_script_error_msg")?:        
        ((filled(cache("all_import_status_error")) && !$is_started>0) ? cache()->pull("all_import_status_error"):false);

 

            return response([
                'started' => $is_started>0,
                'finished' => !$is_started>0,
                'imported_all' => (!filled(cache("all_import_status_error"))),
                'current_row' => (int) cache("import_script_current_row")?:0,
                'total_rows' => (int) cache("import_script_total_rows")?:0,
                'error'     =>  $error
            ]);

            // Cache::pull('key');
            // return response([
            //     // "batch" => $batch->progress(),
            //     'started' => filled(cache("import_script_start_date")),
            //     'finished' => filled(cache("import_script_end_date")),
            //     'current_row' => (int) cache("import_script_current_row")?:0,
            //     'total_rows' => (int) cache("import_script_total_rows")?:0,
            // ]); 
            return response([
                // "batch" => $batch->progress(),
                'started' => filled(cache("import_script_start_date")),
                'finished' => filled(cache("import_script_end_date")),
                'current_row' => (int) cache("import_script_current_row")?:0,
                'total_rows' => (int) cache("import_script_total_rows")?:0,
            ]);
    }
    
    #----------------------------------------------------------------
    #----------------------------------------------------------------
    #method to import Ban Script    
    public function importBanScript(Request $request)
    {
        $validator = Validator::make($request->all(), ['import_file' => 'required|file|mimes:xlsx,xls,csv']);

        if ($validator->fails()) 
            return faildResponse(['Message'=>'Validation Warning', 'Data'=>$validator->errors()->toArray()]);       

        $file = $request->file('import_file');      

        try {
            $spreadsheet = IOFactory::load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $error_msg = '';
            if(!array_slice($sheetData, 1))
                return faildResponse(['Message' => 'Failed!', 'Data' => ['import_file'=>['[!] Inserted File is Empty']]]);

            // Process $sheetData and store it in the database or perform other actions
            // For example, assuming the first row contains headers, you can start from the second row:
            foreach (array_slice($sheetData, 1) as $key => $row) {
                $index=$key+2;
                $row['B'] = trim($row['B']);
                $row['C'] = trim($row['C']);
                $market = Nex_Market::where('market_name', trim($row['C']))->first();
                if($market=== null)
                {
                    $error_msg .= '['.$index.'] '. trim($row['C']).' Market Not Found!<br>';
                    continue;
                }
                $market_id = $market ? $market->id : 0;

                $script_Data = Nex_script::where(['script_name'=>$row['B'],'market_id'=>$market_id])->first();
                if($script_Data=== null)
                {
                    $error_msg .= '['.$index.'] '. $row['B'].' With '. $row['C'].' Market Not Found!<br>';
                    continue;
                }
                $script_Data->update(['is_ban' => 'yes']);        
            }
            if($error_msg!='')
                return faildResponse(['Message' => 'Failed!', 'Data' => ['import_file'=>[$error_msg]]]);
            // Redirect back with a success message
            return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => url()->previous()]);
        } catch (\Exception $e) {
            return faildResponse(['Message' => 'Failed!', 'Data' => ['import_file'=>['Error importing data: ' . $e->getMessage()]]]);
        }    
    }
}
