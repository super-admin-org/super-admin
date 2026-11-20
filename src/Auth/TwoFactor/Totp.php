<?php

namespace SuperAdmin\Admin\Auth\TwoFactor;

/**
 * Time-based one-time passwords (RFC 6238, HMAC-SHA1, 6 digits, 30 seconds),
 * the codes shown by Google Authenticator, Microsoft Authenticator, 1Password, Authy...
 */
class Totp
{
    public const PERIOD = 30;

    public const DIGITS = 6;

    protected const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * A new random secret, base32 encoded (160 bits by default, as RFC 4226 recommends).
     */
    public static function generateSecret(int $bytes = 20): string
    {
        return static::base32Encode(random_bytes($bytes));
    }

    /**
     * The time step a timestamp belongs to.
     */
    public static function timestep(?int $time = null): int
    {
        return intdiv($time ?? time(), static::PERIOD);
    }

    /**
     * The code of a secret for a time step.
     */
    public static function code(string $secret, int $timestep): string
    {
        $hash = hash_hmac('sha1', pack('NN', ($timestep >> 32) & 0xFFFFFFFF, $timestep & 0xFFFFFFFF), static::base32Decode($secret), true);

        $offset = ord($hash[19]) & 0x0F;

        $value = ((ord($hash[$offset]) & 0x7F) << 24)
            | (ord($hash[$offset + 1]) << 16)
            | (ord($hash[$offset + 2]) << 8)
            | ord($hash[$offset + 3]);

        return str_pad((string) ($value % (10 ** static::DIGITS)), static::DIGITS, '0', STR_PAD_LEFT);
    }

    /**
     * Check a code, accepting `$window` time steps before and after now for clock drift.
     *
     * @return int|null the time step the code belongs to, or null when the code is wrong
     */
    public static function verify(string $secret, string $code, int $window = 1, ?int $time = null): ?int
    {
        $code = preg_replace('/\s+/', '', $code);

        if (! preg_match('/^\d{'.static::DIGITS.'}$/', (string) $code)) {
            return null;
        }

        $current = static::timestep($time);

        for ($step = $current - $window; $step <= $current + $window; $step++) {
            if (hash_equals(static::code($secret, $step), $code)) {
                return $step;
            }
        }

        return null;
    }

    /**
     * The otpauth:// link an authenticator app reads from the QR code.
     */
    public static function uri(string $issuer, string $account, string $secret): string
    {
        $label = rawurlencode($issuer).':'.rawurlencode($account);

        return 'otpauth://totp/'.$label.'?'.http_build_query([
            'secret' => $secret,
            'issuer' => $issuer,
            'algorithm' => 'SHA1',
            'digits' => static::DIGITS,
            'period' => static::PERIOD,
        ], '', '&', PHP_QUERY_RFC3986);
    }

    public static function base32Encode(string $bytes): string
    {
        $bits = '';

        foreach (str_split($bytes) as $byte) {
            $bits .= str_pad(decbin(ord($byte)), 8, '0', STR_PAD_LEFT);
        }

        $encoded = '';

        foreach (str_split($bits, 5) as $chunk) {
            $encoded .= static::BASE32_ALPHABET[bindec(str_pad($chunk, 5, '0'))];
        }

        return $bytes === '' ? '' : $encoded;
    }

    public static function base32Decode(string $encoded): string
    {
        $encoded = strtoupper((string) preg_replace('/[\s=-]+/', '', $encoded));
        $bits = '';

        foreach (str_split($encoded) as $char) {
            $index = strpos(static::BASE32_ALPHABET, $char);

            if ($index === false) {
                continue;
            }

            $bits .= str_pad(decbin($index), 5, '0', STR_PAD_LEFT);
        }

        $bytes = '';

        foreach (str_split($bits, 8) as $chunk) {
            if (strlen($chunk) === 8) {
                $bytes .= chr(bindec($chunk));
            }
        }

        return $bytes;
    }
}
