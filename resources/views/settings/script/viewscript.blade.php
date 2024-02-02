@php($ajaxformsubmit = true)
@extends('layouts/contentLayoutMaster')


@section('title', $title)


@section('content')

<section class="app-user-list">
    
    <div class="row">
    <div class="col-12 col-md-4">
        <button class="btn btn-primary mb-1" id="addscript"><i data-feather='plus'></i>&nbsp;Add</button>
        <button class="btn btn-primary mb-1 ms-1 import-script-btn"  data-bs-toggle="modal" data-bs-target="#importmodel"><i data-feather='file-text'></i>&nbsp;Import Script</button>
    </div>
    <div class="col-12 col-md-8">
        <div class="progress-wrapper  col-4 import-script-progress float-end" style="display: none !important">
            <div class="progressText">Scripts Importing...[0/0]</div>
            <div class="progress progress-bar-primary">            
                <div class="progress-bar progress-bar-striped progress-bar-animated " role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">               
                </div>
            </div>
        </div>
        <?php
        $is_started = (int)cache("import_script_current_row")?:0;   
        $error = cache()->pull("import_script_error_msg")?:        
        ((filled(cache("all_import_status_error")) && !$is_started>0) ? cache()->pull("all_import_status_error"):false);
        ?>
    
    @if($error)
        <div class="d-inline-block col-6 import-script-error  px-2 float-end text-end">    
            <small class="error text-danger">{{preg_replace('/<br\s*\/?>/', "\n", $error);}}</small> 
        </div>
    @endif
    </div>

   
    </div>

    {{-- <div class="mb-1 d-inline-block col-4">            
        <div class="progress mt-2">
        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="text-right mt-25 d-inline-block progressText" id="progressText">0 / 0</div>  
    </div> --}}
        
    <div class="card">
      {{-- <div class="card-body border-bottom"> --}}
            {!! createDatatableFormFilter($tableFormData) !!}
      {{-- </div> --}}
    </div>

    <div class="modal animated fade show" id="ajaxModel" aria-hidden="true" >

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">

                    <form id="sliderForm" method="POST" name="sliderForm" action="{{ route('addscript') }}"
                        class="needs-validation" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-1">
                            <input type="text" hidden name="id" id="id" value="0">
                            <label class="form-label" for="basic-addon-name">Market Name</label>
                            <select name="market_id" id="market_id" class="form-control  form-select select2">
                                <option value="" disabled selected>Select Market</option>
                                <!-- Placeholder option -->
                                @foreach ($marketdata as $market)
                                    <option value="{{ $market['id'] }}">
                                        {{ $market['market_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="basic-addon-name">Script Name</label>
                            <input type="text" id="script_name" name="script_name" class="form-control"
                                placeholder="Name" aria-label="Name" aria-describedby="basic-addon-name" />
                            <small class="error script_name-error "></small>
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="basic-addon-name">Quantity</label>
                            <input type="text" id="script_quantity" name="script_quantity" class="form-control"
                                placeholder="Quantity" aria-label="Name" aria-describedby="basic-addon-name" />
                            <small class="error script_quantity-error "></small>
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

    <div class="modal animated " id="importmodel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header ">
                    <h4 class="modal-title">Import Scripts</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="importScriptForm" class="level_form" method="POST" name="sliderForm" action="{{ route('import.script') }}"
                    class="needs-validation" enctype="multipart/form-data">
                    @csrf
                
                    <div class="mb-1">                            
                        <label class="form-label" for="basic-addon-name">Import Script</label>
                        <input type="file" name="import_file" class="form-control" 
                            aria-label="Name" aria-describedby="basic-addon-name" />
                        <small class="error import_file-error"></small>  
                    </div>
                
                    <div class="modal-footer px-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="import_file">Import</button>
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

    <script>
        $(document).ready(function() {
            $(document).on('click', '.edit-button', function() {
                const id = $(this).attr('id');
                $.ajax({
                    url: Base_url+'settings/editscript/' + id,
                    method: 'GET',
                    success: function(dataToEdit) {
                    
                        $('#modelHeading').text('Edit Script');
                        $('#scriptid').val(dataToEdit.id);

                        // Set the value of the "Market Name" select field by its option value
                        $('#market_name').val(dataToEdit.market_id);

                        $('#script_name').val(dataToEdit.script_name);
                        $('#script_quantity').val(dataToEdit.script_quantity);

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
        $(document).ready(function() {
            const id = $(this).data('id');

            $("#addscript").click(function() {
                $('#modelHeading').text('Add Script');
                $('.error').html('');

                // Reset the "Market Name" select field to the placeholder option
                $("#market_name").val(null);

                // Show the modal with the id "ajaxModel"
                $("#ajaxModel").modal("show");
            });
        });
    </script>


{{-- <div id="app">
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="uploadModalLabel">Upload Excel</h5>
          </div>
          <div class="modal-body">
            <p class="upload-text">Click to upload</p>
            <input type="file" name="dee" id="uploadDragger">           
            <div class="progress mt-5">
              <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="text-right mt-1" id="progressText">0 / 0</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
   --}}
  
  <script>


// window.Echo.channel('import-channel')
//   .listen('ExcelImportProgressEvent', (event) => {
//       // Update your progress bar using the event data
//       const currentRow = event.current_row;
//       const totalRows = event.total_rows;
//       console.log(event);
//       // Update your progress bar UI here
//   });

  $(document).ready(function () {
    var currentRow = 0;
    var totalRows = 0;
    var progress = 0;
    var reSubmitStatus = 0;

    @if(((int)cache("import_script_current_row")?:0)>0)
        trackProgress();
    @endif

    $(document).on('click','#import_file', function (e) {
        trackProgress();
    });

    function trackProgress() {
      $.get("{{route('import.script-status')}}", function (data) {

        console.log(data,data.started,data.current_row,currentRow,reSubmitStatus,data.current_row == currentRow);
        
        if(data.started && data.current_row == currentRow)
            reSubmitStatus++;
        else 
            reSubmitStatus = 0;

        // if(reSubmitStatus==10)
        // {
        //     console.log("Resubmit", reSubmitStatus);
        //     reSubmitStatus=0;
        //     // $('#importScriptForm').submit();
        // }
        // console.log(data);
        if (data.finished) {
          currentRow = totalRows;
          progress = 100;
          updateProgressUI();

          var imporerr= '';

        //   if(data.imported_all)
        //   {
        //     imporerr = '<div class="d-inline-block col-6 import-script-error  px-2 float-end text-end"><small class="error text-danger">'+((data.error)?data.erro:data.imported_all)+'</small> </div>';   
        //   }
        //   else 
          
          if(data.error)
          imporerr = '<div class="d-inline-block col-6 import-script-error  px-2 float-end text-end"><small class="error text-danger">'+data.error+'</small> </div>';
            
          $('.import-script-progress').after(imporerr);

          $('.import-script-progress').hide();
          $('.import-script-btn').show();

        } else {  
          $('.import-script-progress').show();
          $('.import-script-btn').hide();        
          totalRows = data.total_rows;
          currentRow = data.current_row;
          progress = Math.ceil((data.current_row / data.total_rows) * 100);
          updateProgressUI();
          trackProgress();
          
        }
      });
    }

    function updateProgressUI() {
      $('.progress-bar').css('width', progress + '%');
      $('.progress-bar').text( progress + '%');
      $('.progressText').text("Scripts Importing... "+currentRow + ' / ' + totalRows);
   
      if (progress === 100) {
            // location.reload();
        }
  
        //   if (progress > 0 && progress < 100 && confirm('Do you want to close?')) {
        //     $('#uploadModal').modal('hide');
        //     location.reload();
        //   } else if (progress === 100) {
        //     $('#uploadModal').modal('hide');
        //     location.reload();
        //   }
    }
  
    // Open the modal when the page loads
    // $('#uploadModal').modal('show');
  });
  </script>
@endsection
