<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Enums\ViewPaths\Admin\Order;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $vendor;
    public $filePath;

    public function __construct($order, $vendor, $filePath)
    {
        $this->order = $order;
        $this->vendor = $vendor;
        $this->filePath = $filePath;
    }

    public function build()
    {
        $companyPhone = getWebConfig(name: 'company_phone');
        $companyEmail = getWebConfig(name: 'company_email');
        $companyName = getWebConfig(name: 'company_name');
        $companyWebLogo = getWebConfig(name: 'company_web_logo');

        return $this->subject('Order Invoice - #' . $this->order->id)
            ->view(Order::GENERATE_INVOICE[VIEW])
            ->with([
                'order' => $this->order,
                'vendor' => $this->vendor,
                'companyPhone' => $companyPhone,
                'companyEmail' => $companyEmail,
                'companyName' => $companyName,
                'companyWebLogo' => $companyWebLogo,
            ])
            ->attach($this->filePath, [
                'as' => 'Order_Invoice_' . $this->order->id . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
