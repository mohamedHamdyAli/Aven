<?php

namespace Webkul\SmsNotification\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private function normalizePhone(string $phone): string
    {
        $clean = preg_replace('/[^0-9+]/', '', $phone);
        if (str_starts_with($clean, '01') || str_starts_with($clean, '010') || str_starts_with($clean, '011') || str_starts_with($clean, '012') || str_starts_with($clean, '015')) {
            return '+20' . ltrim($clean, '0');
        }
        if (!str_starts_with($clean, '+')) {
            return '+20' . ltrim($clean, '0');
        }
        return $clean;
    }

    public function send(string $phone, string $message): bool
    {
        if (!core()->getConfigData('sales.sms_notification.enabled')) {
            return false;
        }

        $phone = $this->normalizePhone($phone);
        $provider = core()->getConfigData('sales.sms_notification.provider') ?? 'vonage';

        try {
            if ($provider === 'vonage') {
                return $this->sendVonage($phone, $message);
            }
            return $this->sendTwilio($phone, $message);
        } catch (\Throwable $e) {
            Log::error('SMS send failed: ' . $e->getMessage(), ['phone' => substr($phone, 0, 6) . '****']);
            return false;
        }
    }

    private function sendVonage(string $phone, string $message): bool
    {
        $response = Http::post('https://rest.nexmo.com/sms/json', [
            'api_key'    => core()->getConfigData('sales.sms_notification.vonage_api_key'),
            'api_secret' => core()->getConfigData('sales.sms_notification.vonage_api_secret'),
            'from'       => core()->getConfigData('sales.sms_notification.vonage_sender') ?? 'SHOP',
            'to'         => ltrim($phone, '+'),
            'text'       => $message,
        ]);
        $data = $response->json();
        $status = $data['messages'][0]['status'] ?? '1';
        if ($status !== '0') {
            Log::warning('Vonage SMS failed', ['status' => $status, 'error' => $data['messages'][0]['error-text'] ?? '']);
            return false;
        }
        return true;
    }

    private function sendTwilio(string $phone, string $message): bool
    {
        $sid   = core()->getConfigData('sales.sms_notification.twilio_account_sid');
        $token = core()->getConfigData('sales.sms_notification.twilio_auth_token');
        $from  = core()->getConfigData('sales.sms_notification.twilio_from');
        $response = Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => $from,
                'To'   => $phone,
                'Body' => $message,
            ]);
        return $response->successful();
    }
}
