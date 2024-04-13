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
                                    action="{{ route('livetv_paginate_data') }}" table="livetv_paginate_data">
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
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form id="sliderForm" method="POST" name="sliderForm" action="{{ route('livetvstore') }}"
                            class="needs-validation" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-1">
                                <input type="hidden"  name="id" id="id">
                                <label class="form-label" for="basic-addon-name">Language</label>
                                <select name="language" id="language" class="form-control form-select select2">
                                    <option value="" disabled selected>Select Language</option>
                                    <option value="english">English</option>
                                    <option value="hindi">Hindi</option>
                                </select>
                            </div>



                            <div class="mb-1">
                                <label class="form-label" for="basic-addon-name">Link(Youtube)</label>
                                <input type="url" id="video_link" name="video_link" class="form-control"
                                    placeholder="Link" aria-label="Name" aria-describedby="basic-addon-name" />
                                <small class="error script_name-error "></small>
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
            $(document).on('click', '.edit-button', function() {
                const id = $(this).data('id');
                
                $.ajax({
                    url: Base_url+'settings/editlivetv/' + id,
                    method: 'GET',
                    success: function(dataToEdit) {
                        console.log(dataToEdit);
                        $('#modelHeading').text('Edit Link');
                        $('#language').val(dataToEdit.language);

                        // Set the value of the "Market Name" select field by its option value
                        $('#video_link').val(dataToEdit.video_link);
                        $('#id','#ajaxModel').val(dataToEdit.id);

                        $('#ajaxModel').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data: ' + error);
                    }
                });
            });
        });
    </script>
  

<script>
    function confirmDelete(id) {
      
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to remove this record?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
               
                axios.delete(`deletelivetv/${id}`)
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

            $("#addlink").click(function() {
                $('#modelHeading').text('Add Link');
                $('.error').html('');

                // Show the modal with the id "ajaxModel"
                $("#ajaxModel").modal("show");
            });
        });
    </script>



  


@endsection
