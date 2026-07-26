@php
    use App\Enums\ViewPaths\Admin\SMSModule;
@endphp
<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/payment-method') ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.business-settings.payment-method.index')}}">{{translate('payment_methods')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/offline-payment-method/*') ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.business-settings.offline-payment-method.index')}}">{{translate('offline_payment_methods')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/'.SMSModule::VIEW[URI]) ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.business-settings.sms-module')}}">{{translate('SMS_config')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/map-api') ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.business-settings.map-api')}}">{{translate('google_map_APIs')}}</a></li>
    </ul>
</div>
