@php
$configData = Helper::applClasses();
$sidebarData = sideContentData();
@endphp
<div
  class="main-menu menu-fixed {{ $configData['theme'] === 'dark' || $configData['theme'] === 'semi-dark' ? 'menu-dark' : 'menu-light' }} menu-accordion menu-shadow"
  data-scroll-to-active="true">
  <div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
      <li class="nav-item me-auto">
        <a class="navbar-brand" href="{{ url('/') }}">
          <span class="brand-logo">
            <img
            src="{{site_logo()}}"
            alt="{{setting('site_name')}}"/>
          </span>
          <h2 class="brand-text">{{setting('site_name')}}</h2>
        </a>
      </li>
      <li class="nav-item nav-toggle">
        <a class="nav-link modern-nav-toggle pe-0" data-toggle="collapse">
          <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
          <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc"
            data-ticon="disc"></i>
        </a>
      </li>
    </ul>
  </div>
  <div class="shadow-bottom"></div>
  <div class="main-menu-content">
    <ul class="navigation navigation-main text-capitalize" id="main-menu-navigation" data-menu="menu-navigation">

    <?php
    if (!empty($sidebarData))
    {
      foreach($sidebarData as $value)
      {
        $is_open = '';
        array_map(function ($childValue) use (&$is_open) {
            $link_attribute = $childValue['link_attribute'] ?? [];
            $is_open = (url()->current() === route($childValue['link'], $link_attribute)) ? "open" : $is_open;
        }, $value['childData']);
        $link_attribute = isset($value['link_attribute'])?$value['link_attribute']:[] ;
        echo
        '<li class="nav-item '.(!empty($value['childData']) ? 'has-sub' : '').' '.(!empty($value['open']) ? 'opens' : '').' '.$is_open.' '.( Route::has($value['link']) && url()->current() === Route($value['link'],$link_attribute) ? "active" :  "" ).'">
              <a href="'.(Route::has($value['link'])? route($value['link']) : 'javascript:void(0)').'" class="d-flex align-items-center " target="_self">
                  <i data-feather="'.$value['icon'].'"></i>
                  <span class="menu-title text-truncate">'.$value['label'].'</span>
                  <!-- <span class="badge badge-light-warning rounded-pill ms-auto me-1">2</span> -->
              </a>';
            if (!empty($value['childData']))
            {
              
              echo
              '<ul class="menu-content">';
              foreach($value['childData'] as $childValue)
              {
                $link_attribute = isset($childValue['link_attribute'])?$childValue['link_attribute']:[] ; 
                echo
                '<li class="'.( url()->current() === Route($childValue['link'],$link_attribute) ? "active" :  "" ).'">
                      <a href="'.(Route::has($childValue['link'])? route($childValue['link'],$link_attribute) : 'javascript:void(0)').'" class="d-flex align-items-center " target="_self">
                          <i data-feather="'.$childValue['icon'].'"></i>
                          <span class="menu-item text-truncate">'.$childValue['label'].'</span>
                      </a>
                  </li>';
              }
              echo
              '</ul>';
            }
          echo 
          '</li>';
      }
    }?>
    
      {{-- Foreach menu item starts --}}
      {{-- @if (isset($menuData[0]))
        @foreach ($menuData[0]->menu as $menu)
          @if (isset($menu->navheader))
            <li class="navigation-header">
              <span>{{ __('locale.' . $menu->navheader) }}</span>
              <i data-feather="more-horizontal"></i>
            </li>
          @else --}}
            {{-- Add Custom Class with nav-item --}}
            {{-- @php
              $custom_classes = '';
              if (isset($menu->classlist)) {
                  $custom_classes = $menu->classlist;
              }
            @endphp
            <li
              class="nav-item {{ $custom_classes }} {{ Route::currentRouteName() === $menu->slug ? 'active' : '' }}">
              <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0)' }}" class="d-flex align-items-center"
                target="{{ isset($menu->newTab) ? '_blank' : '_self' }}">
                <i data-feather="{{ $menu->icon }}"></i>
                <span class="menu-title text-truncate">{{ __('locale.' . $menu->name) }}</span>
                @if (isset($menu->badge))
                  {{$badgeClasses = 'badge rounded-pill badge-light-primary ms-auto me-1' }}
                  <span
                    class="{{ isset($menu->badgeClass) ? $menu->badgeClass : $badgeClasses }}">{{ $menu->badge }}</span>
                @endif
              </a>
              @if (isset($menu->submenu))
                @include('panels/submenu', ['menu' => $menu->submenu])
              @endif
            </li>
          @endif
        @endforeach
      @endif --}}
      {{-- Foreach menu item ends --}}
    </ul>
    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    </div>
    <div class="ps__rail-y" style="top: 0px; height: 546px; right: 0px;">
        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 164px;"></div>
    </div>
</div>
</div>
<!-- END: Main Menu-->
