<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-light navbar-border navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row position-relative">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="{{route('home')}}">
                        <h2 class="brand-text">ACE IMPACT</h2>
                    </a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                </ul>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="{{route('user-management.user.my_profile')}}"><i class="ft-user"></i> Edit Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="ft-power"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>