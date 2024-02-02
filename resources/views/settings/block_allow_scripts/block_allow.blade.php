@php($ajaxformsubmit = true)
@extends('layouts/contentLayoutMaster')


@section('title', $title)

@section('content')

    <section id="basic-vertical-layouts">
        <button class="btn btn-primary mb-1" id="addscript"><i data-feather='plus'></i>&nbsp;Add</button>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">


                        <div class="row ">

                            <div class="col-12">

                                <form class="datatable_paginate row justify-content-between mb-2"
                                    action="{{ route('block_master_script_paginate_data') }}" table="blockmasterscriptpaginationdata_paginate_data">
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

                        <form id="sliderForm" method="POST" name="sliderForm" action="{{ route('block_allow_script_store') }}"
                            class="needs-validation" enctype="multipart/form-data">
                            @csrf


                            <div class="mb-1">
                              
                                <label class="form-label" for="basic-addon-name">Master Name</label>
                                <select name="user_id" id="user_name"
                                    class="form-control form-select select2 ">
                                    <option value="" disabled selected>Select Master</option>
                                    <!-- Placeholder option -->
                                    @foreach ($userdata as $user)
                                        <option value="{{ $user->id }}"
                                            @if ($user->username == $user->username)  @endif>
                                            {{ $user->username }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="error user_id-error "></small>
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
                                            @if ($market->market_name == $market->market_name) selected @endif>
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

                       

                           

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="saveBtn">Block Script</button>
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
    function confirmDelete(id) {
      
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to unblock this record?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, unblock it!'
        }).then((result) => {
            if (result.isConfirmed) {
               
                axios.delete(`deleteallowscript/${id}`)
                    .then(response => {
                        if (response.data.Redirect) {
                            window.location.href = response.data.Redirect;
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    }
</script>


    <script>
        $(document).ready(function() {
            const id = $(this).data('id');
            console.log(id);
            $("#addscript").click(function() {
                $('#modelHeading').text('Ban Script');
            
                $('.error').html('');

                // Reset the "Market Name" select field to the placeholder option
                $("#market_name").val(null);

                // Show the modal with the id "ajaxModel"
                $("#ajaxModel").modal("show");
            });
        });
    </script>



    





@endsection
