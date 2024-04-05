@php($ajaxformsubmit = true)
@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('content')

    <section id="basic-vertical-layouts">
        <div class="modal animated fade show" id="ajaxModel" aria-hidden="true">
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


                            <div class="">
                                <label class="form-label col-2" for="basic-addon-name">Name :</label>
                                <input type="text" id="user_name_code" style="display: inline-block;width:fit-content;"
                                    name="user_name_code" class="form-control border-0 col-10" readonly placeholder="Name"
                                    aria-label="Name" aria-describedby="basic-addon-name" />
                            </div>

                            <div class="mb-1">
                                <label class="form-label col-2" for="basic-addon-name">Script :</label>
                                <input type="text" style="display: inline-block;width:fit-content;"
                                    id="script_trading_symbol" name="trading_symbol" class="form-control border-0 col-10"
                                    readonly placeholder="Name" aria-label="Name" aria-describedby="basic-addon-name" />
                                <small class="error trading_symbol-error "></small>
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="basic-addon-name">Lot</label>
                                <input type="text" id="trade_lot" name="lot" class="form-control" placeholder="Name"
                                    aria-label="Name" aria-describedby="basic-addon-name" />
                                <small class="error lot-error "></small>
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="basic-addon-name">Quantity</label>
                                <input type="text" id="trade_quantity" name="quantity" class="form-control"
                                    placeholder="Quantity" aria-label="Name" aria-describedby="basic-addon-name" />
                                <small class="error quantity-error "></small>
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="basic-addon-name">Price</label>
                                <input type="text" id="trade_price" name="price" class="form-control"
                                    placeholder="Quantity" aria-label="Name" aria-describedby="basic-addon-name" />
                                <small class="error price-error "></small>
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

        <div class="card">
            <?= createDatatableFormFilter($tradersFormData) ?>
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
