@extends('content/settings/settingsMaster')

@section('title', 'Panel')

@section('content')
<div class="row">
  <div class="col-12">
    @include('content.settings.settings-tabs-btns')

    <!-- security -->
    <div class="card">
      <div class="card-header border-bottom">
        <h4 class="card-title">Change Password</h4>
      </div>
      <div class="card-body pt-1">

        <!-- form -->
        <form class="validate-form" method="post" action="{{route('store.security-settings')}}">
          <div class="row">
            <div class="col-12 col-sm-6 mb-1">
              <label class="form-label" for="account-old-password">Current password</label>
              <div class="input-group form-password-toggle input-group-merge">
                <input
                  type="password"
                  class="form-control"
                  id="current_password"
                  name="current_password"
                  placeholder="Enter current password"
                  data-msg="Please current password"
                />
                <div class="input-group-text cursor-pointer">
                  <i data-feather="eye"></i>
                </div>
              </div>
              <small class="error current_password-error"></small>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-6 mb-1">
              <label class="form-label" for="account-new-password">New Password</label>
              <div class="input-group form-password-toggle input-group-merge">
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="form-control"
                  placeholder="Enter new password"
                />
                <div class="input-group-text cursor-pointer">
                  <i data-feather="eye"></i>
                </div>
              </div>
              <small class="error password-error"></small>
            </div>
            <div class="col-12 col-sm-6 mb-1">
              <label class="form-label" for="account-retype-new-password">Retype New Password</label>
              <div class="input-group form-password-toggle input-group-merge">
                <input
                  type="password"
                  class="form-control"
                  id="retype_password"
                  name="retype_password"
                  placeholder="Confirm your new password"
                />
                <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
              </div>
              <small class="error retype_password-error"></small>
            </div>
            <div class="col-12">
              <p class="fw-bolder">Password requirements:</p>
              <ul class="ps-1 ms-25">
                <li class="mb-50">Minimum 6 characters long - the more, the better</li>
                <li class="mb-50">At least one lowercase character</li>
                <li>At least one number, symbol, or whitespace character</li>
              </ul>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary me-1 mt-1">Save changes</button>
              <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button>
            </div>
          </div>
        </form>
        <!--/ form -->

      </div>
    </div>
    <!--/ security -->
  </div>
</div>
@endsection


