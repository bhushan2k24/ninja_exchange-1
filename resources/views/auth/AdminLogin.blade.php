@php
$configData = Helper::applClasses();
@endphp
@extends('layouts/fullLayoutMaster')

@section('title', 'Admin Login')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')

<div class="auth-wrapper auth-basic px-2">
  <div class="auth-inner my-2">
    <!-- Login basic -->
    <div class="card mb-0">
      <div class="card-body">
        <a href="#" class="brand-logo">
          <img
          src="{{site_logo()}}"
          alt="{{setting('site_name')}}" class="h-2rem"/>
          <h2 class="brand-text text-primary ms-1">{{setting('site_name')}}</h2>
        </a>

        <h4 class="card-title mb-1">Welcome to {{setting('site_name')}}! 👋</h4>
        <p class="card-text mb-2">Please sign-in to your account and start the adventure</p>

        <?= createFormHtmlContent($loginFormData)?>
      </div>
    </div>
    <!-- /Login basic -->
  </div>
</div>

@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
<script src="{{asset('vendors/helper/js/form.min.js')}}"></script>
@endsection
