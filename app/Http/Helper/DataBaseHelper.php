<?php
use Illuminate\Support\Facades\DB;
use App\Models\Nex_Setting;
use App\Models\Nex_Level;
use App\Models\Nex_Market;

#function for get form element data ---------------
function getFormContentData($form_name = 'login-form')
{
    return DB::table('nex_user')->select('*')->get();
}
#---------------

#function for get market data ---------------
function marketData($id=0,$with_non_trading_market=0,$only_stock_trading=0)
{

    $get_market = Nex_Market::select('*')->addSelect('market_name as label', 'id as value')->where('market_status','active');
    if($id>0)
        return $get_market->where('id',$id)->first();
   
    if($only_stock_trading>0)
        return $get_market->where('market_type','stock_trading')->get()->toArray();
    elseif($with_non_trading_market>0)
        return $get_market->get()->toArray();
    else
        return $get_market->whereNot('market_type','non_trading')->get()->toArray();
 
    // if($other_markets == 1)
    // {
    //     $market = array_merge($market, [
    //         // [
    //         //     'label'=>'NSEOPT',
    //         //     'value'=>3,
    //         //     'form_field'=>['lot_wise_brokerage','intraday_multiplication','delivery_multiplication']
    //         // ],
    //         // [
    //         //     'label'=>'NSEFUT',
    //         //     'value'=>1,
    //         //     'form_field'=>['amount_wise_brokerage']
    //         // ],
    //         // [
    //         //     'label'=>'NSEEQT',
    //         //     'value'=>6,
    //         //     'form_field'=>['amount_wise_brokerage']
    //         // ],
    //         // [
    //         //     'label'=>'NSECDS',
    //         //     'value'=>7,
    //         //     'form_field'=>['lot_wise_brokerage']
    //         // ],
    //         // [
    //         //     'label'=>'MCXFUT',
    //         //     'value'=>2,
    //         //     'form_field'=>['lot_wise_brokerage','amount_wise_brokerage']
    //         // ],
    //         // [
    //         //     'label'=>'GLOBAL STOCKS',
    //         //     'value'=>5,
    //         //     'form_field'=>['amount_wise_brokerage']
    //         // ],        
    //         // [
    //         //     'label'=>'GLOBAL FUTURES',
    //         //     'value'=>4,
    //         //     'form_field'=>['amount_wise_brokerage']
    //         // ],        
    //         // [
    //         //     'label'=>'FOREX',
    //         //     'value'=>4,
    //         //     'form_field'=>['lot_wise_brokerage']
    //         // ],        
    //         // [
    //         //     'label'=>'CRYPTO',
    //         //     'value'=>4,
    //         //     'form_field'=>['lot_wise_brokerage','intraday_multiplication','delivery_multiplication']
    //         // ],        
    //         // [
    //         //     'label'=>'COMEX',
    //         //     'value'=>4,
    //         //     'form_field'=>['lot_wise_brokerage']
    //         // ],
    //         [
    //             'market_name'=>'CRICKET',
    //             'id'=>'CRICKET',
    //             'market_user_required_fields'=>'[]'
    //         ],
    //         [
    //             'market_name'=>'CASINO',
    //             'id'=>'CASINO',
    //             'market_user_required_fields'=>'[]'
    //         ]
    //     ]);
    // }

    // return  $market;
    // 'lot_wise_brokerage','amount_wise_brokerage','intraday_multiplication','delivery_multiplication'
}
#---------------

#function for get script by market id data ---------------
function scriptData($market_id = 0)
{
	$data = [];
	if ($market_id > 0)
	{
		$scriptData = ["NIFTY", "BANKNIFTY", "AARTIIND", "ABB", "ABBOTINDIA", "ABCAPITAL", "ABFRL", "ACC", "ADANIENT", "ADANIPORTS", "ALKEM", "AMARAJABAT", "AMBUJACEM", "PLLTD", "APOLLOHOSP", "APOLLOTYRE", "ASHOKLEY", "ASIANPAINT", "ASTRAL", "AUBANK", "AUROPHARMA", "AXISBANK", "BAJAJ-AUTO", "BAJAJFINSV", "BAJFINANCE", "ALKRISIND", "BALRAMCHIN", "BANDHANBNK", "BANKBARODA", "BATAINDIA", "BEL", "BERGEPAINT", "BHARATFORG", "BHARTIARTL", "BHEL", "BIOCON", "BOSCHLTD", "PCL", "BRITANNIA", "BSOFT", "CADILAHC", "CANBK", "CANFINHOME", "CHOLAFIN", "CIPLA", "COALINDIA", "COFORGE", "COLPAL", "CONCOR", "COROMANDEL", "ROMPTON", "CUB", "CUMMINSIND", "DABUR", "DALBHARAT", "DEEPAKNTR", "DELTACORP", "DIVISLAB", "DIXON", "DLF", "DRREDDY", "EICHERMOT", "ESCORTS", "XIDEIND", "FEDERALBNK", "GAIL", "GLENMARK", "GMRINFRA", "GNFC", "GODREJCP", "GODREJPROP", "GRANULES", "GRASIM", "GUJGASLTD", "HAL", "HANAUT", "HAVELLS", "CLTECH", "HDFC", "HDFCAMC", "HDFCBANK", "HDFCLIFE", "HEROMOTOCO", "HINDALCO", "HINDCOPPER", "HINDPETRO", "HINDUNILVR", "IBULHSGFIN", "ICICIBANK", "CICIGI", "ICICIPRULI", "IDEA", "IDFCFIRSTB", "IEX", "IGL", "INDHOTEL", "INDIACEM", "INDIAMART", "INDIGO", "INDUSINDBK", "INDUSTOWER", "INFY", "NTELLECT", "IOC", "IPCALAB", "IRCTC", "ITC", "JINDALSTEL", "JKCEMENT", "JSWSTEEL", "JUBLFOOD", "KOTAKBANK", "L&amp;'TFH", "LALPATHLAB", "LAURUSLABS", "ICHSGFIN", "LT", "LTIM", "LTTS", "LUPIN", "M&amp;'M", "M&amp;'MFIN", "MANAPPURAM", "MARICO", "MARUTI", "MCDOWELL-N", "MCX", "METROPOLIS", "MFSL", "MGL", "INDTREE", "MOTHERSON", "MPHASIS", "MRF", "MUTHOOTFIN", "NAM-INDIA", "NATIONALUM", "NAUKRI", "NAVINFLUOR", "NBCC", "NESTLEIND", "NMDC", "NTPC", "BEROIRLTY", "OFSS", "ONGC", "PAGEIND", "PEL", "PERSISTENT", "PETRONET", "PFC", "PFIZER", "PIDILITIND", "PIIND", "PNB", "POLYCAB", "POWERGRID", "VRINOX", "RAIN", "RAMCOCEM", "RBLBANK", "RECLTD", "RELIANCE", "SAIL", "SBILIFE", "SBIN", "SHREECEM", "SHRIRAMFIN", "SIEMENS", "SRF", "STAR", "UNPHARMA", "SUNTV", "SYNGENE", "TATACHEM", "TATACOMM", "TATACONSUM", "TATAMOTORS", "TATAPOWER", "TATASTEEL", "TCS", "TECHM", "TITAN", "TORNTPHARM", "ORNTPOWER", "TRENT", "TVSMOTOR", "UBL", "ULTRACEMCO", "UPL", "VEDL", "VOLTAS", "WHIRLPOOL", "WIPRO", "ZEEL"];

		foreach($scriptData as $key => $val)
		{
			$data[] = ['label'=>$val, 'value'=>$key + 1];
		}
	}
	
	return $data;
}
#---------------

#function for get user data by type ---------------
function userData($type = 'client')
{
	$data = [];
	if ($type == 'broker')
	{
		$data = [
            [
                'label'=>'1211-ONLINE BROKER',
                'value'=>1
            ],
            [
                'label'=>'1210-TEST',
                'value'=>2
            ]
        ];
	}

	if ($type == 'master')
	{
		$data = [
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
        ];
	}

	if ($type == 'client')
	{
		$data = [
            [
                'label'=>'111-DEMO',
                'value'=>1
            ],
            [
                'label'=>'112-DEMO CLIENT',
                'value'=>2
            ],
            [
                'label'=>'113-ONLINE CLIENT',
                'value'=>2
            ]
        ];
	}

	return $data;
}
#---------------

#function for get type data keyword wise ---------------
function typeData($type = 'order')
{
	$data = [];
	if ($type == 'order') 
	{
		$data = [
            [
                'label'=>'buy limit',
                'value'=>1
            ],
            [
                'label'=>'buy stop',
                'value'=>2
            ],
            [
                'label'=>'sell limit',
                'value'=>1
            ],
            [
                'label'=>'sell stop',
                'value'=>2
            ],
        ];
	}
	return $data;
}

#---------------
// function for return setting content
function setting($fieldNames = 'site_name'){
    $setting = Nex_Setting::select('setting_field_value')->where('setting_field_name',$fieldNames)->first()->setting_field_value;
    return $setting;
} 

#function for convert string in lowercase and trim it
function lower($val = '')
{
    return trim(strtolower($val));
}
#-----------------------------------------------------

#-----------------------------------------------------
// function for return setting content
function get_levels($id = 0){
    $get_levels = Nex_Level::select('*');
    if($id>0)
        return $get_levels->where('id',$id)->first();
    return $get_levels->get();
} 
?>