{{-- Dynamic DB Promo Banner — pass $position variable --}}
@php
    $dbPromos = \App\Models\PromoBanner::active()->atPosition($position ?? 'after_hero')->get();
@endphp
@foreach($dbPromos as $pb)
@if($pb->image)
<section class="vk-db-promo" aria-label="{{ $pb->title ?: 'Promotional Offer' }}">
    <div class="container">
        @if($pb->link)
        <a href="{{ $pb->link }}" class="vk-db-promo-link" target="_blank" rel="noopener noreferrer">
        @else
        <div class="vk-db-promo-link">
        @endif
            <img src="{{ $pb->image_url }}" alt="{{ $pb->title ?: 'Promo Banner' }}" class="vk-db-promo-img">
            @if($pb->title || $pb->subtitle)
            <div class="vk-db-promo-text">
                @if($pb->title)<div class="vk-db-promo-title">{{ $pb->title }}</div>@endif
                @if($pb->subtitle)<div class="vk-db-promo-sub">{{ $pb->subtitle }}</div>@endif
                @if($pb->link)<span class="vk-db-promo-cta">View Offer →</span>@endif
            </div>
            @endif
        @if($pb->link)</a>@else</div>@endif
    </div>
</section>
@endif
@endforeach

<style>
.vk-db-promo { padding: 16px 0; }
.vk-db-promo-link {
    display: block; position: relative; border-radius: 14px;
    overflow: hidden; text-decoration: none !important;
    box-shadow: 0 4px 20px rgba(0,0,0,.10);
    transition: transform .25s, box-shadow .25s;
}
a.vk-db-promo-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 36px rgba(0,0,0,.16);
}
.vk-db-promo-img { width: 100%; height: auto; display: block; }
.vk-db-promo-text {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(0,0,0,.65) 0%, transparent 100%);
    padding: 20px 24px 18px;
}
.vk-db-promo-title { color: #fff; font-size: 1.1rem; font-weight: 800; margin-bottom: 4px; }
.vk-db-promo-sub   { color: rgba(255,255,255,.82); font-size: .85rem; margin-bottom: 8px; }
.vk-db-promo-cta   { display: inline-block; background: #FF6B00; color: #fff; font-size: .78rem; font-weight: 700; padding: 5px 14px; border-radius: 20px; }
</style>
