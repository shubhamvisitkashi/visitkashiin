{{-- Promo Banner — auto-slider if multiple images --}}
@php
  $slides = array_filter([
    websiteSetupValue('promo_banner'),
    websiteSetupValue('promo_banner_2'),
    websiteSetupValue('promo_banner_3'),
  ]);
  $slides      = array_values($slides);
  $hasSlider   = count($slides) > 1;
  $sliderId    = 'pb-' . substr(md5(implode(',',$slides)), 0, 6);
@endphp

@if(count($slides) || $promoBannerTitle)
<section class="vk-promo-banner" aria-label="Promotional Banner">
  <div class="container">

    @if(count($slides))

      <div class="vk-pb-wrap{{ $hasSlider ? ' vk-pb-has-slider' : '' }}" id="{{ $sliderId }}" aria-live="polite">

        {{-- Slides --}}
        @foreach($slides as $i => $img)
        <div class="vk-pb-slide{{ $i === 0 ? ' active' : '' }}" data-index="{{ $i }}" aria-hidden="{{ $i !== 0 ? 'true' : 'false' }}">
          @if($promoBannerLink)
          <a href="{{ $promoBannerLink }}" class="vk-pb-card" target="_blank" rel="noopener noreferrer">
          @else
          <div class="vk-pb-card">
          @endif
            <img src="{{ asset('backend/admin/website_setup/' . $img) }}"
                 alt="{{ $promoBannerTitle ?: 'Promotional Offer' }}"
                 class="vk-pb-img"
                 loading="{{ $i === 0 ? 'eager' : 'lazy' }}" />
            @if($promoBannerTitle || $promoBannerSubtitle || $promoBannerLink)
            <div class="vk-pb-overlay">
              <div class="vk-pb-content">
                @if($promoBannerTitle)
                <div class="vk-pb-title">{{ $promoBannerTitle }}</div>
                @endif
                @if($promoBannerSubtitle)
                <div class="vk-pb-sub">{{ $promoBannerSubtitle }}</div>
                @endif
              </div>
            </div>
            @endif
          @if($promoBannerLink)
          </a>
          @else
          </div>
          @endif
        </div>
        @endforeach

        {{-- Arrows (only for slider) --}}
        @if($hasSlider)
        <button class="vk-pb-arrow vk-pb-prev" aria-label="Previous" onclick="pbSlide('{{ $sliderId }}', -1)">&#8249;</button>
        <button class="vk-pb-arrow vk-pb-next" aria-label="Next"     onclick="pbSlide('{{ $sliderId }}',  1)">&#8250;</button>

        {{-- Dots --}}
        <div class="vk-pb-dots" role="tablist">
          @foreach($slides as $i => $img)
          <button class="vk-pb-dot{{ $i === 0 ? ' active' : '' }}"
                  role="tab"
                  aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                  aria-label="Slide {{ $i + 1 }}"
                  onclick="pbGoTo('{{ $sliderId }}', {{ $i }})"></button>
          @endforeach
        </div>
        @endif

      </div>

    @elseif($promoBannerTitle)

      {{-- Text-only strip fallback --}}
      @if($promoBannerLink)
      <a href="{{ $promoBannerLink }}" class="vk-pb-text-strip" target="_blank" rel="noopener noreferrer">
      @else
      <div class="vk-pb-text-strip">
      @endif
        <div class="vk-pb-title">{{ $promoBannerTitle }}</div>
        @if($promoBannerSubtitle)
        <div class="vk-pb-sub" style="color:rgba(255,255,255,.85);">{{ $promoBannerSubtitle }}</div>
        @endif
      @if($promoBannerLink)
      </a>
      @else
      </div>
      @endif

    @endif

  </div>
</section>
@endif

<style>
.vk-promo-banner { padding: 28px 0; }

/* ── Wrapper ── */
.vk-pb-wrap { position: relative; width: 100%; overflow: hidden; border-radius: 18px; box-shadow: 0 6px 28px rgba(0,0,0,0.14); }

/* ── Slide ── */
.vk-pb-slide { display: none; }
.vk-pb-slide.active { display: block; animation: pbFadeIn .45s ease; }
@keyframes pbFadeIn { from{opacity:0;transform:scale(1.012)} to{opacity:1;transform:scale(1)} }

/* ── Card & Image ── */
.vk-pb-card { display: block; position: relative; width: 100%; height: 300px; text-decoration: none; line-height: 0; }
a.vk-pb-card { transition: opacity .2s; }
a.vk-pb-card:hover { opacity: .95; }
.vk-pb-img { width: 100%; height: 100%; object-fit: cover; display: block; }

/* ── Overlay + text ── */
.vk-pb-overlay { position:absolute;inset:0;background:linear-gradient(to right,rgba(0,0,0,.62) 0%,rgba(0,0,0,.18) 55%,transparent 100%);display:flex;align-items:center; }
.vk-pb-content { padding: 32px 40px; max-width: 520px; }
.vk-pb-title { font-family:'Plus Jakarta Sans',sans-serif;font-size:1.65rem;font-weight:800;color:#fff;line-height:1.2;letter-spacing:-.02em;margin-bottom:8px;text-shadow:0 2px 12px rgba(0,0,0,.3); }
.vk-pb-sub { font-family:'Plus Jakarta Sans',sans-serif;font-size:.95rem;color:rgba(255,255,255,.85);margin-bottom:18px;line-height:1.5; }
.vk-pb-btn { display:inline-flex;align-items:center;gap:6px;background:#fff;color:#222;font-family:'Plus Jakarta Sans',sans-serif;font-size:.85rem;font-weight:800;padding:10px 22px;border-radius:50px;white-space:nowrap;transition:background .2s,color .2s; }
a.vk-pb-card:hover .vk-pb-btn { background:#D94F2B;color:#fff; }

/* ── Arrows ── */
.vk-pb-arrow { position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.92);border:none;border-radius:50%;width:42px;height:42px;font-size:1.5rem;line-height:1;cursor:pointer;z-index:10;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 10px rgba(0,0,0,.18);transition:background .2s,transform .2s; }
.vk-pb-arrow:hover { background:#fff;transform:translateY(-50%) scale(1.08); }
.vk-pb-prev { left: 14px; }
.vk-pb-next { right: 14px; }

/* ── Dots ── */
.vk-pb-dots { position:absolute;bottom:14px;left:50%;transform:translateX(-50%);display:flex;gap:7px;z-index:10; }
.vk-pb-dot { width:8px;height:8px;border-radius:50%;border:none;background:rgba(255,255,255,.5);cursor:pointer;padding:0;transition:background .2s,transform .2s; }
.vk-pb-dot.active { background:#fff;transform:scale(1.3); }

/* ── Text-only strip ── */
.vk-pb-text-strip { display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;background:linear-gradient(135deg,#D94F2B 0%,#B83D1F 100%);padding:24px 36px;border-radius:18px;text-decoration:none;box-shadow:0 6px 24px rgba(217,79,43,.25); }
.vk-pb-text-strip .vk-pb-title { font-size:1.15rem;color:#fff;margin-bottom:4px; }
.vk-pb-text-strip .vk-pb-btn { background:#fff;color:#D94F2B;flex-shrink:0; }

/* ── Mobile ── */
@media(max-width:768px){
  .vk-pb-card { height: 220px; }
  .vk-pb-content { padding: 20px 22px; max-width: 100%; }
  .vk-pb-title { font-size: 1.1rem; }
  .vk-pb-sub { font-size: .82rem; margin-bottom: 12px; }
  .vk-pb-overlay { background:linear-gradient(to top,rgba(0,0,0,.65) 0%,rgba(0,0,0,.15) 60%,transparent 100%); align-items:flex-end; }
}
@media(max-width:480px){
  .vk-pb-card { height: 180px; }
  .vk-pb-text-strip { padding: 18px 20px; flex-direction: column; }
}
</style>

@if($hasSlider)
<script>
(function(){
  var timers = {};

  function getSlides(id){ return document.querySelectorAll('#'+id+' .vk-pb-slide'); }
  function getDots(id)  { return document.querySelectorAll('#'+id+' .vk-pb-dot'); }
  function current(id)  { return document.querySelector('#'+id+' .vk-pb-slide.active'); }

  window.pbGoTo = function(id, idx){
    var slides = getSlides(id), dots = getDots(id);
    slides.forEach(function(s,i){ s.classList.toggle('active',i===idx); s.setAttribute('aria-hidden',i!==idx); });
    dots.forEach(function(d,i){ d.classList.toggle('active',i===idx); d.setAttribute('aria-selected',i===idx); });
    resetTimer(id);
  };

  window.pbSlide = function(id, dir){
    var slides = getSlides(id), cur = 0;
    slides.forEach(function(s,i){ if(s.classList.contains('active')) cur = i; });
    pbGoTo(id, (cur + dir + slides.length) % slides.length);
  };

  function resetTimer(id){
    clearInterval(timers[id]);
    timers[id] = setInterval(function(){ pbSlide(id, 1); }, 4500);
  }

  // init auto-play for each slider on the page
  document.querySelectorAll('.vk-pb-has-slider').forEach(function(el){
    resetTimer(el.id);
  });
})();
</script>
@endif
