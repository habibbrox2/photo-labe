<?php

namespace App\Support;

use App\Services\CacheService;

/**
 * Formats money in the site's configured currency.
 *
 * The active code comes from Settings (falling back to config), and each currency
 * carries its own symbol, decimal count and digit grouping — Bangladeshi taka, for
 * instance, groups in lakh/crore style (৳1,00,000) rather than western thousands.
 */
final class Currency
{
    /** Active ISO code, straight from settings with a safe fallback. */
    public static function code(): string
    {
        $code = strtoupper(trim((string) CacheService::setting('currency', self::defaultCode())));

        return self::isSupported($code) ? $code : self::defaultCode();
    }

    public static function defaultCode(): string
    {
        return strtoupper((string) config('currency.default', 'BDT'));
    }

    public static function symbol(?string $code = null): string
    {
        return self::meta($code)['symbol'];
    }

    public static function label(?string $code = null): string
    {
        return self::meta($code)['label'];
    }

    public static function decimals(?string $code = null): int
    {
        return self::meta($code)['decimals'];
    }

    /**
     * Render an amount, e.g. "৳1,25,000.00" — or "৳1,25,000.00 BDT" with the code.
     */
    public static function format(float|int|string|null $amount, ?string $code = null, bool $withCode = false): string
    {
        $code = self::normalise($code);
        $decimals = self::decimals($code);
        $value = (float) ($amount ?? 0);

        $rounded = number_format(abs($value), $decimals, '.', '');
        [$whole, $fraction] = array_pad(explode('.', $rounded, 2), 2, '');

        $grouped = self::meta($code)['grouping'] === 'lakh'
            ? self::groupLakh($whole)
            : preg_replace('/\B(?=(\d{3})+(?!\d))/', ',', $whole);

        $formatted = self::symbol($code).$grouped
            .($decimals > 0 ? '.'.str_pad($fraction, $decimals, '0') : '');

        if ($value < 0) {
            $formatted = '-'.$formatted;
        }

        return $withCode ? $formatted.' '.$code : $formatted;
    }

    /** @return list<string> */
    public static function supported(): array
    {
        return array_keys(config('currency.currencies', []));
    }

    public static function isSupported(?string $code): bool
    {
        return array_key_exists(strtoupper(trim((string) $code)), config('currency.currencies', []));
    }

    /** Options for the settings form: code => "৳ Bangladeshi Taka (BDT)". */
    public static function options(): array
    {
        $options = [];

        foreach (config('currency.currencies', []) as $code => $meta) {
            $options[$code] = trim($meta['symbol']).' '.$meta['label'].' ('.$code.')';
        }

        return $options;
    }

    /**
     * South Asian grouping: the last three digits, then groups of two
     * (1234567 => 12,34,567).
     */
    private static function groupLakh(string $whole): string
    {
        if (strlen($whole) <= 3) {
            return $whole;
        }

        $thousands = substr($whole, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', substr($whole, 0, -3));

        return $rest.','.$thousands;
    }

    /** Resolve any code — unknown or empty codes follow the active currency. */
    private static function normalise(?string $code): string
    {
        $code = strtoupper(trim((string) $code));

        return self::isSupported($code) ? $code : self::code();
    }

    private static function meta(?string $code): array
    {
        $currencies = config('currency.currencies', []);
        $resolved = self::normalise($code);

        // Even a broken config must not stop prices from rendering.
        return $currencies[$resolved]
            ?? $currencies[self::defaultCode()]
            ?? ['label' => 'Currency', 'symbol' => '', 'decimals' => 2, 'grouping' => 'thousand'];
    }
}
