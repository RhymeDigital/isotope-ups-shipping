<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace {

    use Rhymedigital\IsotopeUpsShipping\Model;

    /**
     * Shipping methods
     */
    \Isotope\Model\Shipping::registerModelType('rhyme_ups', Model\Shipping\UPS::class);
}
