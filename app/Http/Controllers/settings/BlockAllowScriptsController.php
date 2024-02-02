<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\Nex_Market;
use App\Models\Nex_block_master_script;
use App\Models\Nex_script;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BlockAllowScriptsController extends Controller
{

    public function view_block_master_script(Request $request)
    {

        // $scriptdata = Nex_script::where('is_ban', 'yes')->paginate(10);
        $marketdata = Nex_Market::where('market_status', 'active')->get();
        $userdata = Administrator::all();
        $file['marketdata'] = $marketdata;
        $file['userdata'] = $userdata;
        // $file['scriptdata'] = $scriptdata;
        $file['title'] = ucwords("Block/Allow Scripts");
        return view('/settings/block_allow_scripts/block_allow', $file);
    }


    public function block_master_script_paginate_data(Request $request)
    {

        if ($request->ajax()) {
            
            $Data = DB::table('nex_block_master_scripts')
            ->join('administrators', 'nex_block_master_scripts.user_id', '=', 'administrators.id')->select('nex_block_master_scripts.*','administrators.username')
            ->join('markets', 'nex_block_master_scripts.market_id', '=', 'markets.id')->select('nex_block_master_scripts.*','administrators.username')
            ->join('scripts', 'nex_block_master_scripts.script_id', '=', 'scripts.id')->select('nex_block_master_scripts.*','administrators.username','scripts.script_name','markets.market_name');
          
            $thead = ['USER NAME','MARKET NAME','SCRIPT NAME','UPDATED AT','ACTION'];
            if(!empty($request->market_name))
                $Data->where('market_id','=',$request->input('market_name'));
            
            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('scripts.script_name','LIKE','%'.$request->search."%")->orWhere('markets.market_name','LIKE','%'.$request->search."%")->orWhere('administrators.username','LIKE','%'.$request->search."%")->orWhere('nex_block_master_scripts.updated_at','LIKE','%'.$request->search."%");
                });
            }
            $tbody = $Data->paginate(10);
            $tbody_data = $tbody->items();
            foreach ($tbody_data as $key => $data) {
                $tbody_data[$key] = 
                    [
                        ucwords($data->username),
                        $data->market_name,
                        $data->script_name,
                        $data->updated_at,
                        ' <button class="btn-success  delete-button" data-id="{{ $data->id }}" onclick="confirmDelete(' . $data->id . ')"">Unblock
                    
                        </button>'
                       
                    ];
          }

          $tbody->setCollection(new Collection($tbody_data));

            return view('datatable.datatable', compact('tbody','thead'))->render();
        }
     
    }


    public function block_allow_script_store(Request $request)
    {
        //  return($request->all());
        $validated = Validator::make($request->all(), [
            'market_id' => 'required', // Make sure market_id is required
            'script_id' => 'required',
            'user_id'=> 'required',
            

            
        ]);
    
        if ($validated->fails()) {
            return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);
        }
        $market = Nex_Market::find($request->market_id);
        $user = Administrator::find($request->level_id);
        $script = Nex_script::find($request->script_id);
        $dataToUpdate = Nex_block_master_script::find($request->id);
    
        // Prepare the data for update or create
        
        $data = [
            'user_id' => $request->user_id,
            'market_id' => $request->market_id,
            'script_id' => $request->script_id,        
        ];
    
        if ($dataToUpdate) {
            // If the script exists, update it
            $dataToUpdate->update($data);
        } else {
            // If the script doesn't exist, create a new one
            Nex_block_master_script::create($data);
        }
    
        return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('view_block_master_script')]);
    }

    public function deleteallowscript(Request $request)
    {
        $script = Nex_block_master_script::find($request->id);
        $script->delete();

        return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('view_block_master_script')]);
    }
    
}
