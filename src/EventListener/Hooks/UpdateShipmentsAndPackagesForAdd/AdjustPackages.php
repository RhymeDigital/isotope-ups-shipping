<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\EventListener\Hooks\UpdateShipmentsAndPackagesForAdd;

use Isotope\ServiceAnnotation\IsotopeHook;

/**
 * @IsotopeHook("updateShipmentsAndPackagesForAdd")
 */
class AdjustPackages
{
    public function __invoke($objItem, $intQuantity, $objCollection, $arrOptions): void
    {

    }
}
