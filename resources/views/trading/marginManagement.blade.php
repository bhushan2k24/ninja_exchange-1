@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('content')
<section id="basic-vertical-layouts">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <?= createFormHtmlContent($marginManagementFormData)?>
          </div>
        <div class="row mb-2">
          <div class="col-md-12">
            <div class="card-datatable table-responsive">
              <table class=" table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>NSEFUT</th>
                    <th>MCXFUT</th>
                    <th>NSEOPT</th>
                    <th>GLOBAL</th>
                    <th>NSEEQT</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th>DEMO (397574) (M)</th>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                  </tr>
                  <tr>
                    <th>DEMO MASTER (447955) (M)</th>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                  </tr>
                  <tr>
                    <th>ONLINE MASTER (958625) (M)</th>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                    <td>0 / 0</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                      <th>Total</th>
                      <th>0 / 0</th>
                      <th>0 / 0</th>
                      <th>0 / 0</th>
                      <th>0 / 0</th>
                      <th>0 / 0</th>
                      <th>0 / 0</th>
                    </tr>
                </tfoot>
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

@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset(mix('js/scripts/tables/table-datatables-advanced.js')) }}"></script>
@endsection