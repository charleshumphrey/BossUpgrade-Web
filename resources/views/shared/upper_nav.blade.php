<nav id="navbar-main" class="navbar is-fixed-top">
    <div class="navbar-brand">
        <a class="navbar-item mobile-aside-button">
            <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
        </a>

    </div>
    <div class="navbar-brand is-right">
        <a
            class="navbar-item --jb-navbar-menu-toggle"
            data-target="navbar-menu">
            <span class="icon"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
        </a>
    </div>
    <div class="navbar-menu" id="navbar-menu">
        <div class="navbar-end">
            <div class="text-black navbar-item dropdown has-divider has-user-avatar">
                <a class="navbar-link">
                    <div class="user-avatar">
                        <!-- <img src="{{ asset('build/assets/images/avatar-default-symbolic-svgrepo-com.svg') }}" alt="Profile_Image" class="rounded-full"> -->
                        <img class="rounded-full" src="{{ Session::get('user')['profileImage']??"Admin"  }}" alt="user_profile">
                    </div>
                    <div class="is-user-name"><span class="">{{ Session::get('user')['username']??"Admin" }}</span></div>
                    <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
                </a>
                <div class="text-black navbar-dropdown">
                    <a href="{{ route('profile') }}" class="navbar-item hover:bg-gray-100">
                        <span class="icon"><i class="mdi mdi-account"></i></span>
                        <span>My Profile</span>
                    </a>
                    <a href="{{ route('change-password') }}" class="navbar-item hover:bg-gray-100">
                        <span class="icon"><i class="mdi mdi-lock"></i></span>
                        <span class="text-sm">Change Password</span>
                    </a>
                    <hr class="navbar-divider hover:bg-gray-100" />
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <button type="submit" class="w-full navbar-item text-red-500 hover:bg-red-50">
                            <span class="icon"><i class="mdi mdi-logout"></i></span>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>