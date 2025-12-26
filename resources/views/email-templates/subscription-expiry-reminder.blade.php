<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ translate('Subscription Expiry Reminder') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('public/assets/back-end/css/email-basic.css') }}">
</head>
<body>
<?php

use App\Models\Order;
use App\Models\Seller;
use App\Models\Shop;
use \App\User;
use \App\Models\SellerSubscription;

$companyPhone = getWebConfig(name: 'company_phone');
$companyEmail = getWebConfig(name: 'company_email');
$companyName = getWebConfig(name: 'company_name');
$companyLogo = getWebConfig(name: 'company_web_logo');

$shop = Shop::find($seller->id);

$currentEndDate = SellerSubscription::where('seller_id', $seller->id)->where('status', true)->first()?->current_end;
$daysLeft = 0;

if($currentEndDate){
    // Convert the dates to Carbon objects
    $currentPlanEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $currentEndDate);
    $currentDate = \Carbon\Carbon::now();
}


if($currentDate < $currentPlanEndDate && $daysLeft < 11){
    // Calculate the difference in days
    $daysLeft = $currentPlanEndDate->diffInDays($currentDate);}

?>

<div class="order-main-table">
    <table class="order-main-sub-table">
        <tbody>
        <tr>
            <td>
                <div class="text-end me-1">
                    <img src="{{asset('/storage/app/public/company/'.$companyLogo) }}" width="30%" alt="{{ translate('company_Logo') }}"/>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

    <table class="order-action-btn" style="margin-top: 30px;">
        <tbody>
            @if ($currentDate < $currentPlanEndDate && $daysLeft < 11)
                <tr>
                    <span class="alert alert-danger mt-3 mr-2" role="alert">
                        <i class="tio-warning"></i>
                        {{ translate('you_have') . ' '. $daysLeft . ' ' . translate('_days_left_for_the_current_plan_to_expire')  }}
                    </span>
                </tr>
            @endif

            <tr>
                <td>{{ translate('You_can_track_your_subscription_clicking_the_below_button') }}</td>
            </tr>
            <tr>
                <td>
                    <div class="my-4">
                        <a href="{{route('vendor.business-settings.subscription.index')}}"
                           class="p-3 radius-5 text-capitalize border-0 btn btn-primary">
                            {{ translate('my_Subscription') }}
                        </a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

</div>

<div class="credit-section">

    <table class="m-auto width-100">
        <tbody>
        <tr>
            <th class="text-start">
                <h1>
                    {{ $companyName }}
                </h1>
            </th>
        </tr>
        <tr>
            <th class="text-start">
                <div> {{ translate('phone') }} : {{ $companyPhone }}</div>
                <div> {{ translate('website') }} : {{ url('/') }}</div>
                <div> {{ translate('email') }} : {{ $companyEmail }}</div>
            </th>
        </tr>
        <tr>
            @php($socialMedia = \App\Models\SocialMedia::where('active_status', 1)->get())
            @if(isset($socialMedia))
                <th class="text-start pt-20px">
                    <div class="width-100 d-flex">
                        @foreach ($socialMedia as $item)
                            <div>
                                <a href="{{$item->link}}" target=”_blank”>
                                    <img src="{{asset('public/assets/back-end/img/'.$item->name.'.png') }}" alt=""
                                         class="h-50px width-50px m-10px">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </th>
            @endif
        </tr>
        </tbody>
    </table>
</div>

</body>
</html>
