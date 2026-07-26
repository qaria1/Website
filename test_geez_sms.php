<?php
/**
 * Geez SMS Test Script
 * Usage: php test_geez_sms.php +251XXXXXXXXX
 * 
 * Run from the project root: c:\Users\user\Desktop\Martreza\Website
 */

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get phone from command line arg, or use a default
$phone = $argv[1] ?? null;

if (!$phone) {
    echo "Usage: php test_geez_sms.php +251XXXXXXXXX\n";
    echo "Example: php test_geez_sms.php +251912345678\n";
    exit(1);
}

echo "=== Geez SMS Test ===\n\n";

// 1. Check if geez_sms config exists in DB
$row = DB::table('addon_settings')
    ->where('key_name', 'geez_sms')
    ->where('settings_type', 'sms_config')
    ->first();

if (!$row) {
    echo "[ERROR] geez_sms not found in addon_settings table!\n";
    exit(1);
}

$config = json_decode($row->live_values, true);

echo "[INFO] Current Config:\n";
echo "  Status:       " . ($config['status'] ? 'ON (1)' : 'OFF (0)') . "\n";
echo "  Token:        " . (empty($config['token']) ? '(empty - NOT SET!)' : substr($config['token'], 0, 8) . '...') . "\n";
echo "  OTP Template: " . ($config['otp_template'] ?? '(empty)') . "\n";
echo "  is_active:    " . $row->is_active . "\n\n";

if (empty($config['token'])) {
    echo "[ERROR] Token is empty! Please enter your Geez SMS API token in Admin > 3rd Party > SMS Config first.\n";
    exit(1);
}

if ($config['status'] != 1) {
    echo "[WARNING] Geez SMS status is OFF. Sending anyway for test...\n\n";
}

// 2. Send test SMS directly
$otp = rand(100000, 999999);
$token = $config['token'];
$message = isset($config['otp_template']) ? str_replace("#OTP#", $otp, $config['otp_template']) : (string)$otp;

echo "[INFO] Sending OTP: $otp\n";
echo "[INFO] Message: $message\n";
echo "[INFO] To: $phone\n";
echo "[INFO] Calling: https://api.geezsms.com/api/v1/sms/send\n\n";

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.geezsms.com/api/v1/sms/send',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => http_build_query([
        'token' => $token,
        'phone' => $phone,
        'msg'   => $message,
    ]),
]);

$res = curl_exec($curl);
$err = curl_error($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "[INFO] HTTP Response Code: $httpCode\n";
echo "[INFO] Response Body: $res\n\n";

if ($err) {
    echo "[ERROR] CURL Error: $err\n";
} else {
    $decoded = json_decode($res, true);
    if ($httpCode >= 200 && $httpCode < 300) {
        echo "[SUCCESS] SMS sent successfully!\n";
    } else {
        echo "[FAILURE] SMS failed. Check the response above for details.\n";
    }
}
