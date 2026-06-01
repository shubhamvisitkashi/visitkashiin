@extends('admin.layouts.app')
@section('content')
<style>
@import url('/visitkashiin/public/backend/assets/css/admin-saas.css');
:root{--sp:#4F46E5;--sp-lt:#EEF2FF;--sg:#10B981;--sr:#EF4444;--st:#0F172A;--sm:#64748B;--sb:#E2E8F0;}
.sf-form-page{padding:24px;max-width:900px;}
.sf-form-hero{background:linear-gradient(135deg,#4F46E5,#7C3AED);border-radius:16px;padding:22px 26px;margin-bottom:24px;display:flex;align-items:center;gap:16px;position:relative;overflow:hidden;}
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
.sf-field:last-child{margin-bottom:0;}
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
/* Strength bar */
.sf-strength{margin-top:6px;}
.sf-strength-bar{height:4px;border-radius:2px;background:#E2E8F0;overflow:hidden;margin-bottom:3px;}
.sf-strength-fill{height:100%;border-radius:2px;width:0;transition:width .3s,background .3s;}
.sf-strength-text{font-size:.7rem;color:var(--sm);}
/* Avatar upload */
.sf-av-upload{display:flex;align-items:center;gap:16px;padding:16px;background:#F8FAFC;border:1.5px dashed var(--sb);border-radius:12px;cursor:pointer;transition:border-color .2s;}
.sf-av-upload:hover{border-color:var(--sp);}
.sf-av-preview{width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#6366F1,#8B5CF6);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:#fff;flex-shrink:0;overflow:hidden;border:3px solid var(--sb);}
.sf-av-preview img{width:100%;height:100%;object-fit:cover;}
.sf-av-info{flex:1;}
.sf-av-info strong{display:block;font-size:.84rem;font-weight:700;color:var(--st);margin-bottom:2px;}
.sf-av-info span{font-size:.73rem;color:var(--sm);}
/* Submit */
.sf-submit-wrap{display:flex;align-items:center;gap:12px;padding:20px 24px;border-top:1px solid var(--sb);background:#FAFAFA;}
.sf-submit{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;border-radius:12px;padding:11px 28px;font-size:.9rem;font-weight:700;cursor:pointer;transition:all .2s;box-shadow:0 4px 14px rgba(79,70,229,.35);}
.sf-submit:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(79,70,229,.4);}
.sf-back{display:inline-flex;align-items:center;gap:7px;background:transparent;color:var(--sm);border:1.5px solid var(--sb);border-radius:12px;padding:10px 20px;font-size:.88rem;font-weight:600;text-decoration:none;transition:all .2s;}
.sf-back:hover{background:#F1F5F9;color:var(--st);border-color:#CBD5E1;}
.sf-error{font-size:.73rem;color:var(--sr);margin-top:4px;display:flex;align-items:center;gap:4px;}
.sf-note{font-size:.73rem;color:var(--sm);margin-top:4px;}
@media(max-width:640px){.sf-form-page{padding:12px;}.sf-form-card-body{padding:16px 14px;}.sf-submit-wrap{flex-wrap:wrap;padding:16px 14px;}}
</style>

<div class="sf-form-page">

  {{-- Hero --}}
  <div class="sf-form-hero">
    <div class="sf-form-hero-icon"><i data-feather="user-plus"></i></div>
    <div class="sf-form-hero-text">
      <h1>Add New Staff Member</h1>
      <p>Fill in the details below to create a new team member account</p>
    </div>
  </div>

  <form action="{{ route('staffs.store') }}" method="POST" enctype="multipart/form-data" id="staffForm">
    @csrf

    {{-- Profile Photo --}}
    <div class="sf-form-card">
      <div class="sf-form-card-head">
        <div class="sf-form-card-icon"><i data-feather="image"></i></div>
        <p class="sf-form-card-title">Profile Photo</p>
      </div>
      <div class="sf-form-card-body">
        <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp" style="display:none;">
        <div class="sf-av-upload" onclick="document.getElementById('avatarInput').click()">
          <div class="sf-av-preview" id="avatarPreview">&#128100;</div>
          <div class="sf-av-info">
            <strong id="avatarFileName">Click to choose a photo</strong>
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
              <input type="text" name="name" class="sf-input" value="{{ old('name') }}" required placeholder="e.g. Rahul Sharma">
              @error('name')<p class="sf-error">{{ $message }}</p>@enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="sf-field">
              <label class="sf-label">Email Address <span>*</span></label>
              <input type="email" name="email" class="sf-input" value="{{ old('email') }}" required placeholder="staff@visitkashi.com">
              @error('email')<p class="sf-error">{{ $message }}</p>@enderror
            </div>
          </div>
          <div class="col-md-4">
            <div class="sf-field">
              <label class="sf-label">Role <span>*</span></label>
              <select name="roles" class="sf-select" required>
                <option value="">— Select Role —</option>
                @foreach($roles as $role)
                  <option value="{{ $role }}" {{ old('roles') == $role ? 'selected' : '' }}>{{ $role }}</option>
                @endforeach
              </select>
              @error('roles')<p class="sf-error">{{ $message }}</p>@enderror
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Password --}}
    <div class="sf-form-card">
      <div class="sf-form-card-head">
        <div class="sf-form-card-icon" style="background:linear-gradient(135deg,#10B981,#059669);"><i data-feather="lock"></i></div>
        <p class="sf-form-card-title">Set Password</p>
      </div>
      <div class="sf-form-card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="sf-field">
              <label class="sf-label">Password <span>*</span></label>
              <div class="sf-input-wrap">
                <input type="password" name="password" id="sfPwd" class="sf-input has-eye" required placeholder="Min. 8 characters" autocomplete="new-password">
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
              <label class="sf-label">Confirm Password <span>*</span></label>
              <div class="sf-input-wrap">
                <input type="password" name="confirm-password" id="sfPwd2" class="sf-input has-eye" required placeholder="Repeat password" autocomplete="new-password">
                <button type="button" class="sf-eye" onclick="sfToggleEye('sfPwd2','sfEye2')"><i data-feather="eye" id="sfEye2"></i></button>
              </div>
              <p class="sf-note" id="sfMatchNote" style="display:none;"></p>
              @error('confirm-password')<p class="sf-error">{{ $message }}</p>@enderror
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="sf-submit-wrap">
      <button type="submit" class="sf-submit" id="sfSaveBtn">
        <i data-feather="check" style="width:16px;height:16px;stroke:#fff;"></i> Add Staff Member
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

    // Avatar preview
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

    // Password strength
    document.getElementById('sfPwd').addEventListener('input', function() {
        var v=this.value, s=0;
        if(v.length>=8)s++; if(/[A-Z]/.test(v))s++; if(/[0-9]/.test(v))s++; if(/[^A-Za-z0-9]/.test(v))s++;
        var fill=document.getElementById('sfStrFill'), text=document.getElementById('sfStrText');
        var map=['','Weak','Fair','Good','Strong'], col=['','#EF4444','#F59E0B','#3B82F6','#10B981'];
        fill.style.width=(s*25)+'%'; fill.style.background=col[s]||'#E2E8F0';
        text.textContent=v.length?map[s]:''; text.style.color=col[s];
    });

    // Match check
    document.getElementById('sfPwd2').addEventListener('input', function() {
        var pw=document.getElementById('sfPwd').value, note=document.getElementById('sfMatchNote');
        if(!this.value){note.style.display='none';return;}
        var match=pw===this.value;
        note.style.display='flex'; note.style.color=match?'#10B981':'#EF4444';
        note.textContent=match?'✓ Passwords match':'✗ Passwords do not match';
    });
});

function sfToggleEye(inputId, iconId) {
    var inp=document.getElementById(inputId), icon=document.getElementById(iconId);
    var show=inp.type==='password';
    inp.type=show?'text':'password';
    icon.setAttribute('data-feather', show?'eye-off':'eye');
    feather.replace();
}
</script>
@endsection
