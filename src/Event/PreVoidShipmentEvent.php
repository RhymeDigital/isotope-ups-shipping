<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\Event;

use Symfony\Contracts\EventDispatcher\Event;
use RahulGodiyal\PhpUpsApiWrapper\VoidShipment;

class PreVoidShipmentEvent extends Event
{
    public const NAME = 'rhyme.isotope.ups_shipping.pre_void_shipment';

    protected VoidShipment $voidShipment;
    protected array $options;

    public function __construct(VoidShipment $voidShipment, array $options=[])
    {
        $this->voidShipment = $voidShipment;
        $this->options = $options;
    }

    public function getVoidShipment(): VoidShipment
    {
        return $this->voidShipment;
    }

    public function setVoidShipment(VoidShipment $voidShipment): void
    {
        $this->voidShipment = $voidShipment;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
