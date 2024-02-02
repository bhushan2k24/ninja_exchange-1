<?php $ajaxformsubmit = true ?>
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
<section id="basic-vertical-layouts">
  <div class="row">
    <div class="col-12">
      <form method="post" action="{{route('userstore')}}">

        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Basic Detail</h4>
          </div>
          <div class="card-body">
            <div class="row">

              <div class="mb-1 col col-md-3">
                  <label class="form-label" for="user_type">User Type</label>
                  <select name="user_type" 
                  class="form-control form-select ">
                    <option value="">Select Type</option>
                    <option value="master">Master</option>
                    @role('master')
                    <option value="broker">Broker</option>
                    <option value="user">User</option>
                    @endrole
                  </select>
                  <small class="error user_type-error"></small>
              </div>

              <div class="mb-1 col col-md-3">
                  <label class="form-label">Email</label>                  
                  <input type="email" id="email" placeholder="Email" class="form-control" name="email">
              </div>
              <div class="mb-1 col col-md-3">
                    <label class="form-label">Password</label>                  
                    <input type="password" id="password" placeholder="Password" class="form-control" name="password">
              </div>
            </div>
          </div>
        </div>

        <div class="card add-section hidden master_section">
          <div class="card-header">
            <h4 class="card-title">ACCOUNT Detail</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="mb-1 col-12 col-md-12">
                  <label class="form-label">Partnership(%)</label>                  
                  <input type="number" placeholder="Partnership(%)" class="form-control" name="partnership" id="partnership">
              </div>

              <div class="mb-1 col col-md-3">
                  <label class="form-label">MAX Intraday Multiplication</label>                  
                  <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="max_intraday_multiplication" id="max_intraday_multiplication" >
              </div>

              <div class="mb-1 col col-md-3">
                <label class="form-label">MAX Delivery Multiplication</label>                  
                <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="max_delivery_multiplication" id="max_delivery_multiplication" >
              </div>

              <div class="col-md-3 col mb-1">
                <label class="form-label">MIN Lot Wise Brokerage</label>     
                    <div class="input-group input-width-drop-down">
                        <input type="number" class="form-control" name="min_lot_wise_brokerage" id="min_lot_wise_brokerage"   placeholder="Min Lot Wise Brokerage">
                        <select name="min_lot_wise_brokerage_is_percentage" id="min_lot_wise_brokerage_is_percentage" class="form-select waves-effect ">
                          <option value='1'>%</option>
                          <option value='0'>₹</option>
                        </select>
                    </div>
              </div>

              <div class="col-md-3 col mb-1">
                <label class="form-label">MIN Amount Wise Brokerage<sup>(100000)</sup></label>     
                    <div class="input-group input-width-drop-down">
                        <input type="number" class="form-control" name="min_amount_wise_brokerage" id="min_amount_wise_brokerage" placeholder="Min Amount Wise Brokerage(100000)">
                        <select name="min_amount_wise_brokerage_is_percentage" id="min_amount_wise_brokerage_is_percentage" class="form-select waves-effect ">
                          <option value='1'>%</option>
                          <option value='0'>₹</option>
                        </select>
                    </div>
              </div>

              {{-- <div class="mb-1 col col-md-3">
                <label class="form-label">MIN Lot Wise Brokerage</label>                  
                <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="max_delivery_multiplication" id="max_delivery_multiplication" >
              </div> --}}

              {{-- <div class="mb-1 col col-md-3">
                <label class="form-label">MIN Amount Wise Brokerage<sup>(100000)</sup></label>                  
                <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="max_delivery_multiplication" id="max_delivery_multiplication" >
              </div> --}}

            </div>
          </div>
        </div>

        <div class="card master_section hidden">
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
                    $accountTypeData = ['extra-small', 'small', 'medium', 'large', 'extra-large', 'super-5000', 'demo'];
                    $accountTypeData = get_levels();
                    
                    foreach($accountTypeData as $key => $val)
                    {
                      echo
                      '<div class="text-capitalize form-check form-check-primary  form-check-inline mt-1">
                        <input type="checkbox" name="user_account_type" '.($key<=5 ? 'checked' : '').' value="'.$val->id.'" class="form-check-input" id="user_account_type'.$key.'">
                        <label class="form-check-label" for="user_account_type'.$key.'">'.strtoupper(str_replace('-', ' ', $val->level_name)).'</label>
                      </div>';
                    }?>
                    </div>
                    <small class="error user_account_type-error"></small>
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

                    foreach($marketData as $key => $value)
                    {
                      // lot_wise_brokerage','amount_wise_brokerage','intraday_multiplication','delivery_multiplication
                      echo
                      '<div class="d-flex flex-column mt-2">
                            <div class="form-check form-switch form-check-primary">
                            <label class="form-check-label mb-50" for="market_type_'.$value['id'].'">'.$value['market_name'].'</label>
                            
                            <input type="checkbox" id="market_type_'.$value['id'].'" name="market_type_value" value="'.$value['id'].'" class="form-check-input" 
                              
                              role="button"
                              aria-controls="collapse_'.$key.'"
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
                        echo '<div class="collapse " id="collapse_'.$key.'">
                            <div class="d-flex p-1 border row">';
                              
                              if(!empty($value['form_field']) && in_array('lot_wise_brokerage',$value['form_field']))
                                echo '<div class="mb-1 col col-md-3">
                                    <label class="form-label">Min Lot Wise Brokerage</label>                  
                                    <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="market_type['.$value['id'].'][lot_wise_brokerage]" disabled>
                                </div>';
                                
              <div class="col-md-3 col mb-1">
                <label class="form-label">MIN Lot Wise Brokerage</label>     
                    <div class="input-group input-width-drop-down">
                        <input type="number" class="form-control" name="min_lot_wise_brokerage" id="min_lot_wise_brokerage"   placeholder="Min Lot Wise Brokerage">
                        <select name="min_lot_wise_brokerage_is_percentage" id="min_lot_wise_brokerage_is_percentage" class="form-select waves-effect ">
                          <option value='1'>%</option>
                          <option value='0'>₹</option>
                        </select>
                    </div>
              </div>

                              
                              if(!empty($value['form_field']) && in_array('amount_wise_brokerage',$value['form_field']))
                                echo '<div class="mb-1 col col-md-3">
                                    <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                    <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="market_type['.$value['id'].'][amount_wise_brokerage]" disabled>
                                </div>';


              <div class="col-md-3 col mb-1">
                <label class="form-label">MIN Amount Wise Brokerage<sup>(100000)</sup></label>     
                    <div class="input-group input-width-drop-down">
                        <input type="number" class="form-control" name="min_amount_wise_brokerage" id="min_amount_wise_brokerage" placeholder="Min Amount Wise Brokerage(100000)">
                        <select name="min_amount_wise_brokerage_is_percentage" id="min_amount_wise_brokerage_is_percentage" class="form-select waves-effect ">
                          <option value='1'>%</option>
                          <option value='0'>₹</option>
                        </select>
                    </div>
              </div>



                              if(!empty($value['form_field']) && in_array('intraday_multiplication',$value['form_field']))
                              echo ' <div class="mb-1 col col-md-3">
                                    <label class="form-label">MAX Intraday Multiplication</label>                  
                                    <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="market_type['.$value['id'].'][intraday_multiplication]" disabled>
                                </div>';
                              
                              if(!empty($value['form_field']) && in_array('delivery_multiplication',$value['form_field']))
                              echo '<div class="mb-1 col col-md-3">
                                  <label class="form-label">MAX Delivery Multiplication</label>                  
                                  <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="market_type['.$value['id'].'][delivery_multiplication]" disabled>
                                </div>';
                              
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
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Remark</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <textarea class="form-control" rows="3" name="user_remark"></textarea> 
              </div>
              <div class="col-12 pt-2 ">
                <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Submit</button>
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
@endsection

@section('page-script')
<script>
  $(document).ready(function(){
    $(document).on('change', '[name=user_type]', function(event) { 
      if($(this).val() == 'master')
      {
        $('.master_section').removeClass('hidden');
        DisableEnableFields($('.master_section'),false);
      } 
      else
      {
        $('.master_section').addClass('hidden');
        DisableEnableFields($('.master_section'));
      }
    });
  });

  function  DisableEnableFields(container,trueFalse = true) 
  {
    var formContainer = $(container);

    console.log(formContainer);
    // Disable text inputs
    formContainer.find("input[type='text']").prop("disabled", trueFalse);

    // Disable radio buttons
    formContainer.find("input[type='radio']").prop("disabled", trueFalse);

    // Disable checkboxes
    formContainer.find("input[type='checkbox']").prop("disabled", trueFalse);

    // Disable select dropdown
    formContainer.find("select").prop("disabled", trueFalse);
  }

  $('[name=market_type_value]').on('change', function(event){ 
    $('#'+$(this).attr('aria-controls')).collapse(($(this).is(':checked')? 'show' : 'hide'));
    if($(this).is(':checked'))
      $('#'+$(this).attr('aria-controls')+' :input').prop("disabled", false);
    else
    {
      $('#'+$(this).attr('aria-controls')+' :input').prop("disabled", true);
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



</script>
  {{-- Page js files --}}
  <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/forms/form-wizard.js')) }}"></script>

  <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

  <script src="{{ asset(mix('js/scripts/tables/table-datatables-advanced.js')) }}"></script>

  <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>

@endsection