<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * @property string $gateway
 */
class SMSModuleUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gateway' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'gateway.required' => translate('the_gateway_field_is_required'),
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                collect(['status'])->each(fn($item, $key) => $this[$item] = $this->has($item) ? (int)$this[$item] : 0);

                $validation = [
                    'gateway' => 'required|in:geez_sms',
                    'mode' => 'required|in:live,test'
                ];
                $additionalData = [];
                if ($this['gateway'] == 'geez_sms') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'token' => 'nullable',
                        'otp_template' => 'required',
                    ];
                }
                $this->validate(array_merge($validation, $additionalData));
            }
        ];
    }
}
