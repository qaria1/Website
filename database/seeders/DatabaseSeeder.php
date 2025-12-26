<?php

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\SubscriptionPlanSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
            //  AdminRoleTable::class,
            //  AdminTable::class,
            //  SellerTableSeeder::class
            SubscriptionPlanSeeder::class,
         ]);

         // Default Ethiopian Birr currency setup
         if (Schema::hasTable('currencies')) {
            $ethiopianCurrencyExists = Currency::where('code', 'ETB')->get();
            if($ethiopianCurrencyExists->count() < 1){
                $currency = new Currency();
                $currency->name = 'Ethiopian Birr';
                $currency->symbol = 'Br';
                $currency->code = 'ETB';
                $currency->exchange_rate = 1;
                $currency->save();
            }
        }
    }
}
