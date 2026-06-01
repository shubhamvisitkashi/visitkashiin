<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Booking Calendar — Enter PIN</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#2c2c54 0%,#7a3dec 100%);font-family:'Inter',system-ui,sans-serif;}
.pin-card{background:#fff;border-radius:20px;padding:40px 36px;width:100%;max-width:380px;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.3);}
.pin-icon{width:64px;height:64px;background:linear-gradient(135deg,#7a3dec,#1c97e9);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;}
.pin-icon svg{width:30px;height:30px;stroke:#fff;}
h1{font-size:1.35rem;font-weight:800;color:#1a1a2e;margin-bottom:6px;}
p{font-size:.88rem;color:#6B7280;margin-bottom:28px;line-height:1.5;}
.pin-inputs{display:flex;gap:8px;justify-content:center;margin-bottom:20px;}
.pin-inputs input{width:46px;height:54px;border:2px solid #E5E7EB;border-radius:10px;text-align:center;font-size:1.4rem;font-weight:800;color:#1a1a2e;outline:none;transition:border-color .15s;}
.pin-inputs input:focus{border-color:#7a3dec;box-shadow:0 0 0 3px rgba(122,61,236,.15);}
.pin-err{background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;border-radius:8px;padding:10px 14px;font-size:.82rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:7px;}
.pin-btn{width:100%;height:48px;background:linear-gradient(135deg,#7a3dec,#1c97e9);color:#fff;border:none;border-radius:12px;font-size:.95rem;font-weight:700;cursor:pointer;transition:opacity .2s;}
.pin-btn:hover{opacity:.9;}
.pin-brand{margin-top:24px;font-size:.8rem;color:#9CA3AF;}
.pin-brand strong{color:#7a3dec;}
</style>
</head>
<body>
<div class="pin-card">
    <div class="pin-icon">
        <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    </div>
    @php $len = 6; @endphp
    <h1>Booking Calendar</h1>
    <p>Enter the {{ $len }}-digit PIN to view the calendar</p>

    @if($error)
    <div class="pin-err">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $error }}
    </div>
    @endif

    <form action="{{ route('public.calendar.verify') }}" method="POST" id="pinForm">
        @csrf
        <input type="hidden" name="pin" id="pinHidden">
        <div class="pin-inputs" id="pinBoxes">
            @for($i=0;$i<$len;$i++)
            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                   id="pd{{ $i }}" autocomplete="off">
            @endfor
        </div>
        <button type="submit" class="pin-btn">Unlock Calendar</button>
    </form>
    <div class="pin-brand">Powered by <strong>Visit Kashi</strong> Admin</div>
</div>
<script>
var boxes = document.querySelectorAll('#pinBoxes input');
boxes.forEach(function(box, i) {
    box.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g,'');
        if (this.value && i < boxes.length - 1) boxes[i+1].focus();
        updateHidden();
    });
    box.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && !this.value && i > 0) boxes[i-1].focus();
    });
    box.addEventListener('paste', function(e) {
        e.preventDefault();
        var paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'');
        paste.split('').forEach(function(ch, j){ if(boxes[i+j]) boxes[i+j].value = ch; });
        updateHidden();
        var last = Math.min(i + paste.length, boxes.length - 1);
        boxes[last].focus();
    });
});
function updateHidden() {
    document.getElementById('pinHidden').value = Array.from(boxes).map(b=>b.value).join('');
}
document.getElementById('pinForm').addEventListener('submit', function() { updateHidden(); });
boxes[0].focus();
</script>
</body>
</html>
