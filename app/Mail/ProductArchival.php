<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductArchival extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $seller;
    public $product;

    public function __construct($seller, $product)
    {
        $this->seller = $seller;
        $this->product = $product;
    }

    public function build()
    {
        return $this->subject(translate('Product_Archived'))->view('email-templates.product-archival', ['seller' => $this->seller, 'product' => $this->product]);
    }
}
