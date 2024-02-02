@extends('content/settings/settingsMaster')

@section('title', 'Panel')

@section('content')
<div class="row">
  <div class="col-12">
    @include('content.settings.settings-tabs-btns')

    <!-- profile -->
    <div class="card">
      <div class="card-header border-bottom">
        <h4 class="card-title">Mail Details</h4>
      </div>
      <div class="card-body py-2 my-25">
        <!-- form -->
        <form class="validate-form"  method="post" action="{{route('store.mail-settings')}}">

          @csrf  
                <div class="row">
                  <div class="col-md-6 col-12">
                      <label class="form-label" for="site_mail_host">Site Mail Host</label><br>
                      <div class="input-group input-group-lg mb-1">
                          <span class="input-group-text"><i data-feather='align-justify'></i></span>
                          <input type="text" name="site_mail_host" id="site_mail_host" class="form-control form-control-lg" placeholder="Enter Site Email" value="{{setting('site_mail_host')}}"/>
                      </div>
                  </div>
                  <div class="col-md-6 col-12">
                      <label class="form-label" for="site_mail_username">Site Mail Username</label><br>

                      <div class="input-group input-group-lg mb-1">
                          <span class="input-group-text"><i data-feather='mail'></i></span>
                          <input type="text" name="site_mail_username" id="site_mail_username" class="form-control form-control-lg" placeholder="Enter Site Contact" value="{{setting('site_mail_username')}}"/>
                      </div>
                  </div>
                  <div class="col-md-6 col-12">
                      <label class="form-label" for="site_mail_password">Site Mail Password</label><br>

                      <div class="input-group input-group-lg mb-1">
                          <span class="input-group-text"><i data-feather='lock'></i></span>
                          <input type="text" name="site_mail_password" id="site_mail_password" class="form-control form-control-lg" placeholder="Enter Site Contact" value="{{setting('site_mail_password')}}"/>
                      </div>
                  </div>
                  <div class="col-md-6 col-12">
                      <label class="form-label" for="site_mail_port">Site Mail Port</label><br>

                      <div class="input-group input-group-lg mb-1">
                          <span class="input-group-text"><i data-feather='paperclip'></i></span>
                          <input type="text" name="site_mail_port" id="site_mail_port" class="form-control form-control-lg" placeholder="Enter Site Email" value="{{setting('site_mail_port')}}"/>
                      </div>
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
@endsection



