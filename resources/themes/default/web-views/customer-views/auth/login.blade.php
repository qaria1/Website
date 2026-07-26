@extends('layouts.front-end.app')

@section('title', translate('sign_in'))

@push('css_or_js')
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
    <div class="container py-4 py-lg-5 my-4 text-align-direction">
         <div class="login-card">
            <div class="mx-auto __max-w-760">
                <h2 class="text-center h4 mb-4 font-bold text-capitalize fs-18-mobile">{{ translate('sign_in') }}</h2>

                {{-- Role Tabs --}}
                <div class="auth-tabs-wrapper">
                    <button class="auth-tab-btn" id="tab-btn-buyer" onclick="switchAuthTab('buyer')">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                        {{ translate('buyer') ?? 'Buyer' }}
                    </button>
                    <button class="auth-tab-btn" id="tab-btn-vendor" onclick="switchAuthTab('vendor')">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        {{ translate('vendor') ?? 'Vendor' }}
                    </button>
                </div>

                {{-- Buyer Tab --}}
                <div class="auth-tab-pane" id="tab-pane-buyer">
                    <form class="needs-validation mt-2" autocomplete="off" action="{{ route('customer.auth.login') }}"
                            method="post" id="customer-login-form">
                        @csrf
                        <div class="form-group">
                            <label class="form-label font-semibold">
                                {{ translate('email') }} / {{ translate('phone')}}
                            </label>
                            <input class="form-control text-align-direction" type="text" name="user_id" id="si-email"
                                    value="{{ old('user_id') }}" placeholder="{{ translate('enter_email_address_or_phone_number') }}"
                                    required>
                            <div class="invalid-feedback">{{ translate('please_provide_valid_email_or_phone_number') }} .</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label font-semibold">{{ translate('password') }}</label>
                            <div class="password-toggle rtl">
                                <input class="form-control text-align-direction" name="password" type="password" id="si-password" placeholder="{{ translate('password_must_be_7+_Character')}}" required>
                                <label class="password-toggle-btn">
                                    <input class="custom-control-input" type="checkbox">
                                        <i class="tio-hidden password-toggle-indicator"></i>
                                        <span class="sr-only">{{ translate('show_password') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group d-flex flex-wrap justify-content-between">
                            <div class="rtl">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="remember"
                                            id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label text-primary" for="remember">{{ translate('remember_me') }}</label>
                                </div>
                            </div>
                            <a class="font-size-sm text-primary text-underline" href="{{ route('customer.auth.recover-password') }}">
                                {{ translate('forgot_password') }}?
                            </a>
                        </div>
                        @php($recaptcha = getWebConfig(name: 'recaptcha'))
                        @if(isset($recaptcha) && $recaptcha['status'] == 1)
                            <div id="recaptcha_element_buyer" class="w-100" data-type="image"></div>
                            <br/>
                        @else
                            <div class="row py-2">
                                <div class="col-6 pr-2">
                                    <input type="text" class="form-control border __h-40" name="default_recaptcha_id_customer_login" value=""
                                        placeholder="{{ translate('enter_captcha_value') }}" autocomplete="off">
                                </div>
                                <div class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                    <a href="javascript:" class="d-flex align-items-center align-items-center get-login-recaptcha-verify" data-link="{{ URL('/customer/auth/code/captcha') }}">
                                        <img src="{{ URL('/customer/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_customer_login') }}" class="input-field rounded __h-80" id="customer_login_recaptcha_id" alt="">
                                        <i class="tio-refresh icon cursor-pointer p-2"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                        <button class="btn btn--primary btn-block btn-shadow" type="submit">{{ translate('log_in') }}</button>
                    </form>
                    <div class="text-center m-3 text-black-50">
                        <small>{{ translate('or_continue_with') }}</small>
                    </div>
                    <div class="d-flex justify-content-center my-3 gap-2">
                    @foreach (getWebConfig(name: 'social_login') as $socialLoginService)
                        @if (isset($socialLoginService) && $socialLoginService['status'])
                            <div>
                                <a class="d-block" href="{{ route('customer.auth.service-login', $socialLoginService['login_medium']) }}">
                                    <img src="{{ asset('public/assets/front-end/img/icons/'.$socialLoginService['login_medium'].'.png') }}" alt="">
                                </a>
                            </div>
                        @endif
                    @endforeach
                    </div>
                    <div class="text-black-50 text-center">
                        <small>
                            {{ translate('Enjoy_New_experience') }}
                            <a class="text-primary text-underline" href="{{ route('customer.auth.sign-up') }}?tab=buyer">
                                {{ translate('sign_up') }}
                            </a>
                        </small>
                    </div>
                </div>

                {{-- Vendor Tab --}}
                <div class="auth-tab-pane" id="tab-pane-vendor">
                    <form class="needs-validation mt-2" autocomplete="off" action="{{ route('vendor.auth.login') }}"
                            method="post" id="vendor-login-form-inline">
                        @csrf
                        <div class="form-group">
                            <label class="form-label font-semibold">{{ translate('your_email') ?? 'Your Email' }}</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                placeholder="email@address.com" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label font-semibold">
                                <span class="d-flex justify-content-between align-items-center">
                                    {{ translate('password') }}
                                    <a class="font-size-sm text-primary" href="{{ route('vendor.auth.forgot-password.index') }}">
                                        {{ translate('forgot_password') }}?
                                    </a>
                                </span>
                            </label>
                            <div class="password-toggle rtl">
                                <input class="form-control text-align-direction" name="password" type="password"
                                    id="vendor-password-inline" placeholder="{{ translate('password_must_be_7+_Character') ?? '8+ characters required' }}" required>
                                <label class="password-toggle-btn">
                                    <input class="custom-control-input" type="checkbox">
                                    <i class="tio-hidden password-toggle-indicator"></i>
                                    <span class="sr-only">{{ translate('show_password') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="vendor-remember" name="remember">
                                <label class="custom-control-label text-primary" for="vendor-remember">{{ translate('remember_me') }}</label>
                            </div>
                        </div>
                        @php($recaptcha_v = getWebConfig(name: 'recaptcha'))
                        @if (isset($recaptcha_v) && $recaptcha_v['status'] == 1)
                            <div id="recaptcha_element_vendor" class="w-100" data-type="image"></div>
                            <br />
                        @else
                            <div class="row py-2">
                                <div class="col-6 pr-0">
                                    <input type="text" class="form-control __h-40 border" name="vendorRecaptchaKey" value=""
                                        placeholder="{{ translate('enter_captcha_value') }}" autocomplete="off">
                                </div>
                                <div class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                    <a class="d-flex align-items-center get-login-recaptcha-verify"
                                        data-link="{{ URL('/vendor/auth/recaptcha') }}">
                                        <img src="{{ URL('/vendor/auth/recaptcha/1?captcha_session_id=vendorRecaptchaSessionKey') }}"
                                            alt="" class="rounded __h-40" id="vendor_recaptcha_id">
                                        <i class="tio-refresh position-relative cursor-pointer p-2"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                        <button type="submit" class="btn btn--primary btn-block btn-shadow">{{ translate('sign_in') }}</button>
                    </form>
                    <div class="text-black-50 text-center mt-3">
                        <small>
                            {{ translate('new_vendor') ?? "Don't have a vendor account?" }}
                            <a class="text-primary text-underline" href="{{ route('customer.auth.sign-up') }}?tab=vendor">
                                {{ translate('apply_now') ?? 'Apply Now' }}
                            </a>
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('script')
<script>
"use strict";
function switchAuthTab(tab) {
    document.querySelectorAll('.auth-tab-pane').forEach(function(el) { el.classList.remove('active'); });
    document.querySelectorAll('.auth-tab-btn').forEach(function(el) { el.classList.remove('active'); });
    document.getElementById('tab-pane-' + tab).classList.add('active');
    document.getElementById('tab-btn-' + tab).classList.add('active');
    try { localStorage.setItem('authLoginTab', tab); } catch(e) {}
}
document.addEventListener('DOMContentLoaded', function() {
    var urlTab = new URLSearchParams(window.location.search).get('tab');
    var savedTab = urlTab || (function() { try { return localStorage.getItem('authLoginTab'); } catch(e) { return null; } })() || 'buyer';
    switchAuthTab(savedTab);
});
</script>

@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        "use strict";
        var onloadCallback = function () {
            if (document.getElementById('recaptcha_element_buyer')) {
                grecaptcha.render('recaptcha_element_buyer', {
                    'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
                });
            }
            if (document.getElementById('recaptcha_element_vendor')) {
                grecaptcha.render('recaptcha_element_vendor', {
                    'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
                });
            }
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
            async defer></script>
@endif
@endpush
