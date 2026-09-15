<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default currency
    |--------------------------------------------------------------------------
    |
    | Used for every amount until an admin picks a different one in Settings, and
    | as the fallback if the stored setting is ever something we do not support.
    |
    */

    'default' => env('CURRENCY_DEFAULT', 'BDT'),

    /*
    |--------------------------------------------------------------------------
    | Supported currencies
    |--------------------------------------------------------------------------
    |
    | Amounts are stored as plain numbers with no conversion, so this only ever
    | changes how money is displayed and which ISO code new orders are stamped
    | with. `grouping` picks the digit grouping: `lakh` is the South Asian style
    | where 1,00,000 sits between 9,999 and 1,00,001 (BDT, INR, PKR), while
    | `thousand` is the western 100,000 style.
    |
    */

    'currencies' => [
        'BDT' => [
            'label' => 'Bangladeshi Taka',
            'symbol' => '৳',
            'decimals' => 2,
            'grouping' => 'lakh',
        ],
        'INR' => [
            'label' => 'Indian Rupee',
            'symbol' => '₹',
            'decimals' => 2,
            'grouping' => 'lakh',
        ],
        'PKR' => [
            'label' => 'Pakistani Rupee',
            'symbol' => '₨',
            'decimals' => 2,
            'grouping' => 'lakh',
        ],
        'USD' => [
            'label' => 'US Dollar',
            'symbol' => '$',
            'decimals' => 2,
            'grouping' => 'thousand',
        ],
        'EUR' => [
            'label' => 'Euro',
            'symbol' => '€',
            'decimals' => 2,
            'grouping' => 'thousand',
        ],
        'GBP' => [
            'label' => 'Pound Sterling',
            'symbol' => '£',
            'decimals' => 2,
            'grouping' => 'thousand',
        ],
        'AED' => [
            'label' => 'UAE Dirham',
            'symbol' => 'AED ',
            'decimals' => 2,
            'grouping' => 'thousand',
        ],
        'SAR' => [
            'label' => 'Saudi Riyal',
            'symbol' => 'SAR ',
            'decimals' => 2,
            'grouping' => 'thousand',
        ],
    ],

];
