@php($ajaxformsubmit = true)
@extends('layouts/contentLayoutMaster')


@section('title', $title)

@section('content')

    <section id="basic-vertical-layouts">
        <button class="btn btn-primary mb-1" id="add_max_quantity"><i data-feather='plus'></i>&nbsp;Add</button>

        <button class="btn btn-primary mb-1 ms-1" id="level_import"><i data-feather='file-text'></i>&nbsp;Level Import</button>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-12">
                                <form class="datatable_paginate row justify-content-between mb-2"
                                    action="{{ route('max_quantity_paginate_data') }}" table="max_quantity_paginate_data">
                                    <div class="row">
                                        <input type="hidden" name="page" value="1">
                                        <div class="col-3">
                                            <select name="level_name" class="form-control form-select select2"
                                                id="levelSelect" data-url="{{ route('fetch-max-quantity-data') }}">
                                                <option value="">select level</option>
                                                @foreach ($leveldata as $data)
                                                    <option value="{{ $data->id }}">{{ $data->level_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form id="sliderForm" method="POST" name="sliderForm" action="{{ route('max_quantity_store') }}"
                            class="needs-validation" enctype="multipart/form-data">
                            @csrf


                            <div class="mb-1">
                                
                                <label class="form-label" for="basic-addon-name">Level Name</label>
                                <select name="level_id" id="level_name"
                                    class="form-control form-select select2">
                                    <option value=""  selected>Select Level</option>
                                    <!-- Placeholder option -->
                                    @foreach ($leveldata as $level)
                                        <option value="{{ $level->id }}"
                                            @if ($level->level_name == $data->level_name)  @endif>
                                            {{ $level->level_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="error level_id-error "></small>
                            </div>

                            <div class="mb-1">
                                <input type="text" hidden name="id" id="scriptid">
                                <label class="form-label" for="basic-addon-name">Market Name</label>
                                <select name="market_id" id="market_name"
                                    class="form-control form-select select2 markettoscript" script_to=".scriptofmarket">
                                    <option value="" disabled selected>Select Market</option>
                                    <!-- Placeholder option -->
                                    @foreach ($marketdata as $market)
                                        <option value="{{ $market->id }}"
                                            @if ($market->market_name == $data->market_name) selected @endif>
                                            {{ $market->market_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="error market_id-error "></small>
                            </div>



                            <div class="mb-1">
                                <label class="form-label" for="basic-addon-name">Script Name</label>
                                <select name="script_id" id="script_name"
                                    class="form-control form-select select2 scriptofmarket">
                                    <option value="">Select Script</option>
                                    <!-- The options will be populated dynamically using JavaScript -->
                                </select>
                                <small class="error script_id-error "></small>
                            </div>

                            <div class="mb-1">
                                <label class="form-label" for="basic-addon-name">Position</label>
                                <input type="text" id="max_quantity_position" name="max_quantity_position" class="form-control"
                                    placeholder="Position" aria-label="Name" aria-describedby="basic-addon-name" />
                                <small class="error max_quantity_position-error "></small>
                            </div>

                            <div class="mb-1">
                                <label class="form-label" for="basic-addon-name">Max Order</label>
                                <input type="text" id="max_quantity_max_order" name="max_quantity_max_order" class="form-control"
                                    placeholder="Max Order" aria-label="Name" aria-describedby="basic-addon-name" />
                                <small class="error max_quantity_max_order-error "></small>
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

        <div class="modal animated show" id="importmodel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-header">
                        <h4 class="modal-title" id="importmodelHeading"></h4>
                    </div>
                    <div class="modal-body">

                        <form id="sliderForm" class="level_form" method="POST" name="sliderForm" action="{{ route('max_quantity_import') }}"
                        class="needs-validation" enctype="multipart/form-data">
                        @csrf
                    
                        <div class="mb-1">
                            
                            <label class="form-label" for="basic-addon-name">Level Import</label>
                            <input type="file" id="level_import" name="level_import" class="form-control" 
                                aria-label="Name" aria-describedby="basic-addon-name" />
                            <small class="error level_import-error"></small>
                         
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
    <script src="{{ asset('js/market/getscript.js') }}"></script>
    <script src="{{ asset('js/market/paginate.js') }}"></script>


    <script>
        $('#search').on('keyup', function() {
            $value = $(this).val();
            $.ajax({
                type: 'get',
                url: '{{ route('search_max_quantity') }}',
                data: {
                    'search': $value
                },
                success: function(data) {
                    $('tbody').html(data);
                }

            });

        });
    </script>

    <script>
        $(document).ready(function() {
            const id = $(this).data('id');

            $("#add_max_quantity").click(function() {
                $('#modelHeading').text('Add Max Quantity');
          
                $('.error').html('');

                // Reset the "Market Name" select field to the placeholder option
                $("#market_name").val(null);

                // Show the modal with the id "ajaxModel"
                $("#ajaxModel").modal("show");
            });

            $("#level_import").click(function() {
                    $('#importmodelHeading').text('Level Import');
                    
                  
                    $('.error').html('');
                    // Show the modal with the id "ajaxModel"
                    $("#importmodel").modal("show");
                });

        });
    </script>

@endsection
