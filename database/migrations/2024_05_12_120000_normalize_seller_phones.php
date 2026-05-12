<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function normalizePhone(string $phone): string
    {
        $phone = trim($phone);
        $digits = preg_replace('/\D+/', '', $phone);

        // 10-digit with leading 0 (Ethiopian national format)
        if (strlen($digits) === 10 && str_starts_with($digits, '0')) {
            return '251' . substr($digits, 1);
        }

        // 12-digit already with 251 prefix
        if (strlen($digits) === 12 && str_starts_with($digits, '251')) {
            return $digits;
        }

        // 9-digit (new format)
        if (strlen($digits) === 9 && str_starts_with($digits, '9')) {
            return '251' . $digits;
        }

        // Return as-is if unrecognized format
        return $phone;
    }

    public function up(): void
    {
        // Normalize seller phones
        $sellers = DB::table('sellers')->select('id', 'phone')->get();
        foreach ($sellers as $seller) {
            $normalized = $this->normalizePhone($seller->phone);
            if ($normalized !== $seller->phone) {
                DB::table('sellers')->where('id', $seller->id)->update(['phone' => $normalized]);
            }
        }

        // Normalize shop contact phones
        $shops = DB::table('shops')->select('id', 'contact')->get();
        foreach ($shops as $shop) {
            if ($shop->contact) {
                $normalized = $this->normalizePhone($shop->contact);
                if ($normalized !== $shop->contact) {
                    DB::table('shops')->where('id', $shop->id)->update(['contact' => $normalized]);
                }
            }
        }
    }

    public function down(): void
    {
        // No reverse - phones should stay normalized
    }
};