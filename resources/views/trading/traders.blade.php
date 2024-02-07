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
                              <label class="form-label" for="basic-addon-name">Name :</label>
                              <input type="text" id="username" style="display: inline-block;width:fit-content;" name="username" class="form-control border-0" readonly
                                  placeholder="Name" aria-label="Name" aria-describedby="basic-addon-name"/>      
                           </div>
                           <div class="">
                              <label class="form-label" for="basic-addon-name">Usercode :</label>
                              <input type="text" id="usercode" style="display: inline-block;width:fit-content;" name="usercode" class="form-control border-0" readonly
                                  placeholder="Usercode" aria-label="Name" aria-describedby="basic-addon-name"/>      
                            </div>
                            <div class="mb-1">
                              <label class="form-label" for="basic-addon-name">Script :</label>
                              <input type="text" style="display: inline-block;width:fit-content;" id="script_trading_symbol" name="trading_symbol" class="form-control border-0" readonly
                                  placeholder="Name" aria-label="Name" aria-describedby="basic-addon-name"/>
                              <small class="error trading_symbol-error "></small>
                           </div>
                            <div class="mb-1">
                                <label class="form-label" for="basic-addon-name">Lot</label>
                                <input type="text" id="trade_lot" name="lot" class="form-control"
                                    placeholder="Name" aria-label="Name" aria-describedby="basic-addon-name"/>
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <?= createDatatableFormFilter($tradersFormData) ?>
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
