@extends('admin.layouts.app')
@section('content')
<style>
:root {
  --pr:#4F46E5;--pr-lt:#EEF2FF;--gr:#10B981;--rd:#EF4444;
  --txt:#0F172A;--sub:#475569;--muted:#94A3B8;
  --bg:#F1F5F9;--card:#fff;--bdr:#E2E8F0;--r:16px;--t:.2s ease;
}
.prof-page{background:var(--bg);min-height:100vh;padding:28px 24px;}
.prof-hero{background:linear-gradient(135deg,#4F46E5 0%,#7C3AED 100%);border-radius:var(--r);padding:32px;margin-bottom:24px;position:relative;overflow:hidden;}
.prof-hero::before{content:'';position:absolute;top:-40px;right:-40px;width:200px;height:200px;background:rgba(255,255,255,.07);border-radius:50%;}
.prof-hero-inner{position:relative;z-index:1;display:flex;align-items:center;gap:24px;flex-wrap:wrap;}
.prof-avatar-wrap{position:relative;flex-shrink:0;}
.prof-avatar{width:100px;height:100px;border-radius:50%;border:4px solid rgba(255,255,255,.4);object-fit:cover;display:block;box-shadow:0 8px 24px rgba(0,0,0,.25);}
.prof-avatar-init{width:100px;height:100px;border-radius:50%;border:4px solid rgba(255,255,255,.4);background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;color:#fff;box-shadow:0 8px 24px rgba(0,0,0,.25);}
.prof-avatar-edit{position:absolute;bottom:2px;right:2px;width:30px;height:30px;border-radius:50%;background:#fff;border:2px solid var(--pr);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.2);transition:transform var(--t);}
.prof-avatar-edit:hover{transform:scale(1.15);}
.prof-avatar-edit svg{width:14px;height:14px;stroke:var(--pr);}
.prof-avatar-edit input{display:none;}
.prof-hero-name{font-size:1.5rem;font-weight:800;color:#fff;margin:0 0 4px;}
.prof-hero-email{font-size:.9rem;color:rgba(255,255,255,.72);margin:0 0 12px;}
.prof-hero-role{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.25);color:#fff;border-radius:20px;padding:4px 14px;font-size:.78rem;font-weight:600;}
.prof-card{background:var(--card);border-radius:var(--r);border:1px solid var(--bdr);box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;margin-bottom:20px;}
.prof-card-head{display:flex;align-items:center;gap:12px;padding:20px 24px;border-bottom:1px solid var(--bdr);background:linear-gradient(135deg,var(--pr-lt),#F5F3FF);}
.prof-card-icon{width:38px;height:38px;border-radius:10px;background:var(--pr);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.prof-card-icon svg{width:18px;height:18px;stroke:#fff;}
.prof-card-title{font-size:1rem;font-weight:700;color:var(--txt);margin:0;}
.prof-card-sub{font-size:.78rem;color:var(--muted);margin:0;}
.prof-card-body{padding:24px;}
.prof-field{margin-bottom:20px;}
.prof-label{display:block;font-size:.78rem;font-weight:700;color:var(--sub);margin-bottom:7px;}
.prof-label span{color:var(--rd);}
.prof-input-wrap{position:relative;}
.prof-input{width:100%;height:48px;background:var(--bg);border:1.5px solid var(--bdr);border-radius:12px;padding:0 16px;font-size:.9rem;color:var(--txt);outline:none;transition:border-color var(--t),box-shadow var(--t);font-family:inherit;}
.prof-input:focus{border-color:var(--pr);background:#fff;box-shadow:0 0 0 3px rgba(79,70,229,.1);}
.prof-input.has-eye{padding-right:48px;}
.prof-eye{position:absolute;right:14px;top:50%;transform:translateY(-50%);width:28px;height:28px;border:none;background:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--muted);padding:0;}
.prof-eye:hover{color:var(--pr);}
.prof-eye svg{width:17px;height:17px;}
.prof-strength{margin-top:8px;}
.prof-strength-bar{height:4px;border-radius:2px;background:#E2E8F0;overflow:hidden;margin-bottom:4px;}
.prof-strength-fill{height:100%;border-radius:2px;width:0;transition:width .3s,background .3s;}
.prof-strength-text{font-size:.72rem;color:var(--muted);}
.prof-avatar-preview{display:none;align-items:center;gap:14px;padding:14px 16px;background:var(--pr-lt);border:1.5px dashed var(--pr);border-radius:12px;margin-top:10px;}
.prof-avatar-preview.show{display:flex;}
.prof-avatar-preview img{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid var(--pr);}
.prof-avatar-preview-name{font-size:.82rem;font-weight:700;color:var(--txt);}
.prof-avatar-preview-size{font-size:.73rem;color:var(--muted);}
.prof-avatar-clear{background:none;border:none;color:var(--rd);cursor:pointer;font-size:22px;padding:0;line-height:1;}
.prof-submit{width:100%;height:52px;border:none;border-radius:14px;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;font-size:.95rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;box-shadow:0 4px 16px rgba(79,70,229,.35);transition:transform var(--t),box-shadow var(--t);margin-top:4px;}
.prof-submit:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(79,70,229,.4);}
.prof-submit svg{width:20px;height:20px;stroke:#fff;}
.prof-note{font-size:.75rem;color:var(--muted);margin-top:6px;display:flex;align-items:center;gap:5px;}
.prof-note svg{width:12px;height:12px;flex-shrink:0;}
.prof-alert-success{display:flex;align-items:center;gap:12px;background:#ECFDF5;border:1.5px solid #6EE7B7;border-radius:12px;padding:14px 18px;margin-bottom:20px;font-size:.88rem;font-weight:600;color:#065F46;}
.prof-alert-success svg{width:20px;height:20px;stroke:#10B981;flex-shrink:0;}
/* Saved password box */
.prof-saved-pwd{background:#F0FDF4;border:1.5px solid #86EFAC;border-radius:12px;padding:14px 16px;margin-bottom:18px;}
.prof-saved-pwd-label{display:flex;align-items:center;gap:6px;font-size:.74rem;font-weight:700;color:#15803D;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;}

/* Staff password table */
.spl-table{width:100%;border-collapse:collapse;}
.spl-table th{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);padding:11px 18px;border-bottom:1px solid var(--bdr);background:#FAFAFA;text-align:left;}
.spl-table td{padding:12px 18px;border-bottom:1px solid #F1F5F9;vertical-align:middle;font-size:.84rem;color:var(--txt);}
.spl-table tbody tr:last-child td{border-bottom:none;}
.spl-table tbody tr:hover{background:#FAFBFF;}
.spl-user{display:flex;align-items:center;gap:10px;}
.spl-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff;flex-shrink:0;overflow:hidden;}
.spl-av img{width:100%;height:100%;object-fit:cover;}
.spl-email{color:var(--muted);font-size:.78rem;}
.spl-role{display:inline-flex;background:#EEF2FF;color:#4F46E5;font-size:.68rem;font-weight:700;padding:2px 9px;border-radius:20px;}
.spl-pwd-wrap{display:flex;align-items:center;gap:6px;}
.spl-pwd-input{height:34px;background:#F8FAFC;border:1.5px solid var(--bdr);border-radius:8px;padding:0 10px;font-size:.82rem;font-weight:700;color:var(--txt);width:160px;outline:none;font-family:monospace;}
.spl-eye,.spl-copy{width:30px;height:30px;border:1.5px solid var(--bdr);border-radius:7px;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--muted);transition:all .18s;flex-shrink:0;}
.spl-eye:hover{border-color:var(--pr);color:var(--pr);}
.spl-copy:hover{border-color:#10B981;color:#10B981;}
.spl-copy.copied{border-color:#10B981;color:#10B981;background:#F0FDF4;}
.spl-no-pwd{font-size:.75rem;color:var(--muted);font-style:italic;}
.spl-edit-btn{width:28px;height:28px;border:1.5px solid var(--bdr);border-radius:7px;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--muted);transition:all .18s;flex-shrink:0;}
.spl-edit-btn:hover{border-color:#6366f1;color:#6366f1;}
/* Inline reset form */
.spl-reset-form{margin-top:6px;}
.spl-reset-inner{display:flex;align-items:center;gap:5px;flex-wrap:wrap;}
.spl-reset-input{height:32px;border:1.5px solid #C7D2FE;border-radius:8px;background:#EEF2FF;padding:0 10px;font-size:.8rem;color:#1a1a1a;outline:none;width:150px;transition:border-color .18s;}
.spl-reset-input:focus{border-color:#6366f1;background:#fff;}
.spl-reset-save{height:32px;display:flex;align-items:center;gap:4px;background:#6366f1;color:#fff;border:none;border-radius:8px;padding:0 12px;font-size:.78rem;font-weight:700;cursor:pointer;transition:background .18s;}
.spl-reset-save:hover{background:#4F46E5;}
.spl-reset-cancel{height:32px;background:none;border:1.5px solid var(--bdr);border-radius:8px;padding:0 10px;font-size:.78rem;color:var(--muted);cursor:pointer;transition:all .18s;}
.spl-reset-cancel:hover{background:#F1F5F9;color:var(--txt);}
@media(max-width:768px){
  .spl-table thead{display:none;}
  .spl-table tbody tr{display:flex;flex-direction:column;padding:12px 16px;border-radius:12px;border:1px solid var(--bdr);margin-bottom:8px;}
  .spl-table td{padding:5px 0;border:none;font-size:.8rem;}
  .spl-pwd-input{width:120px;}
}
@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
@media(max-width:640px){.prof-page{padding:14px 12px;}.prof-hero{padding:22px 18px;margin-top:50px;}.prof-avatar,.prof-avatar-init{width:76px;height:76px;font-size:26px;}.prof-card-body{padding:18px 16px;}.prof-hero-name{font-size:1.15rem;}}
</style>

<div class="prof-page">

  @if(session('success'))
  <div class="prof-alert-success">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
  </div>
  @endif

  {{-- Hero --}}
  <div class="prof-hero">
    <div class="prof-hero-inner">
      <div class="prof-avatar-wrap">
        @if(auth()->user()->avatar_url)
          <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="prof-avatar" id="heroAvatar">
        @else
          <div class="prof-avatar-init" id="heroAvatarInit">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
        @endif
        <label class="prof-avatar-edit" title="Change photo">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          <input type="file" id="heroFileInput" accept="image/*">
        </label>
      </div>
      <div>
        <h1 class="prof-hero-name">{{ auth()->user()->name }}</h1>
        <p class="prof-hero-email">{{ auth()->user()->email }}</p>
        @php $role = auth()->user()->getRoleNames()->first() ?? 'Admin'; @endphp
        <span class="prof-hero-role">&#9989; {{ ucfirst($role) }}</span>
      </div>
    </div>
  </div>

  <form action="{{ route('change.password.store') }}" method="POST" enctype="multipart/form-data" id="profileForm">
    @csrf
    <div class="row g-4">

      {{-- Account Info --}}
      <div class="col-lg-6">
        <div class="prof-card">
          <div class="prof-card-head">
            <div class="prof-card-icon">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
              <p class="prof-card-title">Account Information</p>
              <p class="prof-card-sub">Update your name and profile photo</p>
            </div>
          </div>
          <div class="prof-card-body">

            <div class="prof-field">
              <label class="prof-label">Full Name <span>*</span></label>
              <input type="text" name="name" class="prof-input" value="{{ old('name', auth()->user()->name) }}" required placeholder="Your full name">
              @error('name')<p class="prof-note" style="color:var(--rd);">{{ $message }}</p>@enderror
            </div>

            <div class="prof-field">
              <label class="prof-label">Email Address</label>
              <input type="email" class="prof-input" value="{{ auth()->user()->email }}" readonly style="background:#f8f9fc;color:var(--muted);cursor:not-allowed;">
              <p class="prof-note">&#128274; Contact a super-admin to change your email.</p>
            </div>

            <div class="prof-field">
              <label class="prof-label">Profile Photo</label>
              <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp" style="display:none;">
              <button type="button" onclick="document.getElementById('avatarInput').click()"
                style="width:100%;height:48px;border:1.5px dashed var(--bdr);border-radius:12px;background:var(--bg);color:var(--sub);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all var(--t);"
                onmouseover="this.style.borderColor='var(--pr)';this.style.color='var(--pr)'"
                onmouseout="this.style.borderColor='var(--bdr)';this.style.color='var(--sub)'">
                &#128247; Choose Photo &nbsp;(JPG · PNG · WebP · max 2MB)
              </button>
              <div class="prof-avatar-preview" id="avatarPreview">
                <img id="avatarPreviewImg" src="" alt="Preview">
                <div style="flex:1;min-width:0;">
                  <div class="prof-avatar-preview-name" id="avatarPreviewName"></div>
                  <div class="prof-avatar-preview-size" id="avatarPreviewSize"></div>
                </div>
                <button type="button" class="prof-avatar-clear" onclick="clearAvatar()">&times;</button>
              </div>
              @error('avatar')<p class="prof-note" style="color:var(--rd);">{{ $message }}</p>@enderror
            </div>

          </div>
        </div>
      </div>

      {{-- Change Password --}}
      <div class="col-lg-6">
        <div class="prof-card">
          <div class="prof-card-head">
            <div class="prof-card-icon" style="background:linear-gradient(135deg,#10B981,#059669);">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
              <p class="prof-card-title">Change Password</p>
              <p class="prof-card-sub">Leave blank to keep current password</p>
            </div>
          </div>
          <div class="prof-card-body">

            {{-- Saved password (visible to self) --}}
            @if(auth()->user()->plain_password)
            <div class="prof-saved-pwd">
              <div class="prof-saved-pwd-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"><path stroke="#15803D" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Saved Password
              </div>
              <div class="prof-input-wrap">
                <input type="password" id="profSavedPwd" class="prof-input has-eye"
                       value="{{ auth()->user()->plain_password }}" readonly
                       style="background:#F0FDF4;border-color:#86EFAC;color:#15803D;font-weight:700;font-size:.95rem;letter-spacing:.05em;cursor:default;">
                <button type="button" class="prof-eye" onclick="toggleEye('profSavedPwd','eyeSaved')" title="Show / Hide">
                  <svg id="eyeSaved" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
              </div>
              <div style="display:flex;align-items:center;gap:8px;margin-top:6px;">
                <button type="button" onclick="profCopyPwd()" style="background:none;border:none;color:#16A34A;font-size:.72rem;font-weight:700;cursor:pointer;padding:0;display:flex;align-items:center;gap:4px;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                  Copy to clipboard
                </button>
                <span id="profCopyDone" style="color:#10B981;font-size:.72rem;display:none;">✓ Copied!</span>
              </div>
            </div>
            @else
            <div class="prof-note" style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:8px;padding:9px 12px;margin-bottom:16px;color:#92400E;font-size:.75rem;font-weight:500;display:flex;align-items:center;gap:6px;">
              ⚠️ No saved password found. It will be saved next time you update your password below.
            </div>
            @endif

            <div class="prof-field">
              <label class="prof-label">New Password</label>
              <div class="prof-input-wrap">
                <input type="password" name="password" id="newPassword" class="prof-input has-eye" placeholder="Min. 8 characters" autocomplete="new-password">
                <button type="button" class="prof-eye" onclick="toggleEye('newPassword','eyeNew')">
                  <svg id="eyeNew" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
              </div>
              <div class="prof-strength">
                <div class="prof-strength-bar"><div class="prof-strength-fill" id="strengthFill"></div></div>
                <span class="prof-strength-text" id="strengthText"></span>
              </div>
              @error('password')<p class="prof-note" style="color:var(--rd);">{{ $message }}</p>@enderror
            </div>

            <div class="prof-field">
              <label class="prof-label">Confirm New Password</label>
              <div class="prof-input-wrap">
                <input type="password" name="password_confirmation" id="confirmPassword" class="prof-input has-eye" placeholder="Repeat new password" autocomplete="new-password">
                <button type="button" class="prof-eye" onclick="toggleEye('confirmPassword','eyeConfirm')">
                  <svg id="eyeConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
              </div>
              <div class="prof-note" id="matchNote" style="display:none;"></div>
            </div>

            <p class="prof-note">&#8505;&#65039; Password changes will log you out. Name/photo updates won't.</p>

          </div>
        </div>
      </div>
    </div>

    <button type="submit" class="prof-submit" id="saveBtn">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      Save Changes
    </button>
  </form>

  {{-- ── Staff & Admin Saved Passwords (Super Admin View) ── --}}
  @if(isset($staffList) && $staffList->isNotEmpty())
  <div class="prof-card" style="margin-top:8px;">
    <div class="prof-card-head" style="background:linear-gradient(135deg,#1a0d2e,#2d1b69);">
      <div class="prof-card-icon" style="background:rgba(255,255,255,.15);">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
      <div>
        <p class="prof-card-title" style="color:#fff;">Staff & Admin Passwords</p>
        <p class="prof-card-sub" style="color:rgba(255,255,255,.55);">Saved login passwords — visible to Super Admin only</p>
      </div>
    </div>
    <div class="prof-card-body" style="padding:0;">
      <table class="spl-table">
        <thead>
          <tr>
            <th>Member</th>
            <th>Email</th>
            <th>Role</th>
            <th>Password</th>
          </tr>
        </thead>
        <tbody>
          @foreach($staffList as $staff)
          @php $initial = strtoupper(substr($staff->name,0,1)); @endphp
          <tr>
            <td>
              <div class="spl-user">
                <div class="spl-av">
                  @if($staff->avatar_url)
                    <img src="{{ $staff->avatar_url }}" alt="{{ $staff->name }}">
                  @else
                    {{ $initial }}
                  @endif
                </div>
                <span>{{ $staff->name }}</span>
              </div>
            </td>
            <td class="spl-email">{{ $staff->email }}</td>
            <td>
              @foreach($staff->getRoleNames() as $role)
                <span class="spl-role">{{ $role }}</span>
              @endforeach
            </td>
            <td>
              @if($staff->plain_password)
              {{-- Show saved password --}}
              <div class="spl-pwd-wrap">
                <input type="password" class="spl-pwd-input" value="{{ $staff->plain_password }}" readonly id="spwd_{{ $staff->id }}">
                <button type="button" class="spl-eye" onclick="splToggle('spwd_{{ $staff->id }}', this)" title="Show/Hide">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
                <button type="button" class="spl-copy" onclick="splCopy('spwd_{{ $staff->id }}', this)" title="Copy">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </button>
                {{-- Edit/reset inline --}}
                <button type="button" class="spl-edit-btn" onclick="splShowReset({{ $staff->id }})" title="Change password">
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
              </div>
              @else
              {{-- No password saved — show reset form --}}
              @endif
              {{-- Inline reset form (shown for both cases; hidden by default if has password) --}}
              <div class="spl-reset-form" id="srf_{{ $staff->id }}" style="{{ $staff->plain_password ? 'display:none;' : '' }}">
                <form action="{{ route('staff.reset.password', $staff->id) }}" method="POST" class="spl-reset-inner">
                  @csrf
                  <input type="text" name="new_password" class="spl-reset-input"
                         placeholder="Set new password"
                         value="{{ $staff->plain_password ?? '' }}"
                         autocomplete="off">
                  <button type="submit" class="spl-reset-save">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save
                  </button>
                  @if($staff->plain_password)
                  <button type="button" class="spl-reset-cancel" onclick="splHideReset({{ $staff->id }})">Cancel</button>
                  @endif
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif

</div>

<script>
function splShowReset(id) {
    document.getElementById('srf_'+id).style.display = 'block';
}
function splHideReset(id) {
    document.getElementById('srf_'+id).style.display = 'none';
}

// Staff password table: toggle eye
function splToggle(inputId, btn) {
    var inp = document.getElementById(inputId);
    var show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    btn.querySelector('svg').innerHTML = show
        ? '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
        : '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
}

// Staff password table: copy
function splCopy(inputId, btn) {
    var val = document.getElementById(inputId).value;
    navigator.clipboard.writeText(val).then(function() {
        btn.classList.add('copied');
        btn.title = 'Copied!';
        setTimeout(function(){ btn.classList.remove('copied'); btn.title = 'Copy'; }, 2000);
    });
}

function profCopyPwd() {
    var val = document.getElementById('profSavedPwd').value;
    if (!val) return;
    navigator.clipboard.writeText(val).then(function() {
        var done = document.getElementById('profCopyDone');
        done.style.display = 'inline';
        setTimeout(function(){ done.style.display = 'none'; }, 2000);
    });
}

function toggleEye(inputId, iconId) {
    var inp = document.getElementById(inputId);
    var ico = document.getElementById(iconId);
    var show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    ico.innerHTML = show
        ? '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
        : '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
}

document.getElementById('newPassword').addEventListener('input', function() {
    var v = this.value, s = 0;
    if (v.length >= 8) s++; if (/[A-Z]/.test(v)) s++; if (/[0-9]/.test(v)) s++; if (/[^A-Za-z0-9]/.test(v)) s++;
    var fill = document.getElementById('strengthFill'), text = document.getElementById('strengthText');
    var map = ['','Weak','Fair','Good','Strong'], col = ['','#EF4444','#F59E0B','#3B82F6','#10B981'];
    fill.style.width = (s*25)+'%'; fill.style.background = col[s]||'#E2E8F0';
    text.textContent = v.length ? map[s] : ''; text.style.color = col[s];
});

document.getElementById('confirmPassword').addEventListener('input', function() {
    var pw = document.getElementById('newPassword').value, note = document.getElementById('matchNote');
    if (!this.value) { note.style.display='none'; return; }
    var match = pw === this.value;
    note.style.display='flex'; note.style.color = match ? '#10B981' : '#EF4444';
    note.textContent = match ? '✓ Passwords match' : '✗ Passwords do not match';
});

function handleFile(file) {
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('avatarPreviewImg').src = e.target.result;
        document.getElementById('avatarPreviewName').textContent = file.name;
        document.getElementById('avatarPreviewSize').textContent = (file.size/1024).toFixed(1)+' KB';
        document.getElementById('avatarPreview').classList.add('show');
        var heroImg = document.getElementById('heroAvatar'), heroInit = document.getElementById('heroAvatarInit');
        if (heroImg) { heroImg.src = e.target.result; }
        else {
            var img = document.createElement('img'); img.id='heroAvatar'; img.className='prof-avatar'; img.src=e.target.result; img.alt='Avatar';
            document.querySelector('.prof-avatar-wrap').insertBefore(img, document.querySelector('.prof-avatar-edit'));
            if (heroInit) heroInit.style.display='none';
        }
    };
    reader.readAsDataURL(file);
}

document.getElementById('avatarInput').addEventListener('change', function(){ handleFile(this.files[0]); });
document.getElementById('heroFileInput').addEventListener('change', function(){
    var dt = new DataTransfer(); dt.items.add(this.files[0]);
    document.getElementById('avatarInput').files = dt.files;
    handleFile(this.files[0]);
});
function clearAvatar(){ document.getElementById('avatarInput').value=''; document.getElementById('avatarPreview').classList.remove('show'); }

document.getElementById('profileForm').addEventListener('submit', function(){
    var btn = document.getElementById('saveBtn');
    btn.innerHTML = '<svg width="20" height="20" fill="none" viewBox="0 0 24 24" style="animation:spin 1s linear infinite"><path stroke="#fff" stroke-linecap="round" stroke-width="2" d="M4 12a8 8 0 018-8"/></svg> Saving…';
    btn.disabled = true;
});
</script>
@endsection
