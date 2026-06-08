{{--
  Reusable phone input (no country code selector)
  Usage:
    @include('admin.partials.phone-input', [
        'name'        => 'phone',
        'value'       => old('phone', ''),
        'required'    => true,
        'placeholder' => '9876543210',
        'class'       => 'form-control',
        'id'          => 'phone',
    ])
--}}
@php
  $phoneId          = $id ?? ('phone_'.uniqid());
  $phoneRequired    = $required ?? false;
  $phoneClass       = $class ?? 'form-control';
  $phonePlaceholder = $placeholder ?? 'Enter phone number';
  $phoneValue       = $value ?? old($name ?? 'phone', '');
  $phoneName        = $name ?? 'phone';
  $phoneCodeName    = $codeName ?? 'phone_country_code';
@endphp

<style>
.vk-phone-wrap {
    display: flex;
    align-items: stretch;
    border: 1.5px solid #E2E8F0;
    border-radius: 9px;
    overflow: hidden;
    background: #FAFBFF;
    transition: border-color .15s, box-shadow .15s;
}
.vk-phone-wrap:focus-within {
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.10);
    background: #fff;
}
.vk-phone-input {
    border: none !important;
    border-radius: 9px !important;
    background: transparent !important;
    box-shadow: none !important;
    flex: 1;
    padding: 9px 12px !important;
    font-size: .86rem;
    color: #0F172A;
    outline: none;
    min-width: 0;
    width: 100%;
}
.vk-phone-input:focus { box-shadow: none !important; border: none !important; }
</style>

{{-- Hidden field keeps backward compatibility if controller reads the code name --}}
<input type="hidden" name="{{ $phoneCodeName }}" value="">

<div class="vk-phone-wrap">
    <input type="text"
           name="{{ $phoneName }}"
           id="{{ $phoneId }}"
           class="vk-phone-input {{ $phoneClass }}"
           {{ $phoneRequired ? 'required' : '' }}
           placeholder="{{ $phonePlaceholder }}"
           maxlength="20"
           value="{{ $phoneValue }}"
           inputmode="tel"
           autocomplete="tel"
           oninput="this.value=this.value.replace(/[^0-9+\-() ]/g,'')">
</div>
