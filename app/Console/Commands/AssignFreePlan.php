<?php


// app/Console/Commands/AssignFreePlan.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\FreePlanAssigned;
use function App\Utils\subscription_plan_chosen;

class AssignFreePlan extends Command
{
    protected $signature = 'subscriptions:assign-free-plan';
    protected $description = 'Assign free plan to sellers from the waiting list';

    public function handle()
    {
        $freePlan = DB::table('subscription_plans')->where('slug', FREE_PLAN)->first();
        $freePlanCount = DB::table('seller_subscriptions')
            ->where('plan_id', $freePlan?->id)
            ->where('status', true)
            ->where('is_free', true)
            ->count();
        $freePlanLimit = $freePlan->available_vendors;

        if ($freePlan && $freePlanCount < $freePlanLimit) {
            $availableSpots = $freePlanLimit - $freePlanCount;
            $waitingList = DB::table('seller_waiting_lists')
                ->orderBy('position', 'asc')
                ->limit($availableSpots)
                ->get();

            foreach ($waitingList as $waitingSeller) {

                // check if seller has active plan to skip
                $sellerActivePlanCount = \App\Models\SellerSubscription::where('status', true)->where('seller_id', $waitingSeller->seller_id)->count();

                if($sellerActivePlanCount == 0){

                    $payment_method = 'manual_payment_admin';
                    $reference = null;
                    $type = null;

                    $billingId = \App\Models\PlanBilling::where('plan_id', $freePlan?->id)->first();
                    $request = new Request();
                    $request['billing_type'] = $billingId?->billing_type_id ?? null;

                    $status =  subscription_plan_chosen(request: $request, seller_id: $waitingSeller->seller_id, package_id: $freePlan?->id, payment_method: $payment_method, reference: $reference, type: $type);

                    if ($status) {
                        // Remove from waiting list
                        DB::table('seller_waiting_lists')->where('seller_id', $waitingSeller->seller_id)->delete();

                        // TODO: Implement notification for seller via mobile, SMS or email here that he got a free plan

                        // Notify the seller via email
                        $seller = \App\Models\Seller::find($waitingSeller->seller_id);

                        if($seller){
                            try{
                                Mail::to($seller->email)->send(new FreePlanAssigned($seller));
                            }catch(\Exception $exception) {
                                info($exception);
                            }
                        }

                    }

                }
            }
        }
    }
}
