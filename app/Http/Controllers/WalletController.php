<?php

namespace App\Http\Controllers;

use App\Models\Nex_wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function wallet()
    {
       
        $wallet = Nex_wallet::where('user_id',Auth::id())->paginate(10);

        $file['wallet'] = $wallet;
        $file['title'] = ucwords("wallet");

        // dd( $file['wallet']);

        return view('wallet.wallet', $file);
    }

    public function wallet_paginate_data(Request $request)
    {

        if ($request->ajax()) {
            
            $Data = DB::table('Nex_wallets')->where('user_id',Auth::id());
            
            $thead = ['TRANSACTION TYPE','AMOUNT','REMARKS',"TRANSACTION ID",'UPDATED AT'];
            if(!empty($request->market_name))
                $Data->where('market_id','=',$request->input('market_name'));
            
            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('wallet_transaction_type','LIKE','%'.$request->search."%")->orWhere('wallet_amount','LIKE','%'.$request->search."%")->orWhere('updated_at','LIKE','%'.$request->search."%");
                });
            }
            $tbody = $Data->paginate(10);

            $tbody_data = $tbody->items();
            foreach ($tbody_data as $key => $data) {
                $tbody_data[$key] = 
                    [
                        ucwords($data->wallet_transaction_type),
                        $data->wallet_amount,
                        $data->wallet_remarks,
                        $data->wallet_transaction_id,
                        $data->updated_at,
                       
                    ];
          }

          $tbody->setCollection(new Collection($tbody_data));

            return view('datatable.datatable', compact('tbody','thead'))->render();
        }
      
    }

    public function wallet_store(Request $request)
    {
        // Validate the incoming request data
        $validated = Validator::make($request->all(), [
            'wallet_transaction_type' => 'required',
            'wallet_amount' => 'required',
        ]);

        if ($validated->fails()) {
            return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);
        }        

        $wallet = Nex_wallet::updateOrCreate( 
            ['id' => $request->id],
            [
                'user_id' => Auth::id(),
                'wallet_transaction_type' => $request->wallet_transaction_type,
                'wallet_amount' => $request->wallet_amount,
                'wallet_transaction_id' => rand(11111111,99999999),
            ]
        );

        return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('wallet.view')]);
    }
}
