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
use Rhymedigital\IsotopeUpsShipping\Session\ApiResponseCache;

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
            // Todo: Add logging here!
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
            // Todo: Add logging here!
        }

        return $fltPrice;
    }


    /**
     * Return calculated price for this shipping method
     *
     * @param ?IsotopeProductCollection $collection
     * @return ?float
     * @throws Exception
     */
    protected function getLiveRateQuote(?IsotopeProductCollection $collection = null): ?float
    {
        $collection = $collection ?? Isotope::getCart();

        if (($helper = System::getContainer()->get('rhyme.isotope.ups_shipping.ups_helper')) !== null) {
            return (float)$helper->getRateAmountForService(
                $this->rhyme_ups_enabled_service,
                $helper->getRatesForCollection($collection, $this)
            );
        }

        return 0.0;
    }

}

