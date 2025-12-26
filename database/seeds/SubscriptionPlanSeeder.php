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
            // Create features
            $maxProductUploadCount = Feature::firstOrCreate(['name' => 'max_product_upload_count']);
            $maxProductLifetime = Feature::firstOrCreate(['name' => 'max_product_lifetime']);
            $maxVendorAvailable = Feature::firstOrCreate(['name' => 'max_vendor_available']);

            // Create the default billing type of monthly
            $monthlyBillingType = BillingType::create([
                'name' => 'Monthly',
                'duration_in_days' => 30,
            ]);

            // Create subscription plans
            $exclusivePlan = SubscriptionPlan::firstOrCreate(['name' => 'Exclusive', 'slug' => 'exclusive', 'code' => 'V']);
            $abolPlan = SubscriptionPlan::firstOrCreate(['name' => 'Abol', 'slug' => 'abol', 'code' => 'A']);
            $tonaPlan = SubscriptionPlan::firstOrCreate(['name' => 'Tona', 'slug' => 'tona', 'code' => 'T']);
            $berekaPlan = SubscriptionPlan::firstOrCreate(['name' => 'Bereka', 'slug' => 'bereka', 'code' => 'B']);
            $freePlan = SubscriptionPlan::firstOrCreate(['name' => 'Free', 'slug' => 'free', 'code' => 'F']);

            // Attach features to subscription plans with different values
            $exclusivePlan->features()->attach([
                $maxProductUploadCount->id => ['value' => 70],
                $maxProductLifetime->id => ['value' => 300],
            ]);

            $exclusivePlan->billingTypes()->attach($monthlyBillingType->id, [
                'price' => 1000,
            ]);

            $abolPlan->features()->attach([
                $maxProductUploadCount->id => ['value' => 70],
                $maxProductLifetime->id => ['value' => 300],
            ]);

            $abolPlan->billingTypes()->attach($monthlyBillingType->id, [
                'price' => 1000,
            ]);

            $tonaPlan->features()->attach([
                $maxProductUploadCount->id => ['value' => 40],
                $maxProductLifetime->id => ['value' => 240],
            ]);

            $tonaPlan->billingTypes()->attach($monthlyBillingType->id, [
                'price' => 500,
            ]);

            $berekaPlan->features()->attach([
                $maxProductUploadCount->id => ['value' => 25],
                $maxProductLifetime->id => ['value' => 120],
            ]);

            $berekaPlan->billingTypes()->attach($monthlyBillingType->id, [
                'price' => 350,
            ]);

            $freePlan->features()->attach([
                $maxProductUploadCount->id => ['value' => 10],
                $maxProductLifetime->id => ['value' => 75],
                $maxVendorAvailable->id => ['value' => 250],
            ]);

            $freePlan->billingTypes()->attach($monthlyBillingType->id, [
                'price' => 0,
            ]);

            // Default trial creation with a 2 month duration
            TrialPlanSetting::firstOrCreate([
                'plan_id' => $tonaPlan->id,
                'duration_in_days' => 60,
            ]);
        });
    }
}
