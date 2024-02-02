<?php

namespace App\Http\Controllers;

use App\Models\Nex_Market;
use App\Models\Nex_script;
use App\Models\Nex_script_expire;
use App\Models\Nex_watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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

        $file['watchlist_data'] = $watchlist_data;

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
        $plMarketValue = $request->input('plMarketValue');
    

        $data = DB::table('nex_watchlists')
        ->join('nex_scripts','nex_scripts.id','=','nex_watchlists.script_id')
        ->select('nex_watchlists.*','nex_scripts.script_quantity','nex_scripts.is_ban')
        ->where('watchlist_script_extension', $plMarketValue)->orderBy('id','DESC')->first();
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

    #WatchList Modules Stop
    #----------------------------------------------------------------

    #----------------------------------------------------------------
    // traders module ---------------
    public function traders()
    {
        $file['title'] = 'traders';
        $file['tradersFormData'] = [
            'name'=>'watchlist-form',
            'action'=>'',
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
            ],
        ];
        return view('/trading/traders', $file);
    }
    // ---------------

    // portfolio module ---------------
    public function portfolio()
    {
        $file['title'] = 'portfolio/position';
        $file['portfolioFormData'] = [
            'name'=>'watchlist-form',
            'action'=>'',
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
                    'label'=>'master',
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
                    'tag'=>'input',
                    'type'=>'date',
                    'label'=>'expire date',
                    'name'=>'expire_date',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[]
                ],
            ],
        ];
        return view('/trading/portfolio', $file);
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
