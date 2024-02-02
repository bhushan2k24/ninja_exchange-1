@php
$configData = Helper::applClasses();
@endphp
@extends('layouts/fullLayoutMaster')

@section('title', 'Registration')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
<div class="auth-wrapper auth-cover">
  <div class="auth-inner row m-0">
    <!-- Brand logo-->
    <a class="brand-logo" href="#">
      <img
      src="{{site_logo()}}"
      alt="{{setting('site_name')}}" class="h-2rem"/>
      <h2 class="brand-text text-primary ms-1">{{setting('site_name')}}</h2>
    </a>
    <!-- /Brand logo-->

    <!-- Left Text-->
    <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
      <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
        @if($configData['theme'] === 'dark')
          <img class="img-fluid" src="{{asset('images/pages/login-v2-dark.svg')}}" alt="Login V2" />
          @else
          <img class="img-fluid" src="{{asset('images/pages/login-v2.svg')}}" alt="Login V2" />
          @endif
      </div>
    </div>
    <!-- /Left Text-->

    <!-- Login-->
    <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
      <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
        <h2 class="card-title fw-bold mb-1">Welcome to {{setting('site_name')}}! 👋</h2>
        <p class="card-text mb-2">Please sign-in to your account and start the adventure</p>
        {!! createFormHtmlContent($loginFormData) !!}

        {{-- <div class="mb-1">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="register-privacy-policy" tabindex="4" />
            <label class="form-check-label" for="register-privacy-policy">
              I agree to <a href="#">privacy policy & terms</a>
            </label>
          </div>
        </div> --}}
  
        <p class="text-center mt-2">
          <span>Already have an account?</span>
          <a href="{{route('auth-login')}}">
            <span>Sign in instead</span>
          </a>
        </p>
        
      </div>

      
    </div>
    <!-- /Login-->
  </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
<script src="{{asset(mix('vendors/helper/js/form.min.js'))}}"></script>
@endsection
