<?php

use App\Models\Administrator;
use Illuminate\Support\Facades\DB;
use App\Models\Nex_Setting;
use App\Models\Nex_Level;
use App\Models\Nex_Market;
use App\Models\Nex_script;

#function for get form element data ---------------
function getFormContentData($form_name = 'login-form')
{
    return DB::table('nex_user')->select('*')->get();
}
#---------------

#function for get market data ---------------
function marketData($id=0,$with_non_trading_market=0,$only_stock_trading=0,$market_name='')
{

    $get_market = Nex_Market::select('*')->addSelect('market_name as label', 'id as value')->where('market_status','active');
    if($id>0)
        return $get_market->where('id',$id)->first()->toArray();
    if($market_name!='')
        return $get_market->where('market_name',$market_name)->first()->toArray();
   
    if($only_stock_trading>0)
        return $get_market->where('market_type','stock_trading')->get()->toArray();
    elseif($with_non_trading_market>0)
        return $get_market->get()->toArray();
    else
        return $get_market->whereNot('market_type','non_trading')->get()->toArray();
}
#----------------------------------------------------------------

#----------------------------------------------------------------
#function for get script by market id or all  
function scriptData($market_id = 0,$market_name='')
{
	$get_scripts = [];

    $get_scripts = Nex_script::select('*')->addSelect('script_name as label', 'nex_scripts.id as value')->where('script_status','active');

    if($market_id <= 0 && $market_name == '')
        return $get_scripts->get()->toArray();

    if($market_id > 0)
        return $get_scripts->where('market_id',$market_id)->get()->toArray();

    if($market_name != '')
        return $get_scripts->join('nex_markets','nex_markets.id','=','nex_scripts.market_id')->where('nex_markets.market_name',$market_name)->get()->toArray();


	return $get_scripts;
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