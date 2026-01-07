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
    $lang['MODEL']['tl_iso_shipping']['rhyme_ups'] = ['UPS shipping', 'This is the default shipping method for regular shipping.'];

    /**
     * Miscellaneous
     */
    $lang['MSC']['labelLabel']               = 'Shipping Label';
    $lang['MSC']['trackingNumberLabel']      = 'Tracking Number';
    $lang['MSC']['submitTimeInTransit']      = 'Submit';
    $lang['MSC']['submitTracking']           = 'Track';
    $lang['MSC']['serviceTitleLabel']        = 'Service';
    $lang['MSC']['guaranteedLabel']          = 'Guaranteed';
    $lang['MSC']['estimatedArrivalLabel']    = 'Est. Arrival';
    $lang['MSC']['pickupDatimLabel']         = 'Pickup Cutoff';
    $lang['MSC']['businessTransitDaysLabel'] = '# of Transit Days';
    $lang['MSC']['upsTrackDatimLabel']       = 'Date/Time';
    $lang['MSC']['upsTrackActivityLabel']    = 'Activity';
    $lang['MSC']['upsTrackLocationLabel']    = 'Location';
    $lang['MSC']['upsTrackDetailsLabel']     = 'Details';
    $lang['MSC']['printShippingLabel']       = 'Print Shipping Label';
    $lang['MSC']['noEnabledUpsServices']     = 'No UPS services have been enabled for this store.';
    $lang['MSC']['voidShipment']             = $lang['MSC']['voidShipment'] ?? 'Void shipment';
    $lang['MSC']['confirmVoidShipment']      = $lang['MSC']['confirmVoidShipment'] ?? 'Are you sure you want to void this shipment?';
    $lang['MSC']['shipmentVoidedSuccess']    = $lang['MSC']['shipmentVoidedSuccess'] ?? 'Shipment voided successfully.';

    /**
     * Errors
     */
    $lang['ERR']['upsShipping']              = 'UPS shipping error.%s';
    $lang['ERR']['upsShipmentPurchase']      = 'UPS shipment purchasing error.%s';
    $lang['ERR']['upsBeShipmentChildRecord'] = 'There was a problem generating the UPS shipment information.';
    $lang['ERR']['upsBeShipmentVoid']        = 'There was a problem voiding the UPS shipment.%s';

    /**
     * Reference
     */
    $lang['MSC']['rhyme_ups_services'] = [
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::NEXT_DAY_AIR                         => 'UPS Next Day Air',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::SECOND_DAY_AIR                       => 'UPS 2nd Day Air',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::GROUND                               => 'UPS Ground',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::EXPRESS                              => 'UPS Express',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::EXPEDITED                            => 'UPS Expedited',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_STANDARD                         => 'UPS Standard',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::THREE_DAY_SELECT                     => 'UPS 3 Day Select',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::NEXT_DAY_AIR_SAVER                   => 'UPS Next Day Air Saver',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_NEXT_DAY_AIR_EARLY               => 'UPS Next Day Air® Early',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_WORLDWIDE_ECONOMY_DDU            => 'UPS Worldwide Economy DDU',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::EXPRESS_PLUS                         => 'UPS Express Plus',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::SECOND_DAY_AIR_A_M                   => 'UPS Second Day Air A.M.',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_SAVER                            => 'UPS Saver',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::FIRST_CLASS_MAIL                     => 'First Class Mail',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::PRIORITY_MAIL                        => 'Priority Mail',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::EXPEDITED_MAIL_INNOVATIONS           => 'Expedited Mail Innovations',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::PRIORITY_MAIL_INNOVATIONS            => 'Priority Mail Innovations',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::ECONOMY_MAIL_INNOVATIONS             => 'Economy Mail Innovations',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::MAIL_INNOVATIONS_MI_RETURNS          => 'Mail Innovations (MI) Returns',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_ACCESS_POINT_ECONOMY             => 'UPS Access Point™ Economy',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_WORLDWIDE_EXPRESS_FREIGHT_MIDDAY => 'UPS Worldwide Express Freight Midday',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_WORLDWIDE_ECONOMY_DDP            => 'UPS Worldwide Economy DDP',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_EXPRESS_12_00                    => 'UPS Express® 12:00',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_HEAVY_GOODS                      => 'UPS Heavy Goods',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_TODAY_STANDARD                   => 'UPS Today Standard',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_TODAY_DEDICATED_COURIER          => 'UPS Today Dedicated Courier',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_TODAY_INTERCITY                  => 'UPS Today Intercity',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_TODAY_EXPRESS                    => 'UPS Today Express',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_TODAY_EXPRESS_SAVER              => 'UPS Today Express Saver',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_WORLDWIDE_EXPRESS_FREIGHT        => 'UPS Worldwide Express Freight',
    ];
}
