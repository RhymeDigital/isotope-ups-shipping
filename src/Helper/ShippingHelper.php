<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\Helper;

use Haste\Units\Mass\Scale;
use Haste\Units\Mass\Weighable;
use Haste\Units\Mass\WeightAggregate;
use Isotope\Model\ProductCollectionItem;

class ShippingHelper
{

    /**
     * Calculate the weight of all products in a collection in a specific weight unit
     * @param ProductCollectionItem $objItem
     * @param string                $unit
     * @return float|int|mixed
     */
    public static function getShippingWeight(ProductCollectionItem $objItem, string $unit)
    {
        $objScale = new Scale();

        if (!$objItem->hasProduct()) {
            return 0.0;
        }

        $objProduct = $objItem->getProduct();

        if ($objProduct instanceof WeightAggregate) {
            $objWeight = $objProduct->getWeight();

            if ($objWeight !== null) {
                $objScale->add($objWeight);
            }

        } elseif ($objProduct instanceof Weighable) {
            $objScale->add($objProduct);
        }

        return $objScale->amountIn($unit);
    }
}
