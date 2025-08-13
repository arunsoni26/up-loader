<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class='sidebar-brand' href='index.html'>
            <span class="sidebar-brand-text align-middle">
                {{ env('APP_NAME') }}
                <sup><small class="badge bg-primary text-uppercase">Pro</small></sup>
            </span>
            <svg class="sidebar-brand-icon align-middle" width="32px" height="32px" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5"
                stroke-linecap="square" stroke-linejoin="miter" color="#FFFFFF" style="margin-left: -3px">
                <path d="M12 4L20 8.00004L12 12L4 8.00004L12 4Z"></path>
                <path d="M20 12L12 16L4 12"></path>
                <path d="M20 16L12 20L4 16"></path>
            </svg>
        </a>

        <div class="sidebar-user">
            <div class="d-flex justify-content-center">
                <div class="flex-shrink-0">
                    <img src="{{ asset('img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded me-1" alt="Charles Hall" />
                </div>
                <div class="flex-grow-1 ps-2">
                    <a class="sidebar-user-title dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-start">
                        <a class='dropdown-item' href='pages-profile.html'><i class="align-middle me-1" data-feather="user"></i> Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Log out</a>
                    </div>

                    <div class="sidebar-user-subtitle">Designer</div>
                </div>
            </div>
        </div>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                <!-- Pages -->
            </li>
            <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class='sidebar-link' href='{{ route('dashboard') }}'>
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboards</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('role-permissions') ? 'active' : '' }}">
                <a class='sidebar-link' href='{{ route('role-permissions') }}'>
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Roles & Permissions</span>
                </a>
            </li>
        </ul>
    </div>
</nav>