<ul class="nav nav-pills mb-2">
    <!-- Account -->
    <li class="nav-item">
      <a class="nav-link {{ Route::current()->parameter('panelsettings') === 'panel-settings' ? 'active' : '' }}" href="{{route('panel-settings',['panel-settings'])}}">
        <i data-feather="grid" class="font-medium-3 me-50"></i>
        <span class="fw-bold">Panel</span>
      </a>
    </li>
    <!-- security -->
    <li class="nav-item">
      <a class="nav-link {{ Route::current()->parameter('panelsettings') === 'security-settings' ? 'active' : '' }}" href="{{route('panel-settings',['security-settings'])}}">
        <i data-feather="lock" class="font-medium-3 me-50"></i>
        <span class="fw-bold">Security</span>
      </a>
    </li>
    <!-- mail -->
    <li class="nav-item">
      <a class="nav-link {{ Route::current()->parameter('panelsettings') === 'mail-settings' ? 'active' : '' }}" href="{{route('panel-settings',['mail-settings'])}}">
        <i data-feather="mail" class="font-medium-3 me-50"></i>
        <span class="fw-bold">Mail</span>
      </a>
    </li>
    <!-- design -->
    {{-- <li class="nav-item">
      <a class="nav-link {{ Route::current()->parameter('panelsettings') === 'design-settings' ? 'active' : '' }}" href="{{route('panel-settings',['design-settings'])}}">
        <i data-feather="codesandbox" class="font-medium-3 me-50"></i>
        <span class="fw-bold">Design</span>        
      </a>
    </li> --}}
    {{-- <!-- billing and plans -->
    <li class="nav-item">
      <a class="nav-link {{ Route::current()->parameter('panelsettings') === 'billing-settings' ? 'active' : '' }}" href="{{route('panel-settings',['billing-settings'])}}">
        <i data-feather="bookmark" class="font-medium-3 me-50"></i>
        <span class="fw-bold">Billings &amp; Plans</span>
      </a>
    </li>
    <!-- notification -->
    <li class="nav-item">
      <a class="nav-link {{ Route::current()->parameter('panelsettings') === 'notifications-settings' ? 'active' : '' }}" href="{{route('panel-settings',['notifications-settings'])}}">
        <i data-feather="bell" class="font-medium-3 me-50"></i>
        <span class="fw-bold">Notifications</span>
      </a>
    </li>
    <!-- connection -->
    <li class="nav-item">
      <a class="nav-link {{ Route::current()->parameter('panelsettings') === 'connections-settings' ? 'active' : '' }}" href="{{route('panel-settings',['connections-settings'])}}" >
        <i data-feather="link" class="font-medium-3 me-50"></i>
        <span class="fw-bold">Connections</span>
      </a>
    </li> --}}
  </ul>