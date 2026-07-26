<?php

namespace App\Http\Controllers\Admin\ThirdParty;

use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Enums\GlobalConstant;
use App\Enums\ViewPaths\Admin\SMSModule;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SMSModuleUpdateRequest;
use App\Services\SettingService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SMSModuleController extends BaseController
{
    public function __construct(
        private readonly SettingRepositoryInterface $settingRepo,
        private readonly SettingService             $settingService,
    )
    {
    }

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getView();
    }

    public function getView(): View
    {
        $paymentPublishedStatus = config('get_payment_publish_status') ?? 0;
        $paymentGatewayPublishedStatus = isset($paymentPublishedStatus[0]['is_published']) ? $paymentPublishedStatus[0]['is_published'] : 0;
        $geezExists = $this->settingRepo->getFirstWhere(params: ['key_name' => 'geez_sms', 'settings_type' => 'sms_config']);
        if (!$geezExists) {
            $defaultValues = [
                'gateway' => 'geez_sms',
                'mode' => 'live',
                'status' => 0,
                'token' => '',
                'otp_template' => 'Your OTP code is #OTP#',
            ];
            $this->settingRepo->updateOrInsert(params: ['key_name' => 'geez_sms', 'settings_type' => 'sms_config'], data: [
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'key_name' => 'geez_sms',
                'live_values' => json_encode($defaultValues),
                'test_values' => json_encode($defaultValues),
                'settings_type' => 'sms_config',
                'mode' => 'live',
                'is_active' => 0,
            ]);
        }

        $smsGatewaysList = $this->settingRepo->getListWhereIn(
            whereInFilters: ['settings_type' => ['sms_config'], 'key_name' => GlobalConstant::DEFAULT_SMS_GATEWAYS],
            dataLimit: 'all',
        );

        $smsGateways = $smsGatewaysList->sortBy(function ($item) {
            return count($item['live_values']);
        })->values()->all();

        $paymentUrl = $this->settingService->getVacationData(type: 'sms_setup');
        return view(SMSModule::VIEW[VIEW], compact('smsGateways', 'paymentGatewayPublishedStatus', 'paymentUrl'));
    }

    public function update(SMSModuleUpdateRequest $request): RedirectResponse
    {
        $gateway = 'geez_sms';
        $keep = $this->settingRepo->getFirstWhere(params: ['key_name' => $gateway, 'settings_type' => 'sms_config']);

        if ($keep) {
            // live_values is cast to array by the model
            $hold = is_array($keep['live_values']) ? $keep['live_values'] : json_decode($keep['live_values'], true);

            // Merge the incoming form values into the existing config
            $hold['status'] = $request->get('status', 0);
            $hold['token'] = $request->get('token', $hold['token'] ?? '');
            $hold['otp_template'] = $request->get('otp_template', $hold['otp_template'] ?? '');
            $hold['mode'] = $request->get('mode', 'live');
            $hold['gateway'] = $gateway;

            $this->settingRepo->updateWhere(
                params: ['key_name' => $gateway, 'settings_type' => 'sms_config'],
                data: [
                    'live_values' => json_encode($hold),
                    'test_values' => json_encode($hold),
                    'mode' => $request->get('mode', 'live'),
                    'is_active' => $request->get('status', 0),
                ]
            );
        }

        Toastr::success(GATEWAYS_DEFAULT_UPDATE_200['message']);
        return back();
    }
}
