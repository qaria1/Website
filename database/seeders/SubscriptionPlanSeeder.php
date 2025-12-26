<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\BillingType;
use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\TrialPlanSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*

            Note: Seeder for Plans with their respective features

        */

        DB::transaction(function ($r) {

            // Create the default billing type of monthly
            $monthlyBillingType = BillingType::firstOrCreate([
                'name' => 'Monthly',
                'duration_in_days' => 30,
            ]);

            // create default brand
            \App\Models\Brand::firstOrCreate([
                'name' => 'Default',
            ]);

            // Create subscription plans
            $exclusivePlan = SubscriptionPlan::firstOrCreate([
                'name' => 'Exclusive', 'slug' => 'exclusive', 'code' => 'V', 'max_product_lifecycle' => 300, 'max_product_upload' => "70",
                "discount" => 1, "product_top_search" => 1, "item_verification" => 1, "product_photoshoot" => 1, "free_delivery" => 1
            ]);
            $abolPlan = SubscriptionPlan::firstOrCreate([
                'name' => 'Abol', 'slug' => 'abol', 'code' => 'A', 'max_product_lifecycle' => 300, 'max_product_upload' => "70",
                "discount" => 1, "product_top_search" => 1, "item_verification" => 1, "product_photoshoot" => 1, "free_delivery" => 1
            ]);
            $tonaPlan = SubscriptionPlan::firstOrCreate([
                'name' => 'Tona', 'slug' => 'tona', 'code' => 'T', 'max_product_lifecycle' => 240, 'max_product_upload' => "40",
                "discount" => 1, "product_photoshoot" => 1, "free_delivery" => 1
            ]);
            $berekaPlan = SubscriptionPlan::firstOrCreate([
                'name' => 'Bereka', 'slug' => 'bereka', 'code' => 'B', 'max_product_lifecycle' => 120, 'max_product_upload' => "25",
                "discount" => 1, "free_delivery" => 1
            ]);
            $freePlan = SubscriptionPlan::firstOrCreate([
                'name' => 'Free', 'slug' => 'free', 'code' => 'F', 'max_product_lifecycle' => 75, 'max_product_upload' => "10", 'available_vendors' => 250,
                "free_delivery" => 1
            ]);

            $exclusivePlan->billingTypes()->syncWithoutDetaching([
                $monthlyBillingType->id => [
                    'price' => 1000,
                    'validity' => 30
                ],
            ]);

            $abolPlan->billingTypes()->syncWithoutDetaching([
                $monthlyBillingType->id => [
                    'price' => 1000,
                    'validity' => 30
                ],
            ]);

            $tonaPlan->billingTypes()->syncWithoutDetaching([
                $monthlyBillingType->id => [
                    'price' => 500,
                    'validity' => 30
                ],
            ]);

            $berekaPlan->billingTypes()->syncWithoutDetaching([
                $monthlyBillingType->id => [
                    'price' => 350,
                    'validity' => 30
                ],
            ]);

            $freePlan->billingTypes()->syncWithoutDetaching([
                $monthlyBillingType->id => [
                    'price' => 0,
                    'validity' => 30
                ],
            ]);

            // Default trial creation with a 2 month duration
            TrialPlanSetting::firstOrCreate([
                'plan_id' => $tonaPlan->id,
                'duration_in_days' => 60,
            ]);

            // create trial_period data if not exists
            $trialPeriodExists = \App\Models\BusinessSetting::where('type', 'trial_period')->get();
            if($trialPeriodExists->count() < 1){

                \App\Models\BusinessSetting::create([
                    'type' => 'trial_period',
                    'value' => '{"plan_id":"'.$tonaPlan->id.'","validity":"60","price":"500","status":1}',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });
    }
}
