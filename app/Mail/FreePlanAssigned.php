<?php

// app/Mail/FreePlanAssigned.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FreePlanAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;

    public function __construct($seller)
    {
        $this->seller = $seller;
    }

    public function build()
    {
        return $this->subject(translate('Free_Plan_Assigned'))->view('email-templates.free-plan-assigned', ['seller' => $this->seller]);
    }
}

