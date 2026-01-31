<?php

namespace QuangPhuc\WebsiteReseller\Supports;

use Illuminate\Support\Str;

class CheckoutHelper
{
    protected static string $sessionKey = 'wr_checkout_token';

    public static function getCheckoutToken(): string
    {
        if (session()->has(static::$sessionKey)) {
            return session(static::$sessionKey);
        }

        $token = md5(Str::random(40));
        session([static::$sessionKey => $token]);

        return $token;
    }

    public static function getCheckoutData(?string $token = null): array
    {
        $token = $token ?: static::getCheckoutToken();

        return session(static::getSessionDataKey($token), []);
    }

    public static function setCheckoutData(array $data, ?string $token = null): void
    {
        $token = $token ?: static::getCheckoutToken();

        $existingData = static::getCheckoutData($token);
        $mergedData = array_merge($existingData, $data);

        session([static::getSessionDataKey($token) => $mergedData]);
    }

    public static function clearCheckoutSession(): void
    {
        $token = session(static::$sessionKey);

        if ($token) {
            session()->forget([
                static::$sessionKey,
                static::getSessionDataKey($token),
                'selected_payment_method',
            ]);
        }
    }

    protected static function getSessionDataKey(string $token): string
    {
        return md5('wr_checkout_data_' . $token);
    }
}
