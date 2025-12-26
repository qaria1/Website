<?php

namespace App\Console\Commands;

use App\Mail\ProductArchival;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ArchiveProducts extends Command
{
    protected $signature = 'products:archive';
    protected $description = 'Archive products that have finished their lifecycle';

    public function handle()
    {
        $today = Carbon::now();

        $archivingProducts = \App\Models\Product::
            where('lifetime_end_date', '<', $today)
            ->where('status', true)
            ->where('added_by', 'seller')
            ->get();

        foreach ($archivingProducts as $product) {
            $seller = \App\Models\Seller::find($product->user_id);

            if($seller){
                try{
                    $product->update([
                        'status' => false,
                        'is_lifetime_ended' => 1,
                        'archived_at' => $today,
                    ]);

                    // TODO: Implement notification for seller via mobile, SMS or email here

                    // Notify the seller via email
                    Mail::to($seller->email)->send(new ProductArchival($seller, $product));
                } catch(\Exception $exception) {
                    info($exception);
                }
            }

        }

        try{
            DB::table('products')
                ->where('lifetime_end_date', '<', $today)
                ->where('status', true)
                ->where('added_by', 'seller')
                ->update([
                    'status' => false,
                    'is_lifetime_ended' => 1,
                    'archived_at' => $today,
            ]);
        } catch(\Exception $exception) {
            info($exception);
        }
    }
}

