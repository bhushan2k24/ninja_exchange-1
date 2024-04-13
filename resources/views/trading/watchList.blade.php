@php($no_breadcrumbs = 1)
@php($ajaxformsubmit = true)
@extends('layouts/contentLayoutMaster')
@section('title', $title)
@section('content')
    <section id="basic-vertical-layouts">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row needs-validation" id="watchlist-form" action="{{ route('save.watchlist') }}"
                            method="post">
                            @csrf
                            <div class="col col-md-2 " style="margin-bottom: 1rem">
                                <label class="form-label" for="watchlist_filter_market">SEGMENT</label><select
                                    class="select2 form-select marketToScripts" name="watchlist_filter_market">
                                    <option value="">select</option>
                                    @foreach ($marketdata as $market)
                                        <option value="{{ $market['id'] }}">{{ $market['market_name'] }}</option>
                                    @endforeach
                                </select><small class="error watchlist_filter_market-error"></small>
                            </div>
                            <div class="col col-md-2 " style="margin-bottom: 1rem">
                                <label class="form-label" for="watchlist_filter_script">SCRIPT</label><select
                                    class="select2 form-select markerScripts" name="watchlist_filter_script">
                                    <option value="">select</option>
                                </select><small class="error watchlist_filter_script-error"></small>
                            </div>
                            <div class="col col-md-2 " style="margin-bottom: 1rem">
                                <label class="form-label" for="watchlist_filter_expiry">EXPIRY DATE</label><select
                                    class="select2 form-select" name="watchlist_filter_expiry">
                                    <option value="">Select</option>
                                </select><small class="error watchlist_filter_expiry-error"></small>
                            </div>
                            <div class="col col-md-2 " style="margin-bottom: 1rem">
                                <label class="form-label" for="watchlist_filter_ce_pe">CE/PE</label>
                                <select class="select2 form-select" name="watchlist_filter_ce_pe" disabled>
                                    <option value="">select</option>
                                </select><small class="error watchlist_filter_ce_pe-error"></small>
                            </div>
                            <div class="col col-md-2 " style="margin-bottom: 1rem">
                                <label class="form-label" for="watchlist_filter_strick">STRICK</label>
                                <select class="select2 form-select" name="watchlist_filter_strick" disabled>
                                    <option value="">select</option>
                                </select><small class="error watchlist_filter_strick-error"></small>
                            </div>
                            <div class="col col-md-2 mt-2">
                                <button type="submit"
                                    class="btn btn-primary w-100 text-capitalize waves-effect waves-float waves-light"
                                    tabindex="4">Add</button>
                            </div>
                        </form>
                        <div class="row mb-2 mt-2">
                            <div class="col-md-12">
                                <div class="card-datatable table-responsive">
                                    <table class="dt-responsive table table-sm">
                                        <tbody> 
                                            @if($watchlist_data->isEmpty())
                                                <thead>
                                                    <tr>
                                                        <th>SEGMENT</th>
                                                        <th class="text-nowrap">BID RATE</th>
                                                        <th class="text-nowrap">ASK RATE</th>
                                                        <th>LTP</th>
                                                        <th class="text-nowrap">CHANGE %</th>
                                                        <th class="text-nowrap">NET CHANGE</th>
                                                        <th>HIGH</th>
                                                        <th>LOW</th>
                                                        <th>OPEN</th>
                                                        <th>CLOSE</th>
                                                        <th>REMOVE</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <th class="text-center" colspan="11">
                                                        <i data-feather='check-square' class="text-danger me-50"></i>Your watchlist is empty...</th>
                                                </tbody>
                                            @endif                                            
                                            @foreach ($watchlist_data as $w_key => $watch_scr)
                                                @php($market = '')
                                                @php($symbol = $watch_scr->watchlist_script_extension)
                                                @php($symbol_name = $watch_scr->watchlist_trading_symbol)

                                                @if ($w_key == 0 || ($w_key > 0 && $watchlist_data[$w_key - 1]->market_id != $watch_scr->market_id))
                                                    <thead>
                                                        <tr>
                                                            <th >{{ $watch_scr->watchlist_market_name }}</th>
                                                            <th class="text-nowrap">BID RATE</th>
                                                            <th class="text-nowrap">ASK RATE</th>
                                                            <th>LTP</th>
                                                            <th class="text-nowrap">CHANGE %</th>
                                                            <th class="text-nowrap">NET CHANGE</th>
                                                            <th>HIGH</th>
                                                            <th>LOW</th>
                                                            <th>OPEN</th>
                                                            <th>CLOSE</th>
                                                            <th>REMOVE</th>
                                                        </tr>
                                                    </thead>
                                                @endif
                                                    
                                                <tr>
                                                    <th>{{ $symbol_name }}</th>
                                                    <td pl-marketname="{{ $watch_scr->watchlist_market_name }}" pl-market="{{ $symbol }}"
                                                        class="{{ $market . $symbol . 'BuyPrice' }} cursor-pointer open-watchlistoffcanvas bidclick">
                                                        00.00</td>
                                                    <td pl-marketname="{{ $watch_scr->watchlist_market_name }}" pl-market="{{ $symbol }}"
                                                        class="{{ $market . $symbol . 'SellPrice' }} cursor-pointer open-watchlistoffcanvas askclick">
                                                        00.00</td>
                                                    <td class="{{ $market . $symbol . 'LastTradePrice' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'PriceChangePercentage' }}">00.00
                                                    </td>
                                                    <td><i class="text-success fw-bolder {{ $market . $symbol . 'PriceChangeicon' }}"
                                                            data-feather="trending-up"></i> <span
                                                            class="{{ $market . $symbol . 'PriceChange' }}">00.00</span>
                                                    </td>
                                                    <td class="{{ $market . $symbol . 'High' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Low' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Open' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Close' }}">00.00</td>
                                                    <td>
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-danger btn-sm delete_record" reload-page='1'
                                                            deleteto="{{ encrypt_to('nex_watchlists') . '/' . encrypt_to($watch_scr->id) }}">
                                                            <i data-feather='delete'></i>
                                                        </a>


                                                    </td>
                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="offcanvas offcanvas-bottom rounded-top offcanvas-primary overflow-auto" tabindex="-1" id="offcanvasBottom"
        aria-labelledby="offcanvasBottomLabel">

        <form action="{{ route('save.trade') }}" method="POST">
            
            <input type="hidden" name="script_expires_id" id="script_expires_id">

            <div class="offcanvas-body">
                <button type="button" class="btn-close text-reset"
                            data-bs-dismiss="offcanvas" aria-label="Close"></button>
                <div class="row">
                    <div class="col-md-2 m-auto  text-center p-75">                       
                        <h5 class="offcanvas-title text-primary scriptSymbol" ></h5>
                    </div>
                    <div class="col-md-10">
                        <div class="card-body statistics-body py-0 pe-1">
                            <div class="row justify-content-md-end justify-content-around  gap-25">

                                <div class="col-xl-auto col-sm-6 col-5  mb-xl-0 bg-light-success border-success p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bo  lder mb-0 " pl-BuyPrice="NIFTY-I">0.00</h4>
                                            <b class="card-text font-small-3 mb-0">BID RATE</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-auto col-sm-6 col-5  mb-xl-0 bg-light-success border-success p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0 " pl-SellPrice="NIFTY-I">0.00</h4>
                                            <b class="card-text font-small-3 mb-0">ASK RATE</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-auto col-sm-6 col-5  mb-xl-0 bg-light-success border-success p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-LastTradePrice>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">LTP</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-auto col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-PriceChangePercentage>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">CHANGE(%)</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-auto col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-PriceChange>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">NET CHG</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-auto col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-High>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">HIGH</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-auto col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-Low>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">LOW</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-auto col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-Open>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">OPEN</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-auto col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-Close>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">CLOSE</b>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-2 row custom-options-checkable m-auto mt-1">
                        <div class="col-6 col-md-6 p-0 pe-25">
                            <input class="custom-option-item-check" type="radio" name="tradeBuySell" id="tradeBuy"
                                value="buy" checked />
                            <label class="custom-option-item p-50 text-center" for="tradeBuy">
                                <span class="">
                                    <span class="fw-bolder">Buy</span>
                                </span>
                            </label>
                        </div>

                        <div class="col-6 col-md-6 p-0 ps-25">
                            <input class="custom-option-item-check custom-option-danger" type="radio"
                                name="tradeBuySell" id="tradeSell" value="sell" />
                            <label class="custom-option-item p-50 text-center" for="tradeSell">
                                <span class="">
                                    <span class="fw-bolder">Sell</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- <div class="col-6 col-md-2 mt-1" name="order_type" id="order_type">
                    <select class="select2 form-select">
                        <option value="market">Market</option>
                        <option value="limit">Limit</option>
                        <option value="stop_loss">Stop Loss</option>
                    </select>
                    <small class="error order_type-error"></small>
                </div> --}}

                    <div class="col-6 col-md-2  mt-1">
                        {{-- <label class="form-label" for="login-email">QTY</label> --}}
                        <input class="form-control scriptLot" placeholder="Lot" value="" id="lot"
                            type="number" name="lot" aria-describedby="lot" tabindex="1" step="any">

                            <small class="error lot-error d-block"></small>
                            <label class="form-label text-center ms-5" for="lot">Lot</label>
                        
                    </div>

                    <div class="col-6 col-md-2  mt-1">
                        {{-- <label class="form-label" for="login-email">QTY</label> --}}
                        <input class="form-control scriptQuantityval" placeholder="Quantity" value=""
                            id="quantity" type="number" name="quantity" aria-describedby="quantity" tabindex="1">
                            <small class="error quantity-error d-block"></small>
                            <label class="form-label text-center ms-5" for="quantity">Quantity</label>
                       
                    </div>

                    <div class="col-6 col-md-2  mt-1">
                        {{-- <label class="form-label" for="login-email">QTY</label> --}}
                        <input class="form-control scriptPriceCalc" placeholder="Price" value="" id="price" type="text"
                            name="price" aria-describedby="price" readonly tabindex="3">
                            <small class="error price-error d-block"></small>
                            <label class="form-label text-center ms-5" for="price">Price</label>
                       
                    </div>
                    @if(Auth::user()->user_position!='user')
                    <div class="col-6 col-md-2  mt-1">           
                        <select class="select2 form-select" name="client">
                            @foreach ($user_data as $user)  
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <small class="error client-error d-block"></small>
                        <label class="form-label text-center ms-5" for="client">User</label>
                       
                    </div>
                    @endif
                    <div class="col-12 col-md-2 d-flex align-items-start justify-content-end ps-0 mt-1">
                        <button type="submit"
                            class="btn btn-primary waves-effect waves-float waves-light buy-sell-btn w-100">Continue</button>
                        
                    </div>

                </div>


            </div>
        </form>
    </div>

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
    {{-- <script src="{{ asset(mix('js/scripts/tables/table-datatables-advanced.js')) }}"></script> --}}

    <script>
        $(document).ready(function() {
            $('.bidclick').on('click', function () {
                $('#tradeSell').prop('checked', true);
                
                $('.scriptPriceCalc').removeAttr('pl-SellPrice','');
                $('.scriptPriceCalc').attr('pl-BuyPrice','');
                $('#tradeSell').change();  
                $('.buy-sell-btn').text('Sell');  
                                 
            });

            $('.askclick').on('click', function () {
                $('#tradeBuy').prop('checked', true);
                
                $('.scriptPriceCalc').removeAttr('pl-BuyPrice','');
                $('.scriptPriceCalc').attr('pl-SellPrice','');
                $('.buy-sell-btn').text('Buy');
                $('#tradeBuy').change();
                   
            });

            $('input[name="tradeBuySell"]').change(function() {
                var outerDiv = $(this).closest('.offcanvas');

                var TradingSymbol_market =  $('input[name="TradingSymbol"]').val();
                if ($(this).val() == 'buy') {
                    $('.buy-sell-btn').text('Buy');
                    outerDiv.removeClass('offcanvas-danger')
                    outerDiv.addClass('offcanvas-primary');

                    var plattr_val = $('.scriptPriceCalc').attr('pl-buyprice');
                    $('.scriptPriceCalc').removeAttr('pl-buyprice');

                    $('.scriptPriceCalc').attr('pl-sellprice',plattr_val);
                    $('.scriptPriceCalc').val($('h4[pl-sellprice="' + TradingSymbol_market+'"]').html
                    ());                   

                    // pl-sellprice="ABBOTINDIA-II"

                } else if ($(this).val() == 'sell') {
                    $('.buy-sell-btn').text('Sell');
                    outerDiv.addClass('offcanvas-danger');
                    outerDiv.removeClass('offcanvas-primary');

                     var plattr_val = $('.scriptPriceCalc').attr('pl-sellprice');
                    $('.scriptPriceCalc').removeAttr('pl-sellprice');

                    $('.scriptPriceCalc').attr('pl-buyprice',plattr_val);
                    $('.scriptPriceCalc').val($('h4[pl-buyprice="' + TradingSymbol_market+'"]').html());

                }
            });

            // Trigger the change event initially to set the initial state
            $('input[name="transaction"]:checked').change();         

        });
    </script>

    <script>

        $(document).ready(function() {
            $('.open-watchlistoffcanvas').on('click', function() {

                var TradingSymbol = $(this).attr('pl-market');
                var TradingMarket = $(this).attr('pl-marketname');                

                $.ajax({
                    url: '{{ route('get.watchlist.ajax') }}',
                    method: 'POST',
                    data: {
                        TradingSymbol: TradingSymbol,
                        TradingMarket: TradingMarket
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.Status == 200) {

                            if (response.Data.is_ban == 'yes') {
                                toastr['warning'](
                                    'This script is banned by NSE ! <br>You can\'t trade 👋',
                                    'Banned', {
                                        closeButton: true,
                                        tapToDismiss: false
                                    });
                                return false;    
                            }

                            $('#offcanvasBottom').offcanvas('toggle');  
                            
                            var scriptQuantity = response.Data.script_quantity;                            
                            var scriptExpireId = response.Data.script_expires_id;
                            var tradingSymbol = response.Data.watchlist_trading_symbol;
                            var tradingMarket = response.Data.market_name;
                            console.log(tradingMarket);
                            var lot = 1; 


                            var Options = ['BuyPrice', 'SellPrice', 'Open', 'LastTradePrice',
                                'High', 'Low', 'Close',
                                'InstrumentIdentifier', 'PriceChange',
                                'PriceChangePercentage', 'TradingSymbol', 'Quantity','TradingMarket'
                            ];

                            Options.forEach(opt_value => {
                                var curValue;
                                                                
                                curValue = (opt_value !== 'TradingSymbol' &&  opt_value !== 'Quantity')?
                                $('.' + '' + TradingSymbol + opt_value).html():(opt_value == 'TradingSymbol'?TradingSymbol:(opt_value == 'Quantity'?scriptQuantity:tradingMarket));

                                $("[pl-" + opt_value + "]").attr("pl-" + opt_value,
                                TradingSymbol);
                                $("[pl-" + opt_value + "=" + TradingSymbol + "]").html(
                                    curValue);

                                $('#offcanvasBottom form input[name="'+opt_value+'"]').remove();
                                // Create a hidden input field for each value
                                $('<input>').attr({
                                    type: 'hidden',
                                    name: opt_value,
                                    value: curValue
                                }).appendTo('#offcanvasBottom form');
                            });

                            $('.scriptQuantityval').prop('readonly',(tradingMarket!='NSEFUT'));

                            $('.scriptQuantityval').val(lot * scriptQuantity);
                            $('#script_expires_id').val(scriptExpireId);                            
                            $('.scriptSymbol').html(tradingSymbol);
                            $('.scriptLot').val(lot);

                         
                          
                            var bidvalue = $($('[pl-BuyPrice]')[0]).text().replace(/,/g, '');
                            var askvalue = $($('[pl-SellPrice]')[0]).text().replace(/,/g, '');
                                                    
                            var scriptPrice = ($('[name="tradeBuySell"]:checked').val()=='sell') ?  parseFloat(bidvalue).toFixed(2) : parseFloat(askvalue).toFixed(2);
                         
                            // var formattedScriptPrice = scriptPrice.toFixed(2);
                            $('.scriptPriceCalc').val(scriptPrice);
 
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
        $(document).on('keyup','#offcanvasBottom form  [name="lot"],#offcanvasBottom form  [name="quantity"]',
            function(e) {
                var target_name = e.target.name;
                console.log(target_name);

                var lot  = $('#offcanvasBottom form  [name="lot"]').val();
                var Quantity  = $('#offcanvasBottom form  [name="Quantity"]').val();
                var quantity  = $('#offcanvasBottom form  [name="quantity"]').val();
                var tradingMarket  = $('#offcanvasBottom form  [name="tradingMarket"]').val();

                console.log((quantity/Quantity));

                if(target_name=='quantity' && tradingMarket!='NSEFUT')
                    $('#offcanvasBottom form  [name="lot"]').val((quantity/Quantity));
                else if(target_name=='lot')
                    $('#offcanvasBottom form  [name="quantity"]').val(Quantity*lot);
                
            });
    </script>


    <script>

        $(document).on('change', "[name='watchlist_filter_market']", function(e) {
            $("[name='watchlist_filter_ce_pe']").attr('disabled', true);
            $("[name='watchlist_filter_strick']").attr('disabled', true);

            if ($("option:selected", $(this)).text().trim() == 'NSEOPT') {
                $("[name='watchlist_filter_ce_pe']").removeAttr('disabled');
                $("[name='watchlist_filter_strick']").removeAttr('disabled');
            }
        });

        $(document).on('change',
            "[name='watchlist_filter_script'],[name='watchlist_filter_expiry'],[name='watchlist_filter_ce_pe']",
            function(e) {
                var this_m = this;
                var sent_values = {
                    "value_filter": $(e.target).attr('name'),
                    "market_id": $("[name='watchlist_filter_market']").val(),
                    "script_id": $("[name='watchlist_filter_script']").val(),
                    "expiry_date": $("[name='watchlist_filter_expiry']").val(),
                    "filter_ce_pe": $("[name='watchlist_filter_ce_pe']").val()
                }
                $.ajax({
                    url: "{{ route('get.WatchlistFilterValues') }}",
                    method: 'POST',
                    data: sent_values,
                    success: function(response) {
                        if (response?.Status == 200) {
                            var setValueTo = $("[name='" + response.setValueTo + "']");
                            setValueTo.empty();
                            setValueTo.append($("<option />").val('').text('select'));
                            $.each(response.Data, function(index, item) {
                                setValueTo.append($("<option />").val(item).text(item));
                            });
                            setValueTo.select2();
                        } else {
                            console.info(response);
                        }
                    }
                });
            });

        var MyWatchScript = @json($MyWatchScript);
    </script>
@endsection
