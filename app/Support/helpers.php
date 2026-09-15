<?php

/*
|--------------------------------------------------------------------------
| Global helpers
|--------------------------------------------------------------------------
*/

if (! function_exists('money')) {
    /**
     * Format an amount in the site's currency, e.g. money(125000) => "৳1,25,000.00".
     * Pass an explicit ISO code to render a stored amount in its own currency.
     */
    function money(float|int|string|null $amount, ?string $currency = null, bool $withCode = false): string
    {
        return \App\Support\Currency::format($amount, $currency, $withCode);
    }
}

if (! function_exists('currency_code')) {
    /** ISO code of the active currency, e.g. "BDT". */
    function currency_code(): string
    {
        return \App\Support\Currency::code();
    }
}

if (! function_exists('currency_symbol')) {
    /** Symbol of the active currency (or a given one), e.g. "৳". */
    function currency_symbol(?string $currency = null): string
    {
        return \App\Support\Currency::symbol($currency);
    }
}

if (! function_exists('render_shortcodes')) {
    /**
     * Render inline shortcodes (e.g. [before_after id=3]) inside rich-text
     * content such as page bodies, service/product/portfolio descriptions.
     */
    function render_shortcodes(?string $content): string
    {
        return app(\App\Services\ShortcodeService::class)->render($content);
    }
}
