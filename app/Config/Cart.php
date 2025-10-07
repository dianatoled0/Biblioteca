<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cart extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Cart Session Name
     * --------------------------------------------------------------------------
     *
     * The name of the session variable to use for the cart.
     */
    public string $sessionName = 'cart';

    /**
     * --------------------------------------------------------------------------
     * Save Responses
     * --------------------------------------------------------------------------
     *
     * Whether to save the responses from the cart operations.
     */
    public bool $saveResponses = false;

    /**
     * --------------------------------------------------------------------------
     * Taxes Region
     * --------------------------------------------------------------------------
     *
     * The region to use for taxes.
     */
    public array $taxesRegion = [];

    /**
     * --------------------------------------------------------------------------
     * Rounding
     * --------------------------------------------------------------------------
     *
     * The number of decimals to round to.
     */
    public int $rounding = 2;

    /**
     * --------------------------------------------------------------------------
     * Rounding Mode
     * --------------------------------------------------------------------------
     *
     * The rounding mode to use.
     */
    public string $roundingMode = 'default';

    /**
     * --------------------------------------------------------------------------
     * Currency
     * --------------------------------------------------------------------------
     *
     * The currency to use.
     */
    public array $currency = [
        'code'      => 'USD',
        'name'      => 'US Dollars',
        'symbol'    => '$',
        'decimal'   => '.',
        'thousands' => ',',
        'decimals'  => 2,
    ];
}