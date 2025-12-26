@extends('layouts.back-end.app')

@section('title', translate('add_new_Vendor'))
@section('content')
    <div class="content container-fluid main-card {{ Session::get('direction') }}">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ asset('/public/assets/back-end/img/add-new-seller.png') }}" class="mb-1" alt="">
                {{ translate('add_new_Vendor') }}
            </h2>
        </div>
        <form class="user" action="{{ route('shop.apply') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="status" value="approved">
                    <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                        <img src="{{ asset('public/assets/back-end/img/vendor-information.png') }}" class="mb-1"
                            alt="">
                        {{ translate('vendor_information') }}
                    </h5>
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="form-group">
                                <label for="exampleFirstName"
                                    class="title-color d-flex gap-1 align-items-center">{{ translate('first_name') }}</label>
                                <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                    name="f_name" value="{{ old('f_name') }}"
                                    placeholder="{{ translate('enter_first_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleLastName"
                                    class="title-color d-flex gap-1 align-items-center">{{ translate('last_name') }}</label>
                                <input type="text" class="form-control form-control-user" id="exampleLastName"
                                    name="l_name" value="{{ old('l_name') }}"
                                    placeholder="{{ translate('enter_last_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPhone"
                                    class="title-color d-flex gap-1 align-items-center">{{ translate('phone') }}</label>
                                <input type="number" class="form-control form-control-user" id="exampleInputPhone"
                                    name="phone" value="{{ old('phone') }}" placeholder="{{ translate('ex') }}: 09..."
                                    required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="d-flex justify-content-center">
                                    <img class="upload-img-view" id="viewer"
                                        src="{{ asset('public\assets\back-end\img\400x400\img2.jpg') }}"
                                        alt="{{ translate('banner_image') }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="title-color mb-2 d-flex gap-1 align-items-center">
                                    {{ translate('vendor_Image') }} <span class="text-info">({{ translate('ratio') }}
                                        {{ translate('1') }}:{{ translate('1') }})</span></div>
                                <div class="custom-file text-left">
                                    <input type="file" name="image" id="custom-file-upload"
                                        class="custom-file-input image-input" data-image-id="viewer"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label"
                                        for="custom-file-upload">{{ translate('upload_image') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="isVendorExclusiveBrand">{{ translate('is_vendor_exclusive_brand') }}?
                                    {{-- ({{ translate('default') }}: {{ isset($defaultTrialPlan) ? $defaultTrialPlan->plan?->name : '' }})--}}
                                </label>
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                    data-placement="right"
                                    title="{{ translate('you_must_register_the_brand_under_the_exclusive_brands_menu_if_you_do_not_get_the_list') }}">
                                    <img width="16" src="{{ asset('/public/assets/back-end/img/info-circle.svg') }}"
                                        alt="">
                                </span>
                                <br>
                                <label class="switcher">
                                    <input type="checkbox" name="is_vendor_exclusive_brand" class="switcher_input" id="isVendorExclusiveBrand"
                                        @checked(old('is_vendor_exclusive_brand'))>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                            <div class="seller-brand-selection row d-none">
                                <div class="col-lg-6 form-group">
                                    <div class="form-group">
                                        <label for="brand-selector" class="title-color">{{ translate('select_brand') }}</label>
                                        <select id="brand-selector" class="js-select2-custom form-control" name="brand_id">
                                            <option {{ old('brand_id') == '' ? 'selected' : '' }} value="{{ null }}" selected
                                                    disabled>{{ translate('choose_Brand') }}</option>
                                            @foreach ($brands as $brand)
                                                <option {{ old('brand_id') == $brand['id'] ? 'selected' : '' }} value="{{ $brand['id'] }}">{{ $brand['defaultName'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label for="subscription">{{ translate('select_gender') }}</label>
                            <select name="sex" class="form-control fs-13 border-aliceblue max-240px" id="gender"
                                required>
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
                        <div class="col-lg-6 form-group">
                            <label for="billing_type">{{ translate('age') }}</label>
                            <input value="{{ old('age') }}" type="number" placeholder="{{ translate('enter_age') }}"
                                name="age" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <input type="hidden" name="status" value="approved">
                    <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                        <img src="{{ asset('/public/assets/back-end/img/vendor-information.png') }}" class="mb-1"
                            alt="">
                        {{ translate('account_information') }}
                    </h5>
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail"
                                class="title-color d-flex gap-1 align-items-center">{{ translate('email') }}</label>
                            <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                name="email" value="{{ old('email') }}" placeholder="{{ translate('enter_email') }}"
                                required>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="user_password" class="title-color d-flex gap-1 align-items-center">
                                {{ translate('password') }}
                            </label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="js-toggle-password form-control" name="password" required
                                    id="user_password" minlength="8"
                                    placeholder="{{ translate('password_minimum_8_characters') }}"
                                    data-hs-toggle-password-options='{
                                                         "target": "#changePassTarget",
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": "#changePassIcon"
                                                }'>
                                <div id="changePassTarget" class="input-group-append">
                                    <a class="input-group-text" href="javascript:">
                                        <i id="changePassIcon" class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="confirm_password"
                                class="title-color d-flex gap-1 align-items-center">{{ translate('confirm_password') }}</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="js-toggle-password form-control" name="confirm_password"
                                    required id="confirm_password" placeholder="{{ translate('confirm_password') }}"
                                    data-hs-toggle-password-options='{
                                                         "target": "#changeConfirmPassTarget",
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": "#changeConfirmPassIcon"
                                                }'>
                                <div id="changeConfirmPassTarget" class="input-group-append">
                                    <a class="input-group-text" href="javascript:">
                                        <i id="changeConfirmPassIcon" class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="pass invalid-feedback">{{ translate('repeat_password_not_match') . '.' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3 subscription-plan-card">
                <div class="card-body">
                    <input type="hidden" name="status" value="approved">
                    <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                        <img src="{{ asset('/public/assets/back-end/img/vendor-information.png') }}" class="mb-1"
                            alt="">
                        {{ translate('subscription_setting') }}
                    </h5>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="isTrialPlan">{{ translate('is_trial_plan') }}? ({{ translate('default') }}:
                                    {{ isset($defaultTrialPlan) ? $defaultTrialName : '' }})</label>
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                    data-placement="right"
                                    title="{{ translate('you_can_set_which_plan_should_be_the_default_in_business_settings') }}">
                                    <img width="16" src="{{ asset('/public/assets/back-end/img/info-circle.svg') }}"
                                        alt="">
                                </span>
                                <br>
                                <label class="switcher">
                                    <input type="checkbox" name="is_trial_plan" class="switcher_input" id="isTrialPlan"
                                        @checked(old('is_trial_plan'))>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                            <div class="plan-selection-select row">
                                <div class="col-lg-6 form-group">
                                    <label for="subscription">{{ translate('select_subscription') }}</label>
                                    <select name="subscription_plan"
                                        class="form-control fs-13 border-aliceblue max-240px action-get-billing-types"
                                        id="subscription"
                                        data-url-prefix="{{ route('admin.subscriptions.get-billing-types') . '?plan_id=' }}"
                                        data-element-id="billing-type-select" data-element-type="select">
                                        <option value="" disabled selected>
                                            {{ translate('choose_subscription_plan') }}</option>
                                        @foreach ($subscriptionPlans as $plan)
                                            <option value="{{ $plan->id }}">
                                                {{ $plan->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="billing_type">{{ translate('select_billing_type') }}</label>
                                    <select name="billing_type" class="form-control max-240px action-set-billing-type"
                                        id="billing-type-select">
                                        <option value="" disabled selected>{{ translate('choose_billing_type') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                        <img src="{{ asset('/public/assets/back-end/img/vendor-information.png') }}" class="mb-1"
                            alt="">
                        {{ translate('shop_information') }}
                    </h5>

                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label for="shop_name"
                                class="title-color d-flex gap-1 align-items-center">{{ translate('shop_name') }}</label>
                            <input type="text" class="form-control form-control-user" id="shop_name" name="shop_name"
                                placeholder="{{ translate('enter_shop_name') }}" value="{{ old('shop_name') }}"
                                required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="shop_address"
                                class="title-color d-flex gap-1 align-items-center">{{ translate('shop_address') }}</label>
                            <textarea name="shop_address" class="form-control" id="shop_address" rows="1"
                                placeholder="{{ translate('enter_shop_address') }}">{{ old('shop_address') }}</textarea>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="d-flex justify-content-center">
                                <img class="upload-img-view" id="viewerLogo"
                                    src="{{ asset('public\assets\back-end\img\400x400\img2.jpg') }}"
                                    alt="{{ translate('banner_image') }}" />
                            </div>

                            <div class="mt-4">
                                <div class="d-flex gap-1 align-items-center title-color mb-2">
                                    {{ translate('shop_logo') }}
                                    <span class="text-info">({{ translate('ratio') . ' ' . '1:1' }})</span>
                                </div>

                                <div class="custom-file">
                                    <input type="file" name="logo" id="logo-upload"
                                        class="custom-file-input image-input" data-image-id="viewerLogo"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label"
                                        for="logo-upload">{{ translate('upload_logo') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="d-flex justify-content-center">
                                <img class="upload-img-view upload-img-view__banner" id="viewerBanner"
                                    src="{{ asset('public\assets\back-end\img\400x400\img2.jpg') }}"
                                    alt="{{ translate('banner_image') }}" />
                            </div>
                            <div class="mt-4">
                                <div class="d-flex gap-1 align-items-center title-color mb-2">
                                    {{ translate('shop_banner') }}
                                    <span
                                        class="text-info">{{ THEME_RATIO[theme_root_path()]['Store cover Image'] }}</span>
                                </div>

                                <div class="custom-file">
                                    <input type="file" name="banner" id="banner-upload"
                                        class="custom-file-input image-input" data-image-id="viewerBanner"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label text-capitalize"
                                        for="banner-upload">{{ translate('upload_Banner') }}</label>
                                </div>
                            </div>
                        </div>

                        @if (theme_root_path() == 'theme_aster')
                            <div class="col-lg-6 form-group">
                                <div class="d-flex justify-content-center">
                                    <img class="upload-img-view upload-img-view__banner" id="viewerBottomBanner"
                                        src="{{ asset('public\assets\back-end\img\400x400\img2.jpg') }}"
                                        alt="{{ translate('banner_image') }}" />
                                </div>

                                <div class="mt-4">
                                    <div class="d-flex gap-1 align-items-center title-color mb-2">
                                        {{ translate('shop_secondary_banner') }}
                                        <span
                                            class="text-info">{{ THEME_RATIO[theme_root_path()]['Store Banner Image'] }}</span>
                                    </div>

                                    <div class="custom-file">
                                        <input type="file" name="bottom_banner" id="bottom-banner-upload"
                                            class="custom-file-input image-input" data-image-id="viewerBottomBanner"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label text-capitalize"
                                            for="bottom-banner-upload">{{ translate('upload_bottom_banner') }}</label>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-10">
                        <input type="hidden" name="from_submit" value="admin">
                        <button type="reset" class="btn btn-secondary reset-button">{{ translate('reset') }} </button>
                        <button type="submit" class="btn btn--primary btn-user"
                            id="apply">{{ translate('submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/back-end/js/vendor.js') }}"></script>
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

    <script>
        $(document).ready(function() {
            $('#isVendorExclusiveBrand').change(
                function() {
                    if (this.checked) {
                        $('.seller-brand-selection').removeClass('d-none');
                        $('.brand-selector').attr('selected');
                        // $('.subscription-plan-card').addClass('d-none');
                    } else {
                        $('.seller-brand-selection').addClass('d-none');
                        $('.brand-selector').removeAttr('selected');
                        // $('.subscription-plan-card').removeClass('d-none');
                    }
                }
            );

            if ($('#isVendorExclusiveBrand').is(':checked')) {
                $('.seller-brand-selection').removeClass('d-none');
                $('.brand-selector').attr('selected');
                // $('.subscription-plan-card').addClass('d-none');
            }else{
                // $('.subscription-plan-card').removeClass('d-none');
            }
        })
    </script>

    <script>
        $('.action-get-billing-types').on('change', function() {
            let getUrlPrefix = $(this).data('url-prefix');
            let id = $(this).data('element-id');
            let getElementType = $(this).data('element-type');
            let value = $(this).val();

            // Ajax here to get billing types of the selected subscription plan
            getPlanBillingTypes(getUrlPrefix, id, getElementType, value)

        });

        function getPlanBillingTypes(getUrlPrefix, id, getElementType, value) {
            console.log(getUrlPrefix + value)
            let message = $('#message-select-word').data('text');
            $('#billing-type-select').empty().append(`<option value="" selected disabled>---` + message + `---</option>`);
            $.get({
                url: getUrlPrefix + value,
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (getElementType === 'select') {
                        $('#' + id).empty().append(data.select_tag);
                    }
                },
            });
        }
    </script>
@endpush
