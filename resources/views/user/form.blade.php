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
                      <option value="master">Master</option>
                      @role('master')
                      <option value="broker">Broker</option>
                      <option value="user">User</option>
                      @endrole
                    </select>
                    <small class="error user_type-error"></small>
                </div>
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
                    <select class="form-select select2-ajax-user_broker" name="user_broker_id" id="user_broker_id">
                      <option value="" selected>Select Broker</option>
                    </select>
                    <small class="error user_broker_id-error"></small>
                </div>
              </div>

              <div class="mb-1 col-12 col-md-6">
                  <label class="form-label" for="user_account_type_id">Account Type</label>
                  <select name="user_account_type_id" class="form-control form-select ">
                    <option value="">Select Account Type</option>
                    @php($accountTypeData = get_levels());                    
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
                <input type="number" placeholder="Loss Alert Margin" class="form-control" name="loss_alert_margin" id="loss_alert_margin"  value="{{($isedit?$userData->loss_alert_margin:'')}}">
              </div>
            </div>
            </div>
          </div>
        </div>
        @endif
        {{-- <pre> --}}
        {{-- {{dd($userData)}} --}}
        @if(!$isedit || ($isedit && $userData->hasRole('master')))
        <!--  Master Hidden Block Account details-->
        <div class="card add-section  {{(!($isedit && $userData->hasRole('master')) ? "hidden" :'' )}} master_section">
          <div class="card-header">
            <h4 class="card-title">ACCOUNT DETAILS</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="mb-1 col-12 col-md-6">
                  <label class="form-label">Partnership(%)</label>                  
                  <input type="number" placeholder="Partnership(%)" class="form-control" name="partnership" id="partnership" value="{{$isedit?$userData->partnership:''}}">
              </div>
              <div class="mb-1 col-12 col-md-6">
                <label class="form-label">Master Creation Limit</label>                  
                <input type="number" placeholder="Master Creation Limit" class="form-control" name="master_creation_limit" id="master_creation_limit" value="{{$isedit?$userData->master_creation_limit:''}}" >
              </div>


              <div class="col-md-auto col-12 mb-1">
                <label class="form-label">MIN Lot Wise Brokerage</label>     
                    <div class="input-group input-width-drop-down">
                        <input type="number" class="form-control" name="min_lot_wise_brokerage" id="min_lot_wise_brokerage"   placeholder="Min Lot Wise Brokerage">
                        <select name="min_lot_wise_brokerage_is_percentage" id="min_lot_wise_brokerage_is_percentage" class="form-select waves-effect ">
                          <option value='1'>%</option>
                          <option value='0'>₹</option>
                        </select>
                    </div>
              </div>

              <div class="col-md-auto col-12 mb-1">
                <label class="form-label">MIN Amount Wise Brokerage<sup>(100000)</sup></label>     
                    <div class="input-group input-width-drop-down">
                        <input type="number" class="form-control" name="min_amount_wise_brokerage" id="min_amount_wise_brokerage" placeholder="Min Amount Wise Brokerage(100000)">
                        <select name="min_amount_wise_brokerage_is_percentage" id="min_amount_wise_brokerage_is_percentage" class="form-select waves-effect ">
                          <option value='1'>%</option>
                          <option value='0'>₹</option>
                        </select>
                    </div>
              </div>

              <div class="mb-1 col-12 col-md-auto">
                  <label class="form-label">MAX Intraday Multiplication</label>                  
                  <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="max_intraday_multiplication" id="max_intraday_multiplication" >
              </div>

              <div class="mb-1-12 col-12 col-md-auto">
                <label class="form-label">MAX Delivery Multiplication</label>                  
                <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="max_delivery_multiplication" id="max_delivery_multiplication" >
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

        <div class="card master_section">
          <div class="card-header">
            <h4 class="card-title">Additional Details</h4>
          </div>
          <div class="card-body">
            <div class="col-12 mb-2 border rounded pb-1">
              <h5 class="fw-bolder border-bottom pb-50 m-1">MCXFUT Market</h5>
              @php($mcxmarketData = marketData(0,0,0,'MCXFUT'))             
              @if(!empty($mcxmarketData))
                <input type="hidden" id="market_type_{{$mcxmarketData['id']}}" name="market_type_value" value="{{$mcxmarketData['id']}}">
                <div class="m-25 col-12 row">
                    <div class="mb-1 col-12 col-md-3">
                      <label class="form-label" for="commission_type">Commission Type</label>
                      <select name="commission_type" class="form-control form-select ">
                        <option value="">Select Commission Type</option>
                        <option value="script_wise">Script Wise</option>
                        <option value="same_for_all">Same For All</option>                        
                      </select>
                      <small class="error commission_type-error"></small>
                    </div>

                    <div class="mb-1 col-12 col-md-3">
                      <label class="form-label" for="brokerage_type">Brokerage Type</label>
                      <select name="brokerage_type" class="form-control form-select ">
                        <option value="">Select Brokerage Type</option>
                        <option value="amount_wise">Amount Wise</option>
                        <option value="lot_wise">Lot Wise</option>                        
                      </select>
                      <small class="error brokerage_type-error"></small>
                    </div>

                    <div class="mb-1 col-12 col-md-3">
                        <label class="form-label">Default Intraday Multiplication</label>                  
                        <input type="number" placeholder="Default Intraday Multiplication" class="form-control" name="default_intraday_multiplication" id="default_intraday_multiplication" >
                    </div>
      
                    <div class="mb-1-12 col-12 col-md-3">
                      <label class="form-label">Default Delivery Multiplication</label>                  
                      <input type="number" placeholder="Default Delivery Multiplication" class="form-control" name="default_delivery_multiplication" id="default_delivery_multiplication" >
                    </div>
                </div>

                
              @endif

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
                    $accountTypeData = get_levels();                    
                    foreach($accountTypeData as $key => $val)
                    {
                      $ischecked = ($isedit?(in_array($val->id,$userData->master_account_type_ids)?'checked':''):($key<=5 ? 'checked' : ''));

                      echo
                      '<div class="text-capitalize form-check form-check-primary  form-check-inline mt-1">
                        <input type="checkbox" name="master_account_type_ids" '.$ischecked.' value="'.$val->id.'" class="form-check-input" id="account_type'.$key.'">
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
                    $marketData = marketData(0,1);
                   
                    
                    $UserMarketData = $isedit?$userData->formattedMarketData:[];
                    
                    foreach($marketData as $key => $value)
                    {
                      // lot_wise_brokerage','amount_wise_brokerage','intraday_multiplication','delivery_multiplication
                      $isChecked = isset($UserMarketData[$value['id']])?'checked':'';

                      echo
                      '<div class="d-flex flex-column mt-2">
                            <div class="form-check form-switch form-check-primary">
                            <label class="form-check-label mb-50" for="market_type_'.$value['id'].'">'.$value['market_name'].'</label>
                            
                            <input type="checkbox" id="market_type_'.$value['id'].'" name="market_type_value" value="'.$value['id'].'" class="form-check-input" 
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

                        if(!empty(json_decode($value['market_user_required_fields'])))
                        { 
                          

                          $value['form_field'] = json_decode($value['market_user_required_fields']);
                          $MarketWiseData = isset($UserMarketData[$value['id']])?$UserMarketData[$value['id']] :[];
                          
                          // dd($UserMarketData[$value['id']]['amount_wise_brokerage']['market_field_amount']);
                          // (isset($MarketWiseData['lot_wise_brokerage'])?$MarketWiseData['lot_wise_brokerage']['market_field_amount']:'');                         

                        echo '<div class="collapse '.($isChecked?'show':'').'" id="collapse_'.$key.'">
                            <div class="d-flex p-1 border row">';
                              $isDisabled = $isChecked?'':'disabled';
                              if(!empty($value['form_field']) && in_array('lot_wise_brokerage',$value['form_field']))
                              {
                                $amount = isset($MarketWiseData['lot_wise_brokerage'])?$MarketWiseData['lot_wise_brokerage']['market_field_amount']:'';
                                $is_percentage = isset($MarketWiseData['lot_wise_brokerage'])?$MarketWiseData['lot_wise_brokerage']['amount_is_percentage']:1;

                                echo '<div class="col-md-3 col mb-1">
                                        <label class="form-label">Min Lot Wise Brokerage</label>     
                                            <div class="input-group input-width-drop-down">
                                                <input type="number" class="form-control" name="market_type['.$value['id'].'][lot_wise_brokerage]" placeholder="Min Lot Wise Brokerage" '.$isDisabled.' value="'.$amount.'">
                                                <select '.$isDisabled.' name="market_type['.$value['id'].'][lot_wise_brokerage_is_percentage]" class="form-select waves-effect" >
                                                  <option value=\'1\'>%</option>
                                                  <option value=\'0\' '.($is_percentage?:'selected').'>₹</option>
                                                </select>
                                            </div>
                                            <small class="error market_type'.$value['id'].'lot_wise_brokerage-error"></small>
                                      </div>';
                              }
                              if(!empty($value['form_field']) && in_array('amount_wise_brokerage',$value['form_field']))
                              {
                                $amount = isset($MarketWiseData['amount_wise_brokerage'])?$MarketWiseData['amount_wise_brokerage']['market_field_amount']:'';
                                $is_percentage = isset($MarketWiseData['amount_wise_brokerage'])?$MarketWiseData['amount_wise_brokerage']['amount_is_percentage']:1;

                              echo '<div class="col-md-3 col mb-1">
                                      <label class="form-label">Min Amount Wise Brokerage(100000)</label>     
                                          <div class="input-group input-width-drop-down">
                                              <input type="number" class="form-control" name="market_type['.$value['id'].'][amount_wise_brokerage]" placeholder="Min Amount Wise Brokerage(100000)" '.$isDisabled.' value="'.$amount.'">
                                              <select '.$isDisabled.' name="market_type['.$value['id'].'][amount_wise_brokerage_is_percentage]" class="form-select waves-effect"  value="'.(isset($UserMarketData[$value['id']]['amount_wise_brokerage'])?$UserMarketData[$value['id']]['amount_wise_brokerage']['market_field_amount']:'').'">
                                                <option value=\'1\'>%</option>
                                                <option value=\'0\' '.($is_percentage?:'selected').'>₹</option>
                                              </select>
                                          </div>
                                          <small class="error market_type'.$value['id'].'amount_wise_brokerage-error"></small>
                                    </div>';
                              }  
                              if(!empty($value['form_field']) && in_array('intraday_multiplication',$value['form_field']))
                              {
                                $amount = isset($MarketWiseData['intraday_multiplication'])?$MarketWiseData['intraday_multiplication']['market_field_amount']:'';

                                echo ' <div class="mb-1 col col-md-3">
                                      <label class="form-label">MAX Intraday Multiplication</label>                  
                                      <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="market_type['.$value['id'].'][intraday_multiplication]" '.$isDisabled.' value="'.$amount.'">
                                      <small class="error market_type'.$value['id'].'intraday_multiplication-error"></small>
                                  </div>';
                              }
                              if(!empty($value['form_field']) && in_array('delivery_multiplication',$value['form_field']))
                              {
                                $amount = isset($MarketWiseData['delivery_multiplication'])?$MarketWiseData['delivery_multiplication']['market_field_amount']:'';

                                echo '<div class="mb-1 col col-md-3">
                                    <label class="form-label">MAX Delivery Multiplication</label>                  
                                    <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="market_type['.$value['id'].'][delivery_multiplication]" '.$isDisabled.' value="'.$amount.'">
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

  $('[name=market_type_value]').on('change', function(event){ 
    $('#'+$(this).attr('aria-controls')).collapse(($(this).is(':checked')? 'show' : 'hide'));
    if($(this).is(':checked'))
    {
      $('#'+$(this).attr('aria-controls')+' :input').prop("disabled", false);
      $('#'+$(this).attr('aria-controls')+' :select').prop("disabled", false);
      
    }
    else
    {
      $('#'+$(this).attr('aria-controls')+' :input').prop("disabled", true);
      $('#'+$(this).attr('aria-controls')+' :select').prop("disabled", true);
      var selector = '#'+$(this).attr('aria-controls')+' :input';
      $(selector).each(function() {
        var inputValue = $(this).attr('name');
        // Remove the 'error' class from elements with the specified name
        $('[name="'+inputValue+'"]').removeClass('error');
        // Reset the content of elements with the specified class
        var inputValue = inputValue.replace(/\[|\]/g, '');
        $('.' + inputValue + '-error').html('');       
      });
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
  $(".select2-ajax-user_broker").select2({
  ajax: {
    url: "{{route('getbrokerlist')}}",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
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
  placeholder: 'Search for a Broker',
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
</script>
@endsection