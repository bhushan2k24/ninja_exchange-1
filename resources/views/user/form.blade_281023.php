@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
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
      <form method="post">

        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Basic Detail</h4>
          </div>
          <div class="card-body">
            <div class="row">

              <div class="mb-1 col col-md-3">
                  <label class="form-label" for="user_type">User Type</label>
                  <select name="user_type" onchange="this.value.length > 0 ? $('.add-section').removeClass('hidden') : $('.add-section').addClass('hidden');" class="form-control form-select ">
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
                    <label class="form-label">Username</label>
                    <input type="text" placeholder="Username" class="form-control" name="username">
              </div>
              <div class="mb-1 col col-md-3">
                  <label class="form-label">Email</label>                  
                  <input type="email" placeholder="Email" class="form-control" name="email">
            </div>
              <div class="mb-1 col col-md-3">
                    <label class="form-label">Password</label>                  
                    <input type="password" placeholder="Password" class="form-control" name="password">
              </div>
            </div>
          </div>
        </div>

        <div class="card add-section hidden">
          <div class="card-header">
            <h4 class="card-title">ACCOUNT Detail</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="mb-1 col col-md-3">
                  <label class="form-label">Partnership(%)</label>                  
                  <input type="number" placeholder="Partnership(%)" class="form-control" name="partnership">
              </div>

              <div class="mb-1 col col-md-3">
                  <label class="form-label">MAX Intraday Multiplication</label>                  
                  <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="max_intraday_multiplication">
              </div>

              <div class="mb-1 col col-md-3">
                <label class="form-label">MAX Delivery Multiplication</label>                  
                <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="max_delivery_multiplication">
              </div>

            </div>
          </div>
        </div>

        <div class="card add-section hidden">
          <div class="card-header">
            <h4 class="card-title">Additional Details</h4>
          </div>
          <div class="card-body">
            <div class="row">


              <div class="col-12 mb-2 border rounded pb-1">
                <h5 class="fw-bolder border-bottom pb-50 m-1">Account Type</h5>
                <div class="row from-group">
                 

                    <div class="demo-inline-spacing-s">
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
                </div>
              </div>
              
              <div class="col-12 mb-2 border rounded pb-1">
                <h5 class="fw-bolder border-bottom pb-50 m-1">Market Type</h5>
                
                  <div class="col-12 from-group">
                    <div class="demo-inline-spacing-s">
                    <?php
                    $marketData = marketData(0,1);

                    foreach($marketData as $key => $value)
                    {
                      // lot_wise_brokerage','amount_wise_brokerage','intraday_multiplication','delivery_multiplication
                      echo
                      '<div class="d-flex flex-column mt-2">
                            <div class="form-check form-switch form-check-primary">
                            <label class="form-check-label mb-50" for="market_type_'.$value['id'].'">'.$value['market_name'].'</label>
                            
                            <input type="checkbox" id="market_type_'.$value['id'].'" name="market_type" value="'.$value['id'].'" class="form-check-input" 
                              data-bs-toggle="collapse" href="#collapse_'.$key.'"
                              role="button"
                              aria-expanded="false"
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
                                    <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="partnership">
                                </div>';
                              
                              if(!empty($value['form_field']) && in_array('amount_wise_brokerage',$value['form_field']))
                                echo '<div class="mb-1 col col-md-3">
                                    <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                    <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="partnership">
                                </div>';

                              if(!empty($value['form_field']) && in_array('intraday_multiplication',$value['form_field']))
                              echo ' <div class="mb-1 col col-md-3">
                                    <label class="form-label">MAX Intraday Multiplication</label>                  
                                    <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="max_intraday_multiplication">
                                </div>';
                              
                              if(!empty($value['form_field']) && in_array('delivery_multiplication',$value['form_field']))
                              echo '<div class="mb-1 col col-md-3">
                                  <label class="form-label">MAX Delivery Multiplication</label>                  
                                  <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="max_delivery_multiplication">
                                </div>';
                              
                      echo ' </div>
                          </div>';
                        }
            
            // '
            //           <div class="text-capitalize form-check  form-check-primary mt-1">
            //             <input type="checkbox" name="master_id" value="'.$value['value'].'" class="form-check-input" id="master_id'.$key.'">
            //             <label class="form-check-label" for="master_id'.$key.'">'.$value['label'].'</label>
            //           </div>';
                    }
            ?>
<hr>

                      <!-- NSEOPT -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_NSEOPT">NSEOPT</label>
                        <input type="checkbox" id="market_type_NSEOPT" name="market_type" value="NSEOPT " class="form-check-input" 
                          data-bs-toggle="collapse" href="#collapse_NSEOPT"
                          role="button"
                          aria-expanded="false"
                          aria-controls="collapse_NSEOPT"
                          />
                          <label class="form-check-label" for="market_type_NSEOPT">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  
                      <div class="collapse " id="collapse_NSEOPT">
                        <div class="d-flex p-1 border row">
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Lot Wise Brokerage</label>                  
                                <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="NSEOPT_lot_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="NSEOPT_amount_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">MAX Intraday Multiplication</label>                  
                                <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="NSEOPT_max_intraday_multiplication">
                            </div>
                            <div class="mb-1 col col-md-3">
                              <label class="form-label">MAX Delivery Multiplication</label>                  
                              <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="NSEOPT_max_delivery_multiplication">
                            </div>
                        </div>
                      </div>

                      <!-- NSEFUT -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_NSEFUT">NSEFUT</label>
                        <input type="checkbox" id="market_type_NSEFUT" name="market_type" value="NSEFUT " class="form-check-input" 
                          data-bs-toggle="collapse" href="#collapse_NSEFUT"
                          role="button"
                          aria-expanded="false"
                          aria-controls="collapse_NSEFUT"
                          />
                          <label class="form-check-label" for="market_type_NSEFUT">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  
                      <div class="collapse " id="collapse_NSEFUT">
                        <div class="d-flex p-1 border row">
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Lot Wise Brokerage</label>                  
                                <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="NSEFUT_lot_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="NSEFUT_amount_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">MAX Intraday Multiplication</label>                  
                                <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="NSEFUT_max_intraday_multiplication">
                            </div>
                            <div class="mb-1 col col-md-3">
                              <label class="form-label">MAX Delivery Multiplication</label>                  
                              <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="NSEFUT_max_delivery_multiplication">
                            </div>
                        </div>
                      </div>

                      <!-- NSEEQT -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_NSEEQT">NSEEQT</label>
                        <input type="checkbox" id="market_type_NSEEQT" name="market_type" value="NSEEQT " class="form-check-input" 
                          data-bs-toggle="collapse" href="#collapse_NSEEQT"
                          role="button"
                          aria-expanded="false"
                          aria-controls="collapse_NSEEQT"
                          />
                          <label class="form-check-label" for="market_type_NSEEQT">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  
                      <div class="collapse " id="collapse_NSEEQT">
                        <div class="d-flex p-1 border row">
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Lot Wise Brokerage</label>                  
                                <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="NSEEQT_lot_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="NSEEQT_amount_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">MAX Intraday Multiplication</label>                  
                                <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="NSEEQT_max_intraday_multiplication">
                            </div>
                            <div class="mb-1 col col-md-3">
                              <label class="form-label">MAX Delivery Multiplication</label>                  
                              <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="NSEEQT_max_delivery_multiplication">
                            </div>
                        </div>
                      </div>

                      <!-- NSECDS -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_NSECDS">NSECDS</label>
                        <input type="checkbox" id="market_type_NSECDS" name="market_type" value="NSECDS " class="form-check-input" 
                          data-bs-toggle="collapse" href="#collapse_NSECDS"
                          role="button"
                          aria-expanded="false"
                          aria-controls="collapse_NSECDS"
                          />
                          <label class="form-check-label" for="market_type_NSECDS">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  
                      <div class="collapse " id="collapse_NSECDS">
                        <div class="d-flex p-1 border row">
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Lot Wise Brokerage</label>                  
                                <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="NSECDS_lot_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="NSECDS_amount_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">MAX Intraday Multiplication</label>                  
                                <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="NSECDS_max_intraday_multiplication">
                            </div>
                            <div class="mb-1 col col-md-3">
                              <label class="form-label">MAX Delivery Multiplication</label>                  
                              <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="NSECDS_max_delivery_multiplication">
                            </div>
                        </div>
                      </div>

                      <!-- MCXFUT -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_MCXFUT">MCXFUT</label>
                        <input type="checkbox" id="market_type_MCXFUT" name="market_type" value="MCXFUT " class="form-check-input" 
                          data-bs-toggle="collapse" href="#collapse_MCXFUT"
                          role="button"
                          aria-expanded="false"
                          aria-controls="collapse_MCXFUT"
                          />
                          <label class="form-check-label" for="market_type_MCXFUT">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  
                      <div class="collapse " id="collapse_MCXFUT">
                        <div class="d-flex p-1 border row">
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Lot Wise Brokerage</label>                  
                                <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="MCXFUT_lot_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="MCXFUT_amount_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">MAX Intraday Multiplication</label>                  
                                <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="MCXFUT_max_intraday_multiplication">
                            </div>
                            <div class="mb-1 col col-md-3">
                              <label class="form-label">MAX Delivery Multiplication</label>                  
                              <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="MCXFUT_max_delivery_multiplication">
                            </div>
                        </div>
                      </div>
                      

                      <!-- GLOBAL_STOCKS -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_GLOBAL_STOCKS">GLOBAL STOCKS</label>
                        <input type="checkbox" id="market_type_GLOBAL_STOCKS" name="market_type" value="GLOBAL_STOCKS " class="form-check-input" 
                          data-bs-toggle="collapse" href="#collapse_GLOBAL_STOCKS"
                          role="button"
                          aria-expanded="false"
                          aria-controls="collapse_GLOBAL_STOCKS"
                          />
                          <label class="form-check-label" for="market_type_GLOBAL_STOCKS">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  
                      <div class="collapse " id="collapse_GLOBAL_STOCKS">
                        <div class="d-flex p-1 border row">
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Lot Wise Brokerage</label>                  
                                <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="GLOBAL_STOCKS_lot_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="GLOBAL_STOCKS_amount_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">MAX Intraday Multiplication</label>                  
                                <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="GLOBAL_STOCKS_max_intraday_multiplication">
                            </div>
                            <div class="mb-1 col col-md-3">
                              <label class="form-label">MAX Delivery Multiplication</label>                  
                              <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="GLOBAL_STOCKS_max_delivery_multiplication">
                            </div>
                        </div>
                      </div>


                      <!-- GLOBAL_FUTURES -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_GLOBAL_FUTURES">GLOBAL FUTURES</label>
                        <input type="checkbox" id="market_type_GLOBAL_FUTURES" name="market_type" value="GLOBAL_FUTURES " class="form-check-input" 
                          data-bs-toggle="collapse" href="#collapse_GLOBAL_FUTURES"
                          role="button"
                          aria-expanded="false"
                          aria-controls="collapse_GLOBAL_FUTURES"
                          />
                          <label class="form-check-label" for="market_type_GLOBAL_FUTURES">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  
                      <div class="collapse " id="collapse_GLOBAL_FUTURES">
                        <div class="d-flex p-1 border row">
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Lot Wise Brokerage</label>                  
                                <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="GLOBAL_FUTURES_lot_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="GLOBAL_FUTURES_amount_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">MAX Intraday Multiplication</label>                  
                                <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="GLOBAL_FUTURES_max_intraday_multiplication">
                            </div>
                            <div class="mb-1 col col-md-3">
                              <label class="form-label">MAX Delivery Multiplication</label>                  
                              <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="GLOBAL_FUTURES_max_delivery_multiplication">
                            </div>
                        </div>
                      </div>


                      <!-- FOREX -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_FOREX">FOREX</label>
                        <input type="checkbox" id="market_type_FOREX" name="market_type" value="FOREX " class="form-check-input" 
                          data-bs-toggle="collapse" href="#collapse_FOREX"
                          role="button"
                          aria-expanded="false"
                          aria-controls="collapse_FOREX"
                          />
                          <label class="form-check-label" for="market_type_FOREX">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  
                      <div class="collapse " id="collapse_FOREX">
                        <div class="d-flex p-1 border row">
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Lot Wise Brokerage</label>                  
                                <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="FOREX_lot_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="FOREX_amount_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">MAX Intraday Multiplication</label>                  
                                <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="FOREX_max_intraday_multiplication">
                            </div>
                            <div class="mb-1 col col-md-3">
                              <label class="form-label">MAX Delivery Multiplication</label>                  
                              <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="FOREX_max_delivery_multiplication">
                            </div>
                        </div>
                      </div>


                      <!-- COMEX -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_COMEX">COMEX</label>
                        <input type="checkbox" id="market_type_COMEX" name="market_type" value="COMEX " class="form-check-input" 
                          data-bs-toggle="collapse" href="#collapse_COMEX"
                          role="button"
                          aria-expanded="false"
                          aria-controls="collapse_COMEX"
                          />
                          <label class="form-check-label" for="market_type_COMEX">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  
                      <div class="collapse " id="collapse_COMEX">
                        <div class="d-flex p-1 border row">
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Lot Wise Brokerage</label>                  
                                <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="COMEX_lot_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="COMEX_amount_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">MAX Intraday Multiplication</label>                  
                                <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="COMEX_max_intraday_multiplication">
                            </div>
                            <div class="mb-1 col col-md-3">
                              <label class="form-label">MAX Delivery Multiplication</label>                  
                              <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="COMEX_max_delivery_multiplication">
                            </div>
                        </div>
                      </div>


                      <!-- CRYPTO -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_CRYPTO">CRYPTO</label>
                        <input type="checkbox" id="market_type_CRYPTO" name="market_type" value="CRYPTO " class="form-check-input" 
                          data-bs-toggle="collapse" href="#collapse_CRYPTO"
                          role="button"
                          aria-expanded="false"
                          aria-controls="collapse_CRYPTO"
                          />
                          <label class="form-check-label" for="market_type_CRYPTO">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  
                      <div class="collapse " id="collapse_CRYPTO">
                        <div class="d-flex p-1 border row">
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Lot Wise Brokerage</label>                  
                                <input type="number" placeholder="Min Lot Wise Brokerage" class="form-control" name="CRYPTO_lot_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">Min Amount Wise Brokerage<sup>(100000)</sup></label>                  
                                <input type="number" placeholder="Min Amount Wise Brokerage(100000)" class="form-control" name="CRYPTO_amount_wise_brokerage">
                            </div>
                            <div class="mb-1 col col-md-3">
                                <label class="form-label">MAX Intraday Multiplication</label>                  
                                <input type="number" placeholder="MAX Intraday Multiplication" class="form-control" name="CRYPTO_max_intraday_multiplication">
                            </div>
                            <div class="mb-1 col col-md-3">
                              <label class="form-label">MAX Delivery Multiplication</label>                  
                              <input type="number" placeholder="MAX Delivery Multiplication" class="form-control" name="CRYPTO_max_delivery_multiplication">
                            </div>
                        </div>
                      </div>

                      <!-- SPORTS -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_SPORTS">SPORTS</label>
                        <input type="checkbox" id="market_type_SPORTS" name="market_type" value="SPORTS " class="form-check-input"/>
                          <label class="form-check-label" for="market_type_SPORTS">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  

                      <!-- CASINO -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_CASINO">CASINO</label>
                        <input type="checkbox" id="market_type_CASINO" name="market_type" value="CASINO " class="form-check-input"/>
                          <label class="form-check-label" for="market_type_CASINO">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  

                      <!-- BINARY -->                    
                      <div class="d-flex flex-column mt-1">
                        <div class="form-check form-switch form-check-primary">
                        <label class="form-check-label mb-50" for="market_type_BINARY">BINARY</label>
                        <input type="checkbox" id="market_type_BINARY" name="market_type" value="BINARY " class="form-check-input"/>
                          <label class="form-check-label" for="market_type_BINARY">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                          </label>
                        </div>
                      </div>  


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
                <div class="row from-group">
                  <textarea class="form-control" rows="3" name="user_remark"></textarea>
                </div>  
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
  {{-- Page js files --}}
  <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/forms/form-wizard.js')) }}"></script>

  <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

  <script src="{{ asset(mix('js/scripts/tables/table-datatables-advanced.js')) }}"></script>
@endsection