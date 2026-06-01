{{--
  Top Navbar — Visit Kashi Admin
  Preserved: .sidebar-toggler class (sidebar JS hook), change_theme_form,
             route('admin.theme'), @csrf, Auth::user()->name/email,
             route('change.password'), route('admin.logout'), logout form.
  New:       sa-navbar layout, breadcrumb, search bar, initials avatar.
--}}
<nav class="navbar sa-navbar">

    {{-- Hamburger: .sidebar-toggler triggers existing sidebar open/close JS --}}
    <a href="#" class="sidebar-toggler sa-nav-toggle" aria-label="Toggle sidebar">
        <i data-feather="menu"></i>
    </a>

    {{-- Breadcrumb --}}
    <nav class="sa-breadcrumb" aria-label="breadcrumb">
        <a href="{{ route('admin.dashboard') }}" class="sa-breadcrumb__home" title="Dashboard">
            <i data-feather="home" style="width:14px;height:14px;"></i>
        </a>
        @if(isset($page_title) && $page_title)
            <span class="sa-breadcrumb__sep">›</span>
            <span class="sa-breadcrumb__current">{{ $page_title }}</span>
        @endif
    </nav>

    {{-- Search --}}
    <div class="sa-nav-search">
        <i data-feather="search" class="sa-nav-search__icon"></i>
        <input type="text" placeholder="Search anything…" autocomplete="off">
    </div>

    {{-- Right-side actions --}}
    <div class="sa-nav-actions">

        {{-- Theme toggle --}}
        <form action="{{ route('admin.theme') }}" method="post" id="change_theme_form">
            @csrf
            {{-- Controller checks isset($request->theme_change) → true = set Dark, false = set Light --}}
            @if(!(session()->has('selected_theme') && session()->get('selected_theme') === 'Dark'))
                <input type="hidden" name="theme_change" value="1">
            @endif
            <button type="button" class="sa-nav-btn"
                    title="{{ (session('selected_theme') === 'Dark') ? 'Switch to Light' : 'Switch to Dark' }} Mode"
                    onclick="change_theme()">
                @if(session()->has('selected_theme') && session()->get('selected_theme') === 'Dark')
                    <i data-feather="sun"></i>
                @else
                    <i data-feather="moon"></i>
                @endif
            </button>
        </form>

        {{-- Profile dropdown --}}
        <div class="dropdown">
            <a href="#" class="sa-profile-btn dropdown-toggle"
               id="saProfileDropdown"
               data-bs-toggle="dropdown"
               aria-expanded="false">
                @if(Auth::user()->avatar_url)
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                         class="sa-initials-avatar" style="object-fit:cover;border:2px solid rgba(99,102,241,.25);">
                @else
                    <div class="sa-initials-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                @endif
                <span class="sa-profile-name">{{ Auth::user()->name }}</span>
                <i data-feather="chevron-down" style="width:13px;height:13px;flex-shrink:0;opacity:.55;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end sa-profile-dropdown"
                 aria-labelledby="saProfileDropdown">
                <div class="sa-drop-header">
                    @if(Auth::user()->avatar_url)
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                             class="sa-initials-avatar sa-initials-avatar--lg" style="object-fit:cover;border:3px solid rgba(255,255,255,.3);">
                    @else
                        <div class="sa-initials-avatar sa-initials-avatar--lg">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="name mt-2">{{ Auth::user()->name }}</div>
                    <div class="email">{{ Auth::user()->email }}</div>
                </div>
                <div class="sa-drop-divider"></div>
                <a href="{{ route('change.password') }}" class="sa-drop-item">
                    <i data-feather="key"></i>
                    <span>Change Password</span>
                </a>
                <div class="sa-drop-divider"></div>
                <a href="{{ route('admin.logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="sa-drop-item sa-drop-danger">
                    <i data-feather="log-out"></i>
                    <span>Sign Out</span>
                </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

    </div>{{-- /.sa-nav-actions --}}
</nav>

<script>
    function change_theme() {
        document.getElementById('change_theme_form').submit();
    }
</script>
