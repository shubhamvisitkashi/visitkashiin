{{--
  Top Navbar — Visit Kashi Admin
  Preserved: .sidebar-toggler class (sidebar JS hook), change_theme_form,
             route('admin.theme'), @csrf, Auth::user()->name/email,
             route('change.password'), route('admin.logout'), logout form.
  New:       sa-navbar layout, breadcrumb, search bar, initials avatar.
--}}
<nav class="navbar sa-navbar">

    {{-- Hamburger: .sidebar-toggler triggers existing sidebar open/close JS --}}
    @php $navToggleLogo = websiteSetupValue('app_logo') ?: websiteSetupValue('logo'); @endphp
    <a href="#" class="sidebar-toggler sa-nav-toggle" aria-label="Toggle sidebar">
        @if ($navToggleLogo)
            <img src="{{ asset('backend/admin/website_setup/' . $navToggleLogo) }}"
                 alt="{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}"
                 style="max-width:26px;max-height:26px;object-fit:contain;">
        @else
            <i data-feather="menu"></i>
        @endif
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

        {{-- Enquiry notifications --}}
        @can('enquiry-list')
        <a href="{{ route('enquiry.index') }}" class="sa-nav-btn" title="Enquiries" id="vkEnquiryBell">
            <i data-feather="bell"></i>
            <span class="sa-nav-badge" id="vkEnquiryBadge" style="display:none;"></span>
        </a>
        @endcan

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

@can('enquiry-list')
<script>
(function() {
    var CHECK_URL   = @json(route('enquiry.latest-check'));
    var IS_ENQUIRY_PAGE = @json(Route::currentRouteName() === 'enquiry.index');
    var POLL_MS     = 25000;
    var SEEN_ENQ_KEY   = 'vk_seen_enquiry_id';
    var SEEN_HOTEL_KEY = 'vk_seen_hotel_id';

    var lastKnownCount = null;
    var audioCtx = null;

    function playChime() {
        try {
            audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
            var now = audioCtx.currentTime;
            [880, 1174.66].forEach(function(freq, i) {
                var osc  = audioCtx.createOscillator();
                var gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.value = freq;
                var start = now + i * 0.12;
                gain.gain.setValueAtTime(0, start);
                gain.gain.linearRampToValueAtTime(0.25, start + 0.02);
                gain.gain.exponentialRampToValueAtTime(0.0001, start + 0.35);
                osc.connect(gain).connect(audioCtx.destination);
                osc.start(start);
                osc.stop(start + 0.4);
            });
        } catch (e) {}
    }

    // ── Spoken alert ("Hi, new booking enquiry received. Call now.") ──
    var cachedVoices = [];
    function loadVoices() {
        if ('speechSynthesis' in window) cachedVoices = window.speechSynthesis.getVoices();
    }
    if ('speechSynthesis' in window) {
        loadVoices();
        window.speechSynthesis.onvoiceschanged = loadVoices;
    }

    function pickFemaleVoice() {
        if (!cachedVoices.length) return null;
        var byName = cachedVoices.find(function(v) {
            return /female|zira|samantha|susan|karen|victoria|moira|tessa|veena|google uk english female/i.test(v.name);
        });
        if (byName) return byName;
        var english = cachedVoices.find(function(v) { return /^en/i.test(v.lang); });
        return english || cachedVoices[0];
    }

    function speakNewEnquiry() {
        try {
            if (!('speechSynthesis' in window)) { playChime(); return; }
            var utter = new SpeechSynthesisUtterance('Hi, new booking enquiry received. Call now.');
            var voice = pickFemaleVoice();
            if (voice) utter.voice = voice;
            utter.rate   = 1;
            utter.pitch  = 1.15;
            utter.volume = 1;
            window.speechSynthesis.cancel();
            window.speechSynthesis.speak(utter);
        } catch (e) {
            playChime();
        }
    }

    function updateBadge(count) {
        var badge = document.getElementById('vkEnquiryBadge');
        if (!badge) return;
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    function checkEnquiries() {
        var sinceEnq   = localStorage.getItem(SEEN_ENQ_KEY);
        var sinceHotel = localStorage.getItem(SEEN_HOTEL_KEY);
        var firstRun   = sinceEnq === null;

        var url = CHECK_URL + '?since_enquiry_id=' + (sinceEnq || 0) + '&since_hotel_id=' + (sinceHotel || 0);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(res) { return res.ok ? res.json() : null; })
            .then(function(data) {
                if (!data) return;

                // First-ever check on this browser, or currently viewing the
                // enquiry list: treat everything up to now as "seen", no alert.
                if (firstRun || IS_ENQUIRY_PAGE) {
                    localStorage.setItem(SEEN_ENQ_KEY, data.latest_enquiry_id);
                    localStorage.setItem(SEEN_HOTEL_KEY, data.latest_hotel_id);
                    lastKnownCount = 0;
                    updateBadge(0);
                    return;
                }

                updateBadge(data.new_count);
                if (lastKnownCount !== null && data.new_count > lastKnownCount) {
                    speakNewEnquiry();
                }
                lastKnownCount = data.new_count;
            })
            .catch(function() {});
    }

    checkEnquiries();
    setInterval(checkEnquiries, POLL_MS);
})();
</script>
@endcan
