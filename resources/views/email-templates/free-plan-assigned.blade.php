<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ translate('Free Plan') }}</title>
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

            <tr>
                <td>{{ translate('congratulations') . '!' }} {{ translate('you_have_got_a_free_plan') }}</td>
            </tr>
            <tr>
                <td>{{ translate('You_can_check_your_subscription_by_clicking_the_below_button') }}</td>
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
