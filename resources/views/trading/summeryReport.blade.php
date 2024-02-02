@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('content')
<section id="basic-vertical-layouts">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <?= createFormHtmlContent($summeryReportFormData)?>
        </div>
        <div class="row mb-2">
          <div class="col-md-12">
            <div class="card-datatable table-responsive">
              <table class="dt-responsive table">
                <thead>
                  <tr>
                    <th>Sr No.</th>
                    <th>Name</th>
                    <th>All</th>
                    <th>Outstanding</th>
                    <th>Net MTM</th>
                    <th>Total MTM</th>
                    <th>Self MTM</th>
                    <th>Net Position</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>                    
                    <td>1</td> 
                    <td>ZZZ-Total</td> 
                    <td><i class="fa-regular fa-file-pdf"></i></td>
                    <td><i class="fa-regular fa-file-pdf"></i></td> 
                    <td>0.00</td> 
                    <td>0.00</td> 
                    <td>0.00</td> 
                    <td><i class="fa-regular fa-file-pdf"></i></td> 
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
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