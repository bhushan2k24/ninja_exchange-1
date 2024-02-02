@php($ajaxformsubmit = true)
@extends('layouts/contentLayoutMaster')

@section('title', 'Profile')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">

  <link rel='stylesheet' href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
  
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
@section('content')
    <!-- profile -->
    <div class="card">
      <div class="card-header border-bottom">
        <h4 class="card-title">Profile Details</h4>
      </div>
      <div class="card-body py-2 my-25 ">

        <!-- form -->
        <form class="validate-form"  id="edit_profile" method="post" action="{{route('profile.store')}}"  enctype="multipart/form-data">
          <div class="row">
            <!-- header section -->
            <div class="d-flex col-6">
              <a href="#" class="me-25">
                <?php 

                $image = URL.USER.Auth::user()->profile_picture;
                $name = Auth::user()->name;
                $Initials = getInitials($name);
                $randomState = getRandomColorState();
                $user_Profile = ' <span class="avatar-content">'.$Initials.'</span>';                
                $user_Profile = url_file_exists($image)?                
                '<img src="'.$image.'" alt="'.$name.'" class="uploadedAvatar  rounded " height="100"
                  width="100"   id="account-upload-img" name="account-upload-img">':$user_Profile;                
                $user_Profile = '<div class="avatar bg-light-primary avatar-xxl rounded me-50 border-2 border-white-0">'.$user_Profile.'</div>';
              ?>
              {!!$user_Profile!!}
                
              </a>
              <!-- upload and reset button -->
              <div class="d-flex align-items-end mt-75 ms-1">
                <div>
                  <label for="account-upload" class="btn btn-sm btn-primary mb-75 me-75">Upload</label>
                  <input type="file" name="profile_image" id="account-upload" hidden accept="image/*" />
                  <button type="button" id="account-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                  <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                </div>
              </div>
              <!--/ upload and reset button -->          
            </div>
    
            <div class="d-flex justify-content-end col-6">
              <div class="bg-light-secondary position-relative rounded p-2">
                <div class="d-flex align-items-center flex-wrap">
                  <h4 class="mb-1 me-1">Your Referral Link</h4>
                  <span class="badge badge-light-primary mb-1">To Get benefits</span>
                </div> 

                {{-- benefire --}}
                <h6 class="d-flex align-items-center fw-bolder">
                  <span class="me-50">{{Auth::user()->referral_link}}</span>
                  <span class="copyThis" data-copy="{{Auth::user()->referral_link}}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy font-medium-4 cursor-pointer"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></span>
                </h6>
                {{-- <span>Created on 28 Apr 2021, 18:20 GTM+4:10</span> --}}
              </div>
            </div>
          </div>
          <div class="row  mt-2 pt-50">
            <div class="col-12 col-sm-6 mb-1">
              <label class="form-label" for="accountFirstName">Name</label>
              <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                placeholder="Name"
                value="{{auth('admin')->user()->name}}"
                data-msg="Please enter first name"
              />
            </div>
            <div class="col-12 col-sm-6 mb-1">
              <label class="form-label" for="accountLastName">Your Login Code</label>
              <input
                type="text"
                class="form-control "
                placeholder="Login Code"
                value="{{auth('admin')->user()->usercode}}" readonly
              />
            </div>
            <div class="col-12 col-sm-6 mb-1">
              <label class="form-label" for="mobile">Mobile</label>
              <input
                type="text"
                class="form-control account-number-mask"
                id="mobile"
                name="mobile"
                placeholder="Mobile Number"
                value="{{decrypt_to(auth('admin')->user()->mobile)}}"
              />
            </div>
            <div class="col-12 col-sm-6 mb-1">
              <label class="form-label" for="accountEmail">Email</label>
              <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Email"
                value="{{decrypt_to(auth('admin')->user()->email)}}"
              />
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary mt-1 me-1">Save changes</button>
              <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button>
            </div>
          </div>
        </form>
        <!--/ form -->
      </div>
    </div>
    <!--/ profile -->

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
              <button type="submit" class="btn btn-primary me-1 mt-1">Update Password</button>
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

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.in.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/pages/page-account-settings-account.js')) }}"></script>

  <script>
    // Get a reference to the link element
    // const linkElement = $('.copyThis');

    // Add a click event listener
    $(document).on('click','.copyThis', function (event) {

        var isRtl = $('html').attr('data-textdirection') === 'rtl';
        // Prevent the default link behavior (navigation)
        event.preventDefault();

        // Get the link URL from the data attribute
        const linkToCopy = $(this).attr('data-copy');

        // Create a temporary input element and set its value to the link URL
        const tempInput = document.createElement('input');
        tempInput.value = linkToCopy;

        // Append the input element to the document
        document.body.appendChild(tempInput);

        // Select the text in the input element
        tempInput.select();

        // Copy the selected text to the clipboard
        document.execCommand('copy');

        // Remove the temporary input element
        document.body.removeChild(tempInput);

        // Optionally, provide feedback to the user
        toastr['success'](linkToCopy, 'copied to clipboard!', {
          positionClass: 'toast-top-right',
        });

        // alert('Link copied to clipboard: ' + linkToCopy);
    });
</script>
@endsection
