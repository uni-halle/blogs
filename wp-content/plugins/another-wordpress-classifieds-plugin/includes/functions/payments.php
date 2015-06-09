<?php

function awpcp_paypal_supported_currencies() {
    return array(
        'AUD','BRL','CAD','CZK','DKK','EUR',
        'HKD','HUF','ILS','JPY','MYR','MXN',
        'NOK','NZD','PHP','PLN','GBP','SGD',
        'SEK','CHF','TWD','THB','USD'
    );
}

function awpcp_paypal_supports_currency( $currency_code ) {
    $currency_codes = awpcp_paypal_supported_currencies();

    if ( ! in_array( $currency_code, $currency_codes ) ) {
        return false;
    }

    return true;
}
