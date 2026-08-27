@php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu" @foreach ($configData['menuAttributes'] as $attribute=>
  $value)
  {{ $attribute }}="{{ $value }}" @endforeach>

  <!-- ! Hide app brand if navbar-full -->
  @if (!isset($navbarFull))
  <div class="app-brand demo">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">@include('_partials.macros')</span>
      <span class="app-brand-text demo menu-text fw-bold ms-3">{{ config('variables.templateName') }}</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
      <i class="icon-base ti tabler-x d-block d-xl-none"></i>
    </a>
  </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)
    {{-- adding active and open class if child is active --}}

    {{-- menu headers --}}
    @if (isset($menu->menuHeader))
    <li class="menu-header small">
      <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
    </li>
    @else
    {{-- active menu method --}}
    @php
    // A slug names a route *prefix*: "admin.services" must light up for
    // admin.services.index, .create and .edit alike, not only an exact match.
    $activeClass = null;
    $slugs = (array) $menu->slug;

    foreach ($slugs as $slug) {
    if (Route::is($slug) || Route::is($slug . '.*')) {
    $activeClass = isset($menu->submenu) ? 'active open' : 'active';
    break;
    }
    }
    @endphp

    {{-- main menu --}}
    <li class="menu-item {{ $activeClass }}">
      <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
        class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and
        !empty($menu->target)) target="_blank" @endif>
        @isset($menu->icon)
        <i class="{{ $menu->icon }}"></i>
        @endisset
        <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
        @isset($menu->badge)
        <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
        @endisset
      </a>

      {{-- submenu --}}
      @isset($menu->submenu)
      @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
      @endisset
    </li>
    @endif
    @endforeach
  </ul>

</aside>
