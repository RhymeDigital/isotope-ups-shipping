<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace {

    use Isotope\Model\Shipping;

    $t = Shipping::getTable();
    $lang = &$GLOBALS['TL_LANG'][$t];

    /**
     * Fields
     */
    $lang['rhyme_ups_client_id']       = ['UPS Client ID', 'Please provide your UPS app\'s client ID.'];
    $lang['rhyme_ups_client_secret']   = ['UPS Client secret', 'Please provide your UPS app\'s client secret.'];
    $lang['rhyme_ups_account_number']  = ['UPS Account number (6 characters)', 'Please provide the 6 character UPS account number.'];
    $lang['rhyme_ups_mode']            = ['UPS API Mode', 'Use test to avoid real shipping requests!'];
    $lang['rhyme_ups_enabled_service'] = ['UPS Service Type', 'Select a UPS shipping service to offer.'];
    $lang['rhyme_ups_insure_packages'] = ['Insure UPS packages', 'If this is checked, any packages over the price limit will be insured.'];

    /**
     * Legends
     */
    $lang['ups_legend']	= $lang['ups_legend'] ?? 'UPS settings';

    /**
     * References
     */
    $lang['rhyme_ups_modes'] = [
        'DEV'  => 'Development',
        'PROD' => 'Production',
    ];
}
