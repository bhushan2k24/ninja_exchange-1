<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\Nex_Market;
use App\Models\Nex_script;
use App\Models\Nex_script_expire;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExpiryController extends Controller
{
    public function viewexpiry(Request $request)
    {
      

      $expirydata = Nex_script_expire::where('status', 'active')->paginate(10);
        // dd($expirydata);
  
     
      $data = ['expirydata' => $expirydata];
  

      $marketdata = Nex_Market::where('market_status', 'active')->where('is_expiry','yes')->get();
      $file['marketdata'] = $marketdata;

      $file['expirydata'] = $expirydata;
      $file['title'] = ucwords("expiry");
  
      return view('/settings/expiry/viewexpiry', $file);
    }

    public function expiry_paginate_data(Request $request)
    {

        if ($request->ajax()) 
        {
            
            $Data = DB::table('nex_script_expires');
            $thead = ['MARKET NAME','SCRIPT NAME','TRADING SYMBOL','EXPIRY DATE','UPDATED AT','ACTION'];
            if(!empty($request->market_name))
                $Data->where('market_id','=',$request->input('market_name'));
            
            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('script_name','LIKE','%'.$request->search."%")->orWhere('market_name','LIKE','%'.$request->search."%")->orWhere('expiry_date','LIKE','%'.$request->search."%")->orWhere('script_trading_symbol','LIKE','%'.$request->search."%")->orWhere('script_instrument_type','LIKE','%'.$request->search."%");
                });
            }
            $tbody = $Data->paginate(10);
            $tbody_data = $tbody->items();
            foreach ($tbody_data as $key => $data) {
                $tbody_data[$key] = 
                    [
                        $data->market_name,
                        $data->script_name,
                        $data->script_trading_symbol,
                        $data->expiry_date,
                        $data->updated_at,
                        '<button class="btn-success edit-button" data-id="' . $data->id . '"><i data-feather=\'edit\' class="align-baseline"></i></button>
                        <button class="btn-danger delete-button" data-id="' . $data->id . '" onclick="confirmDelete(' . $data->id . ')"><i data-feather=\'trash-2\' class="align-baseline"></i></button>'
                    ];
          }

          $tbody->setCollection(new Collection($tbody_data));

            return view('datatable.datatable', compact('tbody','thead'))->render();
        }
        
    }

    public function expiry_store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'market_id' => 'required', // Make sure market_id is required
            'script_id' => 'required',
            'expiry_date' => 'required',
        ]);
    
        if ($validated->fails()) {
            return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);
        }
        $market = Nex_Market::find($request->market_id);
        $script = Nex_script::find($request->script_id);
        $dataToUpdate = Nex_script_expire::find($request->id);
    
        // Prepare the data for update or create
        $data = [
            'market_id' => $request->market_id,
            'market_name' => $market->market_name,
            'script_name' =>$script->script_name,
            'script_id' => $request->script_id,
            'expiry_date' =>$request->expiry_date
        ];
    
        if ($dataToUpdate) {
            // If the script exists, update it
            $dataToUpdate->update($data);
        } else {
            // If the script doesn't exist, create a new one
            Nex_script_expire::create($data);
        }
    
        return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('viewexpiry')]);
    }

    public function getscript(Request $request){

        $script = Nex_script::select('id','script_name')->where('market_id',$request->market_id)->get()->toArray();
        return response()->json(['data'=> $script]);
    }
    

    public function deleteexpiry(Request $request)
    {
        $expiry = Nex_script_expire::find($request->id);
        $expiry->delete();
   
        return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>route('viewexpiry')]);
    }

  
    

        public function fetchexpirydata(Request $request)
        {
            $marketId = $request->input('market_id');

            $condition=$marketId > 0 ? '=' : '>';
            // $scriptData = Nex_Nex_script::where('market_id', $condition, $marketId)->limit(10) 
            // ->get();
            $expirydata = Nex_script_expire::where('market_id', $condition,$marketId)->limit(10)->get();
            return response()->json($expirydata);
        }

        public function editexpiry($id)
        {
            $expirydata = Nex_script_expire::find($id);
    
            if ($expirydata) {
                return response()->json($expirydata);
            } else {
                return response()->json(['error' => 'Data not found'], 404);
            }
        }
    
}
