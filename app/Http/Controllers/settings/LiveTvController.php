<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\Nex_live_tv;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LiveTvController extends Controller
{
    
    public function livetv()
    {
       
        $tvdata = Nex_live_tv::where('status', 'active')->paginate(10);

        $file['tvdata'] = $tvdata;
        $file['title'] = ucwords("live tv");

        return view('/settings/livetv/livetv', $file);
    }

    public function livetv_paginate_data(Request $request)
    {

        if ($request->ajax()) {
            
            $Data = DB::table('nex_live_tvs');
            $thead = ['LANGUAGE','LINK','UPDATED AT','ACTION'];
            if(!empty($request->market_name))
                $Data->where('market_id','=',$request->input('market_name'));
            
            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('language','LIKE','%'.$request->search."%")->orWhere('video_link','LIKE','%'.$request->search."%")->orWhere('updated_at','LIKE','%'.$request->search."%");
                });
            }
            $tbody = $Data->paginate(10);
            $tbody_data = $tbody->items();
            foreach ($tbody_data as $key => $data) {
                $tbody_data[$key] = 
                    [
                        ucwords($data->language),
                        $data->video_link,
                        $data->updated_at,
                        '<button class="btn-success edit-button" data-id="' . $data->id . '"><i data-feather=\'edit\' class="align-baseline"></i></button>
                        <button class="btn-danger delete-button" data-id="' . $data->id . '" onclick="confirmDelete(' . $data->id . ')"><i data-feather=\'trash-2\' class="align-baseline"></i></button>'
                    ];
          }

          $tbody->setCollection(new Collection($tbody_data));

            return view('datatable.datatable', compact('tbody','thead'))->render();
        }
      
    }

   
    public function livetv_store(Request $request)
    {
        // Validate the incoming request data
        $validated = Validator::make($request->all(), [
            'language' => 'required',
            'video_link' => 'required',
        ]);

        if ($validated->fails()) {
            return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);
        }        

        $flight = Nex_live_tv::updateOrCreate(
            ['id' => $request->id],
            [
                'language' => $request->language,
                'video_link' => $request->video_link,
            ]
        );

        return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('livetv')]);
    }

    public function editlivetv($id)
    {
        $livetvdata = Nex_live_tv::find($id);

        if ($livetvdata) {
            return response()->json($livetvdata);
        } else {
            return response()->json(['error' => 'Data not found'], 404);
        }
    }

    public function deletelivetv(Request $request)
    {
        $livetv = Nex_live_tv::find($request->id);
        $livetv->delete();
   
        return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>route('livetv')]);
    }
}
