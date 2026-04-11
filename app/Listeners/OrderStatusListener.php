<?php

namespace App\Listeners;

use App\Events\OrderStatusEvent;
use App\Jobs\SendOrderStatusChangeSMS;
use App\Models\Seller;
use App\Traits\PushNotificationTrait;
use App\Utils\InvoiceUtils;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderStatusListener
{
    use PushNotificationTrait;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusEvent $event): void
    {
        $this->sendNotification($event);
    }

    private function sendNotification(OrderStatusEvent $event): void
    {
        $key = $event->key;
        $type = $event->type;
        $order = $event->order;
        $this->sendOrderNotification(key: $key, type: $type, order: $order);

        $orderStatus = ['delivered', 'confirmed'];
        if (in_array($key, $orderStatus)) {
            SendOrderStatusChangeSMS::dispatch($key, $type, $order);
        }

        $vendor = Seller::where('id', $order['details']->first()->seller_id)->first();
        $invoiceOrderStatus = ['confirmed','pending'];
        if (in_array($key, $invoiceOrderStatus)) {
            InvoiceUtils::sendOrderInvoice($order, $vendor);
        }
    }
}
