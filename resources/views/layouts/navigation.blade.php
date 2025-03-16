<div class="navbar-custom">
    <ul class="list-unstyled topnav-menu float-end mb-0">

        <li class="dropdown notification-list topbar-dropdown">
            <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown"
                href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <span class="pro-user-name ms-1">
                    {{ Auth::user()->email }} <i class="mdi mdi-chevron-down"></i>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome !</h6>
                </div>

                <!-- item-->
                <a href="contacts-profile.html" class="dropdown-item notify-item">
                    <i class="fe-user"></i>
                    <span>My Account</span>
                </a>

                <div class="dropdown-divider"></div>

                <!-- item-->
                <a href="{{ url('logout') }}" class="dropdown-item notify-item">
                    <i class="fe-log-out"></i>
                    <span>Logout</span>
                </a>

            </div>
        </li>

        <li class="dropdown notification-list">
            <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                <i class="fe-settings noti-icon"></i>
            </a>
        </li>

    </ul>

    <!-- LOGO -->
    <div class="logo-box">
        <a href="{{ route('dashboard') }}" class="logo logo-light text-center">
            <span class="logo-sm">
                <img src="{{ asset('src/assets/images/logo-black.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('src/assets/images/logo-white.png') }}" alt="" height="45">
            </span>
        </a>
        <a href="{{ route('dashboard') }}" class="logo logo-dark text-center">
            <span class="logo-sm">
                <img src="{{ asset('src/assets/images/logo-black.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('src/assets/images/logo-black.png') }}" alt="" height="45">
            </span>
        </a>
    </div>

    <ul class="list-unstyled topnav-menu topnav-menu-left mb-0">
        <li>
            <button class="button-menu-mobile disable-btn waves-effect">
                <i class="fe-menu"></i>
            </button>
        </li>

        <li>
            <h4 class="page-title-main">
                @yield('title')
            </h4>
        </li>

    </ul>

    <div class="clearfix"></div>

</div>
