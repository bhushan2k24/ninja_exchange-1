@extends('content/settings/settingsMaster')

@section('title', 'Panel')

@section('content')
<style>
  .form-group [type="color"]{
    width: 100%;
    height: 35px;
  }
</style>
<div class="row">
  <div class="col-12">
    @include('content.settings.settings-tabs-btns')

    <!-- profile -->
    <div class="card">
      <div class="card-header border-bottom">
        <h4 class="card-title">Design Details</h4>
      </div>
      <div class="card-body py-2 my-25">
        <!-- form -->
        <form class="validate-form"  method="post" action="{{route('store.design-settings')}}">
          <div class="row">
            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 pb-2">
                <label>Site TopBar Header Background<span class="text-danger">*</span></label>
                <label for="site_topbar_header_background_color"></label><br>
                <input type="color" class="headerwidth" id="site_topbar_header_background_color" name="site_topbar_header_background_color" value="{{setting('site_topbar_header_background_color')}}" onchange="updatecolor('--topbar-header-background',this.value);"><br><br>
            </div>
            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 pb-2">
                <label>Site Page Logo&Title Background<span class="text-danger">*</span></label>
                <label for="site_page_title_background_color"></label><br>
                <input type="color" class="headerwidth" id="site_page_title_background_color" name="site_page_title_background_color" value="{{setting('site_page_title_background_color')}}" onchange="updatecolor('--page-title-background',this.value);"><br><br>
            </div>
            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 pb-2">
                <label>Site Sidebar Background<span class="text-danger">*</span></label>
                <label for="site_sidebar_background_color"></label><br>
                <input type="color" class="headerwidth" id="site_sidebar_background_color" name="site_sidebar_background_color" value="{{setting('site_sidebar_background_color')}}" onchange="updatecolor('--sidebar-background',this.value);"><br><br>
            </div>
            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 pb-2">
                <label>Site TopBar Header Color<span class="text-danger">*</span></label>
                <label for="site_topbar_header_color"></label><br>
                <input type="color" class="headerwidth" id="site_topbar_header_color" name="site_topbar_header_color" value="{{setting('site_topbar_header_color')}}" onchange="updatecolor('--topbar-header-text',this.value);"><br><br>
            </div>
            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 pb-2">
                <label>Site Page Logo Title Color<span class="text-danger">*</span></label>
                <label for="site_page_title_color"></label><br>
                <input type="color" class="headerwidth" id="site_page_title_color" name="site_page_title_color" value="{{setting('site_page_title_color')}}" onchange="updatecolor('--page-title-text',this.value);"><br><br>
            </div>
            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 pb-2">
                <label>Site Sidebar Text Color<span class="text-danger">*</span></label>
                <label for="site_sidebar_color"></label><br>
                <input type="color" class="headerwidth" id="site_sidebar_color" name="site_sidebar_color" value="{{setting('site_sidebar_color')}}" onchange="updatecolor('--sidebar-text',this.value);"><br><br>
            </div>
            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 pb-2 ">
                <!-- <laSite Sidebar Backgroundpan class="text-danger">*</span></label> -->
                <label>Site Animation modal<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-control  animation-control text" name="site_modal_action" id="site_modal_action" >
                        <option value="">Not Selected</option>
                        <option value="flash">flash</option>
                        <option value="pulse">pulse</option>
                        <option value="rubberBand">rubberBand</option>
                        <option value="shake">shake</option>
                        <option value="swing">swing</option>
                        <option value="tada">tada</option>
                        <option value="wobble">wobble</option>
                        <option value="jello">jello</option>
                        <option value="bounceIn">bounceIn</option>
                        <option value="bounceInDown">bounceInDown</option>
                        <option value="bounceInLeft">bounceInLeft</option>
                        <option value="bounceInRight">bounceInRight</option>
                        <option value="bounceInUp">bounceInUp</option>
                        <option value="fadeIn">fadeIn</option>
                        <option value="fadeInDown">fadeInDown</option>
                        <option value="fadeInDownBig">fadeInDownBig</option>
                        <option value="fadeInLeft">fadeInLeft</option>
                        <option value="fadeInLeftBig">fadeInLeftBig</option>
                        <option value="fadeInRight">fadeInRight</option>
                        <option value="fadeInRightBig">fadeInRightBig</option>
                        <option value="fadeInUp">fadeInUp</option>
                        <option value="fadeInUpBig">fadeInUpBig</option>
                        <option value="flip">flip</option>
                        <option value="flipInX">flipInX</option>
                        <option value="flipInY">flipInY</option>
                        <option value="lightSpeedIn">lightSpeedIn</option>
                        <option value="rotateIn">rotateIn</option>
                        <option value="rotateInDownLeft">rotateInDownLeft</option>
                        <option value="rotateInDownRight">rotateInDownRight</option>
                        <option value="rotateInUpLeft">rotateInUpLeft</option>
                        <option value="rotateInUpRight">rotateInUpRight</option>
                        <option value="slideInUp">slideInUp</option>
                        <option value="slideInDown">slideInDown</option>
                        <option value="slideInLeft">slideInLeft</option>
                        <option value="slideInRight">slideInRight</option>
                        <option value="zoomIn">zoomIn</option>
                        <option value="zoomInDown">zoomInDown</option>
                        <option value="zoomInLeft">zoomInLeft</option>
                        <option value="zoomInRight">zoomInRight</option>
                        <option value="zoomInUp">zoomInUp</option>
                        <option value="rollIn">rollIn</option>
                    </select>
                    <button class="btn btn-primary waves-effect waves-ligh triggerAnimation " type="button">Click Me</button>

                </div>
                <span class="span-error" id="site_modal_action-error"></span>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12  ">
              <label>&nbsp;</label>
                <h4 id="animationSandbox" class="d-block border p-50 show text-center border-3">Your Animation</h4>
            </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary mt-1 me-1 ">Save changes</button>
            {{-- <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button> --}}
          </div>
        </div>
        </form>
        <!--/ form -->
      </div>
    </div>
@endsection

@section('page-script')
  <!-- Page js files -->
  <script>
    var html = '';
    $("#site_modal_action option").map(function() {
        if ('<?= setting('site_modal_action') ? setting('site_modal_action') : '' ?>' == this.value) {
            html += '<option selected value="' + this.value + '">' + this.text + '</option>';
        } else {
            html += '<option value="' + this.value + '">' + this.text + '</option>';
        }
    });
    $('#site_modal_action').html(html);

    $(document).ready(function() {
    $('.triggerAnimation').on('click', function() {
        var selectedAnimation = $('#site_modal_action').val();
        $('#animationSandbox').addClass('d-block border p-50 text-center border-3 show ' + selectedAnimation);
    });
});
  </script>
@endsection



