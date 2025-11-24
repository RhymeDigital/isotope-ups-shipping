<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\Model\Shipping;

use Contao\System;
use Exception;
use Isotope\Interfaces\IsotopeProductCollection;
use Isotope\Isotope;
use Isotope\Model\Shipping as Iso_Shipping;
use Rhymedigital\IsotopeUpsShipping\Helper\UPSHelper;

class UPS extends Iso_Shipping
{

    /**
     * @inheritdoc
     *
     * @throws \InvalidArgumentException on unknown quantity mode
     * @throws \UnexpectedValueException on unknown product type condition
     */
    public function isAvailable()
    {
        try {
            if (parent::isAvailable() && $this->getLiveRateQuote()) {
                return true;
            }
        }
        catch (Exception $e) {
            System::getContainer()
                  ->get('monolog.logger.contao.error')
                  ->error('UPS shipping error: ' . (\method_exists($e, 'getResponse') ? (string)$e->getResponse() : $e->getMessage()))
            ;
        }

        return false;
    }

    /**
     * @param null|IsotopeProductCollection $objCollection
     * @return float
     */
    public function getPrice(?IsotopeProductCollection $objCollection = null): float
    {
        $objCollection = $objCollection ?? Isotope::getCart();
        $fltPrice = parent::getPrice($objCollection);

        try {
            $fltPrice += $this->getLiveRateQuote($objCollection);
        }
        catch (Exception $e) {
            System::getContainer()
                  ->get('monolog.logger.contao.error')
                  ->error('UPS shipping error: ' . (\method_exists($e, 'getResponse') ? (string)$e->getResponse() : $e->getMessage()))
            ;
        }

        return $fltPrice ?? 0.00;
    }


    /**
     * Return calculated price for this shipping method
     *
     * @param ?IsotopeProductCollection $collection
     * @return ?float
     * @throws Exception
     */
    public function getLiveRateQuote(?IsotopeProductCollection $collection = null): ?float
    {
        $collection = $collection ?? Isotope::getCart();

        /** @var UPSHelper $helper */
        if (($helper = System::getContainer()->get('rhyme.isotope.ups_shipping.ups_helper')) !== null) {
            return $helper->getRateAmountForService(
                $this->rhyme_ups_enabled_service,
                $helper->getRatesForCollection($collection, $this)
            );
        }

        return 0.0;
    }

    /**
     * Used for Isotope Advanced Shipping
     *
     * @param $shipment
     * @param array $options
     * @return null|float
     * @throws Exception
     */
    public function getQuote($shipment=null, array $options=[]): ?float
    {
        if (($shipment ?? null) !== null
            && \method_exists($shipment, 'getCollection')
            && ($collection = $shipment->getCollection()) instanceof IsotopeProductCollection
        ) {
            try {
                $rate = $this->getLiveRateQuote($collection);
            }
            catch (Exception $e) {}
        }

        return $rate ?? 0.0;
    }

    /**
     * @param $shipment
     * @return mixed
     */
    public function purchaseShipment($shipment)
    {
        if (($shipment ?? null) !== null
            && \method_exists($shipment, 'getCollection')
            && ($collection = $shipment->getCollection()) instanceof IsotopeProductCollection
        ) {
            try {
                /** @var UPSHelper $helper */
                if (($helper = System::getContainer()->get('rhyme.isotope.ups_shipping.ups_helper')) !== null) {
                    if (($response = $helper->createShipmentForCollection($collection)) !== null) {

                        // Success! Store the data for the shipment and the packages
                        $results = $response['data']->ShipmentResponse->ShipmentResults;

                        // Shipment cost
                        if (!empty($results->ShipmentCharges->TotalCharges->MonetaryValue)) {
                            $shipment->price_actual = (float)$results->ShipmentCharges->TotalCharges->MonetaryValue;
                        }

                        // Shipment ID
                        if (!empty($results->ShipmentIdentificationNumber)) {
                            $shipment->rhyme_ups_shipment_identification_number = (string)$results->ShipmentIdentificationNumber;
                        }

                        $shipment->save();

                        // Just loop through the packages in the same order that
                        // we sent them. We don't get a UID back from the response...
                        if (!empty($results->PackageResults)) {
                            $pkgResults = (array)$results->PackageResults;

                            $i = 0;
                            if (($packages = $shipment->getPackages()) !== null) {
                                $packages->reset();

                                while ($packages->next())
                                {
                                    $currentPkg = $packages->current();

                                    if (!empty($pkgResults[$i]))
                                    {
                                        $baseCharge = (float)($pkgResults[$i]->BaseServiceCharge->MonetaryValue ?? 0.00);
                                        $serviceCharge = (float)($pkgResults[$i]->ServiceOptionsCharges->MonetaryValue ?? 0.00);

                                        $currentPkg->tracking_number = (string)($pkgResults[$i]->TrackingNumber ?? '');
                                        $currentPkg->shipping_label = (string)($pkgResults[$i]->ShippingLabel->GraphicImage ?? '');
                                        $currentPkg->price_actual = $baseCharge + $serviceCharge;
                                        $currentPkg->save();
                                    }
                                    $i++;
                                }

                                $packages->reset();
                            }
                        }
                    }
                }
            }
            catch (Exception $e) {
                $message = 'UPS shipment purchase error: ' . (\method_exists($e,'getResponse') ? $e->getResponse()->getContent() : (\method_exists($e,'getMessage') ? $e->getMessage() : ((string)$e)));
                System::log($message, __METHOD__, \TL_ERROR);
            }
        }

        return $shipment;
    }

}

