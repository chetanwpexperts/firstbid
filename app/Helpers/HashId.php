<?php

namespace App\Helpers;

class HashId
{
    private static function getSecret(): string
    {
        return hash('sha256', config('app.key', 'firstbid_secret_salt_2026'), true);
    }

    /**
     * Encode an integer ID into a short, secure, URL-safe hash.
     */
    public static function encode(int|string $id): string
    {
        $data = (string) $id;
        $secret = self::getSecret();
        $sig = substr(hash_hmac('sha256', $data, $secret, true), 0, 3);
        $obfuscated = base64_encode($data . $sig);
        return rtrim(strtr($obfuscated, '+/', '-_'), '=');
    }

    /**
     * Decode a hash back into integer ID. Returns null if invalid or tampered.
     */
    public static function decode(string|int $hash): ?int
    {
        if (is_numeric($hash)) {
            return (int) $hash;
        }

        $b64 = strtr((string)$hash, '-_', '+/') . str_repeat('=', (4 - strlen((string)$hash) % 4) % 4);
        $decoded = base64_decode($b64, true);

        if (!$decoded || strlen($decoded) <= 3) {
            return null;
        }

        $data = substr($decoded, 0, -3);
        $sig = substr($decoded, -3);
        $secret = self::getSecret();

        $expectedSig = substr(hash_hmac('sha256', $data, $secret, true), 0, 3);

        if (hash_equals($expectedSig, $sig) && is_numeric($data)) {
            return (int) $data;
        }

        return null;
    }
}
