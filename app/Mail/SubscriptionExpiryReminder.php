<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiryReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;

    public function __construct($seller)
    {
        $this->seller = $seller;
    }

    public function build()
    {
        return $this->subject(translate('Subscription_Expire_Reminder'))->view('email-templates.subscription-expiry-reminder', ['seller' => $this->seller]);
    }
}

