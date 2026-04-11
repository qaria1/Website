<?php

namespace App\Jobs;

use App\Events\OrderStatusEvent;
use App\Traits\PushNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderStatusChangeSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PushNotificationTrait;

    public function __construct(
        public string $key,
        public string $type,
        public $order
    ) {}

    public function handle(): void
    {
        $this->sendOrderStatusChangeSMS(
            key: $this->key,
            type: $this->type,
            order: $this->order
        );
    }
}
