<?php

namespace App\Http\Controllers;

use App\Models\Nex_Market;
use App\Models\Nex_script;
use App\Models\Nex_script_expire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HelperController extends Controller
{
   public function changeStatus(Request $request)
   {

        if(!$request->route('table_name') || !$request->route('id'))
            return faildResponse(['Message' => 'Provided All Information!']);
            
        $table = decrypt_to(urlencode($request->route('table_name')));
        $id = decrypt_to(urlencode($request->route('id')));


        $status_column_name = $request->route('status_column_name') ? decrypt_to(urlencode($request->route('status_column_name'))) : $table.'_status';

        if(!Schema::hasTable($table)) 
            return faildResponse(['Message' => 'Invalid Table!']);

        if(!Schema::hasColumn($table, $status_column_name)) 
            return faildResponse(['Message' => 'Invalid Columan!']);

        $users = DB::table($table)->where('id',$id)->first() ;
        if(empty($users))
            return faildResponse(['Message' => 'Invalid Id Of Table!']);
        
        $status = $users->{$status_column_name} == 'deactive' ? 'active' : 'deactive';

        $affected = DB::table($table)->where('id', $id)->update([$status_column_name => $status]);

        if($affected);
            return successResponse(['Message' => 'Status Update Successfully!']);

        return faildResponse(['Message' => 'Something went wrong!']);
   }
   #----------------------------------------------------------------
   
   #----------------------------------------------------------------
   #helper function to delete record 
   public function deleteRecord(Request $request)
   {  
        if(!$request->route('table_name') || !$request->route('id'))
            return faildResponse(['Message' => 'Provided All Information!']);
        
        $table = decrypt_to(urlencode($request->route('table_name')));
        $id = decrypt_to(urlencode($request->route('id')));

        if(!Schema::hasTable($table)) 
            return faildResponse(['Message' => 'Invalid Table!']);

        $affected = DB::table($table)->where('id', $id)->delete();

        if($affected);
            return successResponse(['Message' => 'Record Deleted Successfully!']);

        return faildResponse(['Message' => 'Something went wrong!']);
   }
   #----------------------------------------------------------------
   
   #----------------------------------------------------------------
   #helper function to get record 
   public function getRecord(Request $request)
   {  
        if(!$request->route('table_name') || !$request->route('id'))
            return faildResponse(['Message' => 'Provided All Information!']);
        
        $table = decrypt_to(urlencode($request->route('table_name')));
        $id = decrypt_to(urlencode($request->route('id')));

        if(!Schema::hasTable($table)) 
            return faildResponse(['Message' => 'Invalid Table!']);

        $data = DB::table($table)->where('id', $id)->first();

        if($data);
            return successResponse(['Message' => 'Record Fetched Successfully!','Data'=>$data]);

        return faildResponse(['Message' => 'Something went wrong!']);
   }
    #----------------------------------------------------------------
   
    #----------------------------------------------------------------
    #helper function to get record 
    public function getMarketToScripts(Request $request)
    {  
        if(!$request->MarketId || !$request->MarketId)
            return faildResponse(['Message' => 'Provided All Information!']);

        $getScripts = Nex_script::select('id','script_name')->where(['market_id'=>$request->MarketId],['is_ban'=>'no'])->get();

        if($getScripts);
            return successResponse(['Message' => 'Record Fetched Successfully!','Data'=>$getScripts]);
        
        return faildResponse(['Message' => 'Provided All Information!']);
    }

    #----------------------------------------------------------------
    #Function to get TradingExtension for market data
    public function getTradingExtension(Request $request)
    {
        $TradingExtensions = Nex_script_expire::select('script_extension')->
        join('Nex_scripts', 'Nex_scripts.id','=', 'nex_script_expires.script_id')->where('is_ban','no')->pluck('script_extension')->toArray();
        return $TradingExtensions;
    }

    #-------------------------------------------------------------------
    #Function to get Market Script
    public function getMarketScript(Request $request)
    {
        $script = Nex_script::where('market_id', $request->market_id)->get();
        return response()->json($script);
    }

   
}

