<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class BakongService
{
    protected string $apiKey;
    protected string $merchantId;
    protected string $accountId;
    protected string $currency;
    protected string $apiUrl;
    protected string $merchantName;

    public function __construct()
    {
        $this->apiKey = config('services.bakong.api_key');
        $this->merchantId = config('services.bakong.merchant_id');
        $this->accountId = config('services.bakong.account_id');
        $this->currency = config('services.bakong.currency', 'USD');
        $this->merchantName = config('services.bakong.merchant_name', 'Restaurant');
        
        // Use sandbox URL for development, production URL for live
        $this->apiUrl = config('services.bakong.sandbox', true)
            ? 'https://api.sandbox.bakong.nbc.org.kh'
            : 'https://api.bakong.nbc.org.kh';
    }

    /**
     * Generate KHQR code for payment
     *
     * @param string $orderId Order reference
     * @param float $amount Payment amount
     * @return array
     */
    public function generateKHQR(string $orderId, float $amount): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/v1/generate", [
                'merchant_id' => $this->merchantId,
                'account_id' => $this->accountId,
                'merchant_name' => $this->merchantName,
                'currency' => $this->currency,
                'amount' => $amount,
                'reference' => $orderId,
                'terminal_label' => 'POS-001',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'qr_string' => $data['qr_string'] ?? $data['data']['qr_string'] ?? null,
                    'qr_image' => $data['qr_image'] ?? $data['data']['qr_image'] ?? null,
                    'md5' => $data['md5'] ?? $data['data']['md5'] ?? null,
                    'deep_link' => $data['deep_link'] ?? $data['data']['deep_link'] ?? null,
                    'reference' => $orderId,
                    'amount' => $amount,
                ];
            }

            Log::error('Bakong KHQR generation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to generate QR code',
            ];

        } catch (Exception $e) {
            Log::error('Bakong KHQR exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check payment status from Bakong
     *
     * @param string $md5 The MD5 hash from QR generation
     * @return array
     */
    public function checkPaymentStatus(string $md5): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/v1/check_payment_status", [
                'md5' => $md5,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'status' => $data['status'] ?? $data['data']['status'] ?? 'pending',
                    'transaction_id' => $data['transaction_id'] ?? $data['data']['transaction_id'] ?? null,
                    'paid_at' => $data['paid_at'] ?? $data['data']['paid_at'] ?? null,
                ];
            }

            return [
                'success' => false,
                'status' => 'pending',
                'message' => 'Failed to check payment status',
            ];

        } catch (Exception $e) {
            Log::error('Bakong status check exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'status' => 'pending',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate a simple KHQR string locally (fallback when API is unavailable)
     * This creates a basic KHQR-compliant string
     *
     * @param string $orderId
     * @param float $amount
     * @return string
     */
    public function generateLocalKHQR(string $orderId, float $amount): string
    {
        // KHQR format follows EMV QR Code specification
        // This is a simplified version - for production, use the official API
        
        $payload = '';
        
        // Payload Format Indicator (ID 00)
        $payload .= '000201'; // Version 01
        
        // Point of Initiation Method (ID 01)
        $payload .= '010211'; // 11 = Dynamic QR
        
        // Merchant Account Information (ID 29) - Bakong specific
        $merchantInfo = '';
        $merchantInfo .= '00' . str_pad((string)strlen('bakong'), 2, '0', STR_PAD_LEFT) . 'bakong'; // Global Unique ID
        $merchantInfo .= '01' . str_pad((string)strlen($this->accountId), 2, '0', STR_PAD_LEFT) . $this->accountId; // Account ID
        $merchantInfo .= '02' . str_pad((string)strlen($this->merchantId), 2, '0', STR_PAD_LEFT) . $this->merchantId; // Merchant ID
        $payload .= '29' . str_pad((string)strlen($merchantInfo), 2, '0', STR_PAD_LEFT) . $merchantInfo;
        
        // Merchant Category Code (ID 52)
        $payload .= '52045812'; // Restaurant
        
        // Transaction Currency (ID 53) - 840 = USD, 116 = KHR
        $currencyCode = $this->currency === 'KHR' ? '116' : '840';
        $payload .= '53' . str_pad((string)strlen($currencyCode), 2, '0', STR_PAD_LEFT) . $currencyCode;
        
        // Transaction Amount (ID 54)
        $amountStr = number_format($amount, 2, '.', '');
        $payload .= '54' . str_pad((string)strlen($amountStr), 2, '0', STR_PAD_LEFT) . $amountStr;
        
        // Country Code (ID 58)
        $payload .= '5802KH';
        
        // Merchant Name (ID 59)
        $payload .= '59' . str_pad((string)strlen($this->merchantName), 2, '0', STR_PAD_LEFT) . $this->merchantName;
        
        // Merchant City (ID 60)
        $merchantCity = 'Phnom Penh';
        $payload .= '60' . str_pad((string)strlen($merchantCity), 2, '0', STR_PAD_LEFT) . $merchantCity;
        
        // Additional Data Field (ID 62) - Order reference
        $additionalData = '01' . str_pad((string)strlen($orderId), 2, '0', STR_PAD_LEFT) . $orderId;
        $payload .= '62' . str_pad((string)strlen($additionalData), 2, '0', STR_PAD_LEFT) . $additionalData;
        
        // CRC (ID 63) - Calculate CRC16
        $payload .= '6304';
        $crc = $this->calculateCRC16($payload);
        $payload .= $crc;
        
        return $payload;
    }

    /**
     * Calculate CRC16 for KHQR
     *
     * @param string $data
     * @return string
     */
    private function calculateCRC16(string $data): string
    {
        $crc = 0xFFFF;
        $polynomial = 0x1021;
        
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ $polynomial;
                } else {
                    $crc <<= 1;
                }
                $crc &= 0xFFFF;
            }
        }
        
        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Generate QR code image URL using external service
     *
     * @param string $qrString
     * @param int $size
     * @return string
     */
    public function getQRImageURL(string $qrString, int $size = 300): string
    {
        // Use QR code API to generate image
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($qrString);
    }
}
