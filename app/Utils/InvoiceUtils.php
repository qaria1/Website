<?php

namespace App\Utils;


use Mpdf\Mpdf;
use Illuminate\Support\Str;
use App\Mail\OrderInvoiceMail;
use App\Enums\ViewPaths\Admin\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\View as PdfView;

class InvoiceUtils
{
    /**
     * Generate and send order invoice PDF (queued), then delete the temp file.
     */
    public static function sendOrderInvoice($order, $vendor)
    {
        $companyPhone = getWebConfig(name: 'company_phone');
        $companyEmail = getWebConfig(name: 'company_email');
        $companyName = getWebConfig(name: 'company_name');
        $companyWebLogo = getWebConfig(name: 'company_web_logo');

        // --- Generate PDF view ---

        $html = View::make(
            Order::GENERATE_INVOICE[VIEW],
            compact('order', 'vendor', 'companyPhone', 'companyEmail', 'companyName', 'companyWebLogo')
        )->render();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);

        // --- Temp file path ---
        $fileName = 'order_invoice_' . $order->id . '_' . Str::random(8) . '.pdf';
        $tempDir = storage_path('app/private/tmp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tempPath = $tempDir . '/' . $fileName;

        $mpdf->Output($tempPath, \Mpdf\Output\Destination::FILE);
        // --- Queue email with attachment ---
        // --- Send email to customer ---
        if (!empty($order->shipping_address_data->email)) {
            Mail::to($order->shipping_address_data->email)->queue(
                new OrderInvoiceMail($order, $vendor, $tempPath)
            );
        }

        // --- Send email to seller ---
        if (!empty($vendor->email)) {
            Mail::to($vendor->email)->queue(
                new OrderInvoiceMail($order, $vendor, $tempPath)
            );
        }

        // --- Cleanup job after sending ---
        dispatch(function () use ($tempPath) {
            sleep(10); // allow time for queued mail to use the file
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        })->delay(now()->addSeconds(15));

        return true;
    }
}
