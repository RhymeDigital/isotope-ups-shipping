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
    $lang['rhyme_ups_services'] = [
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::NEXT_DAY_AIR                         => 'Next Day Air',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::SECOND_DAY_AIR                       => '2nd Day Air',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::GROUND                               => 'Ground',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::EXPRESS                              => 'Express',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::EXPEDITED                            => 'Expedited',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_STANDARD                         => 'UPS Standard',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::THREE_DAY_SELECT                     => '3 Day Select',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::NEXT_DAY_AIR_SAVER                   => 'Next Day Air Saver',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_NEXT_DAY_AIR_EARLY               => 'UPS Next Day Air® Early',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::UPS_WORLDWIDE_ECONOMY_DDU            => 'UPS Worldwide Economy DDU',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::EXPRESS_PLUS                         => 'Express Plus',
        RahulGodiyal\PhpUpsApiWrapper\Entity\Service::SECOND_DAY_AIR_A_M                   => 'Second Day Air A.M.',
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
