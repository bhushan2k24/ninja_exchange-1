<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\Nex_Market;
use App\Models\Nex_time_setting;
use App\Models\Nex_script;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class TimeSettingController extends Controller
{
    public function view_time_setting(Request $request)
    {

        // $scriptdata = Nex_script::where('is_ban', 'yes')->paginate(10);
        $marketdata = Nex_Market::where('market_status', 'active')->get();
        $file['marketdata'] = $marketdata;
        // dd($marketdata);
        // $file['scriptdata'] = $scriptdata;

        $file['title'] = ucwords("Time Settings");
        return view('settings/time_settings/time_setting', $file);
    }


    public function time_setting_paginate_data(Request $request)
    {

        if ($request->ajax()) {
            
            $Data = DB::table('nex_time_settings')
            ->leftJoin('markets', 'nex_time_settings.market_id', '=', 'markets.id')
            ->leftJoin('scripts', 'nex_time_settings.script_id', '=', 'scripts.id')
            ->select('nex_time_settings.*', 'scripts.script_name', 'markets.market_name');          
          
            $thead = ['MARKET NAME','SCRIPT NAME','START TIME','END TIME','UPDATED AT','ACTION'];
            if(!empty($request->market_name))
                $Data->where('market_id','=',$request->input('market_name'));
            
            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('scripts.script_name','LIKE','%'.$request->search."%")->orWhere('markets.market_name','LIKE','%'.$request->search."%")->orWhere('nex_time_settings.start_time','LIKE','%'.$request->search."%")->orWhere('nex_time_settings.updated_at','LIKE','%'.$request->search."%");
                    
                });
                
            }
            $tbody = $Data->paginate(10);
            $tbody_data = $tbody->items();
            foreach ($tbody_data as $key => $data) {
                $tbody_data[$key] = 
                    [
                       
                        $data->market_name,
                        $data->script_name ?: 'ALL',
                        $data->start_time,
                        $data->end_time,
                        $data->updated_at,
                        ' <button class="btn-success  delete-button" data-id="{{ $data->id }}" onclick="confirmDelete(' . $data->id . ')"">Remove
                        </button>'
                       
                    ];
          }

          $tbody->setCollection(new Collection($tbody_data));

          return view('datatable.datatable', compact('tbody','thead'))->render();
        }
     
    }


    public function time_setting_store(Request $request)
    {
        //  return($request->all());
        $validated = Validator::make($request->all(), [
            'market_id' => 'required', // Make sure market_id is required
            // 'script_id' => 'required',
            'start_time'=>'required',
            'end_time'=>'required',

        ]);
    
        if ($validated->fails()) {
            return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);
        }
      
        $dataToUpdate = Nex_time_setting::find($request->id);
    

        
        $data = [
            'market_id' => $request->market_id,
            
            'script_id' => $request->has('script_id') ? $request->script_id : 0,
                   
            'start_time' => $request->start_time,        
            'end_time' => $request->end_time,        
        ];
    
        if ($dataToUpdate) {
            // If the script exists, update it
            $dataToUpdate->update($data);
        } else {
            // If the script doesn't exist, create a new one
            Nex_time_setting::create($data);
        }
    
        return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('view_time_setting')]);
    }


    public function deletetime(Request $request)
    {
        $time = Nex_time_setting::find($request->id);
        $time->delete();

        return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('view_time_setting')]);
    }

}
