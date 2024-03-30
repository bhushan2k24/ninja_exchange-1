@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('content')
<style type="text/css">
  .datatable-switch.form-check .form-check-input
  {
    height: 32px !important;
    /* background: #7367f0 !important;
    background: #7367f030 !important; */
    width: 32px !important;
  }
  .datatable-switch.form-check .form-check-label .switch-icon-left {
    left: 9px !important;
    top: 5px !important;
    color: #7367f0 !important;
  }
  .datatable-switch.form-check .form-check-label .switch-icon-right {
    left: 9px !important;
    top: 5px !important;
    color: #e7e6ec !important;
  } 
  .datatable-switch.form-check .form-check-input{
    background-image: none !important;
  }
  .form-check-primary .form-check-input:checked {
      border-color: #7367f0;
      background-color: #665dd540;
      color: #7367f0 !important;
  } 
  .datatable-switch.form-check .form-check-label .switch-icon-right 
  {
    color : #ea5455 !important;
  }
  .form-switch .form-check-input:not(:checked) {
    background-color:rgba(234, 84, 85, 0.12) !important;
}
</style>
<section class="app-user-list">
    <div class="card">
            {!! createDatatableFormFilter($userListFormData) !!}      
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
    var settings = {
  "url": "https://astroway.diploy.in/public/storage/images/astrologer_1021709096508.png",
  "method": "GET",
  "timeout": 0,
};

$.ajax(settings).done(function (response) {
  console.log(response);
});

    $(".select2-ajax-user_dropdown").select2({
    ajax: {
      url: "{{route('getRoleWiseUserlist')}}",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          q: params.term, // search term
          user_position:$(this).attr("data-type"), // search term
          page: params.page || 1
        };
      },
      processResults: function (data, params) {
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
    escapeMarkup: function (es) {
      return es;
    }
  });
  function RepoSelection(option) {
      if (!option.id) {
        return option.text;
      }
      var $person =
        `<div class="d-flex align-items-center p-0"><div class="avatar me-1 avatar-sm"><span class="avatar-content bg-${getRandomColorState()}">${getInitials(option.name)}</span></div><p class="mb-0">` +option.name +`</p></div>`;
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