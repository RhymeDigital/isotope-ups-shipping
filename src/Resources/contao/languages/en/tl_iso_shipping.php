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
     * Legends
     */
    $lang['ups_legend']	= $lang['ups_legend'] ?? 'UPS settings';
}
