@php($ajaxformsubmit = true)
@extends('layouts/contentLayoutMaster')


@section('title', $title)

@section('content')

    <section id="basic-vertical-layouts">
        <button class="btn btn-primary mb-1" id="addlink"><i data-feather='plus'></i>&nbsp;Add</button>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">


                        <div class="row ">

                            <div class="col-12">


                                <form class="datatable_paginate row justify-content-between mb-2"
                                    action="{{ route('wallet_paginate_data') }}" table="wallet_paginate_data">
                                    <div class="row">

                                        <input type="hidden" name="page" value="1">
                                       


                                        <div class="col-3 ms-auto">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="search"
                                                    id="search" name="search" />
                                            </div>
                                        </div>



                                    </div>
                                </form>


                            </div>


                        </div>



                    </div>
                </div>
            </div>
        </div>


        <div class="modal animated show" id="ajaxModel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                    </div>
                    <div class="modal-body">

                        <form id="sliderForm" method="POST" name="sliderForm" action="{{ route('wallet.store') }}"
                            class="needs-validation" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-1">
                                <input type="hidden"  name="id" id="id">
                                <label class="form-label" for="basic-addon-name">Type</label>
                                <select name="wallet_transaction_type" id="wallet_transaction_type" class="form-control form-select select2">
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="credit">Credit</option>
                                    <option value="debit">Debit</option>
                                </select>
                            </div>



                            <div class="mb-1">
                                <label class="form-label" for="basic-addon-name">Amount</label>
                                <input type="number" id="wallet_amount" name="wallet_amount" class="form-control"
                                    placeholder="Amount" aria-label="Name" aria-describedby="basic-addon-name" />
                                <small class="error wallet_amount-error "></small>
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
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-advanced.js')) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/market/paginate.js') }}"></script>







    <script>
        $(document).ready(function() {
            const id = $(this).data('id');

            $("#addlink").click(function() {
                $('#modelHeading').text('Add Link');
                $('.error').html('');

                // Show the modal with the id "ajaxModel"
                $("#ajaxModel").modal("show");
            });
        });
    </script>



  


@endsection
