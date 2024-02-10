<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  // Dashboard - Analytics
  public function dashboardAnalytics()
  {
    $pageConfigs = ['pageHeader' => false];
    $TradeData = DB::table('nex_trades')->join('administrators','administrators.id','nex_trades.user_id')->join('nex_script_expires','nex_script_expires.id','nex_trades.script_expires_id')->select('nex_trades.*','nex_trades.created_at as trade_created_at','nex_trades.id as trade_id','nex_trades.updated_at as date','administrators.*','nex_script_expires.market_name','nex_script_expires.market_id','nex_script_expires.script_id','nex_script_expires.script_name','nex_script_expires.script_trading_symbol',DB::raw('(SELECT  sub_admin.name parent_name FROM administrators sub_admin  WHERE id=administrators.parent_id) parent_name'))->latest('nex_trades.created_at')->take(10)->get();
    

    return view('content.dashboard.dashboard-analytics', ['pageConfigs' => $pageConfigs,'TradeData' => $TradeData]);
  }

  // Dashboard - Ecommerce
  public function dashboardEcommerce()
  {
    $pageConfigs = ['pageHeader' => false];

    return view('content.dashboard.dashboard-ecommerce', ['pageConfigs' => $pageConfigs]);
  }
}
