<?php

namespace App\Support;

use Illuminate\Support\Facades\Crypt;

/**
 * Time-trap spam protection: the form embeds an encrypted "form opened at"
 * timestamp; the controller rejects submissions that arrive faster than a
 * human could realistically fill the form. Unlike a session/cookie trap this
 * cannot be bypassed by clearing cookies, and unlike a plain hidden field the
 * timestamp cannot be forged because it is encrypted.
 */
class FormTimeTrap
{
    /** Field name expected in the form payload. */
    public const FIELD = 'form_opened_at';

    /** Minimum seconds a human needs to see and submit a form. */
    public const MIN_SECONDS = 3;

    /** Max age in minutes — protects against replays hours later. */
    public const MAX_AGE_MINUTES = 120;

    /** Encrypted token to embed in the form. */
    public static function token(): string
    {
        return Crypt::encryptString((string) now()->timestamp);
    }

    /**
     * Whether the submission passes the trap.
     * Missing/garbage tokens fail too — a real browser session built by the
     * app always carries a valid token.
     */
    public static function passes(?string $token): bool
    {
        if (! $token) {
            return false;
        }

        try {
            $openedAt = (int) Crypt::decryptString($token);
        } catch (\Throwable) {
            return false;
        }

        $elapsed = now()->timestamp - $openedAt;

        return $elapsed >= self::MIN_SECONDS
            && $elapsed <= self::MAX_AGE_MINUTES * 60;
    }

    /**
     * Valid token with a humanistic "opened at" — for tests that exercise the
     * happy path without sleeping.
     */
    public static function mintValidTokenForTesting(): string
    {
        return Crypt::encryptString((string) now()->subSeconds(30)->timestamp);
    }
}
