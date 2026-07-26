@extends('layouts.front-end.app')

@section('title', translate('sign_up'))

@push('css_or_js')
    <link href="{{ asset('public/assets/back-end/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/back-end/css/croppie.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
    .auth-tabs-wrapper {
        display: flex;
        background: #f0f2f5;
        border-radius: 12px;
        padding: 4px;
        margin-bottom: 24px;
        gap: 4px;
    }
    .auth-tab-btn {
        flex: 1;
        padding: 10px 16px;
        border: none;
        border-radius: 9px;
        background: transparent;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.25s ease;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .auth-tab-btn.active {
        background: #fff;
        color: var(--primary-clr, #0d6efd);
        box-shadow: 0 2px 8px rgba(0,0,0,.10);
    }
    .auth-tab-btn:hover:not(.active) {
        color: #374151;
        background: rgba(255,255,255,0.5);
    }
    .auth-tab-pane { display: none; }
    .auth-tab-pane.active { display: block; }
    </style>
@endpush

@section('content')
    <div class="container py-4 __inline-7 text-align-direction">
        <div class="login-card">
            <div class="mx-auto __max-w-760">
                <h2 class="text-center h4 mb-4 font-bold text-capitalize fs-18-mobile">{{ translate('sign_up') }}</h2>

                {{-- Role Tabs --}}
                <div class="auth-tabs-wrapper">
                    <button class="auth-tab-btn" id="tab-btn-buyer" onclick="switchRegTab('buyer')">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                        {{ translate('buyer') ?? 'Buyer' }}
                    </button>
                    <button class="auth-tab-btn" id="tab-btn-vendor" onclick="switchRegTab('vendor')">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        {{ translate('vendor') ?? 'Vendor' }}
                    </button>
                </div>

                {{-- ===== BUYER REGISTRATION TAB ===== --}}
                <div class="auth-tab-pane" id="tab-pane-buyer">
                    <form class="needs-validation_" id="customer-register-form" action="{{ route('customer.auth.sign-up') }}"
                            method="post">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label font-semibold">{{ translate('first_name')}}</label>
                                    <input class="form-control text-align-direction" value="{{ old('f_name')}}" type="text" name="f_name"
                                            placeholder="{{ translate('Ex') }}: {{ translate('Jhone') }}"
                                            required >
                                    <div class="invalid-feedback">{{ translate('please_enter_your_first_name')}}!</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label font-semibold">{{ translate('last_name') }}</label>
                                    <input class="form-control text-align-direction" type="text" value="{{ old('l_name') }}" name="l_name"
                                            placeholder="{{ translate('Ex') }}: Doe" required>
                                    <div class="invalid-feedback">{{ translate('please_enter_your_last_name') }}!</div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label font-semibold">{{ translate('email_address') }}</label>
                                    <input class="form-control text-align-direction" type="email" value="{{ old('email') }}" name="email"
                                         placeholder="{{ translate('enter_email_address') }}" autocomplete="off"
                                            required>
                                    <div class="invalid-feedback">{{ translate('please_enter_valid_email_address') }}!</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label font-semibold">{{ translate('phone_number') }}</label>
                                    <input class="form-control text-align-direction" type="number"  value="{{ old('phone') }}" name="phone"
                                            placeholder="{{ translate('enter_phone_number') }}"
                                            required>
                                    <div class="invalid-feedback">{{ translate('please_enter_your_phone_number') }}!</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label font-semibold">{{ translate('additional_phone_number') }} {{ '('.translate('optional').')' }}</label>
                                    <input class="form-control text-align-direction" type="number"  value="{{ old('additional_phone') }}" name="additional_phone"
                                            placeholder="{{ translate('enter_additional_phone_number') }}"
                                            >
                                    <div class="invalid-feedback">{{ translate('please_enter_your_additional_phone_number') }}!</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label font-semibold">{{ translate('birth_date') }} ({{ translate('in_gregorian') }})</label>
                                    <input class="form-control text-align-direction" type="date" value="{{ old('birth_date') }}" name="birth_date"
                                        autocomplete="off"
                                            required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label font-semibold">{{ translate('sex') }}</label>
                                    <select name="sex" class="form-control fs-13 border-aliceblue" id="gender" required>
                                        <option value="" disabled selected>
                                            {{ translate('choose_gender') }}</option>
                                        <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>
                                            {{ translate('male') }}
                                        </option>
                                        <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>
                                            {{ translate('female') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label font-semibold">{{ translate('password') }}</label>
                                    <div class="password-toggle rtl">
                                        <input class="form-control text-align-direction" name="password" type="password" id="si-password"
                                                placeholder="{{ translate('minimum_8_characters_long') }}" required>
                                        <label class="password-toggle-btn">
                                            <input class="custom-control-input" type="checkbox"><i
                                                class="tio-hidden password-toggle-indicator"></i><span
                                                class="sr-only">{{ translate('show_password') }} </span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label font-semibold">{{ translate('confirm_password') }}</label>
                                    <div class="password-toggle rtl">
                                        <input class="form-control text-align-direction" name="con_password" type="password"
                                                placeholder="{{ translate('minimum_8_characters_long') }}"
                                                id="si-password-confirm" required>
                                        <label class="password-toggle-btn">
                                            <input class="custom-control-input text-align-direction" type="checkbox">
                                            <i class="tio-hidden password-toggle-indicator"></i>
                                            <span class="sr-only">{{ translate('show_password') }}</span>
                                        </label>
                                    </div>
                                </div>

                            </div>

                            @if ($web_config['ref_earning_status'])
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label font-semibold">{{ translate('refer_code') }} <small class="text-muted">({{ translate('optional') }})</small></label>
                                    <input type="text" id="referral_code" class="form-control"
                                    name="referral_code" placeholder="{{ translate('use_referral_code') }}" value="{{ old('referral_code') }}">
                                </div>
                            </div>
                            @endif

                        </div>
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="rtl">
                                        <label class="custom-control custom-checkbox m-0 d-flex">
                                            <input type="checkbox" class="custom-control-input" name="remember" id="inputChecked">
                                            <span class="custom-control-label">
                                                <span>{{ translate('i_agree_to_Your') }}</span> <a class="font-size-sm text-decoration-underline" target="_blank" href="{{ route('terms') }}">{{ translate('terms_and_condition') }}</a>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    @php($recaptcha = getWebConfig(name: 'recaptcha'))
                                    @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                        <div id="recaptcha_element" class="w-100" data-type="image"></div>
                                    @else
                                    <div class="row">
                                        <div class="col-6 pr-2">
                                            <input type="text" class="form-control border __h-40" name="default_recaptcha_value_customer_regi" value=""
                                                    placeholder="{{ translate('enter_captcha_value') }}" autocomplete="off">
                                        </div>
                                        <div class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                            <a href="javascript:" class="d-flex align-items-center align-items-center get-regi-recaptcha-verify" data-link="{{ URL('/customer/auth/code/captcha') }}">
                                                <img alt="" src="{{ URL('/customer/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_customer_regi') }}" class="input-field rounded __h-80" id="default_recaptcha_id">
                                                <i class="tio-refresh icon cursor-pointer p-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="web-direction">
                            <div class="mx-auto mt-4 __max-w-356">
                                <button class="w-100 btn btn--primary" id="sign-up" type="submit" disabled>
                                    {{ translate('sign_up') }}
                                </button>
                            </div>
                            <div class="text-center m-3 text-black-50">
                                <small>{{ translate('or_continue_with') }}</small>
                            </div>
                            <div class="d-flex justify-content-center my-3 gap-2">
                                @foreach (getWebConfig(name: 'social_login') as $socialLoginService)
                                    @if (isset($socialLoginService) && $socialLoginService['status'])
                                        <div>
                                            <a class="d-block" href="{{ route('customer.auth.service-login', $socialLoginService['login_medium'])}}">
                                                <img src="{{ asset('/public/assets/front-end/img/icons/'.$socialLoginService['login_medium'].'.png') }}" alt="">
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="text-black-50 mt-3 text-center">
                                <small>
                                    {{ translate('Already_have_account ') }}?
                                    <a class="text-primary text-underline" href="{{ route('customer.auth.login') }}?tab=buyer">
                                        {{ translate('sign_in') }}
                                    </a>
                                </small>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- ===== VENDOR REGISTRATION TAB ===== --}}
                <div class="auth-tab-pane" id="tab-pane-vendor">
                    <form class="__shop-apply" action="{{ route('shop.apply') }}" id="vendor-register-form" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="from" value="seller">

                        <div class="card __card mb-3">
                            <div class="card-header">
                                <h5 class="card-title m-0">
                                    <i class="fa fa-user-o" aria-hidden="true"></i>
                                    {{ translate('vendor_Info') }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                            name="f_name" value="{{ old('f_name') }}" placeholder="{{ translate('first_name') }}"
                                            required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName" name="l_name"
                                            value="{{ old('l_name') }}" placeholder="{{ translate('last_name') }}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                            name="email" value="{{ old('email') }}" placeholder="{{ translate('email_address') }}"
                                            required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control form-control-user" id="exampleInputPhone"
                                            name="phone" value="{{ old('phone') }}" placeholder="{{ translate('phone_number') }}"
                                            required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" minlength="6"
                                            id="exampleInputPassword" name="password" placeholder="{{ translate('password') }}"
                                            required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" minlength="6"
                                            id="exampleRepeatPassword" name="confirm_password"
                                            placeholder="{{ translate('repeat_password') }}" required>
                                        <div class="pass invalid-feedback">{{ translate('repeat_password_not_match') }} .</div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="text-center">
                                            <img class="__img-125px object-cover" id="viewer"
                                                src="{{ getValidImage(path: 'public/assets/front-end/img/placeholder/user.png', type: 'avatar') }}"
                                                alt="banner image" />
                                        </div>
                                        <div class="custom-file mt-3">
                                            <input type="file" name="image" id="custom-file-upload" value="{{ old('image') }}"
                                                class="custom-file-input image-preview-before-upload" data-preview="#viewer"
                                                accept=".jpg, .jpeg, .png, .gif, .webp" required>
                                            <label class="custom-file-label"
                                                for="custom-file-upload">{{ translate('upload_image') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-6 form-group">
                                        <select name="sex" class="form-control fs-13 border-aliceblue" id="vendor-gender" required>
                                            <option value="" disabled selected>
                                                {{ translate('choose_gender') }}</option>
                                            <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>
                                                {{ translate('male') }}
                                            </option>
                                            <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>
                                                {{ translate('female') }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <input value="{{ old('age') }}" type="number" placeholder="{{ translate('enter_age') }}"
                                            name="age" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card __card">
                            <div class="card-header">
                                <h5 class="card-title m-0">{{ translate('shop_Info') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="shop_name" name="shop_name"
                                            placeholder="{{ translate('shop_name') }}" value="{{ old('shop_name') }}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <textarea name="shop_address" class="form-control" id="shop_address" rows="1"
                                            placeholder="{{ translate('shop_address') }}" required>{{ old('shop_address') }}</textarea>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="pb-3">
                                            <div class="text-center">
                                                <img class="__img-125px object-cover" id="viewerLogo"
                                                    src="{{ getValidImage(path: 'public/assets/front-end/img/placeholder/placeholder-1-1.png', type: 'logo') }}"
                                                    alt="banner image" />
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <div class="custom-file">
                                                <input type="file" name="logo" id="Logo-upload"
                                                    class="custom-file-input image-preview-before-upload" data-preview="#viewerLogo"
                                                    accept=".jpg, .jpeg, .png, .gif, .webp" required>
                                                <label class="custom-file-label"
                                                    for="Logo-upload">{{ translate('upload_logo') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="pb-3">
                                            <div class="text-center">
                                                <img class="height-100px" id="viewerBanner"
                                                    src="{{ getValidImage(path: 'public/assets/front-end/img/placeholder/placeholder-4-1.png', type: 'wide-banner') }}"
                                                    alt="banner image" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="banner" id="banner-upload"
                                                    class="custom-file-input overflow-hidden __p-2p image-preview-before-upload"
                                                    data-preview="#viewerBanner"
                                                    accept=".jpg, .jpeg, .png, .gif, .webp" required>
                                                <label class="custom-file-label"
                                                    for="banner-upload">{{ translate('upload_Banner') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    @php($recaptcha = getWebConfig(name: 'recaptcha'))
                                    @if (isset($recaptcha) && $recaptcha['status'] == 1)
                                        <div id="recaptcha_element_vendor_reg" class="w-100" data-type="image"></div>
                                        <br />
                                    @else
                                        <div class="col-12">
                                            <div class="row py-2">
                                                <div class="col-6 pr-0">
                                                    <input type="text" class="form-control __h-40 border-0"
                                                        name="default_recaptcha_id_seller_regi" value=""
                                                        placeholder="{{ translate('enter_captcha_value') }}" autocomplete="off"
                                                        required>
                                                </div>
                                                <div class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                                    <span
                                                        class="d-flex align-items-center align-items-center get-vendor-regi-recaptcha-verify"
                                                        data-link="{{ route('vendor.auth.recaptcha', ['tmp' => ':dummy-id']) }}">
                                                        <img src="{{ route('vendor.auth.recaptcha', ['tmp' => 1]) . '?captcha_session_id=sellerRecaptchaSessionKey' }}"
                                                            alt="" class="rounded __h-40" id="vendor_reg_recaptcha_id">
                                                        <i class="tio-refresh position-relative cursor-pointer p-2"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-sm-12">
                                        <div class="form-group mb-0 d-flex flex-wrap justify-content-between">
                                            <label
                                                class="form-group mb-3 d-flex align-items-center flex-grow-1 cursor-pointer user-select-none">
                                                <strong>
                                                    <input type="checkbox" class="mr-1" name="remember"
                                                        id="vendor-remember-input-checked">
                                                </strong>
                                                <span class="mb-4px d-block w-0 flex-grow pl-1">
                                                    <span>{{ translate('i_agree_to_Your_terms') }}</span>
                                                    <a class="font-size-sm" target="_blank" href="{{ route('terms') }}">
                                                        {{ translate('terms_and_condition') }}
                                                    </a>
                                                </span>
                                            </label>
                                        </div>
                                        <input type="hidden" name="from_submit" value="seller">
                                        <button type="submit" class="btn btn--primary btn-user btn-block" id="apply"
                                            disabled>{{ translate('apply_Shop') }} </button>
                                        <div class="text-center mt-3">
                                            <small>
                                                {{ translate('Already_have_account ') ?? 'Already have a vendor account?' }}?
                                                <a class="text-primary text-underline" href="{{ route('customer.auth.login') }}?tab=vendor">
                                                    {{ translate('sign_in') }}
                                                </a>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
"use strict";
function switchRegTab(tab) {
    document.querySelectorAll('.auth-tab-pane').forEach(function(el) { el.classList.remove('active'); });
    document.querySelectorAll('.auth-tab-btn').forEach(function(el) { el.classList.remove('active'); });
    document.getElementById('tab-pane-' + tab).classList.add('active');
    document.getElementById('tab-btn-' + tab).classList.add('active');
    try { localStorage.setItem('authRegTab', tab); } catch(e) {}
}
document.addEventListener('DOMContentLoaded', function() {
    var urlTab = new URLSearchParams(window.location.search).get('tab');
    var savedTab = urlTab || (function() { try { return localStorage.getItem('authRegTab'); } catch(e) { return null; } })() || 'buyer';
    switchRegTab(savedTab);
});
</script>

@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        "use strict";
        var onloadCallback = function () {
            if (document.getElementById('recaptcha_element')) {
                grecaptcha.render('recaptcha_element', {
                    'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
                });
            }
            if (document.getElementById('recaptcha_element_vendor_reg')) {
                grecaptcha.render('recaptcha_element_vendor_reg', {
                    'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
                });
            }
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
@endif

<script>
    $(document).ready(function() {
        $('#isTrialPlan').change(
            function() {
                if (this.checked) {
                    $('.plan-selection-select').hide();
                } else {
                    $('.plan-selection-select').show();
                }
            }
        );

        if ($('#isTrialPlan').is(':checked')) {
            $('.plan-selection-select').hide();
        }
    })
</script>
@endpush
