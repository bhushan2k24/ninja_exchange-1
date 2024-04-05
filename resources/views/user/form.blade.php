@php($ajaxformsubmit = true)
@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

  <style>
      .input-width-drop-down > input {
          position: relative !important;
          flex: none !important;
          width: 75% !important;
      }
      .input-width-drop-down > select {
          position: relative !important;
          flex: none !important;
          width: 25% !important;
      }
  </style>
@endsection

@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
@endsection

@section('content')
@php($isedit = (!is_null($userData) || $id>0))
@php($accountTypeData = ($isedit ? $userData->get_levels() : Auth::user()->get_levels()))

<section id="basic-vertical-layouts">
  <div class="row">
    <div class="col-12">
      <form method="post" action="{{route('store.user')}}">

        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Basic Detail</h4>
          </div>
          <div class="card-body">
            <div class="row">
              @if(!$isedit)
                <div class="mb-1 col-12 col-md-3">
                    <label class="form-label" for="user_type">User Type</label>
                    <select name="user_type" class="form-control form-select ">
                      <option value="">Select Type</option>
                      @if(!Auth::user()->is_last_master)
                      <option value="master">Master</option>
                      @endif
                      @role('master')
                      <option value="broker">Broker</option>
                      <option value="user">User</option>
                      @endrole
                    </select>
                    <small class="error user_type-error"></small>
                </div>
              @else
              <input type="hidden" id="user_type" name="user_type" value="{{$isedit?$userData->getRoleNames()->first():''}}">
              @endif

              <div class="mb-1 col-12 col-md-3">
                <label class="form-label">Name</label>                  
                <input type="name" id="name" placeholder="Name" class="form-control" name="name"  value="{{$isedit?$userData->name:''}}">
              </div>
              <input type="hidden" id="id" name="id" value="{{$id}}">
              
              
              {{-- <div class="mb-1 col col-md-3">
                  <label class="form-label">Email</label>                  
                  <input type="email" id="email" placeholder="Email" class="form-control" name="email">
              </div> --}}
              <div class="mb-1 col-12 col-md-3">
                <label class="form-label">Mobile</label>                  
                <input type="text" id="mobile" placeholder="Mobile" class="form-control  account-number-mask" name="mobile" value="{{$isedit?decrypt_to($userData->mobile):''}}">
              </div>
              @if(!$isedit)
              <div class="mb-1 col-12 col-md-3">
                    <label class="form-label">Password</label>                  
                    <input type="password" id="password" placeholder="Password" class="form-control" name="password">
              </div>
              @endif
            </div>
          </div>
        </div>
        {{-- {{dd($userData)}}   --}}
        @if(!$isedit || ($isedit && $userData->hasRole('user')))
        <!--  User Hidden Block Account details-->
        <div class="card add-section  {{(!($isedit && $userData->hasRole('user')) ? "hidden" :'' )}} user_section">
          <div class="card-header">
            <h4 class="card-title">ACCOUNT DETAILS</h4>
          </div>
          <div class="card-body">
            <div class="row">

            <div class="col-12 col-md-4">
                <div class="invoice-terms mt-1">
                    <div class="d-flex justify-content-between">
                        <label class="invoice-terms-title mb-0" for="order_outside_of_high_low">Order Outside of High Low</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" value='1' class="form-check-input" name="order_outside_of_high_low" id="order_outside_of_high_low" @Checked(($isedit && $userData->order_outside_of_high_low))/>
                            <label class="form-check-label" for="order_outside_of_high_low"></label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <label class="invoice-terms-title mb-0" for=" ">Apply Auto Square</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" value='1' class="form-check-input" name="apply_auto_square" id="apply_auto_square" @Checked(($isedit && $userData->apply_auto_square))/>
                            <label class="form-check-label" for="apply_auto_square"></label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between ">
                        <label class="invoice-terms-title mb-0" for="intra_day_auto_square">Intra Day Auto Square</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" value='1' class="form-check-input" name="intra_day_auto_square" id="intra_day_auto_square" @Checked(($isedit && $userData->intra_day_auto_square))/>
                            <label class="form-check-label" for="intra_day_auto_square"></label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                      <label class="invoice-terms-title mb-0" for="only_position_squareoff">Only Position SquareOff</label>
                      <div class="form-check form-switch">
                          <input type="checkbox" value='1' class="form-check-input" name="only_position_squareoff" id="only_position_squareoff" @Checked(($isedit && $userData->only_position_squareoff))/>
                          <label class="form-check-label" for="only_position_squareoff"></label>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between ">
                      <label class="invoice-terms-title mb-0" for="mtm_linked_with_ledger">MTM Linked with Ledger</label>
                      <div class="form-check form-switch">
                          <input type="checkbox" value='1' class="form-check-input" name="mtm_linked_with_ledger" id="mtm_linked_with_ledger" @Checked(($isedit && $userData->mtm_linked_with_ledger))/>
                          <label class="form-check-label" for="mtm_linked_with_ledger"></label>
                      </div>
                    </div>
                </div>
            </div>

            <div class="mb-1 col-12 col-md-8 row">
              <!-- Broker Name Remote Data -->
              <div class="col-12 col-md-6">
                <label class="form-label" for="select2-ajax">Broker Name</label>
                <div class="mb-1">
                    <select class="form-select select2-ajax-user_dropdown" name="user_broker_id" id="user_broker_id" data-type='broker' data-parent='{{(($isedit ? encrypt_to($userData->parent_id) :'0'))}}'>
                      <option value="" selected>Select Broker</option>
                    </select>
                    <small class="error user_broker_id-error"></small>
                </div>
              </div>

              <div class="mb-1 col-12 col-md-6">
                  <label class="form-label" for="user_account_type_id">Account Type</label>
                  <select name="user_account_type_id" class="form-control form-select select2">
                    <option value="">Select Account Type</option>                                      
                    @foreach($accountTypeData as $key => $val)
                    <option value="{{$val->id}}" @Selected(($isedit && $userData->user_account_type_id==$val->id))>{{strtoupper(str_replace('-', ' ', $val->level_name))}}</option>
                    @endforeach;
                  </select>
                  <small class="error user_account_type_id-error"></small>
              </div>
              <div class="mb-1 col-12 col-md-6">
                  <label class="form-label">Margin Limit</label>                  
                  <input type="number" placeholder="Margin Limit" class="form-control" name="margin_limit" id="margin_limit" value="{{($isedit?$userData->margin_limit:'')}}">
              </div>
              <div class="mb-1 col-12 col-md-6">
                <label class="form-label">Loss Alert Percentage</label>                  
                <input type="number" placeholder="Loss Alert Percentage" class="form-control" name="loss_alert_percentage" id="loss_alert_percentage" value="{{($isedit?$userData->loss_alert_percentage:'')}}">
              </div>
              <div class="mb-1 col-12 col-md-6">
                <label class="form-label">Loss Alert Margin</label>                  
                <input type="number" placeholder="Loss Alert Margin" class="form-control" name="close_alert_margin" id="close_alert_margin"  value="{{($isedit?$userData->close_alert_margin:'')}}">
              </div>
            </div>
            </div>
          </div>
        </div>
        @endif
        
        @if(!$isedit || ($isedit && $userData->hasRole('master')))
        <!--  Master Hidden Block Account details-->
        <div class="card add-section  {{(!($isedit && $userData->hasRole('master')) ? "hidden" :'' )}} master_section">
          <div class="card-header">
            <h4 class="card-title">ACCOUNT DETAILS</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="mb-1 col-12 col-md-@role('admin')6 @else 12 @endrole">
                  <label class="form-label">Partnership(%)</label>                  
                  <input type="number" placeholder="Partnership(%)" class="form-control" name="partnership" id="partnership" value="{{$isedit?$userData->partnership:''}}" max="100">
              </div>
              
              @role('admin')
              <div class="mb-1 col-12 col-md-6">
                <label class="form-label">Master Creation Limit</label>                  
                <input type="number" placeholder="Master Creation Limit" class="form-control" name="master_creation_limit" id="master_creation_limit" value="{{$isedit?$userData->master_creation_limit:''}}" >
              </div>
              @endrole
              <div class="col-md-12 d-flex flex-column flex-lg-row justify-content-lg-center gap-1">
                <div class="">
                  <label class="form-label">MIN Lot Wise Brokerage</label>     
                      <div class="input-group input-width-drop-down">
                          <input type="number" class="form-control" name="min_lot_wise_brokerage" id="min_lot_wise_brokerage"   placeholder="Min Lot Wise Brokerage">
                          <select name="min_lot_wise_brokerage_is_percentage" id="min_lot_wise_brokerage_is_percentage" class="form-select waves-effect ">
                            <option value='1'>%</option>
                            <option value='0'>₹</option>
                          </select>
                      </div>
                </div>

                <div class="">
                  <label class="form-label">MIN Amount Wise Brokerage<sup>(100000)</sup></label>     
                      <div class="input-group input-width-drop-down">
                          <input type="number" class="form-control" name="min_amount_wise_brokerage" id="min_amount_wise_brokerage" placeholder="Min Amount Wise Brokerage(100000)">
                          <select name="min_amount_wise_brokerage_is_percentage" id="min_amount_wise_brokerage_is_percentage" class="form-select waves-effect ">
                            <option value='1'>%</option>
                            <option value='0'>₹</option>
                          </select>
                      </div>
                </div>

                <div class="">
                    <label class="form-label">MAX Intraday Multiplication</label>                  
                    <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="max_intraday_multiplication" id="max_intraday_multiplication" value="{{$isedit?$userData->max_intraday_multiplication:500}}"                     
                    {{!$parant_data->hasRole('admin')?'max='.$parant_data->max_intraday_multiplication:''}} min="0">
                </div>

                <div class="">
                  <label class="form-label">MAX Delivery Multiplication</label>                  
                  <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="max_delivery_multiplication" id="max_delivery_multiplication"  value="{{$isedit?$userData->max_delivery_multiplication:60}}"                   
                  {{!$parant_data->hasRole('admin')?'max='.$parant_data->max_delivery_multiplication:''}} min="0">
                </div>

                <div class="mb-1-12 col-12 col-md-auto ps-md-0">   
                  <label class="form-label"> </label>                
                  <a href="javascript:void(0);" class="btn btn-primary btn-next waves-effect waves-float waves-light form-control"  name="account_details_applay_to_all" id="account_details_applay_to_all"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Apply To All Market">
                      <span class="align-middle d-sm-inline-block ">Apply</span>
                  </a>
                </div>
              </div>

            </div>
          </div>
        </div>    

        <div class="card master_section {{(!($isedit && $userData->hasRole('master')) ? "hidden" :'' )}}">
          <div class="card-header">
            <h4 class="card-title">Additional Details</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 mb-2 border rounded pb-1">
                <h5 class="fw-bolder border-bottom pb-50 m-1">Account Type</h5>
                <div class="row from-group">                 
                    <div class="demo-inline-spacing-s  pb-1">
                    <?php 
                    // $accountTypeData = $isedit ? $userData->get_levels() : Auth::user()->get_levels();        
                    foreach($accountTypeData as $key => $val)
                    {                     
                      $ischecked = ($isedit?(in_array($val->id,$userData->master_account_type_ids)?'checked':''):($key<=5 ? 'checked' : ''));                      
                      echo
                      '<div class="text-capitalize form-check form-check-primary  form-check-inline mt-1">
                        <input type="checkbox" name="master_account_type_ids[]" '.$ischecked.' value="'.$val->id.'" class="form-check-input" id="account_type'.$key.'">
                        <label class="form-check-label" for="account_type'.$key.'">'.strtoupper(str_replace('-', ' ', $val->level_name)).'</label>
                      </div>';
                    }?>
                    </div>
                    <small class="error account_type-error"></small>
                </div>
              </div>
              
              <div class="col-12 mb-2 border rounded pb-1">
                <h5 class="fw-bolder border-bottom pb-50 m-1">Market Type</h5>
                
                  <div class="col-12 from-group">
                    <div class="demo-inline-spacing-s">
                      <small class="error market_type_value-error"></small>
                      <small class="error market_type-error"></small>
                    <?php     
                    // dd(['with_non_trading_market'=>1,'user_id'=>($isedit ? $userData->id:0),'need_user_parent_market'=>($isedit?1:0)]);               
                    $marketData = marketData(['with_non_trading_market'=>1,'user_id'=>($isedit ? $userData->id:0),'need_user_parent_market'=>($isedit?1:0)]);
                    $UserMarketData = $isedit?$userData->formattedMarketData:[];   

                    foreach($marketData as $key => $value)
                    {

                      // if(($isedit && !in_array($value['id'],$userData->AllowedMarketIds(0,1))) && 
                      // (!in_array($value['id'],AllowedMarketIds(Auth::id()))))
                      //   continue;
    
                      // lot_wise_brokerage','amount_wise_brokerage','intraday_multiplication','delivery_multiplication
                      $UserMaxLimit = $parant_data->userMaxLimit(['market_id'=>$value['id']]);                                                
                      $isChecked = isset($UserMarketData[$value['id']])?'checked':'';

                      echo
                      '<div class="d-flex flex-column mt-2">
                            <div class="form-check form-switch form-check-primary">
                            <label class="form-check-label mb-50" for="market_type_'.$value['id'].'">'.$value['market_name'].'</label>
                            
                            <input type="checkbox" id="market_type_'.$value['id'].'" name="market_type_value[]" value="'.$value['id'].'" class="form-check-input" 
                              '.($isChecked?'checked':'').'
                              role="button"
                              aria-controls="collapse_'.$key.'"
                              aria-expanded="'.$isChecked.'"
                              />
                              <label class="form-check-label" for="market_type_'.$value['id'].'">
                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                              </label>
                            </div>
                          </div>';  

                        if(!empty(($value['market_user_required_fields'])))
                        { 
                          $value['form_field'] = ($value['market_user_required_fields']);
                          $MarketWiseData = isset($UserMarketData[$value['id']])?$UserMarketData[$value['id']] :[];         
                          
                          // dd($UserMarketData[$value['id']]['amount_wise_brokerage']['market_field_amount']);
                          // (isset($MarketWiseData['lot_wise_brokerage'])?$MarketWiseData['lot_wise_brokerage']['market_field_amount']:'');                  

                        echo '<div class="collapse '.($isChecked?'show':'').'" id="collapse_'.$key.'">
                            <div class="d-flex p-1 border row">';
                              $isDisabled = $isChecked?'':'disabled';
                              if(!empty($value['form_field']) && in_array('lot_wise_brokerage',$value['form_field']))
                              {
                                
                                $minLimit =  !$parant_data->hasRole('admin')?' min='.$UserMaxLimit['lot_wise_brokerage']['market_field_amount']:'';
                                $minIsPercentage =  !$parant_data->hasRole('admin')?$UserMaxLimit['lot_wise_brokerage']['amount_is_percentage']:1;                                
                                $minAmount =  !$parant_data->hasRole('admin')?$UserMaxLimit['lot_wise_brokerage']['market_field_amount']:'';
                                
                                $amount = isset($MarketWiseData['lot_wise_brokerage'])?$MarketWiseData['lot_wise_brokerage']['market_field_amount']:$minAmount;
                                $is_percentage = isset($MarketWiseData['lot_wise_brokerage'])?$MarketWiseData['lot_wise_brokerage']['amount_is_percentage']:1;
                               

                                echo '<div class="col-md-3 col mb-1">
                                        <label class="form-label">Min Lot Wise Brokerage</label>     
                                            <div class="input-group input-width-drop-down">
                                                <input type="number" class="form-control" name="market_type['.$value['id'].'][lot_wise_brokerage]" placeholder="Min Lot Wise Brokerage" '.$isDisabled.' value="'.$amount.'" '.$minLimit.' >
                                                <select '.$isDisabled.' name="market_type['.$value['id'].'][lot_wise_brokerage_is_percentage]" class="form-select waves-effect" >
                                                  '.($parant_data->hasRole('admin')?
                                                    '<option value=\'1\'>%</option>
                                                     <option value=\'0\' '.($is_percentage?:'selected').'>₹</option>':
                                                     ($minIsPercentage!=0?
                                                     '<option value=\'1\'>%</option>':'
                                                      <option value=\'0\' '.($is_percentage?:'selected').'>₹</option>')
                                                    ).'</select>
                                            </div>
                                            <small class="error market_type'.$value['id'].'lot_wise_brokerage-error"></small>
                                      </div>';
                              }
                              if(!empty($value['form_field']) && in_array('amount_wise_brokerage',$value['form_field']))
                              {
                                $minLimit =  !$parant_data->hasRole('admin')?' min='.$UserMaxLimit['amount_wise_brokerage']['market_field_amount']:'';
                                $minIsPercentage =  !$parant_data->hasRole('admin')?$UserMaxLimit['amount_wise_brokerage']['amount_is_percentage']:0;
                                $minAmount =  !$parant_data->hasRole('admin')?$UserMaxLimit['amount_wise_brokerage']['market_field_amount']:'';

                                $amount = isset($MarketWiseData['amount_wise_brokerage'])?$MarketWiseData['amount_wise_brokerage']['market_field_amount']:$minAmount;
                                $is_percentage = isset($MarketWiseData['amount_wise_brokerage'])?$MarketWiseData['amount_wise_brokerage']['amount_is_percentage']:1;                                

                              echo '<div class="col-md-3 col mb-1">
                                      <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>     
                                          <div class="input-group input-width-drop-down">
                                              <input type="number" class="form-control" name="market_type['.$value['id'].'][amount_wise_brokerage]" placeholder="Min Amount Wise Brokerage(100000)" '.$isDisabled.' value="'.$amount.'" '.$minLimit.'>
                                              <select '.$isDisabled.' name="market_type['.$value['id'].'][amount_wise_brokerage_is_percentage]" class="form-select waves-effect"  value="'.(isset($UserMarketData[$value['id']]['amount_wise_brokerage'])?$UserMarketData[$value['id']]['amount_wise_brokerage']['market_field_amount']:'').'">
                                                '.($parant_data->hasRole('admin')?
                                                    '<option value=\'1\'>%</option>
                                                     <option value=\'0\' '.($is_percentage?:'selected').'>₹</option>':
                                                     ($minIsPercentage!=0?
                                                     '<option value=\'1\'>%</option>':'
                                                      <option value=\'0\' '.($is_percentage?:'selected').'>₹</option>')
                                                    ).'
                                              </select>
                                          </div>
                                          <small class="error market_type'.$value['id'].'amount_wise_brokerage-error"></small>
                                    </div>';
                              }  
                              if(!empty($value['form_field']) && in_array('intraday_multiplication',$value['form_field']))
                              {
                                $minLimit =  !$parant_data->hasRole('admin')?' min="0" max='.$UserMaxLimit['intraday_multiplication']['market_field_amount']:'';
                                $minAmount =  !$parant_data->hasRole('admin')?$UserMaxLimit['intraday_multiplication']['market_field_amount']:'';
                                $amount = isset($MarketWiseData['intraday_multiplication'])?$MarketWiseData['intraday_multiplication']['market_field_amount']:$minAmount;                             


                                echo ' <div class="mb-1 col col-md-3">
                                      <label class="form-label">MAX Intraday Multiplication</label>                  
                                      <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="market_type['.$value['id'].'][intraday_multiplication]" '.$isDisabled.' value="'.$amount.'" '.$minLimit.'>
                                      <small class="error market_type'.$value['id'].'intraday_multiplication-error"></small>
                                  </div>';
                              }
                              if(!empty($value['form_field']) && in_array('delivery_multiplication',$value['form_field']))
                              {
                                $minLimit =  !$parant_data->hasRole('admin')?' min="0" max='.$UserMaxLimit['delivery_multiplication']['market_field_amount']:'';
                                $minAmount =  !$parant_data->hasRole('admin')?$UserMaxLimit['delivery_multiplication']['market_field_amount']:'';
                                $amount = isset($MarketWiseData['delivery_multiplication'])?$MarketWiseData['delivery_multiplication']['market_field_amount']:$minAmount;                               

                                echo '<div class="mb-1 col col-md-3">
                                    <label class="form-label">MAX Delivery Multiplication</label>                  
                                    <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="market_type['.$value['id'].'][delivery_multiplication]" '.$isDisabled.' value="'.$amount.'" '.$minLimit.'>
                                    <small class="error market_type'.$value['id'].'delivery_multiplication-error"></small>

                                  </div>';
                              }
                              
                      echo ' </div>
                          </div>';
                        }
                    }
                  ?>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      @endif

      {{-- user Additional Details  --}}
      @if(!$isedit || ($isedit && $userData->hasRole('user')))
        <div class="card user_section {{(!($isedit && $userData->hasRole('user')) ? "hidden" :'' )}}">
          <div class="card-header">
            <h4 class="card-title">Additional Details</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 mb-0 border rounded pb-1">
                <div class="col-12 from-group">
                  <div class="demo-inline-spacing-s row">
                    <small class="error user_market_type_value-error"></small>
                    <small class="error user_market_type-error"></small>
                    
                  @php($mcxmarketData = marketData(['market_name'=>'MCXFUT','user_id'=>($isedit ? $userData->id:0),'need_user_parent_market'=>($isedit?1:0)]))
                                   
                  @php($UserMarketData = $isedit?$userData->formattedMarketData:[])
                  {{-- {{dd($UserMarketData)}}  --}}
                  @if(!empty($mcxmarketData))

                    @php($value = $mcxmarketData)
                    @php($isChecked = (isset($UserMarketData[$value['id']])?'checked':''))
                    @php($MarketWiseData = $UserMarketData[$value['id']]??[])
                    @php($UserMaxLimit = $parant_data->userMaxLimit(['market_id'=>$value['id']]))

                    {{-- @php($amount = isset($MarketWiseData['lot_wise_brokerage'])?$MarketWiseData['lot_wise_brokerage']['market_field_amount']:''); --}}
                    <div class="col-12">
                      <div class="d-flex flex-column mt-2">
                            <div class="form-check form-switch form-check-primary">
                              <label class="form-check-label mb-50" for="user_market_type_{{$value['id']}}">{{$value['market_name']}}<span class="mcx-user-brok-type-text"></span></label>
                              
                            <input type="checkbox" id="user_market_type_{{$value['id']}}" name="user_market_type_value[]" value="{{$value['id']}}" class="form-check-input" 
                              {{($isChecked?'checked':'')}}
                              role="button"
                              aria-controls="user_collapse_MCXFUT"
                              aria-expanded="{{$isChecked}}"
                              />
                              <label class="form-check-label" for="user_market_type_{{$value['id']}}">
                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                              </label>
                          </div>
                      </div> 
                      <div class="collapse {{($isChecked?'show':'')}}" id="user_collapse_MCXFUT">
                        <div class="d-flex p-0 border rounded row mx-0">
                          
                          @php($marketName = $mcxmarketData['market_name']) 
                          @php($amount = isset($MarketWiseData['commission_type'])?$MarketWiseData['commission_type']['market_field_amount']:'')
                                                    
                          <div class="m-25 col-12 row px-25">
                              <div class="mb-1 col-12 col-md-3">
                                <label class="form-label" for="user_market_type[{{$mcxmarketData['id']}}][commission_type]">Commission Type</label>
                                <select name="user_market_type[{{$mcxmarketData['id']}}][commission_type]" class="form-control form-select ">
                                  <option value="">Select Commission Type</option>
                                  <option value="script_wise" @selected($amount==1)>Script Wise</option>
                                  <option value="same_for_all" @selected($amount==2)>Same For All</option>                        
                                </select>
                                <small class="error user_market_type{{$mcxmarketData['id']}}commission_type-error"></small>
                              </div>

                              @php($amount = isset($MarketWiseData['brokerage_type'])?$MarketWiseData['brokerage_type']['market_field_amount']:'')

                              <div class="mb-1 col-12 col-md-3">
                                <label class="form-label" for="user_market_type[{{$mcxmarketData['id']}}][brokerage_type]">Brokerage Type</label>
                                <select name="user_market_type[{{$mcxmarketData['id']}}][brokerage_type]" class="form-control form-select ">
                                  <option value="">Select Brokerage Type</option>
                                  <option value="amount_wise" @selected($amount==1)>Amount Wise</option>
                                  <option value="lot_wise" @selected($amount==2)>Lot Wise</option>                        
                                </select>
                                <small class="error user_market_type{{$mcxmarketData['id']}}brokerage_type-error"></small>
                              </div>

                              @php($amount = isset($MarketWiseData['delivery_commission'])?$MarketWiseData['delivery_commission']['market_field_amount']:'')
                              @php($is_percentage = isset($MarketWiseData['delivery_commission'])?$MarketWiseData['delivery_commission']['amount_is_percentage']:'0')


                              <input type="hidden"  name="mcx_min_amount_wise_brokerage" value="{{(!$parant_data->hasRole('admin')?$UserMaxLimit['amount_wise_brokerage']['market_field_amount']:'')}}" is_percentage="{{(!$parant_data->hasRole('admin')?$UserMaxLimit['amount_wise_brokerage']['amount_is_percentage']:1)}}">

                              <input type="hidden"  name="mcx_min_lot_wise_brokerage" value="{{(!$parant_data->hasRole('admin')?$UserMaxLimit['lot_wise_brokerage']['market_field_amount']:'')}}" is_percentage="{{(!$parant_data->hasRole('admin')?$UserMaxLimit['lot_wise_brokerage']['amount_is_percentage']:1)}}">

                              <div class="col-12 col-md-6 mcx-user-commission d-none">
                                <div class="row">
                                  <div class="mb-1 col-12 col-md-6">
                                    <label class="form-label">{{$marketName}} Delivery Commission</label>  
                                    <div class="input-group input-width-drop-down">                
                                      <input type="number" placeholder="Enter {{$marketName}} Delivery Commission" class="form-control" name="user_market_type[{{$mcxmarketData['id']}}][delivery_commission]" id="user_market_type[{{$mcxmarketData['id']}}][delivery_commission]" value="{{$amount}}">
                                      <select  name="user_market_type[{{$mcxmarketData['id']}}][delivery_commission_is_percentage]" class="form-select waves-effect rounded-end" >
                                        <option value='1' >%</option>
                                        <option value='0' @selected($is_percentage=='0')>₹</option>
                                      </select>
                                      <small class="error user_market_type{{$mcxmarketData['id']}}delivery_commission-error"></small>
                                    </div>
                                  </div>

                                  @php($amount = isset($MarketWiseData['intraday_commission'])?$MarketWiseData['intraday_commission']['market_field_amount']:'')
                                  @php($is_percentage = isset($MarketWiseData['intraday_commission'])?$MarketWiseData['intraday_commission']['amount_is_percentage']:'0')

                                  <div class="mb-1 col-12 col-md-6">
                                      <label class="form-label">{{$marketName}} Intraday Commission</label>                  
                                      <div class="input-group input-width-drop-down">  
                                        <input type="number" placeholder="Enter {{$marketName}} Intraday Commission" class="form-control" name="user_market_type[{{$mcxmarketData['id']}}][intraday_commission]" id="user_market_type[{{$mcxmarketData['id']}}][intraday_commission]" value="{{$amount}}">
                                        <select  name="user_market_type[{{$mcxmarketData['id']}}][intraday_commission_is_percentage]" class="form-select waves-effect rounded-end" >
                                          <option value='1'>%</option>
                                          <option value='0' @selected($is_percentage=='0')>₹</option>
                                        </select>
                                        <small class="error user_market_type{{$mcxmarketData['id']}}intraday_commission_is_percentage-error"></small>
                                      </div>
                                  </div>  
                              </div>
                            </div>

                            @php($amount = isset($MarketWiseData['default_delivery_commission'])?$MarketWiseData['default_delivery_commission']['market_field_amount']:'')
                            @php($is_percentage = isset($MarketWiseData['default_delivery_commission'])?$MarketWiseData['default_delivery_commission']['amount_is_percentage']:'0')

                            <div class="col-12 col-md-6  mcx-user-default-commission d-none">
                              <div class="row">
                                <div class="mb-1 col-12 col-md-6">
                                  <label class="form-label">Default Delivery Commission</label>
                                  <div class="input-group input-width-drop-down">
                                  <input type="number" placeholder="Default Delivery Commission" class="form-control" name="user_market_type[{{$mcxmarketData['id']}}][default_delivery_commission]" id="user_market_type[{{$mcxmarketData['id']}}][default_delivery_commission]" >

                                  <select  name="user_market_type[{{$mcxmarketData['id']}}][default_delivery_commission_is_percentage]" class="form-select waves-effect rounded-end" >
                                    <option value='1'>%</option>
                                    <option value='0' >₹</option>
                                  </select>

                                  <small class="error user_market_type{{$mcxmarketData['id']}}default_delivery_commission_is_percentage-error"></small>
                                  </div>
                                </div>

                                @php($amount = isset($MarketWiseData['default_delivery_commission'])?$MarketWiseData['default_delivery_commission']['market_field_amount']:'')
                                @php($is_percentage = isset($MarketWiseData['default_delivery_commission'])?$MarketWiseData['default_delivery_commission']['amount_is_percentage']:'0')

                                <div class="mb-1 col-12 col-md-6">
                                  <label class="form-label">Default Intraday Commission</label>
                                  <div class="input-group input-width-drop-down">

                                  <input type="number" placeholder="Default Intraday Commission" class="form-control" name="user_market_type[{{$mcxmarketData['id']}}][default_intraday_commission]" id="user_market_type[{{$mcxmarketData['id']}}][default_intraday_commission]"  >

                                  <select  name="user_market_type[{{$mcxmarketData['id']}}][default_intraday_commission_is_percentage]" class="form-select waves-effect rounded-end" >
                                    <option value='1'>%</option>
                                    <option value='0' >₹</option>
                                  </select>
                                  <small class="error user_market_type{{$mcxmarketData['id']}}default_intraday_commission_is_percentage-error"></small>
                                  </div>
                                </div>
                              </div>
                            </div>

                            @php($scriptsData = scriptData($mcxmarketData['id']))
                            <div class="col-12 mcx-user-scripts-commission d-none">
                              <div class="row">
                              @foreach($scriptsData as $script)

                                @php($amount = !empty($MarketWiseData['script_data'][$script['value']]['delivery_commission'])?$MarketWiseData['script_data'][$script['value']]['delivery_commission']['market_field_amount']:'')
                                @php($is_percentage = !empty($MarketWiseData['script_data'][$script['value']]['delivery_commission'])?$MarketWiseData['script_data'][$script['value']]['delivery_commission']['amount_is_percentage']:'0')


                                <div class="col-12 col-md-4 py-50">
                                  <div class="border rounded p-1"> 
                                  <h6 class=" fw-bolder fw-bolder border-bottom pb-25 ">{{$script['label']}}</h5>
                                    
                                    <div class="mb-1 col-12">
                                      <label class="form-label">{{$script['label']}} Delivery Commission</label>
                                      <div class="input-group input-width-drop-down">
                                      <input type="number" placeholder="{{$script['label']}} Delivery Commission" class="form-control" name="user_market_type[{{$mcxmarketData['id']}}][user_script_wise_commission][{{$script['value']}}][delivery_commission]" id="user_mcx_delivery_commission" value="{{$amount}}">
                                      <select  name="user_market_type[{{$mcxmarketData['id']}}][user_script_wise_commission][{{$script['value']}}][delivery_commission_is_percentage]" class="form-select waves-effect rounded-end" >
                                        <option value='1'>%</option>
                                        <option value='0' @selected($is_percentage=='0')>₹</option>
                                      </select>
                                      <small class="error user_market_typeuser_script_wise_commission{{$script['value']}}delivery_commission_is_percentage-error"></small>
                                      </div>
                                    </div>

                                    @php($amount = !empty($MarketWiseData['script_data'][$script['value']]['intraday_commission'])?$MarketWiseData['script_data'][$script['value']]['intraday_commission']['market_field_amount']:'')
                                    @php($is_percentage = !empty($MarketWiseData['script_data'][$script['value']]['intraday_commission'])?$MarketWiseData['script_data'][$script['value']]['intraday_commission']['amount_is_percentage']:'0')

                                    <div class="mb-1 col-12">
                                      <label class="form-label">{{$script['label']}} Intraday Commission</label>
                                      <div class="input-group input-width-drop-down">
                                      <input type="number" placeholder="{{$script['label']}} Intraday Commission" class="form-control" name="user_market_type[{{$mcxmarketData['id']}}][user_script_wise_commission][{{$script['value']}}][intraday_commission]" id="user_mcx_intraday_commission"  value="{{$amount}}">
                                      <select  name="user_market_type[{{$mcxmarketData['id']}}][user_script_wise_commission][{{$script['value']}}][intraday_commission_is_percentage]" class="form-select waves-effect rounded-end" >
                                        <option value='1'>%</option>
                                        <option value='0'  @selected($is_percentage=='0')>₹</option>
                                      </select>
                                      <small class="error user_market_type{{$mcxmarketData['id']}}user_script_wise_commission{{$script['value']}}intraday_commission-error"></small>
                                      </div>
                                    </div>            
                                  </div>
                                </div>
                              @endforeach
                              </div>
                            </div>
                          </div>            
                        </div>       
                      </div>       
                    </div>       
                  @endif


                  <?php
                  $marketData = marketData(['with_non_trading_market'=>1,'user_id'=>($isedit ? $userData->id:0),'need_user_parent_market'=>($isedit?1:0)]);
                  $UserMarketData = $isedit?$userData->formattedMarketData:[];      

                  
                  foreach($marketData as $key => $value)
                  {
                    if($value['market_name']=='MCXFUT')
                      continue;               
                    
                    $UserMaxLimit = $parant_data->userMaxLimit(['market_id'=>$value['id']]);                    
                    $isChecked = isset($UserMarketData[$value['id']])?'checked':'';                    
                    echo '<div class="col-12  col-md-6">';
                    echo
                        '<div class="d-flex flex-column mt-2">
                            <div class="form-check form-switch form-check-primary">
                              <label class="form-check-label mb-50" for="user_market_type_'.$value['id'].'">'.$value['market_name'].'</label>
                              
                          <input type="checkbox" id="user_market_type_'.$value['id'].'" name="user_market_type_value[]" value="'.$value['id'].'" class="form-check-input" 
                            '.($isChecked?'checked':'').'
                            role="button"
                            aria-controls="user_collapse_'.$key.'"
                            aria-expanded="'.$isChecked.'"
                            />
                            <label class="form-check-label" for="user_market_type_'.$value['id'].'">
                              <span class="switch-icon-left"><i data-feather="check"></i></span>
                              <span class="switch-icon-right"><i data-feather="x"></i></span>
                            </label>
                          </div>
                        </div>';  

                        if(!empty(($value['user_required_fields'])))
                        { 
                          $value['form_field'] = ($value['user_required_fields']);
                          $MarketWiseData = isset($UserMarketData[$value['id']])?$UserMarketData[$value['id']] :[];

                        echo '<div class="collapse '.($isChecked?'show':'').'" id="user_collapse_'.$key.'">
                            <div class="d-flex p-25 border rounded row mx-0">';
                              $isDisabled = $isChecked?'':'disabled';
                              if(!empty($value['form_field']) && in_array('intraday_multiplication',$value['form_field']))
                              {
                                $maxLimit =  !$parant_data->hasRole('admin')?' min="0" max='.$UserMaxLimit['intraday_multiplication']['market_field_amount']:'';
                                $maxIsPercentage =  !$parant_data->hasRole('admin')?$UserMaxLimit['intraday_multiplication']['amount_is_percentage']:1;                                
                                $maxAmount =  !$parant_data->hasRole('admin')?$UserMaxLimit['intraday_multiplication']['market_field_amount']:'';

                                $amount = isset($MarketWiseData['intraday_multiplication'])?$MarketWiseData['intraday_multiplication']['market_field_amount']:$maxAmount;
                                $is_percentage = isset($MarketWiseData['intraday_multiplication'])?$MarketWiseData['intraday_multiplication']['amount_is_percentage']:1;

                                echo '<div class="col-12 col-md-6 col mb-1">
                                        <label class="form-label">Intraday Multiplication</label>  
                                            <input type="number" class="form-control" name="user_market_type['.$value['id'].'][intraday_multiplication]" placeholder="Intraday Multiplication" '.$isDisabled.' value="'.$amount.'" '.$maxLimit.'>
                                            <small class="error user_market_type'.$value['id'].'intraday_multiplication-error"></small>
                                      </div>';
                              }

                              if(!empty($value['form_field']) && in_array('delivery_multiplication',$value['form_field']))
                              {
                                $maxLimit =  !$parant_data->hasRole('admin')?' min="0" max='.$UserMaxLimit['delivery_multiplication']['market_field_amount']:'';
                                $maxIsPercentage =  !$parant_data->hasRole('admin')?$UserMaxLimit['delivery_multiplication']['amount_is_percentage']:1;                                
                                $maxAmount =  !$parant_data->hasRole('admin')?$UserMaxLimit['delivery_multiplication']['market_field_amount']:'';

                                $amount = isset($MarketWiseData['delivery_multiplication'])?$MarketWiseData['delivery_multiplication']['market_field_amount']:$maxAmount;
                                $is_percentage = isset($MarketWiseData['delivery_multiplication'])?$MarketWiseData['delivery_multiplication']['amount_is_percentage']:1;
                                echo '<div class="col-12 col-md-6 col mb-1">
                                        <label class="form-label">Delivery Multiplication</label> 
                                        <input type="number" class="form-control" name="user_market_type['.$value['id'].'][delivery_multiplication]" placeholder="Delivery Multiplication" '.$isDisabled.' value="'.$amount.'" '.$maxLimit.'>
                                        <small class="error user_market_type'.$value['id'].'delivery_multiplication-error"></small>
                                      </div>';
                              }

                              if(!empty($value['form_field']) && (in_array('lot_wise_delivery_commission',$value['form_field']) || in_array('amount_wise_delivery_commission',$value['form_field'])))
                              {
                                $user_key_field =  in_array('amount_wise_delivery_commission',$value['form_field'])?'amount_wise_delivery_commission':'lot_wise_delivery_commission';
                                $user_key_field_name =  in_array('amount_wise_delivery_commission',$value['form_field'])?'(Amount Wise)':'(Lot Wise)';

                                echo '<div class="col-12 col-md-12 col mt-25">
                                  <h6 class="fw-bolder border-bottom py-50">'.$value['market_name'].' '.$user_key_field_name.'</h6></div>';

                                $minLimit =  !$parant_data->hasRole('admin')?' min='.$UserMaxLimit[$user_key_field]['market_field_amount']:'';
                                $minIsPercentage =  !$parant_data->hasRole('admin')?$UserMaxLimit[$user_key_field]['amount_is_percentage']:1;                                
                                $minAmount =  !$parant_data->hasRole('admin')?$UserMaxLimit[$user_key_field]['market_field_amount']:'';

                                $amount = isset($MarketWiseData[$user_key_field])?$MarketWiseData[$user_key_field]['market_field_amount']:$minAmount;
                                $is_percentage = isset($MarketWiseData[$user_key_field])?$MarketWiseData[$user_key_field]['amount_is_percentage']:1;

                                echo '<div class="col-12 col-md-6 col mb-1">
                                        <label class="form-label">'.$value['market_name'].' Delivery Commission</label>     
                                            <div class="input-group input-width-drop-down">
                                                <input type="number" class="form-control" name="user_market_type['.$value['id'].']['.$user_key_field.']" placeholder="Delivery Commission" '.$isDisabled.' value="'.$amount.'" '.$minLimit.'>
                                                <select '.$isDisabled.' name="user_market_type['.$value['id'].']['.$user_key_field.'_is_percentage]" class="form-select waves-effect" >                                                  
                                                  '.($parant_data->hasRole('admin')?
                                                    '<option value=\'1\'>%</option>
                                                     <option value=\'0\' '.($is_percentage?:'selected').'>₹</option>':
                                                     ($minIsPercentage!=0?
                                                     '<option value=\'1\'>%</option>':'
                                                      <option value=\'0\' '.($is_percentage?:'selected').'>₹</option>')
                                                    ).'
                                                </select>
                                            </div>
                                            <small class="error user_market_type'.$value['id'].''.$user_key_field.'-error"></small>
                                      </div>';
                              }

                              if(!empty($value['form_field']) && (in_array('lot_wise_intraday_commission',$value['form_field']) || in_array('amount_wise_intraday_commission',$value['form_field'])))
                              {
                                $user_key_field =  in_array('lot_wise_intraday_commission',$value['form_field'])?'lot_wise_intraday_commission':'amount_wise_intraday_commission';

                                $minLimit =  !$parant_data->hasRole('admin')?' min='.$UserMaxLimit[$user_key_field]['market_field_amount']:'';
                                $minIsPercentage =  !$parant_data->hasRole('admin')?$UserMaxLimit[$user_key_field]['amount_is_percentage']:1;                                
                                $minAmount =  !$parant_data->hasRole('admin')?$UserMaxLimit[$user_key_field]['market_field_amount']:'';

                                $amount = isset($MarketWiseData[$user_key_field])?$MarketWiseData[$user_key_field]['market_field_amount']:$minAmount;
                                $is_percentage = isset($MarketWiseData[$user_key_field])?$MarketWiseData[$user_key_field]['amount_is_percentage']:1;

                                echo '<div class="col-12 col-md-6 col mb-1">
                                        <label class="form-label">'.$value['market_name'].' Intraday Commission</label>     
                                            <div class="input-group input-width-drop-down">
                                                <input type="number" class="form-control" name="user_market_type['.$value['id'].']['.$user_key_field.']" placeholder="Intraday Commission" '.$isDisabled.' value="'.$amount.'" '.$minLimit.'>
                                                <select '.$isDisabled.' name="user_market_type['.$value['id'].']['.$user_key_field.'_is_percentage]" class="form-select waves-effect" >
                                                  '.($parant_data->hasRole('admin')?
                                                    '<option value=\'1\'>%</option>
                                                     <option value=\'0\' '.($is_percentage?:'selected').'>₹</option>':
                                                     ($minIsPercentage!=0?
                                                     '<option value=\'1\'>%</option>':'
                                                      <option value=\'0\' '.($is_percentage?:'selected').'>₹</option>')
                                                    ).'
                                                </select>
                                            </div>
                                            <small class="error user_market_type'.$value['id'].''.$user_key_field.'-error"></small>
                                      </div>';
                              }
                            echo '</div>
                            </div>';
                          }
                    echo '</div>';
                  }                 
                  ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        @endif

        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Remark</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <textarea class="form-control" rows="3" name="user_remark">{{$isedit?$userData->user_remark:''}}</textarea> 
              </div>
              <div class="col-12 pt-2 ">
                <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">{{$isedit?'Update':'Create'}}</button>
                <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
              </div>
            </div>
          </div>
        </div>
        
      </form>
    </div>
  </div>
</section>
@endsection


@section('vendor-script')
{{-- vendor files --}}
  <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.in.js')) }}"></script>
  
@endsection

@section('page-script')
<script>

  // $(document).on('click', '#account_details_applay_to_all' , function () { 
    
  //   var min_lot_wise_brokerage = $('[name="min_lot_wise_brokerage"]').val();
  //   var min_lot_wise_brokerage_is_percentage = $('[name="min_lot_wise_brokerage_is_percentage"]').val();
    
  //   var min_amount_wise_brokerage = $('[name="min_amount_wise_brokerage"]').val();
  //   var min_amount_wise_brokerage_is_percentage = $('[name="min_amount_wise_brokerage_is_percentage"]').val();

  //   var max_intraday_multiplication = $('[name="max_intraday_multiplication"]').val();
  //   var max_delivery_multiplication = $('[name="max_delivery_multiplication"]').val();;

  //   $('.master_section [name*="lot_wise_brokerage"] ').val(min_lot_wise_brokerage);
  //   $('.master_section [name*="lot_wise_brokerage_is_percentage"] ').val(min_lot_wise_brokerage_is_percentage);

  //   $('.master_section [name*="amount_wise_brokerage"] ').val(min_amount_wise_brokerage);
  //   $('.master_section [name*="amount_wise_brokerage_is_percentage"] ').val(min_amount_wise_brokerage_is_percentage);

  //   $('.master_section [name*="intraday_multiplication"] ').val(max_intraday_multiplication);
  //   $('.master_section [name*="delivery_multiplication"] ').val(max_delivery_multiplication);

  //     // $('[name=market_type_value]:checked').each(function(){   
  //     // });

  //   return false; 
  // });  

  $(document).ready(function(){
    $(document).on('change', '[name=user_type]', function(event) { 

      var value = $(this).val();
      $.each($("[name=user_type]").prop("options"), function(i, opt) {
        if(opt.value.length <= 0)
          return;
        if(opt.value == value)
        {
          $('.'+value+'_section').removeClass('hidden');
          DisableEnableFields($('.'+value+'_section'),false);
        }
        else
        {
          $('.'+opt.value+'_section').addClass('hidden');
          DisableEnableFields($('.'+opt.value+'_section'));
        }        


          // if(opt.value.length > 0)
          //   values.push(opt.value);

          //  values[opt.value] = opt.textContent;
      });
      // console.log(values)
      // if($(this).val() == 'master')
      // {
      //   $('.master_section').removeClass('hidden');
      //   DisableEnableFields($('.master_section'),false);
      // } 
      // else
      // {
      //   $('.master_section').addClass('hidden');
      //   DisableEnableFields($('.master_section'));
      // }
    });
  });

  function  DisableEnableFields(container,trueFalse = true) 
  {
    var formContainer = $(container);

    console.log(formContainer.find("select"));
    // Disable text inputs
    formContainer.find("input").not("[name*='market_type']").prop("disabled", trueFalse);

    // // Disable radio buttons
    // formContainer.find("input[type='radio']").not("[name*='market_type']").prop("disabled", trueFalse);

    // // Disable checkboxes
    // formContainer.find("input[type='checkbox']").not("[name*='market_type']").prop("disabled", trueFalse);
    // Disable select dropdown
    formContainer.find("select").not("[name*='market_type']").prop("disabled", trueFalse);
  }

  $('[name="market_type_value[]"],[name="user_market_type_value[]"]').on('change', function(event){ 
    $('#'+$(this).attr('aria-controls')).collapse(($(this).is(':checked')? 'show' : 'hide'));
    if($(this).is(':checked'))
    {
      $('#'+$(this).attr('aria-controls')+' input').prop("disabled", false);
      $('#'+$(this).attr('aria-controls')+' select').prop("disabled", false);

      if($(this).attr('aria-controls')=="user_collapse_MCXFUT") 
        $('[name*="brokerage_type"]').change();

    }
    else
    {
      $('#'+$(this).attr('aria-controls')+' input').prop("disabled", true);
      $('#'+$(this).attr('aria-controls')+' select').prop("disabled", true);
      var selector = '#'+$(this).attr('aria-controls')+' :input';
      $(selector).each(function() {
        var inputValue = $(this).attr('name');
        // Remove the 'error' class from elements with the specified name
        $('[name="'+inputValue+'"]').removeClass('error');
        // Reset the content of elements with the specified class
        var inputValue = inputValue.replace(/\[|\]/g, '');
        $('.' + inputValue + '-error').html('');       
      });
      if($(this).attr('aria-controls')=="user_collapse_MCXFUT") 
        $('.mcx-user-brok-type-text').html('');
    }
   });

  // Confirm 
  
  $(document).on('click','#account_details_applay_to_all', function () {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't to apply values on all Markets!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Change it!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ms-1'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.value) {

          var min_lot_wise_brokerage = $('[name="min_lot_wise_brokerage"]').val();
          var min_lot_wise_brokerage_is_percentage = $('[name="min_lot_wise_brokerage_is_percentage"]').val();
          
          var min_amount_wise_brokerage = $('[name="min_amount_wise_brokerage"]').val();
          var min_amount_wise_brokerage_is_percentage = $('[name="min_amount_wise_brokerage_is_percentage"]').val();

          var max_intraday_multiplication = $('[name="max_intraday_multiplication"]').val();
          var max_delivery_multiplication = $('[name="max_delivery_multiplication"]').val();;

          $('.master_section [name*="lot_wise_brokerage"] ').val(min_lot_wise_brokerage);
          $('.master_section [name*="lot_wise_brokerage_is_percentage"] ').val(min_lot_wise_brokerage_is_percentage);

          $('.master_section [name*="amount_wise_brokerage"] ').val(min_amount_wise_brokerage);
          $('.master_section [name*="amount_wise_brokerage_is_percentage"] ').val(min_amount_wise_brokerage_is_percentage);

          $('.master_section [name*="intraday_multiplication"] ').val(max_intraday_multiplication);
          $('.master_section [name*="delivery_multiplication"] ').val(max_delivery_multiplication);


          Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Your market values has been updated.',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          Swal.fire({
            title: 'Cancelled',
            text: 'Your imaginary market values are safe :)',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        }
      });
    });
</script>

  {{-- Page js files --}}
  <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
  {{-- <script src="{{ asset(mix('js/scripts/forms/form-wizard.js')) }}"></script> --}}

  <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

  <script src="{{ asset(mix('js/scripts/tables/table-datatables-advanced.js')) }}"></script>

  {{-- <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script> --}}
  <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>

  <script>
    $(document).on('change','[name*="brokerage_type"]',function(e) {
      var user_mcx_brokerage_type = $(e.target).val();
      $('.mcx-user-brok-type-text').text((user_mcx_brokerage_type!=''?(user_mcx_brokerage_type=='amount_wise'?'(Amount  Wise)':'(Lot Wise)'):''));

      var brokerage_min =  (user_mcx_brokerage_type!=''?(user_mcx_brokerage_type=='amount_wise'?$('#user_collapse_MCXFUT [name="mcx_min_amount_wise_brokerage"]'):$('#user_collapse_MCXFUT [name="mcx_min_lot_wise_brokerage"]')):'');

      if(brokerage_min)
      {
          var brokerage_min_value = brokerage_min.val();
          var brokerage_min_is_percentage = brokerage_min.attr('is_percentage');

          var option = brokerage_min_is_percentage==1 ?'<option value="1">%</option>' : '<option value="0">₹</option>';
          
          // val(brokerage_min_value)

          $('.mcx-user-commission [name*="intraday_commission"]').attr('min',brokerage_min_value);
          $('.mcx-user-scripts-commission [name*="intraday_commission"]').attr('min',brokerage_min_value);
          $('.mcx-user-default-commission [name*="intraday_commission"]').attr('min',brokerage_min_value);          

          $('.mcx-user-commission [name*="intraday_commission_is_percentage"]').html(option).change();
          $('.mcx-user-scripts-commission [name*="intraday_commission_is_percentage"]').html(option).change();
          $('.mcx-user-default-commission [name*="default_intraday_commission_is_percentage"] ').html(option);

          $('.mcx-user-commission [name*="delivery_commission"]').attr('min',brokerage_min_value);
          $('.mcx-user-scripts-commission [name*="delivery_commission"]').attr('min',brokerage_min_value);
          $('.mcx-user-default-commission [name*="delivery_commission"]').attr('min',brokerage_min_value);          

          $('.mcx-user-commission [name*="delivery_commission_is_percentage"]').html(option).change();;
          $('.mcx-user-scripts-commission [name*="delivery_commission_is_percentage"]').html(option).change();
          $('.mcx-user-default-commission [name*="default_delivery_commission_is_percentage"] ').html(option);

      }
      
    });

    $(document).on('change','[name*="commission_type"]',function(e) {
      var user_mcx_commission_type = $(e.target).val();
      
      if(user_mcx_commission_type=='same_for_all')
      {
        $('.mcx-user-scripts-commission').addClass('d-none');
        $('.mcx-user-default-commission').addClass('d-none');
        $('.mcx-user-commission').removeClass('d-none');

        $('.mcx-user-scripts-commission input').prop("disabled", true);
        $('.mcx-user-scripts-commission select').prop("disabled", true);

        $('.mcx-user-default-commission input').prop("disabled", true);
        $('.mcx-user-default-commission select').prop("disabled", true);

        $('.mcx-user-commission input').prop("disabled", false);
        $('.mcx-user-commission select').prop("disabled", false);

      }
      else if(user_mcx_commission_type=='script_wise')
      {
        $('.mcx-user-commission').addClass('d-none');
        $('.mcx-user-default-commission').removeClass('d-none');
        $('.mcx-user-scripts-commission').removeClass('d-none');


        $('.mcx-user-scripts-commission input').prop("disabled", false);
        $('.mcx-user-scripts-commission select').prop("disabled", false);

        $('.mcx-user-default-commission input').prop("disabled", false);
        $('.mcx-user-default-commission select').prop("disabled", false);

        $('.mcx-user-commission input').prop("disabled", true);
        $('.mcx-user-commission select').prop("disabled", true);
      }
      else
      {
        $('.mcx-user-commission').addClass('d-none');
        $('.mcx-user-default-commission').addClass('d-none');
        $('.mcx-user-scripts-commission').addClass('d-none');

        $('.mcx-user-scripts-commission input').prop("disabled", true);
        $('.mcx-user-scripts-commission select').prop("disabled", true);

        $('.mcx-user-default-commission input').prop("disabled", true);
        $('.mcx-user-default-commission select').prop("disabled", true);

        $('.mcx-user-commission input').prop("disabled", true);
        $('.mcx-user-commission select').prop("disabled", true);
      }
     
    });

    $('[name*="brokerage_type"]').change();
    $('[name*="commission_type"]').change();    

    $(document).on('keyup change focusout','[name*="default_delivery_commission"],[name*="default_intraday_commission"],[name*="default_delivery_commission_is_percentage"],[name*="default_intraday_commission_is_percentage"]',function(e) {

      var default_delivery_commission = $('[name*="default_delivery_commission"]').val();
      var default_delivery_commission_is_per = $('  [name*="default_delivery_commission_is_percentage"]').val();

      var default_intraday_commission = $('[name*="default_intraday_commission"]').val();
      var default_intraday_commission_is_per = $('[name*="default_intraday_commission_is_percentage"]').val();

      $('.mcx-user-scripts-commission [name*="intraday_commission"] ').val(default_intraday_commission);
      $('.mcx-user-scripts-commission [name*="intraday_commission_is_percentage"]').val(default_intraday_commission_is_per);

      $('.mcx-user-scripts-commission [name*="delivery_commission"] ').val(default_delivery_commission);
      $('.mcx-user-scripts-commission [name*="delivery_commission_is_percentage"]').val(default_delivery_commission_is_per);

    });

    $(document).ready(function(){
      $(document).on('focusout','input[max], input[min]', function() {
          var inputValue = parseFloat($(this).val());
          var max = parseFloat($(this).attr('max'));
          var min = parseFloat($(this).attr('min'));
          
          if (!isNaN(max) && inputValue > max) {
              $(this).val(max); // Set value to max if it exceeds max attribute
          } else if (!isNaN(min) && inputValue < min) {
              $(this).val(min); // Set value to min if it's less than min attribute
          }

          $(this).change();
      });
      $(document).on('change','[name*="_is_percentage"]', function() {      
          var inputValue = $(this).closest('.input-group').find('input[type="number"]');
          if ($(this).val() == "1") {
            // If selected value is %
            inputValue.attr('max', '100'); // Set max value to 100
          } else {
            // If selected value is ₹
            inputValue.removeAttr('max'); // Remove max value attribute
          }
      });

      $('[name*="_is_percentage"]:not([name*="default_delivery_commission_is_percentage"]):not([name*="default_intraday_commission_is_percentage"])').change();
  });

    
  </script>

  <script>


$(".select2-ajax-user_dropdown").select2({
    ajax: {
      url: "{{route('getRoleWiseUserlist')}}",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          q: params.term, // search term
          user_position:$(this).attr("data-type"), // search term
          parent:$(this).attr("data-parent")??0, // search term
          page: params.page || 1
        };
      },
      processResults: function (data, params) {
        // parse the results into the format expected by Select2
        // since we are using custom formatting functions we do not need to
        // alter the remote JSON data, except to indicate that infinite
        // scrolling can be useda
        params.page = params.page || 1;
  
        return {
          results: data.data,
          pagination: {
            more: (params.page * 5) < data.total
          }
        };
      },
      cache: true
    },
    placeholder: 'Search Your Terms',
    allowClear: true,
    minimumInputLength: 1,
    templateResult: RepoSelection,
    templateSelection: RepoSelection,
    escapeMarkup: function (es) {
      return es;
    }
  });
  function RepoSelection(option) {
      if (!option.id) {
        return option.text;
      }
      var $person =
        `<div class="d-flex align-items-center p-0"><div class="avatar me-1 avatar-sm"><span class="avatar-content bg-${getRandomColorState()}">${getInitials(option.name)}</span></div><p class="mb-0">` +option.name +`</p></div>`;
      return $person;
  }
  function getRandomColorState() {
    const color_states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
    return color_states[Math.floor(Math.random() * color_states.length)];
  }

  function getInitials(fullName) {
    if (fullName.split(' ').length === 1) {
        return fullName.substring(0, 2).toUpperCase();
    } else {
        return fullName
            .split(' ')
            .slice(0, 2)
            .map(word => word[0].toUpperCase())
            .join('')
            .substring(0, 2);
    }
}
</script>
@endsection