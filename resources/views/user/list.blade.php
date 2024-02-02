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
      {{-- <div class="card-body border-bottom"> --}}
            {!! createDatatableFormFilter($userListFormData) !!}
      {{-- </div> --}}

      {{-- <div class="card-body">  
        <div class="row mb-2 mt-2">
          <div class="col-md-12"> 
            <div class="card-datatable table-responsive">
              <table class="table">
                <thead>
                  <tr role="row">
                    <th>Name</th>
                    <th>Login Id</th>
                  <?php
                  if ($type == 'user')
                  {
                    echo
                    '<th>Broker</th>
                    <th>Master</th>';
                  }
                  if ($type == 'master')
                  {
                    echo
                    '<th>Parent</th>
                    <th>Percentage</th>
                    <th>Master U</th>
                    <th>User U</th>
                    <th>Broker U</th>';
                  }
                  ?>
                    <th>Action</th>
                    <th>Login Time</th>
                    <th>Login IP</th>
                    <th>Join Date</th>
                  </tr>
                </thead>
                <?php
                if($type == 'user')
                {?>
                <tbody>
                  <tr>
                    <td><span style="cursor:pointer" title="Edit">Ansari Md Arman</span></td>
                    <td>364519</td>
                    <td>ONLINE BROKER (985753)<br><br></td>
                    <td>ONLINE MASTER(958625)<br></td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td>2023-07-26 10:25:44</td>
                  </tr>
                  <tr>
                    <td><span style="cursor:pointer" title="Edit">BHOLI DEMO</span></td>
                    <td>787878</td>
                    <td><br><br></td>
                    <td>DEMO MASTER(447955)</td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td>2023-07-26 17:07:32</td>
                    <td>2401:4900:5635:92ac:484f:3841:b7f6:4ce3</td>
                    <td>2023-07-20 11:45:10</td>
                  </tr>
                  <tr>
                    <td><span style="cursor:pointer" title="Edit">DEMO 1</span></td>
                    <td>697728</td>
                    <td><br><br></td>
                    <td>DEMO MASTER(447955)</td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td>2023-07-20 11:50:49</td>
                  </tr>
                  <tr>
                    <td><span style="cursor:pointer" title="Edit">DEMO 2</span></td>
                    <td>605588</td>
                    <td><br><br></td>
                    <td>DEMO MASTER(447955)</td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td>2023-07-20 11:53:11</td>
                  </tr>
                  <tr>
                    <td><span style="cursor:pointer" title="Edit">DEMO 3</span></td>
                    <td>878373</td>
                    <td><br><br></td>
                    <td>DEMO MASTER(447955)</td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td>2023-07-20 20:30:33</td>
                  </tr>
                  <tr>
                    <td><span style="cursor:pointer" title="Edit">DEMO 4</span></td>
                    <td>761716</td>
                    <td><br><br></td>
                    <td>DEMO MASTER(447955)</td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td>2023-07-20 20:22:58</td>
                  </tr>
                  <tr>
                    <td><span style="cursor:pointer" title="Edit">DEMO 5</span></td>
                    <td>479772</td>
                    <td><br><br></td>
                    <td>DEMO MASTER(447955)</td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td>2023-08-18 18:34:12</td>
                    <td>2401:4900:53f2:fefa:aca1:32f2:c859:8338</td>
                    <td>2023-07-22 13:39:36</td>
                  </tr>
                </tbody>
                <?php
                }
                if($type == 'master')
                {?>
                <tbody>
                  <tr>
                    <td>DEMO</td>
                    <td>397574</td>
                    <td></td>
                    <td>20</td>
                    <td><span style="cursor:pointer"></span></td>
                    <td><span style="cursor:pointer"></span></td>
                    <td><span style="cursor:pointer"></span></td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td>2023-07-26 17:57:53</td>
                    <td>49.36.81.91</td>
                    <td>2023-07-26 17:54:57</td>
                  </tr>
                  <tr>
                    <td>DEMO MASTER</td>
                    <td>447955</td>
                    <td></td>
                    <td>0</td>
                    <td><span style="cursor:pointer">0</span></td>
                    <td><span style="cursor:pointer">7</span></td>
                    <td><span style="cursor:pointer">0</span></td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td>2023-08-03 11:59:02</td>
                    <td>2401:4900:1f3e:2492:fd1f:687a:1c07:ad0b</td>
                    <td>2023-07-20 11:26:26</td>
                  </tr>
                  <tr>
                    <td>ONLINE MASTER</td>
                    <td>958625</td>
                    <td></td>
                    <td>100</td>
                    <td><span style="cursor:pointer">0</span></td>
                    <td><span style="cursor:pointer">4</span></td>
                    <td><span style="cursor:pointer">1</span></td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td>2023-07-18 15:45:03</td>
                    <td>2401:4900:1c80:f281:d117:6890:6c43:a3d</td>
                    <td>2022-04-20 11:13:04</td>
                  </tr>
                </tbody>
                <?php
                }
                if($type == 'broker')
                {?>
                <tbody>
                  <tr>
                    <td>ONLINE BROKER</td>
                    <td>985753</td>
                    <td>958625</td>
                    <td><span style="cursor:pointer">4</span></td>
                    <td>0.06</td>
                    <td>0.00</td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td>103.182.164.182</td>
                    <td>2023-01-16 21:09:45</td>
                    <td>2022-04-20 11:15:00</td>
                  </tr>
                  <tr>
                    <td>TEST</td>
                    <td>531669</td>
                    <td>776648</td>
                    <td><span style="cursor:pointer">0</span></td>
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>
                      <div class="d-flex">
                        <button  type="button" title="Invoices" class="btn btn-gradient-info" style="width:fit-content;padding:6px;margin:5px;">L</button>
                        <button  type="button" title="Reset Password" class="btn btn-gradient-warning" style="width:fit-content;padding:6px;margin:5px;">R</button>
                        <button id="user3842"  type="button" title="Change Status" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">A</button>
                        <button id="user3842"  type="button" title="Clear Login Attempt" class="btn btn-gradient-success" style="width:fit-content;padding:6px;margin:5px;">CL</button><button id="user3842"  type="button" title="Refresh Margin" class="btn btn-gradient-primary" style="width:fit-content;padding:6px;margin:5px;">M</button>
                      </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td>2023-07-26 18:16:34</td>
                  </tr>
                </tbody>
                <?php
                }?>
              </table>
            </div>
          </div>
        </div>
      </div> --}}
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
  </script>
@endsection