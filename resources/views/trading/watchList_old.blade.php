@extends('layouts/contentLayoutMaster')
@php($no_breadcrumbs = 1)
@section('title', $title)
@section('content')
    <section id="basic-vertical-layouts">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row" id="watchlist-form"
                            action="http://127.0.0.1/ninja_exchange/trading/watchlist-save" method="post">
                            <div class="col col-md-2 " style="margin-bottom: 1rem">
                                <label class="form-label" for="watchlist_filter_market">SEGMENT</label><select
                                    class="select2 form-select marketToScripts" name="watchlist_filter_market">
                                    <option value="">select</option>
                                    @foreach($marketdata as $market)
                                      <option value="{{$market['id']}}">{{$market['market_name']}}</option>
                                    @endforeach
                                </select><small class="error watchlist_filter_market-error"></small>
                            </div>
                            <div class="col col-md-2 " style="margin-bottom: 1rem">
                                <label class="form-label" for="watchlist_filter_script">SCRIPT</label><select
                                    class="select2 form-select markerScripts" name="watchlist_filter_script">
                                    <option value="">select</option>                                    
                                </select><small class="error script_id-error"></small>
                            </div>
                            <div class="col col-md-2 " style="margin-bottom: 1rem">
                                <label class="form-label" for="watchlist_filter_expiry">EXPIRY DATE</label><select
                                    class="select2 form-select" name="watchlist_filter_expiry">
                                    <option value="">Select</option>
                                </select><small class="error watchlist_expiry_date-error"></small>
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
                                            <thead>
                                                <tr>
                                                    <th>NSE SYM</th>
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

                                            @foreach (['NIFTY23122821000CE'] as $symbol)
                                                @php($market = 'NSE')
                                                <tr>
                                                    <th>{{ $symbol }} 31AUG2023</th>
                                                    <td data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                        aria-controls="offcanvasBottom"
                                                        class="{{ $market . $symbol . 'BuyPrice' }} cursor-pointer">00.00</td>
                                                    <td data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                        aria-controls="offcanvasBottom"
                                                        class="{{ $market . $symbol . 'SellPrice' }} cursor-pointer">00.00</td>
                                                    <td class="{{ $market . $symbol . 'LastTradePrice' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'PriceChangePercentage' }}">00.00</td>
                                                    <td><i class="text-success fw-bolder {{ $market . $symbol . 'PriceChangeicon' }}"
                                                            data-feather="trending-up"></i> <span
                                                            class="{{ $market . $symbol . 'PriceChange' }}">00.00</span></td>
                                                    <td class="{{ $market . $symbol . 'High' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Low' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Low' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Close' }}">00.00</td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm">
                                                            <i data-feather='delete'></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <thead>
                                                <tr>
                                                    <th>NFO SYM</th>
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

                                            @foreach (['NIFTY-I', 'NIFTY23DECFUT'] as $symbol)
                                                @php($market = 'NFO')
                                                <tr>
                                                    <th>{{ $symbol }} 31AUG2023</th>
                                                    <td data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                        aria-controls="offcanvasBottom"
                                                        class="{{ $market . $symbol . 'BuyPrice' }} cursor-pointer">00.00</td>
                                                    <td data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                        aria-controls="offcanvasBottom"
                                                        class="{{ $market . $symbol . 'SellPrice' }} cursor-pointer">00.00
                                                    </td>
                                                    <td class="{{ $market . $symbol . 'LastTradePrice' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'PriceChangePercentage' }}">00.00</td>
                                                    <td><i class="text-success fw-bolder {{ $market . $symbol . 'PriceChangeicon' }}"
                                                            data-feather="trending-up"></i> <span
                                                            class="{{ $market . $symbol . 'PriceChange' }}">00.00</span></td>
                                                    <td class="{{ $market . $symbol . 'High' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Low' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Low' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Close' }}">00.00</td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm">
                                                            <i data-feather='delete'></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <thead>
                                                <tr>
                                                    <th>MCX SYM</th>
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

                                            @foreach (['GOLD-I', 'SILVER-I', 'SILVERM-I', 'SILVERMIC-I'] as $symbol)
                                                @php($market = 'MCX')
                                                <tr>

                                                    <th>{{ $symbol }} 31AUG2023</th>

                                                    <td data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                        aria-controls="offcanvasBottom"
                                                        class="{{ $market . $symbol . 'BuyPrice' }} cursor-pointer text-white">
                                                        00.00</td>
                                                    <td data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                        aria-controls="offcanvasBottom"
                                                        class="{{ $market . $symbol . 'SellPrice' }} cursor-pointer text-white">
                                                        00.00</td>
                                                    <td class="{{ $market . $symbol . 'LastTradePrice' }} text-white">00.00
                                                    </td>


                                                    <td class="{{ $market . $symbol . 'PriceChangePercentage' }}">00.00</td>
                                                    <td><i class="text-success fw-bolder {{ $market . $symbol . 'PriceChangeicon' }}"
                                                            data-feather="trending-up"></i> <span
                                                            class="{{ $market . $symbol . 'PriceChange' }}">00.00</span></td>
                                                    <td class="{{ $market . $symbol . 'High' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Low' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Low' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Close' }}">00.00</td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm">
                                                            <i data-feather='delete'></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <thead>
                                                <tr>
                                                    <th>FOREX SYM</th>
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

                                            @foreach (['AUDJPY', 'AUDUSD', 'CADJPY', 'EURUSD', 'GBPUSD'] as $symbol)
                                                @php($market = 'FOREX')
                                                <tr>
                                                    <th>{{ $symbol }}</th>
                                                    <td class="{{ $market . $symbol . 'BuyPrice' }} cursor-pointer">00.00</td>
                                                    <td class="{{ $market . $symbol . 'SellPrice' }} cursor-pointer">00.00
                                                    </td>
                                                    <td class="{{ $market . $symbol . 'LastTradePrice' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'PriceChangePercentage' }}">00.00</td>
                                                    <td><i class="text-success fw-bolder {{ $market . $symbol . 'PriceChangeicon' }}"
                                                            data-feather="trending-up"></i> <span
                                                            class="{{ $market . $symbol . 'PriceChange' }}">00.00</span></td>
                                                    <td class="{{ $market . $symbol . 'High' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Low' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Low' }}">00.00</td>
                                                    <td class="{{ $market . $symbol . 'Close' }}">00.00</td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm">
                                                            <i data-feather='delete'></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            {{--
                    <tr>                    
                      <td>GOLD-I 31AUG2023</td>
                      <td></td>
                      <td style="background: #be2114;"  class="MCXGOLD-IBuyPrice">19,422.00</td>
                      <td style="background: #be2114;">19,425.00</td>
                      <td style="background: #be2114;">19,422.15</td>
                      <td>-0.45</td>
                      <td><i class="text-danger fw-bolder" data-feather="chevrons-down"></i>  -87.85</td>
                      <td>19,487.10</td>
                      <td>19,393.00</td>
                      <td>19,450.00</td>
                      <td>19,510.00</td>
                      <td>
                        <button class="btn btn-danger btn-sm">
                          <i data-feather="trash"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>                    
                      <td style="background: #04b962;">NIFTY 31AUG2023</td>
                      <td style="background: #04b962;"></td>
                      <td style="background: #04b962;">19,422.00</td>
                      <td style="background: #04b962;">19,425.00</td>
                      <td style="background: #04b962;">19,422.15</td>
                      <td style="background: #04b962;">-0.45</td>
                      <td style="background: #04b962;"><i class="text-success fw-bolder" data-feather="chevrons-up"></i>87.85</td>
                      <td style="background: #04b962;">19,487.10</td>
                      <td style="background: #04b962;">19,393.00</td>
                      <td style="background: #04b962;">19,450.00</td>
                      <td style="background: #04b962;">19,510.00</td>
                      <td>
                        <button class="btn btn-danger btn-sm">
                          <i data-feather="trash"></i>
                        </button>
                      </td>
                    </tr>
                    
                    <tr>                    
                      <td>SILVER-I 21NOV2023</td>
                      <td></td>
                      <td style="background: #be2114;" class="MCXSILVER-IBuyPrice">19,422.00</td>
                      <td style="background: #be2114;">19,800.00</td>
                      <td style="background: #0c51c4;">19,422.15</td>
                      <td>-0.45</td>
                      <td><i class="text-success" data-feather="chevrons-up"></i>+87.85</td>
                      <td>19,487.10</td>
                      <td>19,393.00</td>
                      <td>19,000.00</td>
                      <td>19,890.00</td>
                      <td>
                        <button class="btn btn-danger btn-sm">
                          <i data-feather="trash"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>                    
                      <td>SILVERM-I 22NOV2023</td>
                      <td></td>
                      <td style="background: #0c51c4;" class="MCXSILVERM-IBuyPrice">20,000.00</td>
                      <td style="background: #0c51c4;">19,425.00</td>
                      <td style="background: #0c51c4;">19,422.15</td>
                      <td>-0.45</td>
                      <td><i class="text-danger" data-feather="chevrons-down"></i>-87.85</td>
                      <td>19,487.10</td>
                      <td>19,393.00</td>
                      <td>19,450.00</td>
                      <td>20,500.00</td>
                      <td>
                        <button class="btn btn-danger btn-sm">
                          <i data-feather="trash"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>                    
                      <td style="background: #04b962;" >SILVERMIC 31AUG2023</td>
                      <td style="background: #04b962;"></td>
                      <td style="background: #b90404;" class="MCXSILVERMIC-IBuyPrice">19,422.00</td>
                      <td style="background: #04b962;" class="MCXSILVERMIC-ISellPrice">19,425.00</td>
                      <td style="background: #04b962;">19,422.15</td>
                      <td style="background: #04b962;">-0.45</td>
                      <td style="background: #04b962;"><i class="text-success fw-bolder" data-feather="chevrons-up"></i>87.85</td>
                      <td style="background: #04b962;">19,487.10</td>
                      <td style="background: #04b962;">19,393.00</td>
                      <td style="background: #04b962;">19,450.00</td>
                      <td style="background: #04b962;">19,510.00</td>
                      <td>
                        <button class="btn btn-danger btn-sm">
                          <i data-feather="trash"></i>
                        </button>
                      </td>
                    </tr>
                    --}}
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

        {{-- <div class="offcanvas-header">
      <h5 id="offcanvasBottomLabel" class="offcanvas-title">GOLD-I 31AUG2023</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div> --}}
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-md-2 m-auto  text-center p-75">
                    <h5 class="offcanvas-title text-primary">GOLD-I 31AUG2023</h5>
                    {{-- <div class="form-group">
           <input type="text" class="form-control" placeholder="search" value="GOLD-I 31AUG2023" readonly> 
      </div> --}}
                </div>
                <div class="col-md-10">
                    <div class="card-body statistics-body py-0 pe-1">
                        <div class="row justify-content-md-end justify-content-around  gap-25">

                            <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-success border-success p-50 rounded">
                                <div class="d-flex flex-row">
                                    <div class="my-auto">
                                        <h4 class="fw-bo  lder mb-0">0.00</h4>
                                        <b class="card-text font-small-3 mb-0">BID RATE</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-success border-success p-50 rounded">
                                <div class="d-flex flex-row">
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">0.00</h4>
                                        <b class="card-text font-small-3 mb-0">ASK RATE</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-success border-success p-50 rounded">
                                <div class="d-flex flex-row">
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">0.00</h4>
                                        <b class="card-text font-small-3 mb-0">LTP</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                <div class="d-flex flex-row">
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">0.00</h4>
                                        <b class="card-text font-small-3 mb-0">CHANGE(%)</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                <div class="d-flex flex-row">
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">0.00</h4>
                                        <b class="card-text font-small-3 mb-0">NET CHG</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                <div class="d-flex flex-row">
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">0.00</h4>
                                        <b class="card-text font-small-3 mb-0">HIGH</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                <div class="d-flex flex-row">
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">0.00</h4>
                                        <b class="card-text font-small-3 mb-0">LOW</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                <div class="d-flex flex-row">
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">0.00</h4>
                                        <b class="card-text font-small-3 mb-0">OPEN</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-sm-6 col-5  mb-xl-0 bg-light-secondary border-secondary p-50 rounded">
                                <div class="d-flex flex-row">
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">0.00</h4>
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
                        <input class="custom-option-item-check custom-option-danger" type="radio" name="tradeBuySell"
                            id="tradeSell" value="sell" />
                        <label class="custom-option-item p-50 text-center" for="tradeSell">
                            <span class="">
                                <span class="fw-bolder">Sell</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="col-6 col-md-2 mt-1" name="order_type" id="order_type">
                    <select class="select2 form-select">
                        <option value="market">Market</option>
                        <option value="limit">Limit</option>
                        <option value="stop_loss">Stop Loss</option>
                    </select>
                    <small class="error order_type-error"></small>
                </div>

                <div class="col-6 col-md-2  mt-1">
                    {{-- <label class="form-label" for="login-email">QTY</label> --}}
                    <input class="form-control " placeholder="Quantity" value="" id="quantity" type="number"
                        name="quantity" aria-describedby="quantity" tabindex="1">
                    <small class="error quantity-error"></small>
                </div>

                <div class="col-6 col-md-2  mt-1">
                    {{-- <label class="form-label" for="login-email">QTY</label> --}}
                    <input class="form-control " placeholder="Price" value="" id="price" type="number"
                        name="price" aria-describedby="price" tabindex="3">
                    <small class="error price-error"></small>
                </div>

                <div class="col-6 col-md-2  mt-1">
                    {{-- <label class="form-label" for="client">Client</label> --}}
                    <select class="select2 form-select" name="client">
                        <option value="market">Abc</option>
                        <option value="client">Def</option>
                        <option value="stop_loss">Ghi</option>
                    </select>
                    <small class="error client-error"></small>
                </div>
                <div class="col-12 col-md-2 d-flex align-items-start justify-content-end ps-0 mt-1">
                    <button type="submit"
                        class="btn btn-primary me-1 waves-effect waves-float waves-light">Continue</button>
                    <button type="button" class="btn btn-outline-secondary waves-effect"
                        data-bs-dismiss="offcanvas">Cancel</button>
                </div>

            </div>


        </div>
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



    <!-- Include the socket.io v2 client library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>

    <script>
        // const socket = io('https://thedatamining.org:6003');

        // // // // Define the data to be sent
        // // // const addMarketWatchData = { product: ['GOLD-I','SILVER-I','SILVERM-I','SILVERMIC-I','AUDJPY','AUDUSD','CADJPY','EURUSD','GBPUSD','NIFTY-I'] };
        // // // // Emit the 'addMarketWatch' event with the specified data
        // // // socket.emit('addMarketWatch', addMarketWatchData);


        // var stock_array = ['GOLD-I', 'SILVER-I', 'SILVERM-I', 'SILVERMIC-I', 'AUDJPY', 'AUDUSD', 'CADJPY', 'EURUSD',
        //     'GBPUSD', 'NIFTY-I', 'BANKNIFTY-I', 'NIFTY23122821000CE', 'EURINR24NOVFUT'];

        // stock_array.forEach(function($val) {
        //     // Emit the 'addMarketWatch' event with the specified data
        //     socket.emit('addMarketWatch', {
        //         product: $val
        //     });
        // });


        // socket.on('marketWatch', (marketData) => {
        //     // console.log('Received marketWatch event with data:', marketData);  
        //     if (marketData?.data?.Exchange !== undefined)
        //         setMarketValues(marketData);
        // });

        function setMarketValues(marketData) {
            var Exchange = marketData.data.Exchange;
            var BuyPrice = marketData.data.BuyPrice;
            var InstrumentIdentifier = marketData.data.InstrumentIdentifier;
            var PriceChange = marketData.data.PriceChange;
            var PriceChangePercentage = marketData.data.PriceChangePercentage;

            var Options = ['BuyPrice', 'SellPrice', 'Open', 'LastTradePrice', 'High', 'Low', 'Close',
                'InstrumentIdentifier', 'PriceChange', 'PriceChangePercentage',];

            Options.forEach(opt_value => {

                if (marketData.data[opt_value] != undefined) {
                    var curValue = $('.' + Exchange + InstrumentIdentifier + opt_value).html();

                    $('.' + Exchange + InstrumentIdentifier + opt_value).html((marketData.data[opt_value])
                        .toLocaleString('en-US', {
                            style: 'decimal',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));

                    if (['BuyPrice', 'SellPrice', 'LastTradePrice'].indexOf(opt_value) !== -1) {

                        var nowval = $('.' + Exchange + InstrumentIdentifier + opt_value).html();
                        // console.log(curValue,nowval ,'<',(curValue<nowval),'==',(curValue==nowval),'>',(curValue>nowval));

                        if (curValue < nowval)
                            $('td.' + Exchange + InstrumentIdentifier + opt_value).css('background-color',
                                '#28c76f');
                        if (curValue > nowval)
                            $('td.' + Exchange + InstrumentIdentifier + opt_value).css('background-color',
                                '#ea5455');
                        else
                            $('td.' + Exchange + InstrumentIdentifier + opt_value).css('background-color',
                                '#0C51C4');

                    }
                    // else if (['BuyPrice', 'SellPrice','LastTradePrice'].indexOf(opt_value) !== -1 && marketData.data['PriceChange'] < 0) {
                    //     $('td.'+Exchange+InstrumentIdentifier+opt_value).css('background-color', '#BF2114');
                    // }

                    if (['PriceChange'].indexOf(opt_value) !== -1) {
                        var iconlink = Exchange + InstrumentIdentifier + opt_value + 'icon';

                        $('.' + iconlink).replaceWith(feather.icons[(marketData.data['PriceChange'] < 0 ?
                            'trending-down' : 'trending-up')].toSvg({
                            "class": (marketData.data['PriceChange'] < 0 ? 'text-danger' :
                                'text-success') + ' ' + iconlink
                        }));

                        $('.' + iconlink).closest('.avatar').removeClass((marketData.data['PriceChange'] < 0 ?
                            'bg-light-success' : 'bg-light-danger'));
                        $('.' + iconlink).closest('.avatar').addClass((marketData.data['PriceChange'] > 0 ?
                            'bg-light-success' : 'bg-light-danger'));

                        if (marketData.data['PriceChange'] < 0)
                            $('td.' + Exchange + InstrumentIdentifier + opt_value + 'icon').addClass('text-danger');
                        else
                            $('td.' + Exchange + InstrumentIdentifier + opt_value + 'icon').removeClass(
                                'text-danger');
                    }
                }
            });
        }
        
        $(document).on('change', "[name='watchlist_filter_market']", function (e) {
            $("[name='watchlist_filter_ce_pe']").attr('disabled',true);
            $("[name='watchlist_filter_strick']").attr('disabled',true);

            if($("option:selected",$(this)).text().trim() == 'NSEOPT')
            {
                $("[name='watchlist_filter_ce_pe']").removeAttr('disabled');
                $("[name='watchlist_filter_strick']").removeAttr('disabled');
            }
        });

        $(document).on('change', "[name='watchlist_filter_script'],[name='watchlist_filter_expiry'],[name='watchlist_filter_ce_pe']", function (e) {            
            var this_m = this;
            var sent_values = {
                "value_filter": $(e.target).attr('name'),
                "script_id": $("[name='watchlist_filter_script']").val(),
                "expiry_date": $("[name='watchlist_filter_expiry']").val(),
                "filter_ce_pe": $("[name='watchlist_filter_ce_pe']").val()
            }
            $.ajax({
                url: "{{route('get.WatchlistFilterValues')}}",
                method: 'POST',
                data: sent_values,
                success: function (response) {
                    // console.log(response);
                    if (response?.Status == 200) {

                        var setValueTo = $("[name='"+response.setValueTo+"']");
                        setValueTo.empty();
                        setValueTo.append($("<option />").val('').text('select'));
                        $.each(response.Data, function (index, item) {
                            setValueTo.append($("<option />").val(item).text(item));
                        });
                        setValueTo.select2();
                    } else {
                        console.info(response);
                    }              
                }
            });
        });

        var ip  = location.host;
        const mysocket = io(ip+':3000');   

        mysocket.on('connect', (sock) => {
            // mysocket.socket.sessionid;
            console.log('Connection established',mysocket.id);
            mysocket.emit('updateData', {
            // product: ["{{Auth::user()->id==1?'GOLD-I':'SILVER-I'}}"]
            product: ['GOLD-I', 'SILVER-I', 'SILVERM-I', 'SILVERMIC-I', 'AUDJPY', 'AUDUSD', 'CADJPY', 'EURUSD',
            'GBPUSD', 'NIFTY23122821000CE', 'EURINR24NOVFUT']
        });
        });

        mysocket.on('updateData', (marketData) => {
            console.log('Received data:', marketData);
            if (marketData?.data?.Exchange !== undefined)
                setMarketValues(marketData);
        });

        mysocket.on('connect_error', (error) => {
            console.error('Socket.IO connection error:', error);
        });

        

        // Listen for the 'marketWatch' event
        //     socket.on('marketWatch', (marketData) => {

        //       marketData.forEach(value => {
        //           console.log(value);
        //           // Your code for each filtered element goes here
        //       });

        // //     if (!Array.isArray(marketData)) {
        // //     console.error('marketData is not an array');
        // // } else {
        // //     // Now you can use filter
        // //     marketData = marketData.filter(marketWatch => marketWatch.InstrumentIdentifier);

        // //     marketData.forEach(value => {
        // //         console.log(value);
        // //         // Your code for each filtered element goes here
        // //     });
        // // }




        //     console.log('Received marketWatch event with data:', marketData);
        //     // Handle market data received from the server
        //     // ...
        //   });

        // Close the connection (optional)
        // socket.close();
    </script>
@endsection
