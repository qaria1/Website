<?php
$seller = \App\Models\Seller::find($sellerId);
$wallet = \App\Models\SellerWallet::where('seller_id', $seller->id)->first();
if (isset($wallet) == false) {
    \Illuminate\Support\Facades\DB::table('seller_wallets')->insert([
        'seller_id' => $seller->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $wallet = \App\Models\SellerWallet::where('seller_id', $seller->id)->first();
}
?>
@if (isset($seller_subscription) && $seller_subscription->plan_id == $plan->id)
    <h3 class="modal-title text-center">{{ translate('Renew_Subscription_Plan') }}</h3>
@else
    <h3 class="modal-title text-center">{{ translate('migrate_to_new_plan') }}</h3>
@endif
<!-- Modal body -->
<div class="modal-body">
    @if (isset($seller_subscription))
        <div class="change-plan-wrapper align-items-center">
            <div class="plan-item">
                <div class="plan-header">
                    <img class="plan-header-shape" src="{{ dynamicAsset('/public/assets/back-end/img/plan-1.svg') }}"
                        alt="">
                    <h3 class="title">{{ $seller_subscription?->plan?->name }}</h3>
                </div>
                {{-- <h2 class="price">
                    {{ \App\CentralLogics\Helpers::format_currency($seller_subscription?->price) }}<sub>/
                        {{ $seller_subscription?->validity }} days</sub></h2> --}}
            </div>

            <!-- Plan Seperator Arrow -->
            <div class="plan-seperator-arrow mx-auto">
                <img src="{{ dynamicAsset('/public/assets/back-end/img/arrow.svg') }}" alt="" class="w-100">
            </div>
            <!-- Plan Seperator Arrow -->

            <div class="plan-item">
                <div class="plan-header">
                    <div class="checkicon active"></div>
                    <img class="plan-header-shape" src="{{ dynamicAsset('/public/assets/back-end/img/plan-2.svg') }}"
                        alt="">
                    <h3 class="title">{{ $plan->name }}</h3>
                </div>
                {{-- <h2 class="price">{{ \App\CentralLogics\Helpers::format_currency($plan->price) }} <sub>/
                        {{ $plan->validity }} {{ translate('days') }}</sub></h2> --}}
            </div>
        </div>
    @else
    @endif

    {{-- <div class="mb-4 mb-lg-5 subscription__plan-info-wrapper bg-ECEEF1 rounded-20">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="subscription__plan-info">
                    <div class="info">
                        {{ translate('validity') }}
                    </div>
                    <h4 class="subtitle">{{ $plan->validity }} {{ translate('days') }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="subscription__plan-info">
                    <div class="info">
                        {{ translate('price') }}
                    </div>
                    <h4 class="subtitle">{{ \App\CentralLogics\Helpers::format_currency($plan->price) }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="subscription__plan-info">
                    <div class="info">
                        {{ translate('bill_status') }}
                    </div>
                    @if (isset($seller_subscription) && $seller_subscription->plan_id == $plan->id)
                        <h4 class="subtitle">{{ translate('renew') }}</h4>
                    @else
                        <h4 class="subtitle">{{ translate('migrate_to_new_plan') }}</h4>
                    @endif
                </div>
            </div>
        </div>
    </div> --}}

    <form action="{{ route('admin.sellers.subscription.package_renew_change_update') }}" method="post">
        @csrf
        @method('POST')
        <input type="hidden" value="{{ $plan->id }}" name="plan_id">
        <input type="hidden" value="{{ $seller_subscription?->plan?->id }}" name="from_plan_id">
        <input type="hidden" value="{{ $sellerId }}" name="seller_id">

        <div class="form-group">
            <select name="billing_type" id="" class="form-control w-100" required>
                <option value="" disabled selected>{{ translate('choose_billing_type') }}</option>
                @forelse ($billingTypes as $billing)
                    <option value="{{ $billing?->billing_type_id }}">
                        {{ $billing?->billingType?->name . ' - ' . setCurrencySymbol(amount: usdToDefaultCurrency(amount: $billing?->price), currencyCode: getCurrencyCode(type: 'default')) }}
                    </option>
                @empty
                @endforelse
            </select>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="payment__method">
                    <input type="radio" name="payment_type" value="pay_now" checked="" hidden="">
                    <div class="payment__method-card">
                        <span class="checkicon"></span>
                        <h4 class="title">{{ translate('manual_payment') }} </h4>
                        <div>
                            {{ translate('collect_payment_manually_from_the_seller') }}
                        </div>
                    </div>
                </label>
            </div>
            <div class="col-md-6">
                <label class="payment__method">
                    <input type="radio" name="payment_type" value="wallet" hidden="">
                    <div class="payment__method-card">
                        <span class="checkicon"></span>
                        <h4 class="title">{{ translate('pay_from_seller_wallet') }}
                        </h4>
                        <div>
                            <strong>
                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $wallet->total_earning), currencyCode: getCurrencyCode(type: 'default')) }}
                            </strong>
                            {{ translate('payable_amount_in_the_wallet') }}
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <div class="__btn-container btn--container justify-content-end mt-5" style="display: flex;">
            <button type="button" data-dismiss="modal"
                class="btn btn--reset px-lg-5">{{ translate('Cancel') }}</button>
            @if (isset($seller_subscription) && $seller_subscription->plan_id == $plan->id)
                <button type="submit" name="button" value="renew" class="btn btn--primary">
                    <span class="ml-1">{{ translate('Renew_Subscription_Plan') }}</span> </button>
            @else
                <button type="submit" name="button" value="totally_new" class="btn btn--primary">
                    <span class="ml-1">{{ translate('Change_Subscription_Plan') }}</span> </button>
            @endif

        </div>
    </form>

</div>
