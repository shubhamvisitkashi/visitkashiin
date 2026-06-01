{{--
  Shared layout for static info pages (Privacy Policy, Cancellation & Refund, About Us).
  Usage:
    @include('frontend.pages._layout', ['title' => '...', 'breadcrumb' => '...'])
      ... content ...
    @endinclude  ← not needed; wrap with @section('content') in parent
--}}

{{-- ── Styles pushed once per page ── --}}
@push('styles')
<style>
/* ── Static info page shared styles ── */
.vk-page-hero {
    background: linear-gradient(135deg, #1A2B4C 0%, #0d1420 100%);
    padding: 64px 0 44px;
    text-align: center;
    color: #fff;
}
.vk-page-hero h1 {
    font-size: clamp(26px, 3.8vw, 42px);
    font-weight: 800;
    margin: 0 0 8px;
    letter-spacing: -.02em;
}
.vk-page-hero p {
    font-size: 15px;
    color: rgba(255,255,255,.68);
    margin: 0 auto;
    max-width: 520px;
}
.vk-page-breadcrumb {
    margin: 0 0 16px;
    font-size: 13px;
    color: rgba(255,255,255,.5);
}
.vk-page-breadcrumb a { color: rgba(255,255,255,.65); }
.vk-page-breadcrumb span { margin: 0 6px; }

.vk-page-wrap {
    padding: 60px 0 80px;
    background: #f8f9fa;
}
.vk-page-card {
    background: #fff;
    border-radius: 20px;
    padding: 48px 52px;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
    max-width: 860px;
    margin: 0 auto;
}
@media(max-width: 767px) {
    .vk-page-card { padding: 28px 20px; }
}

/* Typography inside policy/info pages */
.vk-page-card h2 {
    font-size: 20px;
    font-weight: 700;
    color: #1A2B4C;
    margin: 36px 0 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f0f2f5;
}
.vk-page-card h2:first-of-type { margin-top: 0; }
.vk-page-card h3 {
    font-size: 16px;
    font-weight: 600;
    color: #D94F2B;
    margin: 20px 0 8px;
}
.vk-page-card p {
    font-size: 15px;
    color: #555;
    line-height: 1.8;
    margin: 0 0 14px;
}
.vk-page-card ul, .vk-page-card ol {
    padding-left: 20px;
    margin: 0 0 16px;
    list-style: disc;
}
.vk-page-card ol { list-style: decimal; }
.vk-page-card li {
    font-size: 15px;
    color: #555;
    line-height: 1.8;
    margin-bottom: 6px;
}
.vk-page-card a { color: #D94F2B; }
.vk-page-card a:hover { text-decoration: underline !important; }

.vk-page-meta {
    font-size: 13px;
    color: #999;
    margin-bottom: 32px;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.vk-page-meta span i { margin-right: 5px; }

/* Highlight boxes */
.vk-info-box {
    background: #fff8f5;
    border-left: 4px solid #D94F2B;
    border-radius: 0 10px 10px 0;
    padding: 16px 20px;
    margin: 20px 0;
}
.vk-info-box p { margin: 0; color: #444; }

.vk-refund-table {
    width: 100%;
    border-collapse: collapse;
    margin: 16px 0 24px;
    font-size: 14px;
}
.vk-refund-table th {
    background: #1A2B4C;
    color: #fff;
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
}
.vk-refund-table td {
    padding: 11px 16px;
    border-bottom: 1px solid #f0f2f5;
    color: #555;
    vertical-align: top;
}
.vk-refund-table tr:last-child td { border-bottom: none; }
.vk-refund-table tr:nth-child(even) td { background: #fafafa; }
.vk-refund-table td.highlight { color: #D94F2B; font-weight: 600; }

/* About Us extras */
.vk-about-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin: 32px 0;
}
@media(max-width: 600px) {
    .vk-about-stats { grid-template-columns: repeat(2, 1fr); }
}
.vk-about-stat {
    background: #f8f9fa;
    border-radius: 14px;
    padding: 20px 16px;
    text-align: center;
}
.vk-about-stat strong {
    display: block;
    font-size: 28px;
    font-weight: 800;
    color: #D94F2B;
    line-height: 1;
}
.vk-about-stat span {
    font-size: 13px;
    color: #666;
    margin-top: 4px;
    display: block;
}
.vk-about-values {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin: 20px 0;
}
@media(max-width: 576px) {
    .vk-about-values { grid-template-columns: 1fr; }
}
.vk-about-value {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 18px 20px;
    display: flex;
    gap: 14px;
    align-items: flex-start;
}
.vk-about-value i {
    font-size: 22px;
    color: #D94F2B;
    margin-top: 2px;
    flex-shrink: 0;
}
.vk-about-value h4 {
    font-size: 15px;
    font-weight: 700;
    color: #1A2B4C;
    margin: 0 0 4px;
}
.vk-about-value p { font-size: 13px; margin: 0; color: #666; }

.vk-about-cta {
    background: linear-gradient(135deg, #1A2B4C, #D94F2B);
    border-radius: 16px;
    padding: 28px 32px;
    text-align: center;
    margin-top: 36px;
    color: #fff;
}
.vk-about-cta h3 { font-size: 20px; font-weight: 700; margin: 0 0 8px; color: #fff; border: none; padding: 0; }
.vk-about-cta p { color: rgba(255,255,255,.8); margin: 0 0 18px; }
.vk-about-cta a {
    display: inline-block;
    background: #fff;
    color: #D94F2B;
    padding: 11px 28px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 14px;
}
.vk-about-cta a:hover { background: #f5f5f5; text-decoration: none !important; }
</style>
@endpush
