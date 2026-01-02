<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace {

    $t = 'tl_iso_shipment';

    if (isset($GLOBALS['TL_LANG'][$t]))
    {
        $lang = &$GLOBALS['TL_LANG'][$t];

        /**
         * Fields
         */
        $lang['rhyme_ups_shipment_identification_number'] = ['UPS shipment identification number', 'This is the shipment identification number return from UPS when the shipment has been created in their system.'];
        $lang['rhyme_ups_shipment_voided_at'] = ['UPS shipment voided at', 'This is the date and time that the shipment was voided.'];

        /**
         * Legends
         */
        $lang['ups_legend'] = 'UPS shipping information';
    }

}
