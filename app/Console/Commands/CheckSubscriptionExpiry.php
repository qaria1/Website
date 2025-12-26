<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\SubscriptionExpiryReminder;

class CheckSubscriptionExpiry extends Command
{
    protected $signature = 'subscriptions:check-expiry';
    protected $description = 'Check and handle subscription expiry for sellers';

    public function handle()
    {
        $today = Carbon::now();
        $expireDateRange = $today->copy()->addDays(11);

        // Send email for vendors with less than 11 days due plan
        $expiringSubscriptions = DB::table('seller_subscriptions')
            ->where('current_end', '<=', $expireDateRange)
            ->where('current_end', '>', $today)
            ->where('status', true)
            ->get();

        foreach ($expiringSubscriptions as $subscription) {
            $seller = \App\Models\Seller::find($subscription->seller_id);

            if($seller){
                try{
                    // TODO: Implement notification for seller via mobile, SMS or email here

                    // Notify the seller via email
                    Mail::to($seller->email)->send(new SubscriptionExpiryReminder($seller));
                }catch(\Exception $exception) {
                    info($exception);
                }
            }

        }

        // Change status of active plan if expired
        try{
            DB::table('seller_subscriptions')
                ->where('current_end', '<', $today)
                ->where('status', true)
                ->update([
                    'status' => false
                ]);
        } catch(\Exception $exception) {
            info($exception);
        }
    }
}
