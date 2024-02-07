@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('content')
<section id="basic-vertical-layouts">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
   
        <?= createDatatableFormFilter($portfolioFormData)?>
       
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