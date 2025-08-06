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
use Contao\Cache;
use Exception;
use Isotope\Interfaces\IsotopeProductCollection;
use Isotope\Isotope;
use Isotope\Model\Shipping as Iso_Shipping;

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
            if (parent::isAvailable() && $this->getLiveRateQuote() !== null) {
                return true;
            }
        }
        catch (Exception $e) {}

        return false;
    }

    /**
     * Return calculated price for this shipping method
     *
     * @param ?IsotopeProductCollection $objCollection
     * @return float
     */
    public function getPrice(?IsotopeProductCollection $objCollection = null): float
    {
        $objCollection = $objCollection ?? Isotope::getCart();
        $fltPrice = parent::getPrice($objCollection);

        return $this->getLiveRateQuote($objCollection) + $fltPrice;
    }


    /**
     * Return calculated price for this shipping method
     *
     * @param ?IsotopeProductCollection $objCollection
     * @return ?float
     * @throws Exception
     */
    protected function getLiveRateQuote(?IsotopeProductCollection $objCollection = null): ?float
    {
        $objCollection = $objCollection ?? Isotope::getCart();

        // Get a hash for the cache
        $strHash = static::makeHash($objCollection, array($this->rhyme_ups_enabled_service));

        if (!Cache::has($strHash)) {
            Cache::set($strHash, System::getContainer()->get('rhyme.isotope.ups_shipping.ups_helper')->getRateForCollection($objCollection, $this));
        }

        return Cache::get($strHash);
    }


    /**
     * Build a Hash string based on the shipping address
     *
     * @param ?IsotopeProductCollection $objCollection
     * @param null|array                $arrExtras
     * @return string
     */
     protected static function makeHash(?IsotopeProductCollection $objCollection = null, ?array $arrExtras=array()): string
     {
         $objCollection = $objCollection ?? Isotope::getCart();

         $strBase = static::class;
         $strBase .= !empty($arrExtras) ? \implode(',', $arrExtras) : '';

         if (($address = $objCollection->getShippingAddress()) !== null) {
             $strBase .= $address->street_1;
             $strBase .= $address->street_2;
             $strBase .= $address->street_3;
             $strBase .= $address->city;
             $strBase .= $address->subdivision;
             $strBase .= $address->postal;
             $strBase .= $address->country;
         }

         // Hash the cart too
         foreach ($objCollection->getItems() as $item)
         {
	         $strBase .= $item->quantity;
	         $strBase .= $item->id;
	         $strBase .= implode(',', $item->getOptions());
         }

         return \md5($strBase);
     }

}

