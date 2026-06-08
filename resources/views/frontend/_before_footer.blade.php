
{{-- ══════════════════════════════════════════════
     Shared pre-footer sections — included on ALL pages via app.blade.php
     1. Why Travelers Choose Visit Kashi
     2. WhatsApp CTA Strip
     3. Varanasi Weather Widget
══════════════════════════════════════════════ --}}

{{-- 1. Why Choose Us --}}
<section class="vk-why" aria-labelledby="vk-why-global">
  <div class="container">
    <div class="vk-section__header--center vk-reveal">
      <h2 class="vk-section__title" id="vk-why-global">Why Travelers Choose Visit Kashi</h2>
      <p class="vk-section__subtitle">We make your Varanasi experience seamless and unforgettable</p>
    </div>
    <div class="vk-why__grid">
      <div class="vk-why__card vk-reveal">
        <span class="vk-why__icon" aria-hidden="true">🛡️</span>
        <div class="vk-why__card-body">
          <h3>100% Verified Services</h3>
          <p>Every hotel, boat operator, and guide is personally verified by our local team before listing on our platform.</p>
        </div>
      </div>
      <div class="vk-why__card vk-reveal vk-reveal--delay-1">
        <span class="vk-why__icon" aria-hidden="true">💬</span>
        <div class="vk-why__card-body">
          <h3>24 / 7 Local Support</h3>
          <p>Our on-ground Varanasi team is available round the clock to assist you before, during, and after your trip.</p>
        </div>
      </div>
      <div class="vk-why__card vk-reveal vk-reveal--delay-2">
        <span class="vk-why__icon" aria-hidden="true">⚡</span>
        <div class="vk-why__card-body">
          <h3>Instant Confirmation</h3>
          <p>Book in under a minute. Receive instant booking confirmation with all details directly on your device.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- 2. WhatsApp CTA Strip --}}
<section class="vk-cta-strip" aria-label="Contact us on WhatsApp">
  <div class="container">
    <div class="vk-cta-strip__inner">
      <div class="vk-cta-strip__text">
        <strong>Need Help Planning Your Trip?</strong>
        <span>Chat with our local Varanasi travel experts instantly</span>
      </div>
      <a href="https://wa.me/+917080109919"
         target="_blank"
         rel="noopener noreferrer"
         class="vk-cta-strip__btn"
         aria-label="Chat on WhatsApp">
        <img src="{{ asset('frontend/images/whatsapp.png') }}" alt="" aria-hidden="true" width="22" height="22" />
        Chat on WhatsApp
      </a>
    </div>
  </div>
</section>

{{-- 3. Weather Widget (self-contained fetch via cache) --}}
@php
  $_w = cache()->remember('varanasi_weather', 1800, function () {
    try {
      $res = \Illuminate\Support\Facades\Http::timeout(4)->get('https://api.open-meteo.com/v1/forecast', [
        'latitude'      => 25.3176,
        'longitude'     => 82.9739,
        'current'       => 'temperature_2m,weather_code,wind_speed_10m,relative_humidity_2m',
        'daily'         => 'temperature_2m_max,temperature_2m_min,sunrise,sunset',
        'timezone'      => 'Asia/Kolkata',
        'forecast_days' => 1,
      ]);
      return $res->ok() ? $res->json() : null;
    } catch (\Exception $e) { return null; }
  });
@endphp

@if(!empty($_w['current']))
@php
  $wc  = $_w['current'];
  $wd  = $_w['daily'] ?? [];
  $_tc = round($wc['temperature_2m'] ?? 0);
  $_tf = round($_tc * 9/5 + 32);
  $_lc = round($wd['temperature_2m_min'][0] ?? 0);
  $_hc = round($wd['temperature_2m_max'][0] ?? 0);
  $_lf = round($_lc * 9/5 + 32);
  $_hf = round($_hc * 9/5 + 32);
  $_hum  = $wc['relative_humidity_2m'] ?? '--';
  $_wind = round($wc['wind_speed_10m'] ?? 0);
  $_sun  = isset($wd['sunrise'][0])  ? \Carbon\Carbon::parse($wd['sunrise'][0])->format('H:i')  : '--';
  $_set  = isset($wd['sunset'][0])   ? \Carbon\Carbon::parse($wd['sunset'][0])->format('H:i')   : '--';
  $_code = $wc['weather_code'] ?? 0;

  $_wmoMap = [
    0=>'☀️ Clear', 1=>'🌤️ Mainly Clear', 2=>'⛅ Partly Cloudy', 3=>'☁️ Overcast',
    45=>'🌫️ Foggy', 48=>'🌫️ Icy Fog',
    51=>'🌦️ Light Drizzle', 53=>'🌦️ Drizzle', 55=>'🌧️ Heavy Drizzle',
    61=>'🌧️ Light Rain', 63=>'🌧️ Rain', 65=>'🌧️ Heavy Rain',
    71=>'❄️ Light Snow', 73=>'❄️ Snow', 75=>'❄️ Heavy Snow',
    80=>'🌦️ Showers', 81=>'🌦️ Showers', 82=>'🌧️ Heavy Showers',
    95=>'⛈️ Thunderstorm', 96=>'⛈️ Thunderstorm', 99=>'⛈️ Thunderstorm',
  ];
  $_entry  = $_wmoMap[$_code] ?? '☀️ Clear';
  $_icon   = explode(' ', $_entry)[0];
  $_cond   = trim(substr($_entry, mb_strlen($_icon)));

  $_uid = 'vkW' . substr(md5(request()->path()), 0, 5);
@endphp

<section class="vk-weather" aria-label="Varanasi Weather">
  <div class="container">
    <div class="vk-weather__inner">
      <div class="vk-weather__left">
        <div class="vk-weather__heading">Weather in Varanasi</div>
        <div class="vk-weather__main">
          <div class="vk-weather__temp" id="{{ $_uid }}Temp">{{ $_tc }}&deg;</div>
          <span class="vk-weather__icon" aria-hidden="true">{{ $_icon }}</span>
          <span class="vk-weather__condition">{{ $_cond }}</span>
        </div>
        <div class="vk-weather__toggle">
          <button class="vk-wt-btn active" id="{{ $_uid }}BtnC" onclick="vkWUnit('{{ $_uid }}','C')">Celsius</button>
          <span class="vk-wt-sep">|</span>
          <button class="vk-wt-btn"        id="{{ $_uid }}BtnF" onclick="vkWUnit('{{ $_uid }}','F')">Fahrenheit</button>
        </div>
      </div>
      <div class="vk-weather__stats">
        <div class="vk-weather__stat"><span class="vk-ws-lbl">Sunrise</span><span class="vk-ws-val">{{ $_sun }}</span></div>
        <div class="vk-weather__stat"><span class="vk-ws-lbl">Sunset</span><span class="vk-ws-val">{{ $_set }}</span></div>
        <div class="vk-weather__stat"><span class="vk-ws-lbl">Low</span><span class="vk-ws-val" id="{{ $_uid }}Low">{{ $_lc }}&deg;</span></div>
        <div class="vk-weather__stat"><span class="vk-ws-lbl">High</span><span class="vk-ws-val" id="{{ $_uid }}High">{{ $_hc }}&deg;</span></div>
        <div class="vk-weather__stat"><span class="vk-ws-lbl">Humidity</span><span class="vk-ws-val">{{ $_hum }}%</span></div>
        <div class="vk-weather__stat"><span class="vk-ws-lbl">Wind</span><span class="vk-ws-val">{{ $_wind }} km/h</span></div>
      </div>
    </div>
  </div>
</section>
<script>
(function(){
  var d={'{{ $_uid }}':{c:{{ $_tc }},f:{{ $_tf }},lc:{{ $_lc }},lf:{{ $_lf }},hc:{{ $_hc }},hf:{{ $_hf }}}};
  window.vkWUnit=function(id,u){
    var v=d[id];if(!v)return;
    document.getElementById(id+'Temp').innerHTML=(u==='C'?v.c:v.f)+'&deg;';
    document.getElementById(id+'Low').innerHTML=(u==='C'?v.lc:v.lf)+'&deg;';
    document.getElementById(id+'High').innerHTML=(u==='C'?v.hc:v.hf)+'&deg;';
    document.getElementById(id+'BtnC').classList.toggle('active',u==='C');
    document.getElementById(id+'BtnF').classList.toggle('active',u==='F');
  };
})();
</script>
@endif

{{-- Scroll-reveal: runs after DOM is fully parsed --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
  var els = document.querySelectorAll('.vk-reveal');
  if (!els.length) return;
  if (!('IntersectionObserver' in window)) {
    els.forEach(function(el){ el.classList.add('is-visible'); });
    return;
  }
  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if (e.isIntersecting){ e.target.classList.add('is-visible'); io.unobserve(e.target); }
    });
  }, { threshold: 0.10 });
  els.forEach(function(el){ io.observe(el); });
});
</script>
