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


                                            @foreach ($watchlist_data as $w_key => $watch_scr)
                                                @php($market = '')
                                                @php($symbol = $watch_scr->watchlist_script_extension)
                                                @php($symbol_name = $watch_scr->watchlist_trading_symbol)


                                                @if ($w_key == 0 || ($w_key > 0 && $watchlist_data[$w_key - 1]->market_id != $watch_scr->market_id))
                                                    <thead>
                                                        <tr>
                                                            <th>{{ $watch_scr->watchlist_market_name }}</th>
                                                            <th>BID RATE</th>
                                                            <th>ASK RATE</th>
                                                            <th>LTP</th>
                                                            <th>CHANGE %</th>
                                                            <th>NET CHANGE</th>
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
                                                    <td pl-market="{{ $symbol }}"
                                                        class="{{ $market . $symbol . 'BuyPrice' }} cursor-pointer open-watchlistoffcanvas bidclick">
                                                        00.00</td>
                                                    <td pl-market="{{ $symbol }}"
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

    <div class="offcanvas offcanvas-bottom rounded-top offcanvas-primary" tabindex="-1" id="offcanvasBottom"
        aria-labelledby="offcanvasBottomLabel">

        <form action="{{ route('save.trade') }}" method="POST">
            
            <input type="hidden" name="script_expires_id" id="script_expires_id">

            <div class="offcanvas-body">
                <div class="row">
                    <div class="col-md-2 m-auto  text-center p-75">
                       
                        <h5 class="offcanvas-title text-primary scriptSymbol" >GOLD-I 31AUG2023</h5>

                    </div>
                    <div class="col-md-10">
                        <div class="card-body statistics-body py-0 pe-1">
                            <div class="row justify-content-md-end justify-content-around  gap-25">

                                <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-success border-success p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bo  lder mb-0 " pl-BuyPrice="NIFTY-I">0.00</h4>
                                            <b class="card-text font-small-3 mb-0">BID RATE</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-success border-success p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0 " pl-SellPrice="NIFTY-I">0.00</h4>
                                            <b class="card-text font-small-3 mb-0">ASK RATE</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-success border-success p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-LastTradePrice>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">LTP</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-PriceChangePercentage>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">CHANGE(%)</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-PriceChange>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">NET CHG</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-High>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">HIGH</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-Low>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">LOW</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                    <div class="d-flex flex-row">
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0" pl-Open>0.00</h4>
                                            <b class="card-text font-small-3 mb-0">OPEN</b>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
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
                            type="number" name="lot" aria-describedby="lot" tabindex="1">
                            <label class="form-label text-center ms-5" for="lot">Lot</label>
                        <small class="error lot-error"></small>
                    </div>

                    <div class="col-6 col-md-2  mt-1">
                        {{-- <label class="form-label" for="login-email">QTY</label> --}}
                        <input class="form-control scriptQuantityval" placeholder="Quantity" value=""
                            id="quantity" type="number" name="quantity" aria-describedby="quantity" tabindex="1">
                            <label class="form-label text-center ms-5" for="quantity">Quantity</label>
                        <small class="error quantity-error"></small>
                    </div>

                    <div class="col-6 col-md-2  mt-1">
                        {{-- <label class="form-label" for="login-email">QTY</label> --}}
                        <input class="form-control scriptPriceCalc" placeholder="Price" value="" id="price" type="text"
                            name="price" aria-describedby="price" readonly tabindex="3">
                            <label class="form-label text-center ms-5" for="price">Price</label>
                        <small class="error price-error"></small>
                    </div>
                    @if(Auth::user()->user_position!='user')
                    <div class="col-6 col-md-2  mt-1">           
                        <select class="select2 form-select" name="client">
                            @foreach ($user_data as $user)  
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <label class="form-label text-center ms-5" for="client">User</label>
                        <small class="error client-error"></small>
                    </div>
                    @endif
                    <div class="col-12 col-md-2 d-flex align-items-start justify-content-end ps-0 mt-1">
                        <button type="submit"
                            class="btn btn-primary me-1 waves-effect waves-float waves-light">Continue</button>
                        <button type="button" class="btn btn-outline-secondary waves-effect"
                            data-bs-dismiss="offcanvas">Cancel</button>
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
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-advanced.js')) }}"></script>

    <script>
        $(document).ready(function() {
            $('.bidclick').on('click', function () {
                $('#tradeSell').prop('checked', true);
                $('#tradeSell').change();

                   
            });

            $('.askclick').on('click', function () {
                $('#tradeBuy').prop('checked', true);
                $('#tradeBuy').change();
                   
            });

            $('input[name="tradeBuySell"]').change(function() {


                var outerDiv = $(this).closest('.offcanvas');
                if ($(this).val() == 'buy') {
                    outerDiv.removeClass('offcanvas-danger')
                    outerDiv.addClass('offcanvas-primary');
                } else if ($(this).val() == 'sell') {
                    outerDiv.addClass('offcanvas-danger');
                    outerDiv.removeClass('offcanvas-primary');
                }
            });

            // Trigger the change event initially to set the initial state
            $('input[name="transaction"]:checked').change();

          

        });
    </script>

    <script>

        $(document).ready(function() {
            $('.open-watchlistoffcanvas').on('click', function() {


                var plMarketValue = $(this).attr('pl-market');

                $.ajax({
                    url: '{{ route('get.watchlist.ajax') }}',
                    method: 'POST',
                    data: {
                        plMarketValue: plMarketValue
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

                            } else {
                                $('#offcanvasBottom').offcanvas('toggle');

                            }

                            var Options = ['BuyPrice', 'SellPrice', 'Open', 'LastTradePrice',
                                'High', 'Low', 'Close',
                                'InstrumentIdentifier', 'PriceChange',
                                'PriceChangePercentage', 'TradingSymbol', 'Quantity'
                            ];

                            Options.forEach(opt_value => {
                                var curValue;

                                if (opt_value === 'TradingSymbol') {
                                    curValue = tradingSymbol;
                                } else if (opt_value === 'Quantity') {
                                    curValue = scriptQuantity;
                                } else {
                                    curValue = $('.' + '' + plMarketValue + opt_value)
                                        .html();
                                }

                                $("[pl-" + opt_value + "]").attr("pl-" + opt_value,
                                    plMarketValue);
                                $("[pl-" + opt_value + "=" + plMarketValue + "]").html(
                                    curValue);

                                       // Create a hidden input field for each value
                                        $('<input>').attr({
                                            type: 'hidden',
                                            name: opt_value,
                                            value: curValue
                                        }).appendTo('form');
                            });


                            var scriptQuantity = response.Data.script_quantity;
                            var scriptExpireId = response.Data.script_expires_id;
                            $('#script_expires_id').val(scriptExpireId);
                            var tradingSymbol = response.Data.watchlist_trading_symbol;
                            $('.scriptSymbol').html(tradingSymbol);
                            
                                var lot = '1'; 
                                $('.scriptLot').val(lot);
                                $('.scriptQuantityval').val(lot * scriptQuantity);

                                var bidvalue = $('.fw-bo[pl-BuyPrice]').text();
                                bidvalue = bidvalue.replace(',', '');
                                var askvalue = $('.fw-bolder[pl-SellPrice]').text();
                                askvalue = askvalue.replace(',', '');

                               
                                var scriptPrice;

                                if ($('#tradeSell').is(':checked')) {
                                        scriptPrice = lot*parseFloat(bidvalue);
                                    } else {
                                        scriptPrice = lot*parseFloat(askvalue);
                                }

                                var formattedScriptPrice = scriptPrice.toFixed(2);
                                $('.scriptPriceCalc').val(formattedScriptPrice);
 
                        }
                    },

                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });
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
