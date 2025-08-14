<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace {

    use Isotope\Model\Address;

    $t = Address::getTable();
    $lang = &$GLOBALS['TL_LANG'][$t];

    /**
     * Fields
     */
    $lang['address_classification'] = ['Address classification', 'Please select the classification for this address.'];

    /**
     * References
     */
    $lang['address_classifications'] = [
        'residential' => 'Residential',
        'business'    => 'Business',
    ];
}
