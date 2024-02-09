@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('content')

    <section id="basic-vertical-layouts">
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
