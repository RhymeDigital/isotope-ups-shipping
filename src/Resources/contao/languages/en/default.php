<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace {

    $lang = &$GLOBALS['TL_LANG'];

    /**
     * Shipping methods
     */
    $lang['MODEL']['tl_iso_shipping.ups'] = ['UPS shipping', 'This is the default shipping method for regular shipping.'];

    /**
     * Miscellaneous
     */
    $lang['MSC']['labelLabel'] = 'Shipping Label';
    $lang['MSC']['trackingNumberLabel'] = 'Tracking Number';
    $lang['MSC']['submitTimeInTransit'] = 'Submit';
    $lang['MSC']['submitTracking'] = 'Track';
    $lang['MSC']['serviceTitleLabel'] = 'Service';
    $lang['MSC']['guaranteedLabel'] = 'Guaranteed';
    $lang['MSC']['estimatedArrivalLabel'] = 'Est. Arrival';
    $lang['MSC']['pickupDatimLabel'] = 'Pickup Cutoff';
    $lang['MSC']['businessTransitDaysLabel'] = '# of Transit Days';
    $lang['MSC']['upsTrackDatimLabel'] = 'Date/Time';
    $lang['MSC']['upsTrackActivityLabel'] = 'Activity';
    $lang['MSC']['upsTrackLocationLabel'] = 'Location';
    $lang['MSC']['upsTrackDetailsLabel'] = 'Details';
    $lang['MSC']['printShippingLabel'] = 'Print Shipping Label';
    $lang['MSC']['noEnabledUpsServices'] = 'No UPS services have been enabled for this store.';

    /**
     * Reference
     */
    $lang['MSC']['apiMode']['test']		= 'Test Mode';
    $lang['MSC']['apiMode']['live']		= 'Live Mode';
}
