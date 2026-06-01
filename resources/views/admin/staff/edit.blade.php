@extends('admin.layouts.app')
@section('content')
<style>
:root{--sp:#4F46E5;--sp-lt:#EEF2FF;--sg:#10B981;--sr:#EF4444;--st:#0F172A;--sm:#64748B;--sb:#E2E8F0;}
.sf-form-page{padding:24px;max-width:900px;}
.sf-form-hero{background:linear-gradient(135deg,#0f766e,#0d9488);border-radius:16px;padding:22px 26px;margin-bottom:24px;display:flex;align-items:center;gap:16px;position:relative;overflow:hidden;}
.sf-form-hero::before{content:'';position:absolute;top:-30px;right:-30px;width:140px;height:140px;background:rgba(255,255,255,.07);border-radius:50%;}
.sf-form-hero-icon{width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1;}
.sf-form-hero-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.sf-form-hero-text{position:relative;z-index:1;}
.sf-form-hero-text h1{color:#fff;font-size:1.15rem;font-weight:700;margin:0 0 2px;}
.sf-form-hero-text p{color:rgba(255,255,255,.7);font-size:.8rem;margin:0;}
.sf-form-card{background:#fff;border-radius:16px;border:1px solid var(--sb);box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;margin-bottom:20px;}
.sf-form-card-head{display:flex;align-items:center;gap:10px;padding:16px 20px;border-bottom:1px solid var(--sb);background:linear-gradient(135deg,var(--sp-lt),#F5F3FF);}
.sf-form-card-icon{width:34px;height:34px;border-radius:9px;background:var(--sp);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sf-form-card-icon i[data-feather]{width:16px;height:16px;stroke:#fff;}
.sf-form-card-title{font-size:.9rem;font-weight:700;color:var(--st);margin:0;}
.sf-form-card-body{padding:22px 24px;}
.sf-field{margin-bottom:18px;}
.sf-label{display:block;font-size:.76rem;font-weight:700;color:var(--sm);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em;}
.sf-label span{color:var(--sr);}
.sf-input-wrap{position:relative;}
.sf-input{width:100%;height:46px;background:#F8FAFC;border:1.5px solid var(--sb);border-radius:11px;padding:0 14px;font-size:.88rem;color:var(--st);outline:none;transition:border-color .2s,box-shadow .2s;font-family:inherit;}
.sf-input:focus{border-color:var(--sp);background:#fff;box-shadow:0 0 0 3px rgba(79,70,229,.1);}
.sf-input.has-eye{padding-right:46px;}
.sf-eye{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:28px;height:28px;border:none;background:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--sm);padding:0;transition:color .2s;}
.sf-eye:hover{color:var(--sp);}
.sf-eye i[data-feather]{width:16px;height:16px;}
.sf-select{width:100%;height:46px;background:#F8FAFC;border:1.5px solid var(--sb);border-radius:11px;padding:0 14px;font-size:.88rem;color:var(--st);outline:none;cursor:pointer;transition:border-color .2s;}
.sf-select:focus{border-color:var(--sp);background:#fff;box-shadow:0 0 0 3px rgba(79,70,229,.1);}
.sf-strength{margin-top:6px;}
.sf-strength-bar{height:4px;border-radius:2px;background:#E2E8F0;overflow:hidden;margin-bottom:3px;}
.sf-strength-fill{height:100%;border-radius:2px;width:0;transition:width .3s,background .3s;}
.sf-strength-text{font-size:.7rem;color:var(--sm);}
/* Avatar */
.sf-av-upload{display:flex;align-items:center;gap:16px;padding:16px;background:#F8FAFC;border:1.5px dashed var(--sb);border-radius:12px;cursor:pointer;transition:border-color .2s;}
.sf-av-upload:hover{border-color:var(--sp);}
.sf-av-preview{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#6366F1,#8B5CF6);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#fff;flex-shrink:0;overflow:hidden;border:3px solid var(--sb);}
.sf-av-preview img{width:100%;height:100%;object-fit:cover;}
.sf-av-info strong{display:block;font-size:.84rem;font-weight:700;color:var(--st);margin-bottom:2px;}
.sf-av-info span{font-size:.73rem;color:var(--sm);}
/* Submit */
.sf-submit-wrap{display:flex;align-items:center;gap:12px;padding:20px 24px;border-top:1px solid var(--sb);background:#FAFAFA;}
.sf-submit{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;border-radius:12px;padding:11px 28px;font-size:.9rem;font-weight:700;cursor:pointer;transition:all .2s;box-shadow:0 4px 14px rgba(79,70,229,.35);}
.sf-submit:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(79,70,229,.4);}
.sf-back{display:inline-flex;align-items:center;gap:7px;background:transparent;color:var(--sm);border:1.5px solid var(--sb);border-radius:12px;padding:10px 20px;font-size:.88rem;font-weight:600;text-decoration:none;transition:all .2s;}
.sf-back:hover{background:#F1F5F9;color:var(--st);}
.sf-error{font-size:.73rem;color:var(--sr);margin-top:4px;}
.sf-note{font-size:.73rem;color:var(--sm);margin-top:4px;}
.sf-pwd-note{background:#FFFBEB;border:1px solid #FDE68A;border-radius:8px;padding:8px 12px;font-size:.75rem;color:#92400E;margin-bottom:16px;}
/* Current password box */
.sf-current-pwd-box{background:#F0FDF4;border:1.5px solid #86EFAC;border-radius:12px;padding:14px 16px;margin-bottom:16px;}
.sf-current-pwd-header{display:flex;align-items:flex-start;gap:10px;margin-bottom:12px;}
.sf-current-pwd-icon{font-size:20px;flex-shrink:0;line-height:1;}
.sf-current-pwd-header strong{display:block;font-size:.84rem;font-weight:700;color:#15803D;}
.sf-current-pwd-header span{display:block;font-size:.71rem;color:#16A34A;margin-top:1px;}
@media(max-width:640px){.sf-form-page{padding:12px;}.sf-form-card-body{padding:16px 14px;}.sf-submit-wrap{flex-wrap:wrap;padding:16px 14px;}}
</style>

<div class="sf-form-page">

  @if(session('success'))
  <div style="display:flex;align-items:center;gap:10px;background:#ECFDF5;border:1.5px solid #6EE7B7;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:.88rem;font-weight:600;color:#065F46;">
    <i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;"></i>{{ session('success') }}
  </div>
  @endif

  {{-- Hero --}}
  <div class="sf-form-hero">
    <div class="sf-form-hero-icon"><i data-feather="edit-2"></i></div>
    <div class="sf-form-hero-text">
      <h1>Edit Staff — {{ $data->name }}</h1>
      <p>Update account details, role, password, or profile photo</p>
    </div>
  </div>

  <form action="{{ route('staffs.update', $data->id) }}" method="POST" enctype="multipart/form-data" id="staffForm">
    @csrf
    @method('PATCH')

    {{-- Profile Photo --}}
    <div class="sf-form-card">
      <div class="sf-form-card-head">
        <div class="sf-form-card-icon"><i data-feather="image"></i></div>
        <p class="sf-form-card-title">Profile Photo</p>
      </div>
      <div class="sf-form-card-body">
        <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp" style="display:none;">
        <div class="sf-av-upload" onclick="document.getElementById('avatarInput').click()">
          <div class="sf-av-preview" id="avatarPreview">
            @if($data->avatar_url)
              <img src="{{ $data->avatar_url }}" alt="{{ $data->name }}">
            @else
              {{ strtoupper(substr($data->name,0,1)) }}
            @endif
          </div>
          <div class="sf-av-info">
            <strong id="avatarFileName">{{ $data->avatar ? 'Current photo — click to change' : 'Click to choose a photo' }}</strong>
            <span>JPG, PNG, WebP · Max 2MB · Recommended 200×200px</span>
          </div>
        </div>
        @error('avatar')<p class="sf-error">{{ $message }}</p>@enderror
      </div>
    </div>

    {{-- Account Info --}}
    <div class="sf-form-card">
      <div class="sf-form-card-head">
        <div class="sf-form-card-icon"><i data-feather="user"></i></div>
        <p class="sf-form-card-title">Account Information</p>
      </div>
      <div class="sf-form-card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="sf-field">
              <label class="sf-label">Full Name <span>*</span></label>
              <input type="text" name="name" class="sf-input" value="{{ old('name', $data->name) }}" required>
              @error('name')<p class="sf-error">{{ $message }}</p>@enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="sf-field">
              <label class="sf-label">Email Address <span>*</span></label>
              <input type="email" name="email" class="sf-input" value="{{ old('email', $data->email) }}" required>
              @error('email')<p class="sf-error">{{ $message }}</p>@enderror
            </div>
          </div>
          <div class="col-md-4">
            <div class="sf-field">
              <label class="sf-label">Role <span>*</span></label>
              <select name="roles" class="sf-select" required>
                <option value="">— Select Role —</option>
                @foreach($roles as $role)
                  <option value="{{ $role }}" {{ in_array($role, $userRole) ? 'selected' : '' }}>{{ $role }}</option>
                @endforeach
              </select>
              @error('roles')<p class="sf-error">{{ $message }}</p>@enderror
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Change Password --}}
    <div class="sf-form-card">
      <div class="sf-form-card-head">
        <div class="sf-form-card-icon" style="background:linear-gradient(135deg,#10B981,#059669);"><i data-feather="lock"></i></div>
        <p class="sf-form-card-title">Change Password <span style="font-size:.72rem;font-weight:400;color:var(--sm);">(leave blank to keep current)</span></p>
      </div>
      <div class="sf-form-card-body">
        {{-- Current Password (admin view only) --}}
        @if($data->plain_password)
        <div class="sf-current-pwd-box">
          <div class="sf-current-pwd-header">
            <span class="sf-current-pwd-icon">&#128274;</span>
            <div>
              <strong>Current Password (Admin View)</strong>
              <span>Staff's saved password — visible only to super admin</span>
            </div>
          </div>
          <div class="sf-input-wrap" style="max-width:360px;">
            <input type="password" id="sfCurrentPwd" class="sf-input has-eye"
                   value="{{ $data->plain_password }}" readonly
                   style="background:#F0FDF4;border-color:#86EFAC;color:#15803D;font-weight:700;cursor:default;">
            <button type="button" class="sf-eye" onclick="sfToggleEye('sfCurrentPwd','sfEyeCurrent')" title="Show / Hide">
              <i data-feather="eye" id="sfEyeCurrent"></i>
            </button>
          </div>
          <p class="sf-note" style="margin-top:6px;">
            &#128203;
            <button type="button" onclick="sfCopyPwd()" style="background:none;border:none;color:#7C3AED;font-size:.72rem;font-weight:700;cursor:pointer;padding:0;">Copy to clipboard</button>
            <span id="sfCopyDone" style="color:#10B981;font-size:.72rem;display:none;"> ✓ Copied!</span>
          </p>
        </div>
        @else
        <p class="sf-pwd-note" style="background:#FEF9C3;border-color:#FDE047;color:#713F12;">
          &#9888; No saved password found. A plain-text copy will be saved the next time this staff's password is updated.
        </p>
        @endif

        <p class="sf-pwd-note">&#128274; Leave both fields empty to keep the current password unchanged.</p>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="sf-field">
              <label class="sf-label">New Password</label>
              <div class="sf-input-wrap">
                <input type="password" name="password" id="sfPwd" class="sf-input has-eye" placeholder="Min. 8 characters" autocomplete="new-password">
                <button type="button" class="sf-eye" onclick="sfToggleEye('sfPwd','sfEye1')"><i data-feather="eye" id="sfEye1"></i></button>
              </div>
              <div class="sf-strength">
                <div class="sf-strength-bar"><div class="sf-strength-fill" id="sfStrFill"></div></div>
                <span class="sf-strength-text" id="sfStrText"></span>
              </div>
              @error('password')<p class="sf-error">{{ $message }}</p>@enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="sf-field">
              <label class="sf-label">Confirm New Password</label>
              <div class="sf-input-wrap">
                <input type="password" name="confirm-password" id="sfPwd2" class="sf-input has-eye" placeholder="Repeat password" autocomplete="new-password">
                <button type="button" class="sf-eye" onclick="sfToggleEye('sfPwd2','sfEye2')"><i data-feather="eye" id="sfEye2"></i></button>
              </div>
              <p class="sf-note" id="sfMatchNote" style="display:none;"></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="sf-submit-wrap">
      <button type="submit" class="sf-submit">
        <i data-feather="save" style="width:16px;height:16px;stroke:#fff;"></i> Save Changes
      </button>
      <a href="{{ route('staffs.index') }}" class="sf-back">
        <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back
      </a>
    </div>

  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();

    document.getElementById('avatarInput').addEventListener('change', function() {
        var file = this.files[0]; if (!file) return;
        var reader = new FileReader();
        reader.onload = function(e) {
            var p = document.getElementById('avatarPreview');
            p.innerHTML = '<img src="'+e.target.result+'" alt="Preview">';
            document.getElementById('avatarFileName').textContent = file.name;
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('sfPwd').addEventListener('input', function() {
        var v=this.value, s=0;
        if(v.length>=8)s++; if(/[A-Z]/.test(v))s++; if(/[0-9]/.test(v))s++; if(/[^A-Za-z0-9]/.test(v))s++;
        var fill=document.getElementById('sfStrFill'), text=document.getElementById('sfStrText');
        var map=['','Weak','Fair','Good','Strong'], col=['','#EF4444','#F59E0B','#3B82F6','#10B981'];
        fill.style.width=(s*25)+'%'; fill.style.background=col[s]||'#E2E8F0';
        text.textContent=v.length?map[s]:''; text.style.color=col[s];
    });

    document.getElementById('sfPwd2').addEventListener('input', function() {
        var pw=document.getElementById('sfPwd').value, note=document.getElementById('sfMatchNote');
        if(!this.value){note.style.display='none';return;}
        var match=pw===this.value;
        note.style.display='flex'; note.style.color=match?'#10B981':'#EF4444';
        note.textContent=match?'✓ Passwords match':'✗ Passwords do not match';
    });
});

function sfCopyPwd() {
    var val = document.getElementById('sfCurrentPwd').value;
    navigator.clipboard.writeText(val).then(function() {
        var done = document.getElementById('sfCopyDone');
        done.style.display = 'inline';
        setTimeout(function(){ done.style.display = 'none'; }, 2000);
    });
}

function sfToggleEye(inputId, iconId) {
    var inp=document.getElementById(inputId), icon=document.getElementById(iconId);
    var show=inp.type==='password';
    inp.type=show?'text':'password';
    icon.setAttribute('data-feather', show?'eye-off':'eye');
    feather.replace();
}
</script>
@endsection
