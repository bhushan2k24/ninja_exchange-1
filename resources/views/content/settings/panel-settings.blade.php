@extends('content/settings/settingsMaster')

@section('title', 'Panel')

@section('content')
<div class="row">
  <div class="col-12">
    @include('content.settings.settings-tabs-btns')

    <!-- profile -->
    <div class="card">
      <div class="card-header border-bottom">
        <h4 class="card-title">Panel Details</h4>
      </div>
      <div class="card-body py-2 my-25">
        <!-- form -->
        <form class="validate-form"  method="post" action="{{route('store.panel-settings')}}">

        <div class="row">
          <!-- header section -->
          <div class="col-12 col-sm-6 mb-1">
            <h4 class="mb-75">Logo</h4>
            <div class="d-flex">
              <a href="#" class="me-25"for="site_logo_base">
                <img
                  src="{{site_logo()}}"
                  for="site_logo_base"
                  id="site_logo-img"
                  class="uploadedAvatar rounded me-50"
                  alt="Site Logo"
                  height="100"
                  width="200"
                />
              </a>
              <!-- upload and reset button -->
              <div class="d-flex align-items-end mt-75 ms-1">
                <div>
                  <label for="site_logo_base" class="btn btn-sm btn-primary mb-75 me-75">Upload</label>
                  <input type="file"  class="base_file_upload" name="site_logo_base" id="site_logo_base" hidden accept="image/*" />
                  {{-- <button type="button" id="logo-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button> --}}
                  <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                </div>
              </div>
              <!--/ upload and reset button -->
            </div>
          </div>

          <div class="col-12 col-sm-6 mb-1">
            <h4 class="mb-75">Favicon</h4>
            <div class="d-flex">
              <a href="javascript:void(0);" class="me-25"  for="site_favicon_logo_base">
                <img
                  src="{{site_favicon_logo()}}"
                  for="site_favicon_logo_base"
                  id="site_favicon_logo-img"
                  class="uploadedAvatar rounded me-50"
                  alt="Site Logo"
                  height="100"
                  width="100"
                />
              </a>
              <!-- upload and reset button -->
              <div class="d-flex align-items-end mt-75 ms-1">
                <div>
                  <label for="site_favicon_logo_base" class="btn btn-sm btn-primary mb-75 me-75">Upload</label>
                  <input type="file" class="base_file_upload"  name="site_favicon_logo_base" id="site_favicon_logo_base" hidden accept="image/*" />
                  {{-- <button type="button" id="favicon-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button> --}}
                  <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                </div>
              </div>
              <!--/ upload and reset button -->
            </div>
          </div>
        </div>
        <!--/ header section -->

        <div class="row mt-2 pt-50">
          <div class="col-12 col-sm-6 mb-1">
            <label class="form-label" for="site_name">Site Name</label>
            <input
              type="text"
              class="form-control"
              id="site_name"
              name="site_name"
              placeholder="Site Name"
              value="{{setting('site_name')}}"
              data-msg="Please enter site name"
            />
          </div>
          <div class="col-12 col-sm-6 mb-1">
            <label class="form-label" for="site_link">Site URL</label>
            <input
              type="text"
              class="form-control"
              id="site_link"
              name="site_link"
              placeholder="{{url('')}}"
              value="{{url('')}}"
              data-msg="Please enter Site URL"
              readonly
            />
          </div>
          <div class="col-12 col-sm-6 mb-1">
            <label class="form-label" for="accountPhoneNumber">Phone Number</label>
            <input
              type="text"
              class="form-control account-number-mask"
              id="site_contact"
              name="site_contact"
              placeholder="Phone Number"
              value="{{setting('site_contact')}}"
            />
          </div>
          <div class="col-12 col-sm-6 mb-1">
            <label class="form-label" for="accountPhoneNumber">Whatsapp Number</label>
            <input
              type="text"
              class="form-control account-number-mask"
              id="site_whattsapp_number"
              name="site_whattsapp_number"
              placeholder="Phone Number"
              value="{{setting('site_whattsapp_number')}}"
            />
          </div>
          <div class="col-12 col-sm-6 mb-1">
            <label class="form-label" for="site_email">Email</label>
            <input
              type="email"
              class="form-control"
              id="site_email"
              name="site_email"
              placeholder="Site Email"
              value="{{setting('site_email')}}"
            />
          </div>
          <div class="col-12 col-sm-6 mb-1">
            <label class="form-label" >Address</label>
            <input type="textarea" class="form-control" id="site_postal_address" name="site_postal_address" placeholder="Your Address" value="{{setting('site_postal_address')}}" />
          </div>
          <div class="col-12 col-sm-6 mb-1">
            <label class="form-label" >Site Desctiption</label>
            <input type="textarea" class="form-control" id="site_description" name="site_description" placeholder="Your Address" value="{{setting('site_description')}}" />
          </div>
          <div class="col-12 col-sm-6 mb-1">
            <label class="form-label" >Site About Us</label>
            <input type="textarea" class="form-control" id="site_about_us" name="site_about_us" placeholder="Your Address" value="{{setting('site_about_us')}}" />
          </div>
          <div class="col-12 col-sm-6 mb-1">
            <label class="form-label" >Site Keywords</label>
            <input type="textarea" class="form-control" id="site_keywords" name="site_keywords" placeholder="Your Address" value="{{setting('site_keywords')}}" />
          </div>
          <div class="col-12 col-sm-3 mb-1">
            <label class="form-label" >Site Status</label>
            <div class="radio radio-single ">
              <input type="radio" id="site_status_publish" value="publish" name="site_status" aria-label="Publish"  class="form-check-input" {{ setting('site_status') == 'publish' ? 'checked' : '' }}>
              <label for="site_status_publish">Publish</label>
            </div>
            <div class="radio radio-success radio-single">
              <input type="radio" id="site_status_unpublish" value="unpblish" name="site_status"  aria-label="Unpublish"  class="form-check-input" {{ setting('site_status') == 'unpblish' ? 'checked' : '' }}>
              <label for="site_status_unpublish">Unpublish</label>
            </div>
          </div>
          <div class="col-12 col-sm-3 mb-1">
            <label class="form-label" >Site Security Status</label>
            <div class="radio radio-single  ">
              <input type="radio" id="site_security_on" class="form-check-input" value="on" name="site_security" aria-label="ON" {{ setting('site_security') == 'on' ? 'checked' : '' }}>
              <label for="site_security_on">ON</label>
            </div>
            <div class="radio radio-success radio-single">
              <input type="radio" id="site_security_off" class="form-check-input" value="off" name="site_security" aria-label="OFF" {{ setting('site_security') == 'off' ? 'checked' : '' }}>
              <label for="site_security_off">OFF</label>
            </div>
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary mt-1 me-1">Save changes</button>
            {{-- <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button> --}}
          </div>
        </div>
        </form>
        <!--/ form -->
      </div>
    </div>
@endsection



