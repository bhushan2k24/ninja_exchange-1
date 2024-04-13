<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Administrator;
use App\Models\Nex_user_market_detail;
use App\Models\Nex_master_market_detail;
use Illuminate\Support\Facades\Auth;
use App\Models\Nex_Market;
use App\Models\Nex_script;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

class UserController extends Controller
{
    // user data type wise module ---------------
    public function index($type = 'user')
    {
        $file['breadcrumbs'] = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Users"], ['name' => ucwords("$type list")]];        


        $file['title'] = ucwords("$type list");
        $file['user_list_type'] = $type;
        $file['userListFormData'] = [
            'name'=>'userlist-form',
            'action'=>route('getUserList'),
            'btnGrid'=>2,
            'no_submit'=>1,
            'fieldData'=>[
                [
                    'tag'=>'select',
                    'roles'=>$type == 'user' ? ['admin','master'] : ($type=='master' ? [''] : ($type=='broker' ? [''] : [''])),
                    'type'=>'',
                    'label'=>'Broker',
                    'name'=>'borker_id',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>[],
                    'outer_div_classes'=>'mb-0',
                    'element_extra_classes'=>'select2-ajax-user_dropdown',
                    'element_extra_attributes'=>' data-type="broker" '
                ],
                [
                    'tag'=>'select',
                    'roles'=>$type == 'user' ? ['admin','master'] : ($type=='master' ? ['admin'] : ($type=='broker' ? ['admin'] : [''])),
                    'type'=>'',
                    'label'=>'Master',
                    'name'=>'master_id',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>[],
                    'outer_div_classes'=>'mb-0',
                    'element_extra_classes'=>'select2-ajax-user_dropdown',
                    'element_extra_attributes'=>' data-type="master" '
                ],
                [
                    'tag'=>'select',
                    'roles'=>$type == 'user' ? ['admin','master'] : ($type=='master' ? [''] : ($type=='broker' ? [''] : [''])),
                    'type'=>'',
                    'label'=>'Client',
                    'name'=>'client_id',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>[],
                    'outer_div_classes'=>'mb-0',
                    'element_extra_classes'=>'select2-ajax-user_dropdown',
                    'element_extra_attributes'=>' data-type="user" '
                ],
                [
                    'tag'=>'select',
                    'type'=>'',
                    'label'=>'Status',
                    'name'=>'user_status',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>[
                        [
                            'label'=>'Active',
                            'value'=>'active'
                        ],
                        [
                            'label'=>'Deactive',
                            'value'=>'deactive'
                        ]
                        ],
                    'outer_div_classes'=>'mb-0'
                ],
                [
                    'tag'=>'input',
                    'type'=>'hidden',
                    'label'=>' ',
                    'name'=>'user_list_type',
                    'validation'=>'',
                    'grid'=>0,
                    'value'=>$type,
                    'data'=>[],
                    'outer_div_classes'=>'mb-0'
                ],
                [
                    'tag'=>'input',
                    'type'=>'date',
                    'label'=>'Login After',
                    'name'=>'login_after',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>[],
                    'outer_div_classes'=>'mb-0'
                ],
                [
                    'tag'=>'input',
                    'type'=>'date',
                    'label'=>'Login Before',
                    'name'=>'login_before',
                    'validation'=>'',
                    'grid'=>3,
                    'data'=>[],
                    'outer_div_classes'=>'mb-0'
                ],
                [
                    'tag'=>'input',
                    'type'=>'date',
                    'label'=>'Join After',
                    'name'=>'join_after',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[],
                    'outer_div_classes'=>'mb-0'
                ],
                [
                    'tag'=>'input',
                    'type'=>'date',
                    'label'=>'Join Before',
                    'name'=>'join_before',
                    'validation'=>'',
                    'grid'=>2,
                    'data'=>[],
                    'outer_div_classes'=>'mb-0'
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
        
        // if ($type != 'user') {
        //     unset($file['userListFormData']['fieldData'][0]);
        //     unset($file['userListFormData']['fieldData'][1]);
        // }
        return view('user.list', $file);
    }
    // ---------------

    // user form module ---------------
    public function form($id = 0)
    {
        // if(Auth::user()->hasRole(['broker','user']) || Auth::user()->is_last_master)
        //     abort(404);
        // dd(AllowedMarketIds(decrypt_to($id)));
        
        $title = ucwords(($id>0?"Edit":"Add")." Account");
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Users"], ['name' => $title]];

        $userData = Administrator::find(decrypt_to($id));   
        // dd($userData->parent->userMaxLimit(['market_name'=>'MCXFUT']));
        
        // dd( $userData->formatted_market_data);
        if($id!=0 && is_null($userData))
            abort(404);

        if($userData)
        {
            // if($userData->hasRole(['admin']) || (Auth::user()->hasRole('admin') && !$userData->hasRole(['master'])) || (Auth::user()->hasRole('master') && !$userData->hasRole(['master','broker','user']))) 
            //     abort(404);
            // Auth::user()->is_last_master

        }

        $parant_data = ($userData)?$userData->parent:Auth::user();


        return view('user.form', compact('title', 'id', 'breadcrumbs', 'userData', 'parant_data'));        
    }
    // ---------------
    #----------------------------------------------------------------

    # Store User-----------------------------------------
    public function StoreUsers(Request $request)
    {

        if(Auth::user()->hasRole(['broker','user']))
            abort(404);

        $validator = Validator::make($request->all(), [
                    'user_type' => (Auth::user()->hasRole('admin') ? 'required|in:master,user' : 'required|in:master,broker,user'),
                    'name' => 'required',
                    // 'mobile' => [
                    //     'required','regex:/^([0-9\s\-\+\(\)]*)$/','min:10','max:16',
                    //     function ($attribute, $value, $fail) use ($request) {

                    //         $exists = Administrator::where('mobile', encrypt_to($value));
                    //         if(!empty($request->id))
                    //             $exists->where('id','!=',decrypt_to($request->id));

                    //         if ($exists->exists())
                    //             $fail('The '.$attribute.' has already been taken.');
                    //     },
                    // ],
                    // 'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:Administrators',
                    // 'email' => 'required|unique:Administrators',
                    'password' => [function ($attribute, $value, $fail) use ($request) {
                                if(empty($request->id) && empty($value))
                                    $fail('The '.$attribute.' field is required');
                            }]
                ]); 
        if ($validator->fails()) 
            return faildResponse(['Message'=>'Validaiton Warning', 'Data'=>$validator->errors()->toArray()]);

        $validationArray = [];

        
        if($request->user_type == 'user')
        {
            
        }

        if($request->user_type == 'master')
        {
            if(Auth::user()->hasRole(['master']) && empty($request->id))
            {
                if(Auth::user()->remaining_master_limit<=0)
                {
                    return faildResponse(['Message'=>'Master creation limit reached. Unable to generate more!', 'Data'=>['name'=>['Master creation limit reached. Unable to generate more!']]]);
                }
            }
            $validationArray = [
                'master_account_type_ids'=>'required',
                'partnership' => 'required||numeric|between:0,101',
                'max_intraday_multiplication' => 'required',
                'max_delivery_multiplication' => 'required',
                'market_type' => 'required|array',
                'market_type.*.*' => 'integer',
                'market_type_value.*' => 'required|integer'
            ];
            
            $validator = Validator::make($request->all(),$validationArray);
            if ($validator->fails()) 
            {
                $errors = [];
                foreach ($validator->errors()->toArray() as $key => $value)
                {
                    $convertedString = $key;
                    if (preg_match('/\.(\d+)\./', $key, $matches)) {
                        // Extract the number between dots using regular expression
                        $number = $matches[1];
                        // Replace the dot notation with square brackets
                        $convertedString = str_replace('.' . $number . '.', '[' . $number . '][', $key);
                        $convertedString .= ']';
                        $value = str_replace($key,last(explode('.',$key)),$value);
                    }
                    $errors[$convertedString]  = $value;
                }
                return faildResponse(['Message'=>'Validaiton Warning', 'Data'=> $errors]);
            }
        }

        $user = Administrator::find(decrypt_to($request->id));
        $userSaveData = [
            'name' => $request->name,
            // 'mobile' => encrypt_to($request->mobile),
            'mobile' => encrypt_to(rand(1111111111,9999999999)),
            'email' => encrypt_to($request->email),
            'username' => str_replace(' ','',$request->name).rand(111111,999999),

            'usercode' => rand(111111,999999),
            'referral_token' => str_replace(' ','',$request->name).rand(111,999),
            'parent_id' => Auth::id(),
            'referrer_id' => Auth::id(),                    
            'password' => bcrypt($request->password),            
            'user_position' => $request->user_type,            
            'registered_ip' => $request->ip(),

            'partnership' => $request->partnership ?: 0,            
            'min_lot_wise_brokerage' => $request->min_lot_wise_brokerage ?: 0,
            'min_lot_wise_brokerage_is_percentage' => $request->min_lot_wise_brokerage_is_percentage?:1,
            'min_amount_wise_brokerage' => $request->min_amount_wise_brokerage ?:0,
            'min_amount_wise_brokerage_is_percentage' => $request->min_amount_wise_brokerage_is_percentage?:1 ,
            
            'max_intraday_multiplication' => $request->max_intraday_multiplication ?: 0,
            'max_delivery_multiplication' => $request->max_delivery_multiplication ?: 0,            
            'master_creation_limit' => $request->master_creation_limit ?: '0',  
            
            'order_outside_of_high_low' => $request->order_outside_of_high_low ?: '0',
            'apply_auto_square' => $request->apply_auto_square ?: 1,
            'intra_day_auto_square' => $request->intra_day_auto_square ?: '0',
            'only_position_squareoff' => $request->only_position_squareoff ?: '0',
            'mtm_linked_with_ledger' => $request->mtm_linked_with_ledger ?: '1',
            'user_broker_id' => $request->user_broker_id ?: '0',
            'loss_alert_percentage' => $request->loss_alert_percentage ?: '0',
            'close_alert_margin' => $request->close_alert_margin ?: '0',
            'user_account_type_id' => $request->user_account_type_id ?:'0',
            'margin_limit' => $request->margin_limit ?: '0',
            
            'user_remark' => $request->user_remark,
        ];

        if(!empty($request->id))
        {
           unset($userSaveData['usercode']);
           unset($userSaveData['referral_token']);
           unset($userSaveData['parent_id']);
           unset($userSaveData['referrer_id']);
           unset($userSaveData['password']);
           unset($userSaveData['user_position']);
           unset($userSaveData['registered_ip']);
        }
        if(!Auth::user()->hasRole(['admin']))
            unset($userSaveData['master_creation_limit']);        

        $user = Administrator::updateOrCreate(['id'=>decrypt_to($request->id)],$userSaveData);        
        // $user->save($userSaveData);
        
        if(empty($request->id))
        {
            $usercode = strlen($user->id)>=6 ? $user->id : (str_pad(rand(1, 9999), (6-(strlen($user->id)>6 ? 6 : strlen($user->id))), '0', STR_PAD_RIGHT)).$user->id;

            $user->update(['referral_token'=>"NXCREF".numberToCharacterString($user->id),
                            'usercode'=>$usercode]);
            $user->assignRole($request->user_type);
        }
        
        if($user->hasRole(['broker']))
            return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>route('view.user',[$user->getRoleNames()->first()])]);            

        if($user->hasRole(['user']))
        {
            Nex_user_market_detail::where('user_id', $user->id)->delete();

            $user_market_type_value = is_array($request->user_market_type_value)?$request->user_market_type_value :[$request->user_market_type_value];

            foreach ($user_market_type_value as $m_tkey => $m_tvalue) 
            {
                $marketData = marketData(['id'=>$m_tvalue,'user_id'=>$user->id,'need_user_parent_market'=>1]);
                if(is_null($marketData))
                    continue;
                
                $marketData = (object)$marketData;
                if(!empty($request->user_market_type[$m_tvalue]))
                {
                    foreach ($request->user_market_type[$m_tvalue] as $k => $v)
                    {      
                        if(strpos($k,'_is_percentage'))
                         continue;   

                        $v = ($k == 'commission_type')?($v=='script_wise'?1:2): (($k == 'brokerage_type') ? ($v=='amount_wise'?1:2) :$v);


                        // if(isset($request->user_market_type[$m_tvalue]['commission_type']) && $request->user_market_type[$m_tvalue]['commission_type'] == 'script_wise' && in_array($k,['delivery_commission','delivery_commission_is_percentage','intraday_commission','intraday_commission_is_percentage']) )
                        //     continue;
                        // elseif(isset($request->user_market_type[$m_tvalue]['commission_type']) && $request->user_market_type[$m_tvalue]['commission_type'] != 'script_wise'&& in_array($k,['default_delivery_commission','default_delivery_commission_is_percentage','default_intraday_commission','default_intraday_commission_is_percentage']))
                        //     continue;       
                      
                        if($k == 'user_script_wise_commission')
                        {                                                   
                            foreach($v  as $script_k => $scriptv)
                            {
                                $scriptsData = Nex_script::select('*')->where('id',$script_k)->where('market_id',$m_tvalue)->first();
                                
                                if(is_null($scriptsData))
                                    continue;

                                foreach ($scriptv as $scriptv_key => $scriptv_value)
                                {                                                
                                    if(strpos($scriptv_key,'_is_percentage'))
                                        continue; 
                                    
                                    $is_percentage = (in_array($scriptv_key,['delivery_commission','intraday_commission']) ? $scriptv[$scriptv_key.'_is_percentage']: '0');

                                    $user_market_detail = Nex_user_market_detail::updateOrInsert(
                                        [   
                                            'user_id' => $user->id, 
                                            'market_id' => $m_tvalue,
                                            'market_name' => $marketData->market_name,
                                            'market_field' => $scriptv_key,
                                            'script_id' => $scriptsData->id,
                                        ],
                                        [
                                            'user_id' => $user->id, 
                                            'market_id' => $m_tvalue,
                                            'market_name' => $marketData->market_name,
                                            'market_field' => $scriptv_key,
                                            'market_field_amount' => $scriptv_value??'0',
                                            'amount_is_percentage' => $is_percentage,
                                            'script_id' => $scriptsData->id,
                                            'script_name' => $scriptsData->script_name,
                                        ]
                                    );
                                }
                            }
                            continue;
                        }   

                        $is_percentage = (in_array($k,['delivery_commission ','intraday_commission','default_delivery_commission','default_intraday_commission']) ? $request->user_market_type[$m_tvalue][$k.'_is_percentage']: '0');  
                    
                        $user_market_detail = Nex_user_market_detail::updateOrInsert(
                            [   
                                'user_id' => $user->id, 
                                'market_id' => $m_tvalue,
                                'market_name' => $marketData->market_name,
                                'market_field' => $k
                            ],
                            [
                                'user_id' => $user->id, 
                                'market_id' => $m_tvalue,
                                'market_name' => $marketData->market_name,
                                'market_field' => $k,
                                'market_field_amount' => $v??'0',
                                'amount_is_percentage' => $is_percentage,
                                'script_id' => '0',
                                'script_name' => null,
                            ]
                        );
                    }
                }
                else
                {
                    $user_market_detail = Nex_user_market_detail::updateOrInsert(
                        [   'user_id' => $user->id, 
                            'market_id' => $m_tvalue,
                            'market_name' => $marketData->market_name
                        ],
                        [
                            'user_id' => $user->id, 
                            'market_id' => $m_tvalue,
                            'market_name' => $marketData->market_name,
                            'amount_is_percentage' => '0',
                            'script_id' => '0',
                            'script_name' => '',
                        ]);
                }
            }
        }
        elseif($user->hasRole(['master']))
        {
            Nex_master_market_detail::where('user_id', $user->id)->delete();

            $user->update(['master_account_type_ids'=>(is_array($request->master_account_type_ids)
                ?$request->master_account_type_ids:[$request->master_account_type_ids])]);

            $market_type_value = is_array($request->market_type_value)?$request->market_type_value :[$request->market_type_value];

              
            foreach ($market_type_value as $m_tkey => $m_tvalue) 
            { 
                $marketData = marketData(['id'=>$m_tvalue,'user_id'=>$user->id,'need_user_parent_market'=>1]);
                if(empty($marketData))
                    continue;

                $marketData =  (object)$marketData;
                
                if(!empty($request->market_type[$m_tvalue]))
                {
                    
                    foreach ($request->market_type[$m_tvalue] as $k => $v)
                    {                        
                        if(strpos($k,'_is_percentage'))
                            continue;

                        $is_percentage = (in_array($k,['amount_wise_brokerage','lot_wise_brokerage']) ? $request->market_type[$m_tvalue][$k.'_is_percentage']: '0');
                        $user_market_detail = Nex_master_market_detail::updateOrInsert(
                            [   
                                'user_id' => $user->id, 
                                'market_id' => $m_tvalue,
                                'market_name' => $marketData->market_name,
                                'market_field' => $k
                            ],
                            [
                                'user_id' => $user->id, 
                                'market_id' => $m_tvalue,
                                'market_name' => $marketData->market_name,
                                'market_field' => $k,
                                'market_field_amount' => $v,
                                'amount_is_percentage' => $is_percentage,
                            ]
                        );
                    }
                }
                else
                {
                    $user_market_detail = Nex_master_market_detail::updateOrInsert(
                    [   'user_id' => $user->id, 
                        'market_id' => $m_tvalue,
                        'market_name' => $marketData->market_name
                    ],
                    [
                        'user_id' => $user->id, 
                        'market_id' => $m_tvalue,
                        'market_name' => $marketData->market_name,
                        'amount_is_percentage' => '0'
                    ]
                    );
                }
            }
        }  
        return successResponse(['Message'=>'Success!', 'Data'=>[]]);
        return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>route('view.user',[$user->getRoleNames()->first()])]);
    }
    #----------------------------------------------------------------

    #----------------------------------------------------------------
    #Function to get User List
    public function getUserList(Request $request)
    {
        // if ($request->ajax())
         {
            $user_list_type = $request->user_list_type?:'user';
            $Data = Administrator::role([$user_list_type])->with('roles');

            // if(!Auth::user()->hasRole('admin'))
            //     $Data->where('parent_id', Auth::id());  


            $thead = ['Name', 'Login ID'];
            $nhed = [];

            if ($user_list_type == 'user')
                array_push($thead,'Broker','Master');
            elseif($user_list_type == 'master')
                array_push($thead,'Parent','Percentage','Master U','User U','Broker U');

            array_push($thead,'Action', 'Login Time', 'Login IP', 'Join Date');

            if(!empty($request->user_status))
                $Data->where('user_status',$request->user_status);

            if(!empty($request->login_after))
                $Data->where('last_login_at','>',$request->login_after);

            if(!empty($request->login_before))
                $Data->where('last_login_at','<',$request->login_before);
            
            if(!empty($request->join_after))
                $Data->where('created_at','>',$request->join_after);

            if(!empty($request->join_before))
                $Data->where('created_at','<',$request->join_before);    

            if(!empty($request->borker_id))
                $Data->where('user_broker_id',$request->borker_id);  

            if(!empty($request->master_id))
                $Data->where('parent_id',$request->master_id);

            if(!empty($request->client_id))
                $Data->where('id',$request->client_id);                 

            if(!empty($request->search))
            {
                $Data->where(function ($query) use ($request) {
                    $query->where('name','LIKE','%'.$request->search."%")
                          ->orWhere('usercode','LIKE','%'.$request->search."%")
                          ->orWhere('created_at','LIKE','%'.$request->search."%");
                });
            }
            // $tbody = $Data->get()->toArray();
            // dd(URL.USER.$tbody[1]['profile_picture'],url_file_exists(URL.USER.$tbody[1]['profile_picture']));

            $limit = $request->limit ? $request->limit : 5;

            $tbody = $Data->paginate($limit);
            $tbody_data = $tbody->items();
            foreach ($tbody_data as $key => $data) {
                // dd($data->children());
                $profile = $this->getUserNameWithAvatar($data->name,URL.USER.$data->profile_picture,$data->usercode);     
                $MasterchildAvatarGroup = $BrokerchildAvatarGroup = $UserchildAvatarGroup = 0;
                $user_broker_profile = $user_parent_profile = '';

                if($data->user_parent)
                {
                    $parent = $data->user_parent;
                    $user_parent_profile = $this->getUserNameWithAvatar($parent->name,URL.USER.$parent->profile_picture,$parent->usercode);
                }
                if ($user_list_type == 'user' )
                {                    
                    if($data->user_broker)
                    {
                        $broker = $data->user_broker;
                        $user_broker_profile = $this->getUserNameWithAvatar($broker->name,URL.USER.$broker->profile_picture,$broker->usercode);
                    }
                }
                elseif($user_list_type == 'master')
                {
                    $children = $data->children('master',4)->get();
                    $ChildCount = $data->getChildCount('master');   
                    if(!$children->isEmpty())
                        $MasterchildAvatarGroup = $this->getUsersAvatarGroup($children,$ChildCount);

                    $children = $data->children('broker',4)->get();
                    $ChildCount = $data->getChildCount('broker');   
                    if(!$children->isEmpty())
                        $BrokerchildAvatarGroup = $this->getUsersAvatarGroup($children,$ChildCount);

                    $children = $data->children('user',4)->get();
                    $ChildCount = $data->getChildCount('user');   
                    if(!$children->isEmpty())
                        $UserchildAvatarGroup = $this->getUsersAvatarGroup($children,$ChildCount);
                }               
                
                $tbody_data[$key] = [
                        $profile,
                        $data->usercode];
                        
                        if ($user_list_type == 'user')
                        $tbody_data[$key] = array_merge($tbody_data[$key],[($data->user_broker?$user_broker_profile:'-'),($data->user_parent?$user_parent_profile:'-')]);
                        elseif($user_list_type == 'master')
                        $tbody_data[$key] = array_merge($tbody_data[$key],[($data->user_parent?$user_parent_profile:'-'),$data->partnership,$MasterchildAvatarGroup,$UserchildAvatarGroup,$BrokerchildAvatarGroup]);
                    
                        $tbody_data[$key] =  array_merge($tbody_data[$key],
                        ['<div class="avatar avatar-status bg-light-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice">
                            <span class="avatar-content">
                                <i data-feather=\'file-text\' class="avatar-icon"></i>
                            </span>
                        </div>

                        <div class="avatar avatar-status bg-light-warning itsrst-pass" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Password" rstpassto="'.str_replace(url('/').'/','',route('resetpassword.user',[encrypt_to($data->id)])).'">
                            <span class="avatar-content">
                                <i data-feather=\'key\' class="avatar-icon"></i>
                            </span>
                        </div>

                        <a href="'.Route('edit.user',[encrypt_to($data->id)]).'"class="avatar avatar-status bg-light-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <span class="avatar-content">
                                <i data-feather=\'edit\' class="avatar-icon"></i>
                            </span>
                        </a>

                        <div class="avatar avatar-status bg-light-success itclrlogin" data-bs-toggle="tooltip" data-bs-placement="top" title="Clear Login Attempt" clrloginto="'.str_replace(url('/').'/','',route('clearloginlttempts.user',[encrypt_to($data->usercode)])).'">
                            <span class="avatar-content">
                                <i data-feather=\'unlock\' class="avatar-icon"></i>
                            </span>
                        </div>

                        <div class="datatable-switch form-check form-switch form-check-primary d-inline-block align-middle"  data-bs-toggle="tooltip" data-bs-placement="top" title="Click to '.($data->user_status=='active'?'Deactivate':'Activate').'">
                        <input type="checkbox" class="form-check-input change_status" id="StatusSwitch'.$key.'" '.($data->user_status=='active'?'checked':'').' statusto="'.encrypt_to('administrators').'/'.encrypt_to($data->id).'/'.encrypt_to('user_status').'"/>                            
                            <label class="form-check-label" for="StatusSwitch'.$key.'">
                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                            </label>
                        </div> ',
                        date('Y-m-d H:i:s',strtotime($data->last_login_at)),
                        $data->last_login_ip?:'-',
                        date('Y-m-d H:i:s',strtotime($data->created_at)),
                    ]);
          }
          $tbody->setCollection(new Collection($tbody_data));

            return view('datatable.datatable', compact('tbody','thead'))->render();
        }
        
    }
    #----------------------------------------------------------------

    #----------------------------------------------------------------
    #Function to get User List
    public function getUserNameWithAvatar($name='',$image=NULL,$position=' ')
    {
        if($name=='')
            return $name;

        $Initials = getInitials($name);
        $randomState = getRandomColorState();
        $user_parent_profile = '<span class="avatar-content">'.$Initials.'</span>';
    
        $user_parent_profile = url_file_exists($image)?                
        '<img src="'.$image.'" alt="'.$name.'" height="32" width="32">':$user_parent_profile;
        
        $user_parent_profile = 
            '<div class="d-flex justify-content-left align-items-center">
                <div class="avatar-wrapper">
                    <div class="avatar  bg-light-'.$randomState.'  me-1">
                    '.$user_parent_profile.'
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <a href="javascript:void(0);" class="user_name text-truncate text-body">
                    <span class="fw-bolder">'.ucwords($name).'</span>
                    </a>
                    <small class="emp_post text-muted">'. $position.'</small>
                </div>
            </div>';
        return $user_parent_profile;
    }
    #----------------------------------------------------------------

    #----------------------------------------------------------------
    #Function to get User Avatar Group
    public function getUsersAvatarGroup($UsersGroup = [],$count = 0)
    {
        if(!$UsersGroup)
            return '';        
        $AvatarGroup = '<div class="avatar-group">';        
        foreach($UsersGroup as $key => $user)
        {
            $Initials = getInitials($user->name);
            $randomState = getRandomColorState();

            $user_parent_profile = '<span class="avatar-content bg-'.$randomState.'">'.$Initials.'</span>';

            $user_parent_profile = url_file_exists(URL.USER.$user->profile_picture)?                
            '<img src="'.URL.USER.$user->profile_picture.'" alt="'.$user->name.'" height="32" width="32">':$user_parent_profile;

            $AvatarGroup .=  '<div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom"       title="'.$user->name.'" class="avatar pull-up avatar-sm bg-'.$randomState.'">
                    '.$user_parent_profile.'
                </div>';
        }
        $userCount = $count - $UsersGroup->count();
        $AvatarGroup .= '<h6 class="align-self-center cursor-pointer ms-50 mb-0">'.(($userCount)>0?'+'.$userCount:'').'</h6>
        </div>';

        return $AvatarGroup;

    }
    #----------------------------------------------------------------

    #----------------------------------------------------------------
    #Function to get User List
    public function getBrokerlist(Request $request)
    {
        $Data = Administrator::role('broker');

        if(!Auth::user()->hasRole('admin'))
         $Data->where('parent_id', Auth::id());  

        if(!empty($request->q))
        {
            $Data->where(function ($query) use ($request) {
                $query->where('name','LIKE','%'.$request->q."%")
                      ->orWhere('usercode','LIKE','%'.$request->q."%");
            });
        }
        $Brokerlist = $Data->paginate(5);
        return response()->json($Brokerlist);
    }

    #----------------------------------------------------------------
    #Function to get User List
    public function getRoleWiseUserlist(Request $request)
    {
        if(!$request->user_position)
            return response()->json([]);   

        $Data = Administrator::role($request->user_position);

        $DataForUser = Administrator::find(($request->parent?decrypt_to($request->parent):Auth::id())); 
        // if(!Auth::user()->hasRole('admin'))
        //  $Data->where('parent_id', Auth::id()); 
        
        if($DataForUser->hasRole('master'))
        {
            // $Data->where('parent_id', Auth::id());           //user|broker|master            
            $Data->whereIn('parent_id', [$DataForUser->id, ...Administrator::role('master')->where('parent_id',$DataForUser->id)->pluck('id')]);              
        }           
        elseif($DataForUser->hasRole('user'))
        {
            // $Data->where('parent_id', Auth::user()->parent_id);7
            if($request->user_position=='user')
                $Data->where('id', $DataForUser->id);
            elseif($request->user_position=='master')
                $Data->where('id', $DataForUser->parent_id);
            elseif($request->user_position=='broker')
                $Data->where('id', $DataForUser->user_broker_id);

        }            
        elseif($DataForUser->hasRole('broker'))
        {
            // $Data->where('parent_id', Auth::user()->parent_id)->where('user_broker_id',Auth::id());
            if($request->user_position=='user')
                $Data->where('user_broker_id', $DataForUser->id);
            elseif($request->user_position=='master')
                $Data->where('id', $DataForUser->parent_id);
            elseif($request->user_position=='broker')
                $Data->where('id', $DataForUser->id);
        }            

        if(!empty($request->q))
        {
            $Data->where(function ($query) use ($request) {
                $query->where('name','LIKE','%'.$request->q."%")
                      ->orWhere('usercode','LIKE','%'.$request->q."%");
            });
        }
        $Userlist = $Data->paginate(5);
        return response()->json($Userlist);   
    }


    #----------------------------------------------------------------
    #Function to Reset Password
    public function userPasswordReset(Request $request,$id=0)
    {
        if($id==0)
            return response()->json([]);   

        $user = Administrator::where('id', decrypt_to($id))->update(['password' => bcrypt('123456')]);     
        if($user) 
            return successResponse(['Message' => 'Password Reseted Successfully!']);

        return faildResponse(['Message' => 'Something went wrong!']); 
    }
    #----------------------------------------------------------------
    # Clear Login limite Attempts.
    public function clearLoginAttempts(Request $request,$code='')
    {
        RateLimiter::clear(decrypt_to($code));
        return successResponse(['Message' => 'Cleared Login Attempts Successfully!']);
    }

}


