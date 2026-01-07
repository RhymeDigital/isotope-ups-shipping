<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 * @link            https://rhyme.digital
 * @license         https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\EventListener\Hooks\ParseBackendTemplate;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Environment;

/**
 * @Hook("parseBackendTemplate")
 */
class AddVoidButtonToShipmentList
{
    public function __invoke(string $buffer, string $template): string
    {
        // This is set via the "parseTemplate" hook
        if (!($shipmentId = ($GLOBALS['RHYME_UPS_ADD_SHIPMENT_VOID'] ?? null))
            || \strpos($template, 'be_iso_shipment') !== 0
        ) {
            return $buffer;
        }

        unset($GLOBALS['RHYME_UPS_ADD_SHIPMENT_VOID']);

        $href = Environment::get('request').'&amp;voidShipment='.$shipmentId;
        $label = $GLOBALS['TL_LANG']['MSC']['voidShipment'] ?? 'Void Shipment';

        $button = \sprintf(
            '<a href="%s" title="%s" class="tl_submit rhymeUpsVoidShipment" onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['confirmVoidShipment'] ?? 'Are you sure you want to void this shipment?') . '\'))return false;">%s</a>',
            $href,
            $label . ' ' . $shipmentId,
            $label
        );

        if (($lastPos = \strrpos($buffer, '</')) !== false) {
            $buffer = \substr_replace($buffer, $button.'</', $lastPos, 0);
        }

        return $buffer;
    }
}
