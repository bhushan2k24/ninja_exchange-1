<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Nex_Market;
use App\Models\Nex_script;
use App\Models\Nex_script_expire;
use App\Models\Nex_trade;
use App\Models\Nex_wallet;
use App\Models\Nex_watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class TradingController extends Controller
{
    #----------------------------------------------------------------
    #WatchList Modules Start
    #WatchList View Page
    public function watchList()
    {
        $file['title'] = 'WatchList';        
        $file['marketdata'] = marketdata(0,0,1);
        
        $watchlist_data=Nex_watchlist::select('id','market_id','watchlist_market_name','watchlist_trading_symbol','watchlist_script_extension')->orderBy('market_id')->get();

        $user_data=Administrator::where('user_position','user')->where('user_status','active')->get();

        $file['watchlist_data'] = $watchlist_data;
        $file['user_data'] = $user_data;

        $file['MyWatchScript'] = $watchlist_data->pluck('watchlist_script_extension');

        return view('trading.watchList', $file);
    }
    #----------------------------------------------------------------
    #----------------------------------------------------------------
    #Remove From Watchlist
    public function removewatchlist(Request $request,$id)
    {
        $watchlist = Nex_watchlist::find($id);
        $watchlist->delete();
   
        return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>route('view.watchlist')]);
    }


    #----------------------------------------------------------------
    #WatchList Save Page

    public function saveWatchList(Request $request)
    {

          $validated = Validator::make($request->all(), [
            'watchlist_filter_market' => 'required',
            'watchlist_filter_script' => 'required',
            'watchlist_filter_expiry' => 'required',
        ]);
        if ($validated->fails()) 
            return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);

        $market = Nex_Market::find($request->watchlist_filter_market);
        $script = Nex_script::find($request->watchlist_filter_script);


        if($market->market_name=='NSEOPT'){
            $validated = Validator::make($request->all(), [
                'watchlist_filter_market' => 'required',
                'watchlist_filter_script' => 'required',
                'watchlist_filter_expiry' => 'required',
                'watchlist_filter_ce_pe' => 'required',
                'watchlist_filter_strick' => 'required',
            ]);
            if ($validated->fails()) 
                return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);
        }


        $script_expire = Nex_script_expire::where('market_id', $request->watchlist_filter_market)
                ->where('script_id', $request->watchlist_filter_script)
                ->where('expiry_date', $request->watchlist_filter_expiry);

                if($market->market_name=='NSEOPT'){
                    $script_expire = $script_expire->where('script_instrument_type', $request->watchlist_filter_ce_pe)->where('script_strike_price', $request->watchlist_filter_strick);
                }
        $script_expire =  $script_expire->first();

        $nex_add_watchlist = Nex_watchlist::updateOrCreate( 
            ['market_id'=>$request->market_id,'script_id' => $request->watchlist_filter_script,'script_expires_id'=>$script_expire->id],
            [
                'user_id'=>Auth::id(),
                'market_id' => $request->watchlist_filter_market,
                'watchlist_market_name' => $market->market_name,
                'script_id' => $request->watchlist_filter_script,
                'watchlist_script_name' => $script->script_name,
                'script_expires_id'=>$script_expire->id,
                'watchlist_script_expiry_date' => $request->watchlist_filter_expiry,
                'watchlist_instrument_type' => $script_expire->script_instrument_type,
                'watchlist_script_strike_price' => $script_expire->script_strike_price,
                'watchlist_trading_symbol' => $script_expire->script_trading_symbol,
                'watchlist_script_extension' => $script_expire->script_extension

            ]
        ); 
        return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route('view.watchlist')]);
    }

    #----------------------------------------------------------------
    #----------------------------------------------------------------
    #Get With Ajax 

    public function getwatchlistdata(Request $request){
        $TradingSymbol = $request->input('TradingSymbol');
        
    

        $data = DB::table('nex_watchlists')
        ->join('nex_scripts','nex_scripts.id','=','nex_watchlists.script_id')
        ->select('nex_watchlists.*','nex_scripts.script_quantity','nex_scripts.is_ban')
        ->where('watchlist_script_extension', $TradingSymbol)->where('user_id',Auth::id())->orderBy('id','DESC')->first();


        return successResponse(['Data' => $data]);
    }
    



    #----------------------------------------------------------------
    #to get dependent filter data
    public function getWatchlistFilterValues(Request $request)
    {
        if(!$request->value_filter)
           return faildResponse(['Message'=>'Please pass a valid filter!']);
        
        $getScripts = new Nex_script_expire;

        if($request->value_filter=='watchlist_filter_script')
        {
            if(!$request->script_id)
               return faildResponse(['Message'=>'Please pass a script!']);
            $filterdata = $getScripts->select('expiry_date')->where(['script_id'=>$request->script_id])->groupBy('expiry_date')->pluck('expiry_date')->toArray();

            return successResponse(['Message' => 'Record Fetched Successfully!','setValueTo'=>'watchlist_filter_expiry','Data'=>$filterdata]);
        }
        elseif($request->value_filter=='watchlist_filter_expiry')
            return successResponse(['Message' => 'Record Fetched Successfully!','setValueTo'=>'watchlist_filter_ce_pe','Data'=>['CE','PE']]);
        elseif($request->value_filter=='watchlist_filter_ce_pe')
        {
            if(!$request->script_id || !$request->expiry_date || !$request->filter_ce_pe)
                return faildResponse(['Message'=>'Please pass a Script , Expiry Date , CE/PE!']);
            
            $filterdata = $getScripts->select('script_strike_price')->where(
                [
                'market_id'=>$request->market_id,
                'script_id'=>$request->script_id,
                'script_instrument_type'=>$request->filter_ce_pe,
                'expiry_date'=>$request->expiry_date
                ]        
            )->where('script_strike_price','>',0)->pluck('script_strike_price')->toArray();

            return successResponse(['Message' => 'Record Fetched Successfully!','setValueTo'=>'watchlist_filter_strick','Data'=>$filterdata]);
        }
        
        return faildResponse(['Message' => 'Provided All Information!']);
    }


    #Trade Store
    public function store_trade(Request $request)
    {
        // dd($request->all());
        $validated = Validator::make($request->all(), [
            'lot' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);

        if ($validated->fails()) 
            return faildResponse(['Message' => 'Validation Warning', 'Data' => $validated->errors()->toArray()]);

        if ($request->tradeBuySell === 'buy'){

            $id = !Auth::guard('admin')->user()->hasRole('user') ? $request->client : Auth::id();


            $totalWalletAmount = Nex_wallet::where('user_id', $id)->sum('wallet_amount');
            if ($request->price > $totalWalletAmount) {
                return faildResponse(['Data'=>['price'=>['Insufficient Balance To Trade']],'Message'=>'Insufficient Balance To Trade']);
            }
        }

        $user_id = $request->client == '' ? Auth::id() : $request->client;
        $created_by = Auth::id();     
        
        
        $updateFields = (!empty($request->id)&& $request->id>0) ? 
        [
            'trade_quantity' => $request->has('quantity') ? $request->quantity : 0,
            'trade_lot' => $request->has('lot') ? $request->lot : 0,
            'trade_price' => $request->has('price') ? $request->price :0,
        ]:
        [
            'user_id' => $user_id,
            'created_by' => Auth::id(),
            'script_expires_id' => $request->has('script_expires_id') ? $request->script_expires_id : 0,
            'trade_bidrate' => $request->has('BuyPrice') ? $request->BuyPrice : 0,
            'trade_askrate' => $request->has('SellPrice') ? $request->SellPrice : 0,
            'trade_ltp' => $request->has('LastTradePrice') ? $request->LastTradePrice : 0,
            'trade_change' => $request->has('PriceChangePercentage') ? $request->PriceChangePercentage : 0,
            'trade_netchange' => $request->has('PriceChange') ? $request->PriceChange : 0,
            'trade_high' => $request->has('High') ? $request->High : 0,
            'trade_low' => $request->has('Low') ? $request->Low : 0,
            'trade_open' => $request->has('Open') ? $request->Open : 0,
            'trade_close' => $request->has('Close') ? $request->Close : 0,
            'trade_type' => $request->has('tradeBuySell') ? $request->tradeBuySell : '',
            'trade_order_type' => '', 
            'trade_reference_id' => $request->has('script_extension') ? $request->script_extension : 0,
            'user_ip' => $request->getClientIp(),

            'trade_quantity' => $request->has('quantity') ? $request->quantity : 0,
            'trade_lot' => $request->has('lot') ? $request->lot : 0,
            'trade_price' => $request->has('price') ? $request->price : 0,
        ];
        


             $nex_trade = Nex_trade::updateOrCreate( 
            ['id' => $request->id],
            $updateFields);
        

            $transactionType = ($request->tradeBuySell === 'buy') ? 'debit' : 'credit';
            $walletAmount = ($request->tradeBuySell === 'buy') ? -abs($nex_trade['trade_price']) : abs($nex_trade['trade_price']);

            $walletData = [
                'user_id' =>  $user_id,
                'wallet_transaction_type' => $transactionType,
                'wallet_amount' => $walletAmount,
                'wallet_transaction_id'=>rand(11111111,99999999)
            ];

            Nex_wallet::create($walletData);
            
            return successResponse(['Message' => 'Success!', 'Data' => [], 'Redirect' => route((!empty($request->id)&& $request->id>0)?'view.trades':'view.watchlist')]);
    }

    #WatchList Modules Stop
    #----------------------------------------------------------------

    #----------------------------------------------------------------
    // traders module ---------------
    public function traders()
    {
        $file['title'] = 'traders';
        $file['tradersFormData'] = [
            'name'=>'watchlist-form',
            'action'=>route('trades-paginate-data'),
            'btnGrid'=>2,
            'submit'=>'find orders',
            'fieldData'=>[
                [
                    'tag'=>'input',
                    'type'=>'checkbox',
                    'label'=>'',
                    'name'=>'order_type',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[
                        [
                            'label'=>'pending orders',
                            'value'=>'pending'
                        ],
                        [
                            'label'=>'executed orders',
                            'value'=>'executed'
                        ]
                    ]
                ],
                [
                    'tag'=>'input',
                    'type'=>'date',
                    'label'=>'trade after',
                    'name'=>'trader_after',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ],
                [
                    'tag'=>'input',
                    'type'=>'date',
                    'label'=>'trade before',
                    'name'=>'trader_before',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'segment',
                    'name'=>'market_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>marketData()
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'script',
                    'name'=>'script_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>scriptData(1)
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'broker',
                    'name'=>'borker_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('broker')
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'master',
                    'name'=>'master_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('master')
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'client',
                    'name'=>'client_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('client')
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'order type',
                    'name'=>'order_type_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>typeData('order')
                ],
                [
                    'tag'=>'input',
                    'type'=>'reset',
                    'value'=>'Filter Reset',
                    'label'=>'',
                    'name'=>'Reset Filter',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[],
                    'outer_div_classes'=>'mb-0',
                    'element_extra_classes'=>'btn btn-outline-secondary mt-2 btn-sm'
                ],
            ],
        ];
        return view('trading.traders', $file);
    }




    public function trades_paginate_data(Request $request)
    {
        if ($request->ajax()) {            
            $Data = DB::table('nex_trades')->join('administrators','administrators.id','nex_trades.user_id')->join('nex_script_expires','nex_script_expires.id','nex_trades.script_expires_id')->select('nex_trades.*','nex_trades.id as trade_id','nex_trades.updated_at as date','administrators.*','nex_script_expires.market_name','nex_script_expires.script_trading_symbol');
            $thead = ['D','CLIENT','MARKET','SCRIPT',"B/S",'ORDER TYPE','LOT','QTY','ORDER PRICE','STATUS','USER IP','UPDATED AT','ACTION'];
            if(!empty($request->market_name))
                $Data->where('market_id','=',$request->input('market_name'));
            
            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('script_name','LIKE','%'.$request->search."%")->orWhere('market_name','LIKE','%'.$request->search."%")->orWhere('updated_at','LIKE','%'.$request->search."%");
                });
            }
            $tbody = $Data->paginate(10);
            // dd($tbody);
            $tbody_data = $tbody->items();
            foreach ($tbody_data as $key => $data) {
                $tbody_data[$key] = 
                [
                    '<i data-feather="smartphone"></i>',
                    $data->usercode,
                    $data->market_name,
                    $data->script_trading_symbol,
                    $data->trade_type,
                    $data->trade_order_type,
                    $data->trade_lot,
                    $data->trade_quantity,
                    $data->trade_price,
                    ucwords($data->trade_status),
                    $data->user_ip,
                    $data->date,
                    '<a href="javascript:void(0);" class="avatar avatar-status bg-light-danger delete_record" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" deleteto="'.encrypt_to('nex_trades').'/'.encrypt_to($data->trade_id).'">
                    <span class="avatar-content">
                        <i data-feather=\'trash-2\' class="avatar-icon"></i>
                    </span>
                    </a>
                    <a href="javascript:void(0);" class="avatar avatar-status bg-light-primary edit2-button openmodal-ajaxModel" recordof="getsingletrade/'.encrypt_to($data->trade_id).'" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" id="'.$data->trade_id.'">
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

    public function getSingleTrade(Request $request)
    {

        $data= DB::table('nex_trades')->join('administrators','administrators.id','nex_trades.user_id')->join('nex_script_expires','nex_script_expires.id','nex_trades.script_expires_id')->select('nex_trades.*','administrators.username','administrators.usercode','nex_script_expires.market_name','nex_script_expires.script_trading_symbol')->where('nex_trades.id',decrypt_to($request->id))->first();

        if($data);
            return successResponse(['Message' => 'Record Fetched Successfully!','Data'=>$data]);

        return faildResponse(['Message' => 'Something went wrong!']);

    }
    
    // ---------------

    // portfolio module ---------------
    public function portfolio()
    {
        $file['title'] = 'portfolio/position';
        $file['portfolioFormData'] = [
            'name'=>'watchlist-form',
            'action'=>route('portfolio-paginate-data'),
            'btnGrid'=>2,
            'submit'=>'find orders',
            'fieldData'=>[
                [
                    'tag'=>'input',
                    'type'=>'radio',
                    'label'=>'',
                    'name'=>'portfolio_type',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[
                        [
                            'label'=>'all',
                            'value'=>'all'
                        ],
                        [
                            'label'=>'outstanding',
                            'value'=>'outstanding'
                        ]
                    ]
                ],
                [
                    'tag'=>'input',
                    'type'=>'radio',
                    'label'=>'',
                    'name'=>'wise_type',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[
                        [
                            'label'=>'client wise',
                            'value'=>'client'
                        ],
                        [
                            'label'=>'script wise',
                            'value'=>'script wise'
                        ]
                    ]
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'Market',
                    'name'=>'market_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>marketData()
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'Script',
                    'name'=>'script_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>scriptData(1)
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'Master',
                    'name'=>'master_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[
                        [
                            'label'=>'1011-DEMO',
                            'value'=>1
                        ],
                        [
                            'label'=>'1012-DEMO MASTER',
                            'value'=>2
                        ],
                        [
                            'label'=>'1013-ONLINE MASTER',
                            'value'=>2
                        ]
                    ]
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'Client',
                    'name'=>'client_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('client')
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'Broker',
                    'name'=>'borker_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('broker')
                ],
                [
                    'tag'=>'input',
                    'type'=>'date',
                    'label'=>'Expire Date',
                    'name'=>'expire_date',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ],
                [
                    'tag'=>'input',
                    'type'=>'reset',
                    'value'=>'Filter Reset',
                    'label'=>'',
                    'name'=>'Reset Filter',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[],
                    'outer_div_classes'=>'mb-0',
                    'element_extra_classes'=>'btn  btn-outline-secondary  mt-2 btn-sm'
                ],
                // [
                //     'tag'=>'button',
                //     'type'=>'button',
                //     'value'=>'rolloverall',
                //     'label'=>'Roll Over All',
                //     'name'=>'rolloverall',
                //     'validation'=>'',
                //     'grid'=>2,
                //     'data'=>[],
                //     'outer_div_classes'=>'mb-0',
                //     'element_extra_classes'=>'btn btn-success'
                // ],
                // [
                //     'tag'=>'button',
                //     'type'=>'button',
                //     'value'=>'closeposition',
                //     'label'=>'Close Position',
                //     'name'=>'closeposition',
                //     'validation'=>'',
                //     'grid'=>2,
                //     'data'=>[],
                //     'outer_div_classes'=>'mb-0',
                //     'element_extra_classes'=>'btn btn-danger '
                // ],
                // [
                //     'tag'=>'button',
                //     'type'=>'button',
                //     'value'=>'rolloverall',
                //     'label'=>'Roll Over All',
                //     'name'=>'rolloverall',
                //     'validation'=>'',
                //     'grid'=>2,
                //     'data'=>[],
                //     'outer_div_classes'=>'mb-0',
                //     'element_extra_classes'=>'btn btn-success'
                // ],
            ],
        ];
        return view('trading.portfolio', $file);
    }




    public function portfolio_paginate_data(Request $request)
    {
        if ($request->ajax()) {            
            $Data = DB::table('nex_trades')->join('administrators','administrators.id','nex_trades.user_id')->join('nex_script_expires','nex_script_expires.id','nex_trades.script_expires_id')->select('nex_trades.*','administrators.*','nex_script_expires.market_name','nex_script_expires.script_trading_symbol','nex_script_expires.expiry_date');
            $thead = ['MARKET','CLIENT','SCRIPT','T. BUY Q','BUY A. P.','T. SELL Q','SELL A. P.','NET Q','A/B P.','MTM','AUTO CLOSE','ACTION'];
            if(!empty($request->market_name))
                $Data->where('market_id','=',$request->input('market_name'));
            
            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('script_name','LIKE','%'.$request->search."%")->orWhere('market_name','LIKE','%'.$request->search."%")->orWhere('updated_at','LIKE','%'.$request->search."%");
                });
            }
            $tbody = $Data->paginate(10);
            // dd($tbody);
            $tbody_data = $tbody->items();
            foreach ($tbody_data as $key => $data) {
                $tbody_data[$key] = 
                [
                    $data->market_name,
                    $data->usercode,
                    $data->script_trading_symbol,
                    $data->trade_quantity,
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    $data->expiry_date,
                    '<a href="javascript:void(0);" class="avatar avatar-status bg-light-danger delete_record" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" deleteto="'.encrypt_to('nex_trades').'/'.encrypt_to($data->id).'">
                    <span class="avatar-content">
                        <i data-feather=\'trash-2\' class="avatar-icon"></i>
                    </span>
                    </a>'
                 
                    
                ];
          }
          $tbody->setCollection(new Collection($tbody_data));

          return view('datatable.datatable', compact('tbody','thead'))->render();
        }     
    }
    // ---------------

    // blockedScript module ---------------
    public function blockedScript()
    {
        $file['title'] = 'Banned/Blocked Scripts';
        
        return view('/trading/bannedScripts', $file);
    }
    // ---------------

    // marginManagement module ---------------
    public function marginManagement()
    {
        $file['title'] = 'Margin Management';

        $file['marginManagementFormData'] = [
            'name'=>'marginManagement-form',
            'action'=>'',
            'btnGrid'=>2,
            'submit'=>'Submit',
            'fieldData'=>[
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'master',
                    'name'=>'master_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('master')
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'client',
                    'name'=>'client_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('client')
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'broker',
                    'name'=>'borker_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('broker')
                ],
                [
                    'tag'=>'button',
                    'type'=>'',
                    'label'=>'Clear Filter',
                    'name'=>'Clear Filter',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ]

            ],
        ];

        return view('/trading/marginManagement', $file);
    }
    // ---------------

    // manualTrade module ---------------
    public function manualTrade()
    {
        $file['title'] = 'Manual Trade';
        $file['manualTradeFormData'] = [
            'name'=>'watchlist-form',
            'action'=>'',
            'btnGrid'=>2,
            'submit'=>'Search',
            'fieldData'=>[
                [
                    'tag'=>'input',
                    'type'=>'date',
                    'label'=>'trade date',
                    'name'=>'trade_date',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>[]
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'market',
                    'name'=>'market_id',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>marketData()
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'script',
                    'name'=>'script_id',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>scriptData(1)
                ],                
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'client',
                    'name'=>'client_id',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>userData('client')
                ],
                [
                    'tag'=>'input',
                    'type'=>'radio',
                    'label'=>'',
                    'name'=>'manualTrade_type',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>[
                        [
                            'label'=>'With Brokerage',
                            'value'=>'With Brokerage'
                        ],
                        [
                            'label'=>'Without Brokerage',
                            'value'=>'Without Brokerage'
                        ]
                    ]
                ],
                [
                    'tag'=>'input',
                    'type'=>'radio',
                    'label'=>'',
                    'name'=>'wise_type',
                    'validation'=>'',
                    'grid'=>1,
                    'data'=>[
                        [
                            'label'=>'Buy',
                            'value'=>'buy'
                        ],
                        [
                            'label'=>'Sell',
                            'value'=>'sell'
                        ]
                    ]
                ],
                [
                    'tag'=>'input',
                    'type'=>'number',
                    'label'=>'LOT',
                    'name'=>'lot',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ],
                [
                    'tag'=>'input',
                    'type'=>'number',
                    'label'=>'QTY',
                    'name'=>'qty',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ],
                [
                    'tag'=>'input',
                    'type'=>'number',
                    'label'=>'Price',
                    'name'=>'price',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ],
                
            ],
        ];

        return view('/trading/manualTrade', $file);
    }
    // ---------------

    // summeryReport module ---------------
    public function summeryReport()
    {
        $file['title'] = 'Summery Report';
        $file['summeryReportFormData'] = [
            'name'=>'watchlist-form',
            'action'=>'',
            'btnGrid'=>2,
            'submit'=>'find orders',
            'fieldData'=>[               
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'market',
                    'name'=>'market_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>marketData()
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'script',
                    'name'=>'script_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>scriptData(1)
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'valan',
                    'name'=>'valan_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[
                        [
                            'label'=>'14AUG-19AUG',
                            'value'=>1
                        ],
                        [
                            'label'=>'07AUG-12AUG',
                            'value'=>2
                        ],
                        [
                            'label'=>'31JUL-05AUG',
                            'value'=>3
                        ],
                        [
                            'label'=>'24JUL-29JUL',
                            'value'=>4
                        ],
                        [
                            'label'=>'17JUL-22JUL',
                            'value'=>5
                        ],
                        [
                            'label'=>'10JUL-15JUL',
                            'value'=>6
                        ]
                    ]
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'master',
                    'name'=>'master_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('master')
                ],
                
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'broker',
                    'name'=>'borker_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('broker')
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'client',
                    'name'=>'client_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('client')
                ],
                [
                    'tag'=>'input',
                    'type'=>'datetime-local',
                    'label'=>'start date',
                    'name'=>'start_date',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ],
                [
                    'tag'=>'input',
                    'type'=>'datetime-local',
                    'label'=>'end date',
                    'name'=>'end_date',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ],
                [
                    'tag'=>'button',
                    'type'=>'',
                    'label'=>'Clear Filter',
                    'name'=>'Clear Filter',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ],
                [
                    'tag'=>'button',
                    'type'=>'',
                    'label'=>'Script Wise Summary',
                    'name'=>'Script Wise Summary',
                    'validation'=>'',
                    'grid'=>3,
                    'extra-class'=>'btn btn-secondary',
                    'data'=>[]
                ]
            ],
        ];
        return view('/trading/summeryReport', $file);
    }
    // ---------------
    
    // selfProfitLoss module ---------------
    public function selfProfitLoss()
    {
        $file['title'] = 'Self Profit & Loss Report';
        $file['selfProfitLossFormData'] = [
            'name'=>'watchlist-form',
            'action'=>route('view.watchlist'),
            'btnGrid'=>2,
            'submit'=>'submit',
            'method'=>'post',
            'fieldData'=>[
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'valan',
                    'name'=>'valan_id',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>[
                        [
                            'label'=>'14AUG-19AUG',
                            'value'=>1
                        ],
                        [
                            'label'=>'07AUG-12AUG',
                            'value'=>2
                        ],
                        [
                            'label'=>'31JUL-05AUG',
                            'value'=>3
                        ],
                        [
                            'label'=>'24JUL-29JUL',
                            'value'=>4
                        ],
                        [
                            'label'=>'17JUL-22JUL',
                            'value'=>5
                        ],
                        [
                            'label'=>'10JUL-15JUL',
                            'value'=>6
                        ]
                    ]
                ],
                [
                    'tag'=>'button',
                    'type'=>'',
                    'label'=>'Clear Filter',
                    'name'=>'Clear Filter',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ]
            ],
        ];
        return view('/trading/selfProfitLoss', $file);
    }
    // ---------------

    // brokerageRefresh module ---------------
    public function brokerageRefresh()
    {
        $file['title'] = 'Brokerage Refresh';
        $file['brokerageRefreshFormData'] =  [
            'name'=>'watchlist-form',
            'action'=>'',
            'btnGrid'=>2,
            'submit'=>'Submit',
            'fieldData'=>[ 
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'client',
                    'name'=>'client_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('client')
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'broker',
                    'name'=>'borker_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('broker')
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'master',
                    'name'=>'master_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>userData('master')
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'valan',
                    'name'=>'valan_id',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[
                        [
                            'label'=>'14AUG-19AUG',
                            'value'=>1
                        ],
                        [
                            'label'=>'07AUG-12AUG',
                            'value'=>2
                        ],
                        [
                            'label'=>'31JUL-05AUG',
                            'value'=>3
                        ],
                        [
                            'label'=>'24JUL-29JUL',
                            'value'=>4
                        ],
                        [
                            'label'=>'17JUL-22JUL',
                            'value'=>5
                        ],
                        [
                            'label'=>'10JUL-15JUL',
                            'value'=>6
                        ]
                    ]
                ],
                [
                    'tag'=>'button',
                    'type'=>'',
                    'label'=>'Clear Filter',
                    'name'=>'Clear Filter',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ]
                
            ],
        ];;
        return view('/trading/brokerageRefresh', $file);
    }
    // ---------------
}
