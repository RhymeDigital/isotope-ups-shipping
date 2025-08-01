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
    $lang['rhyme_ups_access_key']      = ['Access Key', 'Please provide the access key supplied by UPS.'];
    $lang['rhyme_ups_username']        = ['Username', 'Please provide your UPS account username.'];
    $lang['rhyme_ups_password']        = ['Password', 'Please provide your UPS API password.'];
    $lang['rhyme_ups_account_number']  = ['Account number', 'Please provide your UPS Account number (for shipping/label generation. To obtain, log into My UPS and then click "Account Summary". The value is listed as a UPS Account Number.)'];
    $lang['rhyme_ups_mode']            = ['UPS API Mode', 'Use test to avoid real shipping requests!'];
    $lang['rhyme_ups_enabled_service'] = ['UPS Service Type','Select a UPS shipping service to offer.'];
    $lang['rhyme_ups_insure_packages'] = ['Insure UPS packages','If this is checked, any packages over the price limit will be insured.'];

    /**
     * Legends
     */
    $lang['ups_legend']	= $lang['ups_legend'] ?? 'UPS settings';

    /**
     * References
     */
    $lang['rhyme_ups_services']['01'] = 'Next Day Air';
    $lang['rhyme_ups_services']['02'] = '2nd Day Air';
    $lang['rhyme_ups_services']['03'] = 'UPS Ground';
    $lang['rhyme_ups_services']['07'] = 'Worldwide Express';
    $lang['rhyme_ups_services']['08'] = 'Worldwide Expedited';
    $lang['rhyme_ups_services']['11'] = 'International Standard';
    $lang['rhyme_ups_services']['12'] = '3 Day Select';
    $lang['rhyme_ups_services']['13'] = 'Next Day Air Saver';
    $lang['rhyme_ups_services']['14'] = 'Next Day Air Early AM';
    $lang['rhyme_ups_services']['54'] = 'Worldwide Express Plus';
    $lang['rhyme_ups_services']['65'] = 'International Saver';
}
