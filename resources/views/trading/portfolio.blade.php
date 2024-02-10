@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('content')

    <section id="basic-vertical-layouts">
        <div class="modal animated fade show " id="ajaxModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="sliderForm" method="POST" name="sliderForm" action="{{ route('save.trade') }}"
                            class="needs-validation" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="id">


                            <div class="mb-1">
                                <label class="form-label col-2" for="basic-addon-name">Script :</label>
                                <input type="text" style="display: inline-block;width:fit-content;"
                                    id="script_trading_symbol" name="trading_symbol" class="form-control border-0 col-10"
                                    readonly placeholder="Name" aria-label="Name" aria-describedby="basic-addon-name" />
                                <small class="error trading_symbol-error "></small>
                            </div>
                            <div class="mb-1">
                                <label class="form-label col-2" for="basic-addon-name">Quantity :</label>
                                <input type="text" style="display: inline-block;width:fit-content;"
                                    id="trade_quantity" name="quantity" class="form-control border-0 col-10"
                                    readonly placeholder="Quantity" aria-label="Name" aria-describedby="basic-addon-name"/>
                                <small class="error quantity-error "></small>
                            </div>
                            <div class="mb-1">
                                <label class="form-label col-2" for="basic-addon-name">Price :</label>
                                <input type="text" style="display: inline-block;width:fit-content;"
                                    id="trade_price" name="price" class="form-control border-0 col-10"
                                    readonly placeholder="Quantity" aria-label="Name" aria-describedby="basic-addon-name"/>
                                <small class="error price-error "></small>
                            </div>

                            <div class="mb-1">
                                <label class="form-label" for="basic-addon-name">Type :</label>
                                <select name="market_id" id="market_id" class="form-control  form-select select2">
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="limit">Limit</option>
                                    <option value="stop_loss">Stop Loss</option>
                                    
                                </select>
                            </div>
                            
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="saveBtn">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row " style="margin-bottom: -1rem">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-1">
                        <div class="d-flex gap-50">

                            <div class="flex-fill bg-light-primary border- p-50 rounded text-center">
                                <div class="d-flex flex-row ">
                                    <div class="my-auto flex-fill">
                                        <h4 class="fw-bo  lder mb-0 " pl-buyprice="NIFTY-I">21,839.00</h4>
                                        <b class="card-text font-small-3 mb-0">TOTAL MTM</b>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-fill bg-light-primary   p-50 rounded">
                                <div class="d-flex flex-row text-center">
                                    <div class="my-auto flex-fill">
                                        <h4 class="fw-bolder mb-0 " pl-sellprice="NIFTY-I">21,849.00</h4>
                                        <b class="card-text font-small-3 mb-0">SELF MTM</b>
                                    </div>
                                </div>
                            </div>
                            <div class=" flex-fill bg-light-primary   p-50 rounded">
                                <div class="d-flex flex-row text-center">
                                    <div class="my-auto flex-fill">
                                        <h4 class="fw-bolder mb-0" pl-lasttradeprice="NIFTY-I">21,848.90</h4>
                                        <b class="card-text font-small-3 mb-0">DOWNLINE MTM</b>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-fill bg-light-primary  p-50 rounded">
                                <div class="d-flex flex-row text-center">
                                    <div class="my-auto flex-fill">
                                        <h4 class="fw-bolder mb-0" pl-pricechangepercentage="NIFTY-I">0.16</h4>
                                        <b class="card-text font-small-3 mb-0">UPLINE MTM</b>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-fill bg-light-primary  p-50 rounded">
                                <div class="d-flex flex-row text-center">
                                    <div class="my-auto flex-fill">
                                        <h4 class="fw-bolder mb-0" pl-pricechange="NIFTY-I">34.30</h4>
                                        <b class="card-text font-small-3 mb-0">TOTAL QTY</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <?= createDatatableFormFilter($portfolioFormData) ?>
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
        $(".select2-ajax-user_dropdown").select2({
            ajax: {
                url: "{{ route('getRoleWiseUserlist') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        user_position: $(this).attr("data-type"), // search term
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
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
            escapeMarkup: function(es) {
                return es;
            }
        });

        function RepoSelection(option) {
            if (!option.id) {
                return option.text;
            }
            var $person =
                `<div class="d-flex align-items-center p-0"><div class="avatar me-1 avatar-sm"><span class="avatar-content bg-${getRandomColorState()}">${getInitials(option.name)}</span></div><p class="mb-0">` +
                option.name + `</p></div>`;
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
