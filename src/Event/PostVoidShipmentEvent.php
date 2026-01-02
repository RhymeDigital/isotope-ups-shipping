<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\Event;

use RahulGodiyal\PhpUpsApiWrapper\VoidShipment;
use Symfony\Contracts\EventDispatcher\Event;

class PostVoidShipmentEvent extends Event
{
    public const NAME = 'rhyme.isotope.ups_shipping.post_void_shipment';

    protected VoidShipment $voidShipment;
    protected array $response;
    protected array $options;

    public function __construct(VoidShipment $voidShipment, array $response, array $options=[])
    {
        $this->response = $response;
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

    public function getResponse(): array
    {
        return $this->response;
    }

    public function setResponse(array $response): void
    {
        $this->response = $response;
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
