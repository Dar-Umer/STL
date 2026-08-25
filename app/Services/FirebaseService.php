<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    public static function sendToUser(int $userId, string $title, string $body, array $data = []): int
    {
        $serviceAccount = self::serviceAccount();
        if (!$serviceAccount) {
            return 0;
        }

        $tokens = DeviceToken::where('user_id', $userId)->pluck('token')->toArray();
        if (empty($tokens)) {
            return 0;
        }

        $accessToken = self::accessToken($serviceAccount);
        if (!$accessToken) {
            return 0;
        }

        $projectId = $serviceAccount['project_id'];
        $payloadData = collect(array_merge($data, ['click_action' => 'FLUTTER_NOTIFICATION_CLICK']))
            ->map(fn($v) => (string) $v)
            ->toArray();

        $sent = 0;
        foreach ($tokens as $token) {
            try {
                $response = Http::withToken($accessToken)
                    ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                        'message' => [
                            'token' => $token,
                            'notification' => [
                                'title' => $title,
                                'body' => $body,
                            ],
                            'data' => $payloadData,
                        ],
                    ]);

                if ($response->successful()) {
                    $sent++;
                } else {
                    $result = $response->json();
                    $error = $result['error']['details'][0]['errorCode']
                        ?? $result['error']['message']
                        ?? 'unknown';
                    Log::warning("FCM v1 error for user {$userId}: {$error}");
                    if (in_array($error, ['UNREGISTERED', 'INVALID_ARGUMENT'])) {
                        DeviceToken::where('token', $token)->delete();
                    }
                }
            } catch (\Exception $e) {
                Log::warning('FCM v1 send failed: ' . $e->getMessage());
            }
        }

        return $sent;
    }

    private static function serviceAccount(): ?array
    {
        $raw = setting('notification_credentials', '');
        if (!$raw) {
            return null;
        }

        $creds = json_decode($raw, true);
        $json = $creds['service_account_json'] ?? null;

        if ($json && str_starts_with($json, 'encrypted:')) {
            try {
                $json = Crypt::decryptString(substr($json, 10));
            } catch (\Exception $e) {
                Log::warning('Failed to decrypt service account JSON');
                return null;
            }
        }

        $account = $json ? json_decode($json, true) : null;

        if (!$account || empty($account['client_email']) || empty($account['private_key'])) {
            return null;
        }

        return $account;
    }

    private static function accessToken(array $serviceAccount): ?string
    {
        $now = time();
        $jwt = self::generateJwtAssertion($serviceAccount, $now);

        if (!$jwt) {
            return null;
        }

        try {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            $result = $response->json();
            return $result['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::warning('FCM OAuth2 token exchange failed: ' . $e->getMessage());
            return null;
        }
    }

    private static function generateJwtAssertion(array $sa, int $now): string
    {
        $header = self::base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = self::base64UrlEncode(json_encode([
            'iss' => $sa['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]));

        $key = openssl_pkey_get_private($sa['private_key']);
        if (!$key) {
            Log::warning('Failed to parse FCM service account private key');
            return '';
        }

        $signature = '';
        openssl_sign("{$header}.{$payload}", $signature, $key, 'sha256WithRSAEncryption');
        openssl_free_key($key);

        return "{$header}.{$payload}." . self::base64UrlEncode($signature);
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
