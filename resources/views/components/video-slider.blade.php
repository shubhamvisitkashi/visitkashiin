@props([
    'videos'       => collect(),
    'heading'      => 'Watch the Experience',
    'subheading'   => null,
    'subscribeUrl' => null,
    'itemName'     => 'Varanasi Boat Ride',
])
@php
    $videos = collect($videos)->filter(fn($v) => !empty($v->video_id))->values();
    $uid = 'vks' . \Illuminate\Support\Str::random(8);
@endphp
@once
    @push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/video-slider.min.css') }}?v={{ filemtime(public_path('frontend/css/video-slider.min.css')) }}">
    @endpush
@endonce

{{-- Shared JS pushed once, ahead of any per-instance init call below --}}
@once
@push('scripts')
<script>
/* ── Reusable multi-instance "Watch the Experience" video slider ──
   Shared by every page that includes this video-slider component. Each
   instance is addressed by its own uid so multiple sliders can coexist
   on one page. */
(function () {
    window.vksYtInit = function (uid) {
        var track = document.getElementById(uid + 'Track');
        var prev  = document.getElementById(uid + 'Prev');
        var next  = document.getElementById(uid + 'Next');
        var dots  = document.getElementById(uid + 'Dots');
        if (!track) return;

        function cardStep() {
            var card = track.querySelector('.vks-yt-card');
            if (!card) return 200;
            var gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap || 12) || 12;
            return card.getBoundingClientRect().width + gap;
        }

        function pageCount() {
            return Math.max(1, Math.round(track.scrollWidth / Math.max(track.clientWidth, 1)));
        }

        function currentPage() {
            return Math.round(track.scrollLeft / Math.max(track.clientWidth, 1));
        }

        function renderDots() {
            if (!dots) return;
            var pages = pageCount();
            if (pages <= 1) { dots.innerHTML = ''; return; }
            var html = '';
            for (var i = 0; i < pages; i++) {
                html += '<button type="button" class="vks-yt-dot" aria-label="Go to video group ' + (i + 1) + '" data-page="' + i + '"></button>';
            }
            dots.innerHTML = html;
            updateDots();
        }

        function updateDots() {
            if (!dots) return;
            var page = currentPage();
            dots.querySelectorAll('.vks-yt-dot').forEach(function (d, i) {
                d.classList.toggle('active', i === page);
            });
        }

        function updateNav() {
            var max = track.scrollWidth - track.clientWidth - 2;
            if (prev) prev.disabled = track.scrollLeft <= 2;
            if (next) next.disabled = track.scrollLeft >= max;
            updateDots();
        }

        if (prev) prev.addEventListener('click', function () { track.scrollBy({ left: -cardStep() * 2, behavior: 'smooth' }); });
        if (next) next.addEventListener('click', function () { track.scrollBy({ left: cardStep() * 2, behavior: 'smooth' }); });

        if (dots) {
            dots.addEventListener('click', function (e) {
                var btn = e.target.closest('.vks-yt-dot');
                if (!btn) return;
                var page = parseInt(btn.dataset.page, 10) || 0;
                track.scrollTo({ left: page * track.clientWidth, behavior: 'smooth' });
            });
        }

        track.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight') track.scrollBy({ left: cardStep(), behavior: 'smooth' });
            if (e.key === 'ArrowLeft')  track.scrollBy({ left: -cardStep(), behavior: 'smooth' });
        });

        var scrollTimer;
        track.addEventListener('scroll', function () {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(updateNav, 60);
        }, { passive: true });

        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () { renderDots(); updateNav(); }, 150);
        });

        renderDots();
        updateNav();
    };

    window.vksYtOpen = function (evt, uid, videoId, title) {
        if (evt) evt.preventDefault();
        var modal = document.getElementById(uid + 'Modal');
        if (!modal) return false;
        var iframe  = document.getElementById(uid + 'Iframe');
        var titleEl = document.getElementById(uid + 'ModalTitle');
        iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0&playsinline=1';
        if (titleEl) titleEl.textContent = title || '';
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
        return false;
    };

    window.vksYtClose = function (uid) {
        var modal = document.getElementById(uid + 'Modal');
        if (!modal) return;
        var iframe = document.getElementById(uid + 'Iframe');
        if (iframe) iframe.src = '';
        modal.classList.remove('open');
        document.body.style.overflow = '';
    };

    document.addEventListener('click', function (e) {
        var modal = e.target.closest('.vks-yt-modal');
        if (modal && e.target === modal) {
            var uid = modal.id.replace(/Modal$/, '');
            window.vksYtClose(uid);
        }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.vks-yt-modal.open').forEach(function (m) {
            window.vksYtClose(m.id.replace(/Modal$/, ''));
        });
    });
})();
</script>
@endpush
@endonce

@if($videos->isNotEmpty())
<section class="vks-yt-section" aria-label="{{ $heading }}">
    <div class="vks-yt-header">
        <div class="vks-yt-header-left">
            <div class="vks-yt-logo" aria-hidden="true">
                <svg width="22" height="16" viewBox="0 0 28 20" fill="none"><rect width="28" height="20" rx="4" fill="#FF0000"/><path d="M11 6l8 4-8 4V6z" fill="#fff"/></svg>
            </div>
            <div>
                <h2 class="vks-yt-title">{{ $heading }}</h2>
                @if($subheading)<p class="vks-yt-sub">{{ $subheading }}</p>@endif
            </div>
        </div>
        @if($subscribeUrl)
        <a href="{{ $subscribeUrl }}" target="_blank" rel="noopener noreferrer" class="vks-yt-subscribe" aria-label="Subscribe to Visit Kashi on YouTube">
            <svg width="14" height="10" viewBox="0 0 28 20" fill="none"><rect width="28" height="20" rx="4" fill="#fff" fill-opacity=".3"/><path d="M11 6l8 4-8 4V6z" fill="#fff"/></svg>
            Subscribe
        </a>
        @endif
    </div>

    <div class="vks-yt-slider-wrap">
        <button type="button" class="vks-yt-nav prev" id="{{ $uid }}Prev" aria-label="Scroll videos left" disabled>&#8249;</button>

        <div class="vks-yt-track" id="{{ $uid }}Track" tabindex="0" role="region" aria-label="{{ $heading }} — scrollable video list">
            @foreach($videos as $i => $vid)
                @php $vidTitle = $vid->title ?: ($itemName . ' — Video ' . ($i + 1)); @endphp
                <a href="https://www.youtube.com/shorts/{{ $vid->video_id }}"
                   class="vks-yt-card"
                   target="_blank" rel="noopener noreferrer"
                   aria-label="Watch {{ e($vidTitle) }} on YouTube"
                   onclick="return vksYtOpen(event, '{{ $uid }}', '{{ $vid->video_id }}', '{{ e($vidTitle) }}')">
                    <div class="vks-yt-thumb">
                        <img src="{{ $vid->thumbnail }}" alt="{{ e($vidTitle) }}" loading="lazy" width="300" height="533"
                             onerror="this.src='https://img.youtube.com/vi/{{ $vid->video_id }}/default.jpg'">
                        <div class="vks-yt-play-btn" aria-hidden="true">&#9654;</div>
                        <span class="vks-yt-shorts-badge" aria-hidden="true">
                            <svg width="9" height="12" viewBox="0 0 10 14" fill="none"><path d="M5.8 0L0 7.5h4.2L4.2 14 10 6.5H5.8L5.8 0z" fill="#fff"/></svg>
                            Shorts
                        </span>
                    </div>
                    <p class="vks-yt-caption">{{ $vidTitle }}</p>
                </a>
            @endforeach
        </div>

        <button type="button" class="vks-yt-nav next" id="{{ $uid }}Next" aria-label="Scroll videos right">&#8250;</button>
    </div>

    <div class="vks-yt-dots" id="{{ $uid }}Dots" aria-hidden="true"></div>
</section>

<div class="vks-yt-modal" id="{{ $uid }}Modal" role="dialog" aria-modal="true" aria-label="Video player">
    <div class="vks-yt-modal-box">
        <button class="vks-yt-modal-close" aria-label="Close video" onclick="vksYtClose('{{ $uid }}')">&#x2715;</button>
        <p class="vks-yt-modal-title" id="{{ $uid }}ModalTitle"></p>
        <div class="vks-yt-iframe-wrap">
            <iframe id="{{ $uid }}Iframe" src="" allowfullscreen frameborder="0"
                    allow="autoplay; encrypted-media; picture-in-picture" title="{{ $heading }} video player"></iframe>
        </div>
    </div>
</div>

{{-- JSON-LD: VideoObject list — mirrors the cards above exactly --}}
@php
    $videoSliderSchema = [
        '@context' => 'https://schema.org',
        '@type'    => 'ItemList',
        'name'     => $heading,
        'itemListElement' => $videos->values()->map(function ($v, $i) {
            return [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'item'     => [
                    '@type'        => 'VideoObject',
                    'name'         => $v->title ?: 'Varanasi Boat Ride Video — Visit Kashi',
                    'description'  => ($v->title ? $v->title . ' — ' : '') . 'Boat ride experience in Varanasi with Visit Kashi.',
                    'thumbnailUrl' => [$v->thumbnail],
                    'embedUrl'     => 'https://www.youtube.com/embed/' . $v->video_id,
                    'contentUrl'   => $v->youtube_url,
                    'uploadDate'   => ($v->created_at ?? now())->toAtomString(),
                    'publisher'    => [
                        '@type' => 'Organization',
                        'name'  => 'Visit Kashi',
                        'logo'  => ['@type' => 'ImageObject', 'url' => asset('frontend/images/logo1.png')],
                    ],
                ],
            ];
        })->values()->all(),
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($videoSliderSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

@push('scripts')
<script>vksYtInit('{{ $uid }}');</script>
@endpush
@endif
